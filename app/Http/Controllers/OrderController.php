<?php

namespace App\Http\Controllers;

use App\Services\FonnteService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Fabric;
use App\Models\Notification;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\TestStatus\Notice;

class OrderController extends Controller
{
    public function create(Request $request )
    {
        $venues = Venue::with('areas.rooms')->get();

        $fabrics = Fabric::where('stock_meter', '>', 0)->get();

        $selectedVenueId = $request->query('venue_id');

        return view('orders.create', compact('venues', 'fabrics', 'selectedVenueId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'note' => 'nullable|string',
            'add_on_detail' => 'nullable|string',

            'items'      => 'required|array|min:1',
            'items.*.venue_room_id' => 'required|exists:venue_rooms,id',
            'items.*.fabric_id'     => 'required|exists:fabrics,id',
            'items.*.quantity'      => 'required|numeric|min:1',
        ]);

        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);
        $days  = $start->diffInDays($end) ?: 1;

        try {
            DB::beginTransaction();

            $grandTotal = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $fabric = Fabric::find($item['fabric_id']);

                if ($fabric->stock_meter < $item['quantity']) {
                    return back()->withErrors(['msg' => "Stok kain {$fabric->name} tidak cukup! Tersisa: {$fabric->stock_meter}m"]);
                }

                $subtotal = $fabric->price_per_meter * $item['quantity'] * $days;
                $grandTotal += $subtotal;

                $orderItemsData[] = [   
                    'fabric_id'       => $fabric->id,
                    'venue_room_id'   => $item['venue_room_id'],
                    'quantity'        => $item['quantity'],
                    'price_per_meter' => $fabric->price_per_meter,
                    'subtotal'        => $subtotal,
                ];
            }

            $order = Order::create([
                'user_id'       => Auth::id(),
                'order_number'  => 'RNT-' . strtoupper(uniqid()),
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'total_days'    => $days,
                'total_price'   => $grandTotal,
                'status'        => 'pending',
                'note'          => $request->note,
                'add_on_detail' => $request->add_on_detail, 
            ]);

            foreach ($orderItemsData as $data) {
                $data['order_id'] = $order->id;
                OrderItem::create($data);
            }

            Notification::create([
                'user_id' => $order->user_id,
                'title'   => 'Booking Created',
                'message' => 'Order #' . $order->order_number . ' created. Waiting for approval.',
                'type'    => 'order_info',
                'is_read' => false,
            ]);

            $this->sendWhatsAppNotifications($order, $grandTotal);

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Booking berhasil dibuat! Menunggu konfirmasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            return back()->withErrors(['msg' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
        }
    }

