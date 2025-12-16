<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Fabric;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\InventoryLog;
use Illuminate\Http\Request;

class FabricController extends Controller
{
    public function homepage(){
        $fabrics = Fabric::latest()->take(6)->get();
        $reviews = collect([
            (object)[
                'user' => (object)['name' => 'Sarah Amalia'],
                'rating' => 5,
                'review' => 'Kualitas kain sangat bagus, dingin dan jatuh. Cocok untuk gaun pesta.'
            ],
            (object)[
                'user' => (object)['name' => 'Budi Santoso'],
                'rating' => 5,
                'review' => 'Pelayanan sewa sangat cepat dan admin ramah. Recommended vendor!'
            ],
            (object)[
                'user' => (object)['name' => 'Jessica Tan'],
                'rating' => 4,
                'review' => 'Pilihan warnanya lengkap banget. Suka sekali dengan koleksi sutranya.'
            ],
        ]);

        return view('welcome', compact('fabrics', 'reviews'));
    }
    
    public function index(Request $request)
    {
        $query = Fabric::with(['category', 'supplier']);

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $fabrics = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('fabrics.index', compact('fabrics', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('fabrics.create', compact('categories', 'suppliers'));
    }

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string'
        ]);

        // Handle upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('fabrics', 'public');
            $validated['image'] = $imagePath;
        }

        $fabric = DB::transaction(function () use ($validated) {
            $newFabric = Fabric::create($validated);

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

    public function show(Fabric $fabric){
        $fabric->load([
            'category',
            'supplier',
            'orderItems.reviewItem'
        ]);

        $reviews = $fabric->orderItems
            ->pluck('reviewItem')
            ->filter();

        $averageRating = round($reviews->avg('rating'), 1);
        $totalReviews = $reviews->count();

        return view('fabrics.show', compact(
            'fabric',
            'reviews',
            'averageRating',
            'totalReviews'
        ));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string'
        ]);

        // Handle update gambar
        if ($request->hasFile('image')) {
            if ($fabric->image) {
                Storage::disk('public')->delete($fabric->image);
            }
            $imagePath = $request->file('image')->store('fabrics', 'public');
            $validated['image'] = $imagePath;
        }

        $fabric->update($validated);

        return redirect()->route('fabrics.show', $fabric)->with('success', 'Fabric updated successfully!');
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'change_type' => 'required|in:restock,adjustment,damage',
            'change_amount' => 'required|numeric',
            'note' => 'nullable|string'
        ]);

        $fabric = Fabric::findOrFail($id);

        DB::transaction(function () use ($fabric, $request) {
            $input = $request->change_amount;
            $type = $request->change_type;
            $currentStock = $fabric->stock_meter;
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
            }

            $newstock = $currentStock + $logAmount;

            if ($newstock < 0){
                throw ValidationException::withMessages([
                    'change_amount' => 'Stok tidak mencukupi! Stok saat ini: ' . $currentStock
                ]);
            }

            $fabric->stock_meter = $newstock;
            $fabric->save();

            InventoryLog::create([
                'fabric_id' => $fabric->id,
                'user_id' => Auth::id() ?? 1,
                'change_type' => $request->change_type,
                'change_amount' => $logAmount,
                'note' => $request->note
            ]);
        });

        return redirect()->route('fabrics.show', $fabric)->with('success', 'Stock updated successfully!');
    }

    public function destroy(Fabric $fabric)
    {
        if ($fabric->image) {
            Storage::disk('public')->delete($fabric->image);
        }
        $fabric->delete();
        return redirect()->route('fabrics.index')->with('success', 'Fabric deleted successfully!');
    }
}