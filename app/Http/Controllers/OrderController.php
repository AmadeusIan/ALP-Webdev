<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Fabric;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // POST /api/orders (Checkout Belanja)
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'items' => 'required|array', // Harus berupa array list barang
            'items.*.fabric_id' => 'required|exists:fabrics,id',
            'items.*.quantity_meter' => 'required|numeric|min:0.1',
        ]);

        try {
            // Mulai Transaksi Database
            $order = DB::transaction(function () use ($request) {
                
                $totalOrderPrice = 0;
                $itemsToInsert = [];

                // Looping setiap barang yang dibeli
                foreach ($request->items as $item) {
                    
                    // A. Ambil Data Kain & Kunci Barisnya (PENTING: lockForUpdate)
                    // Ini mencegah User A dan User B beli stok terakhir secara bersamaan
                    $fabric = Fabric::where('id', $item['fabric_id'])->lockForUpdate()->first();

                    // B. Cek Stok Cukup Gak?
                    if ($fabric->stock_meter < $item['quantity_meter']) {
                        throw new \Exception("Stok {$fabric->name} tidak cukup. Sisa: {$fabric->stock_meter}");
                    }

                    // C. Hitung Subtotal Harga
                    $subtotal = $fabric->price_per_meter * $item['quantity_meter'];
                    $totalOrderPrice += $subtotal;

                    // D. Kurangi Stok Kain
                    $fabric->decrement('stock_meter', $item['quantity_meter']);

                    // E. Catat di Log Inventory (History Stok)
                    InventoryLog::create([
                        'fabric_id' => $fabric->id,
                        'change_type' => 'sale', // Tipe: Penjualan
                        'change_amount' => -$item['quantity_meter'], // Minus
                        'note' => 'Penjualan via Aplikasi'
                    ]);

                    // F. Siapkan data untuk tabel order_items
                    $itemsToInsert[] = [
                        'fabric_id' => $fabric->id,
                        'quantity_meter' => $item['quantity_meter'],
                        'total_price' => $subtotal
                    ];
                }

                // 2. Buat Order Header (Data Utama)
                $newOrder = Order::create([
                    'user_id' => $request->user()->id, // Ambil ID dari Token User yang Login
                    'order_date' => now(),
                    'total_price' => $totalOrderPrice,
                    'status' => 'pending' // Status awal
                ]);

                // 3. Masukkan Detail Barang ke tabel order_items
                // createMany adalah fitur Eloquent untuk insert banyak sekaligus
                $newOrder->items()->createMany($itemsToInsert);

                return $newOrder;
            });

            // Jika sukses semua
            return response()->json([
                'message' => 'Order berhasil dibuat!',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            // Jika ada stok kurang atau error lain, transaksi batal otomatis
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}