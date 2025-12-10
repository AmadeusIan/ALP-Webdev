<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $fabric = Fabric::findOrFail($id);
        $cart = session()->get('cart', []);

        $quantity = $request->input('quantity', 1);


        if($quantity > $fabric->stock_meter) {
            return redirect()->back()->with('error', 'Stok tidak cukup!');
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $fabric->name,
                "quantity" => $quantity,
                "price" => $fabric->price_per_meter,
                "image" => $fabric->image,
                "max_stock" => $fabric->stock
            ];
        }

        


        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Fabric added to cart successfully!');
    }

    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {

        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Fabric removed successfully');
        }
        return redirect()->back();
    }
}
