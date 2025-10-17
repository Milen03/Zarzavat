<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class ProfileController extends Controller
{
    
    public function index(){
        if (Auth::check()) {
            $user = Auth::user();
        
        // Алтернативен подход
        $orders = Order::where('user_id', $user->id)
                  ->with('items.product')
                  ->get();
        
        } else {
            // For guests, get orders from session
            $guestOrders = session()->get('guest_orders', []);
            
            if (!empty($guestOrders)) {
                $orderIds = array_keys($guestOrders);
                $orders = Order::whereIn('id', $orderIds)
                         ->with('items.product')
                         ->get();
            } else {
                $orders = collect(); // empty collection
            }
        }
        
        return view('profile.index', compact('orders'));
    }
    
    public function edit(Order $order)
    {
        if (Auth::check()) {
            // For logged-in users - check via Gate
            if (Gate::denies('view', $order)) {
                abort(403);
            }
        } else {
            // For guests - check if the order is in the session
            $guestOrders = session()->get('guest_orders', []);
            if (!isset($guestOrders[$order->id])) {
                abort(403);
            }
        }
        
        $order->load('items.product');
        return view('profile.edit', compact('order'));
    }

    public function update(Request $request, OrderItem $item)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        // Check access to this OrderItem
        if (Auth::check()) {
            $order = $item->order;
            if (Gate::denies('view', $order)) {
                abort(403);
            }
        } else {
            $guestOrders = session()->get('guest_orders', []);
            $order = $item->order;
            if (!isset($guestOrders[$order->id])) {
                abort(403);
            }
        }

        $item->update(['quantity' => $request->quantity]);
        return redirect()->back()->with('success', 'Артикулът е обновен успешно!');
    }

    public function destroy(OrderItem $item)
    {
        // Проверка за достъп до този OrderItem
        if (Auth::check()) {
            $order = $item->order;
            if (Gate::denies('view', $order)) {
                abort(403);
            }
        } else {
            $guestOrders = session()->get('guest_orders', []);
            $order = $item->order;
            if (!isset($guestOrders[$order->id])) {
                abort(403);
            }
        }
        
        $item->delete();
        return redirect()->back()->with('success', 'Артикулът е изтрит успешно!');
    }
    
   
    
}