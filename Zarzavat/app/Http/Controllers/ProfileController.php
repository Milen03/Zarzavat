<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // За логнати потребители
            $orders = Order::where('user_id', Auth::id())
                         ->orderBy('created_at', 'desc')
                         ->get();
        } else {
            // За гости - взимаме поръчките от сесията
            $guestOrders = session()->get('guest_orders', []);
            
            if (empty($guestOrders)) {
                $orders = collect(); // празна колекция
            } else {
                $orderIds = array_keys($guestOrders);
                $orders = Order::whereIn('id', $orderIds)
                             ->orderBy('created_at', 'desc')
                             ->get();
            }
        }
        
        return view('profile.index', compact('orders'));
    }
    
    public function edit($orderId) // променено за да работи с ID вместо с Order модел
    {
        if (Auth::check()) {
            // За регистрирани потребители
            $order = Order::where('id', $orderId)
                        ->where('user_id', Auth::id())
                        ->with('items.product')
                        ->first();
        } else {
            // За гости
            $guestOrders = session()->get('guest_orders', []);
            
            if (isset($guestOrders[$orderId])) {
                $order = Order::with('items.product')
                            ->find($orderId);
            } else {
                $order = null;
            }
        }

        if (!$order) {
            return redirect()->route('profile.index')
                           ->with('error', 'Поръчката не е намерена или нямате достъп до нея');
        }
        
        return view('profile.edit', compact('order'));
    }

    public function update(Request $request, $itemId) // променено за да работи с ID вместо с OrderItem модел
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        $item = OrderItem::findOrFail($itemId);
        $order = $item->order;
        
        // Проверка за достъп
        if (Auth::check()) {
            $hasAccess = $order->user_id === Auth::id();
        } else {
            $guestOrders = session()->get('guest_orders', []);
            $hasAccess = isset($guestOrders[$order->id]);
        }

        if (!$hasAccess) {
            return redirect()->route('profile.index')
                           ->with('error', 'Нямате достъп до този елемент');
        }

        // Обновяване на количеството
        $item->quantity = $request->quantity;
        $item->save();
        
        // Преизчисляване на общата цена
        $totalPrice = $order->items()->sum(DB::raw('price * quantity'));
        $order->total_price = $totalPrice;
        $order->save();
        
        return redirect()->route('profile.edit', $order->id)
                       ->with('success', 'Количеството е обновено успешно');
    }

    public function destroy($itemId) // променено за да работи с ID вместо с OrderItem модел
    {
        $item = OrderItem::findOrFail($itemId);
        $order = $item->order;
        
        // Проверка за достъп
        if (Auth::check()) {
            $hasAccess = $order->user_id === Auth::id();
        } else {
            $guestOrders = session()->get('guest_orders', []);
            $hasAccess = isset($guestOrders[$order->id]);
        }

        if (!$hasAccess) {
            return redirect()->route('profile.index')
                           ->with('error', 'Нямате достъп до този елемент');
        }

        // Изтриване на елемента
        $item->delete();
        
        // Преизчисляване на общата цена
        $totalPrice = $order->items()->sum(DB::raw('price * quantity'));
        $order->total_price = $totalPrice;
        $order->save();
        
        // Ако няма повече елементи, можем да пренасочим към списъка с поръчки
        if ($order->items()->count() === 0) {
            $order->delete(); // Опционално - изтриване на празната поръчка
            return redirect()->route('profile.index')
                           ->with('success', 'Поръчката е изтрита, защото не съдържаше продукти');
        }
        
        return redirect()->route('profile.edit', $order->id)
                       ->with('success', 'Продуктът е изтрит от поръчката');
    }
}