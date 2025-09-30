<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function checkout(){
        $cart = session()->get('cart',[]);
        if(!$cart || count($cart)==0){
            return redirect()->route('cart.index')->with('error','Колияката е празна');
        }

        return view('checkout.index', compact('cart'));
    }


    public function placeOrder(Request $request){

        $cart = session()->get('cart',[]);
        if(!$cart || count($cart)==0){
            return redirect()->route('cart.index');
        }


        $request->validate([               //Трябва да се изнесе 
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);


        $total = 0;
        foreach($cart as $item){
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
    'user_id' => Auth::id(),
    'name' => $request->name,
    'email' => $request->email,
    'phone' => $request->phone,
    'address' => $request->address,
    'total_price' => $total,

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

        return redirect()->route('cart.index')->with('success', 'Поръчката е успешно направена!');

    }
}
