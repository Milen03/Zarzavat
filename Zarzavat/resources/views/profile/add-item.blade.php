
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-4">Добавяне на продукт към Поръчка #{{ $order->id }}</h2>
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('profile.add-item', $order->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="product_id" class="block text-gray-700 font-medium mb-2">Изберете продукт</label>
                <select id="product_id" name="product_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-green-500" required>
                    <option value="">-- Изберете продукт --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                            {{ $product->name }} - {{ number_format($product->price, 2) }} лв. ({{ $product->stock }} налични)
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="quantity" class="block text-gray-700 font-medium mb-2">Количество</label>
                <div class="flex items-center">
                    <input type="number" id="quantity" name="quantity" min="1" value="1" class="border border-gray-300 rounded-md px-3 py-2 w-32 focus:outline-none focus:border-green-500" required>
                    <div class="ml-4">
                        <span class="text-sm text-gray-600">Налични: <span id="available-stock">-</span></span>
                    </div>
                </div>
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6 p-4 bg-gray-50 rounded-md">
                <div class="flex justify-between items-center">
                    <span class="font-medium">Цена за единица:</span>
                    <span id="unit-price">0.00 лв.</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-medium">Общо:</span>
                    <span id="total-price" class="font-bold">0.00 лв.</span>
                </div>
            </div>
            
            <div class="flex justify-between">
                <a href="{{ route('profile.edit', $order->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                    Отказ
                </a>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-6 rounded">
                    Добави към поръчката
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const availableStock = document.getElementById('available-stock');
        const unitPrice = document.getElementById('unit-price');
        const totalPrice = document.getElementById('total-price');
        
        function updateProductInfo() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            
            if (selectedOption.value) {
                const price = parseFloat(selectedOption.dataset.price);
                const stock = parseInt(selectedOption.dataset.stock);
                
                availableStock.textContent = stock;
                unitPrice.textContent = price.toFixed(2) + ' лв.';
                
                // Ограничаваме количеството до наличността
                quantityInput.max = stock;
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }
                
                updateTotal();
            } else {
                availableStock.textContent = '-';
                unitPrice.textContent = '0.00 лв.';
                totalPrice.textContent = '0.00 лв.';
            }
        }
        
        function updateTotal() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            
            if (selectedOption.value) {
                const price = parseFloat(selectedOption.dataset.price);
                const quantity = parseInt(quantityInput.value);
                
                const total = price * quantity;
                totalPrice.textContent = total.toFixed(2) + ' лв.';
            }
        }
        
        productSelect.addEventListener('change', updateProductInfo);
        quantityInput.addEventListener('change', updateTotal);
        quantityInput.addEventListener('input', updateTotal);
        
        // Инициализация
        updateProductInfo();
    });
</script>
@endsection