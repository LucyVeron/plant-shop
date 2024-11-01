<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $cart[$product->id] = [
            "name" => $product->name,
            "quantity" => $request->input('quantity', 1),
            "price" => $product->price,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart');
        unset($cart[$product->id]);
        session()->put('cart', $cart);
    }
}
