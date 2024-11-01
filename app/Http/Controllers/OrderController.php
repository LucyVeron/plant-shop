<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => array_sum(array_column($cart, 'price')),
            'status' => 'pending',
        ]);

        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('orders.show', $order);
    }
}
