<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Fabric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function create(Fabric $fabric)
    {
        return view('order.create', compact('fabric'));
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
        $totalPrice = $subtotal; 

        DB::transaction(function () use ($request, $fabric, $days, $totalPrice, $subtotal) {
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'RNT-' . time(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $days,
                'total_price' => $totalPrice,
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

            $orders = Order::with(['user', 'items.fabric'])->latest()->paginate(10);
        } else {
            $orders = Order::with(['items.fabric'])->where('user_id', $user->id)->latest()->paginate(10);
        }

        return view('order.index', compact('orders'));
    }



    public function approve(Order $order)
    {

        $order->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' has been approved!');
    }

    public function reject(Order $order)
    {
        $order->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' has been rejected.');
    }
}