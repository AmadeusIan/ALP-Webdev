<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Fabric;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\InventoryLog;
use Illuminate\Http\Request;


class FabricController extends Controller
{
    // GET /api/fabrics (Lihat Semua Kain)
    public function index(Request $request)
    {
        // Mengambil data kain beserta nama Kategori & Supplier-nya
        $query = Fabric::with(['category', 'supplier']);

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }


        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $fabrics = $query->latest()->get();
        $categories = Category::all();

        return view('fabrics.index', compact('fabrics', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('fabrics.create', compact('categories', 'suppliers'));
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
            'stock_meter' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        // Gunakan Transaksi Database agar data konsisten
        $fabric = DB::transaction(function () use ($validated) {
            // 1. Buat Kain
            $newFabric = Fabric::create($validated);

            // 2. Catat Log Stok Awal
            InventoryLog::create([
                'fabric_id' => $newFabric->id,
                'user_id' => Auth::id(),
                'change_type' => 'initial',
                'change_amount' => $validated['stock_meter'],
                'note' => 'Stok awal barang baru'
            ]);

            return $newFabric;
        });

        return redirect()->route('fabrics.index')->with('success', 'Fabric added successfully!');
    }

    public function show(Fabric $fabric)
    {
        $fabric->load(['category', 'supplier']);
        return view('fabrics.show', compact('fabric'));
    }

    public function edit(Fabric $fabric)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('fabrics.edit', compact('fabric', 'categories', 'suppliers'));
    }

    public function editStock(Fabric $fabric)
    {
        return view('fabrics.restock', compact('fabric'));
    }


    public function update(Request $request, Fabric $fabric)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'color' => 'nullable|string',
            'material' => 'nullable|string',
            'price_per_meter' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        $fabric->update($validated);

        return redirect()->route('fabrics.show')->with('success', 'Fabric updated successfully!');
    }

    public function updateStock(Request $request, $id)

    {
        $request->validate([
            'change_type' => 'required|in:restock,adjustment,damage', // Jenis perubahan
            'change_amount' => 'required|numeric', // Bisa minus jika barang rusak
            'note' => 'nullable|string'
        ]);

        $fabric = Fabric::findOrFail($id);

        DB::transaction(function () use ($fabric, $request) {
            $input = $request->change_amount;
            $type = $request->change_type;
            $logAmount = 0;

            if ($type === 'adjustment') {
                $logAmount = $input - $fabric->stock_meter;

                $fabric->stock_meter = $input;
            } else {
                if ($type === 'restock') {
                    $logAmount = abs($input);
                } else {
                    $logAmount = -1 * abs($input);
                }
                $fabric->stock_meter += $logAmount;
            }
            $fabric->save();

            // 2. Catat siapa yang mengubah dan kenapa
            InventoryLog::create([
                'fabric_id' => $fabric->id,
                'change_type' => $request->change_type,
                'change_amount' => $request->change_amount,
                'note' => $request->note
            ]);
        });
        return redirect()->route('fabrics.show', $fabric)->with('success', 'Stock updated successfully!');
    }

    public function destroy(Fabric $fabric)
    {
        $fabric->delete();
        return redirect()->route('fabrics.index')->with('success', 'Fabric deleted successfully!');
    }
}
