<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FabricController extends Controller
{
    // GET /api/fabrics (Lihat Semua Kain)
    public function index()
    {
        // Mengambil data kain beserta nama Kategori & Supplier-nya
        $fabrics = Fabric::with(['category', 'supplier'])->get();
        return response()->json(['data' => $fabrics]);
    }

    // POST /api/fabrics (Tambah Kain Baru)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'color' => 'nullable|string',
            'material' => 'nullable|string',
            'price_per_meter' => 'required|numeric',
            'stock_meter' => 'required|numeric|min:0', // Stok awal
        ]);

        // Gunakan Transaksi Database agar data konsisten
        $fabric = DB::transaction(function () use ($validated) {
            // 1. Buat Kain
            $newFabric = Fabric::create($validated);

            // 2. Catat Log Stok Awal
            InventoryLog::create([
                'fabric_id' => $newFabric->id,
                'change_type' => 'initial',
                'change_amount' => $validated['stock_meter'],
                'note' => 'Stok awal barang baru'
            ]);

            return $newFabric;
        });

        return response()->json(['message' => 'Kain berhasil ditambahkan', 'data' => $fabric], 201);
    }

    // POST /api/fabrics/{id}/stock (Update Stok: Restock atau Koreksi)
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'change_type' => 'required|in:restock,adjustment,damage', // Jenis perubahan
            'change_amount' => 'required|numeric', // Bisa minus jika barang rusak
            'note' => 'nullable|string'
        ]);

        $fabric = Fabric::findOrFail($id);

        DB::transaction(function () use ($fabric, $request) {
            // 1. Update jumlah stok di tabel fabrics
            // Jika restock: nambah. Jika damage: berkurang.
            $fabric->stock_meter += $request->change_amount;
            $fabric->save();

            // 2. Catat siapa yang mengubah dan kenapa
            InventoryLog::create([
                'fabric_id' => $fabric->id,
                'change_type' => $request->change_type,
                'change_amount' => $request->change_amount,
                'note' => $request->note
            ]);
        });

        return response()->json([
            'message' => 'Stok berhasil diperbarui', 
            'current_stock' => $fabric->stock_meter
        ]);
    }
}