    private function sendWhatsAppNotifications($order, $grandTotal)
    {
        if ($order->user->phone) {
            $pesanUser = "Halo Kak *{$order->user->name}* 👋,\n\n" .
                "Pesanan Sewa Dekorasi Anda berhasil dibuat!\n" .
                "📝 *No. Order:* #{$order->order_number}\n" .
                "💰 *Total:* Rp " . number_format($grandTotal, 0, ',', '.') . "\n" .
                "⏳ *Status:* Menunggu Konfirmasi Admin\n\n" .
                "Terima kasih!";
            FonnteService::send($order->user->phone, $pesanUser);
        }

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($admin->phone) {
                $pesanAdmin = "🔔 *ORDER BARU MASUK*\n\n" .
                    "Customer: {$order->user->name}\n" .
                    "Order: #{$order->order_number}\n" .
                    "Total: Rp " . number_format($grandTotal, 0, ',', '.') . "\n\n" .
                    "Segera cek dashboard untuk approve.";
                FonnteService::send($admin->phone, $pesanAdmin);
            }
        }
    }


    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {

            $orders = Order::with(['user', 'items.fabric', 'items.room'])->latest()->paginate(10);
        } else {
            $orders = Order::with(['items.fabric', 'items.room'])->where('user_id', $user->id)->latest()->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }


    public function show(Order $order)
    {

        $order->load(['user', 'items.fabric']);
        return view('orders.show', compact('order'));
    }

    public function updatePrice(Request $request, Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot edit price for processed orders');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.price_per_meter' => 'required|numeric|min:0'
        ]);

        $newGrandTotal = 0;

        foreach ($request->items as $itemId => $data) {
            $item = $order->items()->find($itemId);

            if ($item) {
                $pricePerMeter = $data['price_per_meter'];
                $days = $order->total_days;
                $newSubTotal = $pricePerMeter * $item->quantity * $days;
                $item->update([
                    'price_per_meter' => $pricePerMeter,
                    'subtotal' => $newSubTotal
                ]);

                $newGrandTotal = +$newSubTotal;
            }
            $order->update(['total_price' => $newGrandTotal]);

            return redirect()->back()->with('success', 'Order prices updated successfully.');
        }
    }


    public function approve(Order $order)
    {

        $order->update(['status' => 'approved']);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Approved',
            'message' => 'Your order #' . $order->order_number . ' has been approved by the admin.',
            'type' => 'order_info',
            'is_read' => false,
        ]);


        if ($order->user->phone) {
            $pesan = "✅ *PESANAN DISETUJUI*\n\n" .
                "Halo Kak *{$order->user->name}*,\n\n" .
                "Kabar gembira! Pesanan Anda dengan nomor *#{$order->order_number}* telah disetujui oleh Admin.\n\n" .
                "*Langkah Selanjutnya:*\n" .
                "Silakan lakukan pembayaran atau persiapan pengambilan barang sesuai jadwal sewa.\n\n" .
                "Terima kasih telah memilih Kana Covers!";

            FonnteService::send($order->user->phone, $pesan);
        }

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' has been approved!');
    }

    public function reject(Order $order)
    {
        $order->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Rejected',
            'message' => 'Your order #' . $order->order_number . ' has been rejected by the admin.',
            'type' => 'order_info',
            'is_read' => false,
        ]);

        if ($order->user->phone) {
            $pesan = " *PESANAN TIDAK DAPAT DIPROSES*\n\n" .
                "Halo Kak *{$order->user->name}*,\n\n" .
                "Mohon maaf, pesanan Anda dengan nomor *#{$order->order_number}* saat ini tidak dapat kami proses (Stok tidak tersedia / Jadwal penuh).\n\n" .
                "Silakan hubungi Admin kami jika Anda ingin berkonsultasi mengenai opsi kain pengganti.\n\n" .
                "Terima kasih atas pengertiannya. 🙏";

            FonnteService::send($order->user->phone, $pesan);
        }

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' has been rejected.');
    }

    public function storeCart(Request $request)
    {

        $cart = session()->get('cart', []);

        if (!$cart) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'note' => 'nullable|string',
        ]);

        $start = \Carbon\Carbon::parse($request->start_date);
        $end = \Carbon\Carbon::parse($request->end_date);
        $days = $start->diffInDays($end);
        if ($days == 0) {
            $days = 1;
        }

        DB::transaction(function () use ($request, $cart, $days) {

            $grandTotal = 0;
            foreach ($cart as $id => $item) {
                $grandTotal += $item['price'] * $item['quantity'] * $days;
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'RNT-' . strtoupper(uniqid()),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $days,
                'total_price' => $grandTotal,
                'status' => 'pending',
                'note' => $request->note,
            ]);

            foreach ($cart as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'fabric_id' => $id,
                    'quantity' => $item['quantity'],
                    'price_per_meter' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'] * $days,
                ]);
            }

            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Fabric Added to Cart',
                'message' => 'You have placed an order #' . $order->order_number . ' with total price ' . $grandTotal . '.',
                'type' => 'cart_info',
                'is_read' => false,
            ]);

            if ($request->user()->phone) {
                $pesanUser = "Halo Kak *{$request->user()->name}*,\n\n" .
                    "Terima kasih! Pesanan Anda telah berhasil dibuat. Berikut detailnya:\n\n" .
                    "*No. Order:* #{$order->order_number}\n" .
                    "*Total:* Rp " . number_format($grandTotal, 0, ',', '.') . "\n" .
                    "*Status:* Menunggu Konfirmasi Admin\n\n" .
                    "Mohon tunggu sebentar ya, Admin kami akan segera mengecek pesanan Anda.\n\n" .
                    "Terima kasih telah memilih Kana Covers! ";

                FonnteService::send($request->user()->phone, $pesanUser);
            }

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                if ($admin->phone) {
                    $pesanAdmin = "🔔*PESANAN BARU MASUK*\n\n" .
                        "Halo Admin, ada pesanan baru yang perlu diproses:\n\n" .
                        "*Customer:* {$request->user()->name}\n" .
                        "*No. Order:* #{$order->order_number}\n" .
                        "*Total:* Rp " . number_format($grandTotal, 0, ',', '.') . "\n\n" .
                        "Mohon segera login ke dashboard untuk melakukan konfirmasi. ";

                    FonnteService::send($admin->phone, $pesanAdmin);
                }
            }
        });

        session()->forget('cart');
        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}
