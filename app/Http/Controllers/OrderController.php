<?php

namespace App\Http\Controllers;

use App\Services\FonnteService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Fabric;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\TestStatus\Notice;

class OrderController extends Controller
{
    public function create(Fabric $fabric)
    {
        return view('orders.create', compact('fabric'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fabric_id' => 'required|exists:fabrics,id',
            'quantity' => 'required|numeric|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'note' => 'nullable|string',
        ]);

        $fabric = Fabric::findOrFail($request->fabric_id);

        if ($fabric->stock_meter < $request->quantity) {
            return back()->withErrors(['quantity' => 'Stok tidak cukup! Tersisa: ' . $fabric->stock_meter . 'm']);
        }

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) ?: 1;


        $subtotal = ($fabric->price_per_meter * $request->quantity) * $days;
        $grandTotal = $subtotal;

        DB::transaction(function () use ($request, $fabric, $days, $grandTotal, $subtotal) {

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'RNT-' . time(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $days,
                'total_price' => $grandTotal,
                'status' => 'pending',
                'note' => $request->note,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'fabric_id' => $fabric->id,
                'quantity' => $request->quantity,
                'price_per_meter' => $fabric->price_per_meter,
                'subtotal' => $subtotal,
            ]);


            Notification::create([
                'user_id' => $order->user_id,
                'title' => 'Order Created',
                'message' => 'Your order #' . $order->order_number . ' has been created and is pending approval.',
                'type' => 'order_info',
                'is_read' => false,
            ]);

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'New Order Pending Approval',
                    'message' => 'User ' . $request->user()->name . ' has created order #' . $order->order_number . ' awaiting your approval.',
                    'type' => 'order_info',
                    'is_read' => false,
                ]);
            }


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

            // C. Kurangi Stok?
            // Opsional: Langsung kurangi stok atau tunggu Admin Approve.
            // Untuk rental, biasanya stok "dibooking" (pending), tapi fisik belum keluar.
            // Di sini kita TIDAK kurangi stok dulu agar tercatat di Inventory Log nanti saat barang keluar (Admin Approval).
        });

        return redirect()->route('orders.index')->with('success', 'Booking successful! Waiting for approval.');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {

            $orders = Order::with(['user', 'items.fabric', 'items.reviewItem'])->latest()->paginate(10);
        } else {
            $orders = Order::with(['items.fabric', 'items.reviewItem'])->where('user_id', $user->id)->latest()->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }

    public function show($id)
{
    $order = Order::where('id', $id)
        ->where('user_id', Auth::id())
        ->with([
            'items.fabric',
            'items.reviewItem'
        ])
        ->firstOrFail();

    return view('orders.show', compact('order'));
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
