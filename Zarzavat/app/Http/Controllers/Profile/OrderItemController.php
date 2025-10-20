<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Service\OrderService;
use Illuminate\Http\Request;


class OrderItemController extends Controller{
    protected $orderService;

    public function __construct(OrderService $orderService){
        $this->orderService = $orderService;
    }

    public function create($orderId){
        $order = $this->orderService->getOrder($orderId);

        if(!$order){
            return redirect()->route('profile.index')
            ->with('error','Поръчката не е намерена или нямате достъп до нея');
        }

       if (!$this->orderService->isOrderEditable($order)) {
            return redirect()->route('profile.edit', $order->id)
                           ->with('error', 'Може да добавяте продукти само към поръчки със статус "В очакване"');
        }

         // Вземаме всички налични продукти, без тези, които са вече в поръчката
        $existingProductIds = $order->items()->pluck('product_id')->toArray();
        $products = Product::where('stock', '>', 0)
                         ->whereNotIn('id', $existingProductIds)
                         ->orderBy('name')
                         ->get();

                           if ($products->isEmpty()) {
            return redirect()->route('profile.edit', $order->id)
                           ->with('error', 'Няма налични продукти, които да добавите към поръчката');
        }
        
        return view('profile.add-item', compact('order', 'products'));
    }

    public function store(Request $request, $orderId){
         $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

          $order = $this->orderService->getOrder($orderId);
        
        if (!$order) {
            return redirect()->route('profile.index')
                           ->with('error', 'Поръчката не е намерена или нямате достъп до нея');
        }
        
        // Проверка дали поръчката може да се редактира
        if (!$this->orderService->isOrderEditable($order)) {
            return redirect()->route('profile.edit', $order->id)
                           ->with('error', 'Може да добавяте продукти само към поръчки със статус "В очакване"');
        }
        
        // Намираме продукта
        $product = Product::find($request->product_id);
        
        // Проверка дали продуктът е наличен
        if (!$product || $product->stock < $request->quantity) {
            return redirect()->back()
                           ->with('error', 'Продуктът не е наличен в желаното количество');
        }
        
        // Проверяваме дали продуктът вече не е в поръчката
        $existingItem = $order->items()->where('product_id', $product->id)->first();
        if ($existingItem) {
            return redirect()->back()
                           ->with('error', 'Този продукт вече е добавен в поръчката');
        }
        
         // Създаваме нов артикул в поръчката
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);
        
        // Намаляваме количеството на продукта
        $product->stock -= $request->quantity;
        $product->save();
        
        // Преизчисляваме общата цена
        $this->orderService->recalculateOrderTotal($order);
        
        return redirect()->route('profile.edit', $order->id)
                       ->with('success', 'Продуктът е добавен успешно към поръчката');
    }
    
     public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);
        
        $item = OrderItem::findOrFail($itemId);
        $order = $item->order;
        
        // Проверка за достъп
        if (!$this->orderService->hasAccessToOrder($order->id)) {
            return redirect()->route('profile.index')
                           ->with('error', 'Нямате достъп до този елемент');
        }
        
        // Проверка дали поръчката може да се редактира
        if (!$this->orderService->isOrderEditable($order)) {
            return redirect()->route('profile.edit', $order->id)
                           ->with('error', 'Може да редактирате само поръчки със статус "В очакване"');
        }
        
        // Обновяване на количеството
        $item->quantity = $request->quantity;
        $item->save();
        
        // Преизчисляваме общата цена
        $this->orderService->recalculateOrderTotal($order);
        
        return redirect()->route('profile.edit', $order->id)
                       ->with('success', 'Количеството е обновено успешно');
    }
        
     public function destroy($itemId)
    {
        $item = OrderItem::findOrFail($itemId);
        $order = $item->order;
        
        // Проверка за достъп
        if (!$this->orderService->hasAccessToOrder($order->id)) {
            return redirect()->route('profile.index')
                           ->with('error', 'Нямате достъп до този елемент');
        }
        
        // Проверка дали поръчката може да се редактира
        if (!$this->orderService->isOrderEditable($order)) {
            return redirect()->route('profile.edit', $order->id)
                           ->with('error', 'Може да редактирате само поръчки със статус "В очакване"');
        }
        
        // Връщаме количеството обратно на продукта
        if ($item->product) {
            $item->product->stock += $item->quantity;
            $item->product->save();
        }
        
        // Изтриване на елемента
        $item->delete();
        
        // Преизчисляваме общата цена
        $this->orderService->recalculateOrderTotal($order);
        
        // Ако няма повече елементи, можем да пренасочим към списъка с поръчки
        if ($order->items()->count() === 0) {
            $order->delete(); // Изтриване на празната поръчка
            return redirect()->route('profile.index')
                           ->with('success', 'Поръчката е изтрита, защото не съдържаше продукти');
        }
        
        return redirect()->route('profile.edit', $order->id)
                       ->with('success', 'Продуктът е изтрит от поръчката');
    }
}