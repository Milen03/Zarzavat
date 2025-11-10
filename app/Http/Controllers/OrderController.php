<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (! $cart || count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Количката е празна');
        }

        return view('checkout.index', compact('cart'));
    }

    public function placeOrder(OrderRequest $request)
    {

        $cart = session()->get('cart', []);
        if (! $cart || count($cart) == 0) {
            return redirect()->route('cart.index');
        }

        $validated = $request->validated();

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'total_price' => $total,
        ]);

        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            $product = Product::find($id);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }

        }

        if (! Auth::check()) {
            $guestOrders = session()->get('guest_orders', []);
            $guestOrders[$order->id] = true;
            session()->put('guest_orders', $guestOrders);
        }

        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Поръчката е успешно направена!');
    }
}
