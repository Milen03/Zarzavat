<?php

namespace App\Service;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService{
    public function hasAccessToOrder($orderId)
    {
        if (Auth::check()) {
            return Order::where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->exists();
        } else {
            $guestOrders = session()->get('guest_orders', []);
            return isset($guestOrders[$orderId]);
        }
    }
    
    /**
     * Взимане на поръчка с проверка за достъп
     */
    public function getOrder($orderId, $withRelations = [])
    {
        if (Auth::check()) {
            // За регистрирани потребители
            $query = Order::where('id', $orderId)
                          ->where('user_id', Auth::id());
        } else {
            // За гости
            $guestOrders = session()->get('guest_orders', []);
            
            if (!isset($guestOrders[$orderId])) {
                return null;
            }
            
            $query = Order::where('id', $orderId);
        }
        
        // Добавяне на релации, ако са посочени
        if (!empty($withRelations)) {
            $query->with($withRelations);
        }
        
        return $query->first();
    }
    
    /**
     * Преизчисляване на общата цена на поръчката
     */
    public function recalculateOrderTotal(Order $order)
    {
        $totalPrice = $order->items()->sum(DB::raw('price * quantity'));
        $order->total_price = $totalPrice;
        $order->save();
        
        return $order;
    }
    
    /**
     * Проверка дали поръчката може да бъде редактирана
     */
    public function isOrderEditable(Order $order)
    {
        return $order->status === 'pending';
    }
}
