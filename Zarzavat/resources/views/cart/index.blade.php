
@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen py-6 bg-gradient-to-br from-green-50 to-emerald-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Моята количка</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button class="text-green-700" onclick="this.parentElement.remove()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        @if($cart && count($cart) > 0)
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="py-4 px-6 text-left font-semibold">Продукт</th>
                                <th class="py-4 px-6 text-center font-semibold">Количество (кг)</th>
                                <th class="py-4 px-6 text-center font-semibold">Цена</th>
                                <th class="py-4 px-6 text-center font-semibold">Общо</th>
                                <th class="py-4 px-6 text-right font-semibold">Действие</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $item)
                                @php $total += $item['price'] * $item['quantity']; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <!-- Ако имате снимка, може да я добавите тук -->
                                            <div>
                                                <span class="font-medium">{{ $item['name'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center justify-center space-x-1">
                                            @csrf
                                            <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                                                <button type="button" onclick="decreaseQuantity('{{ $id }}')" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <input 
                                                    type="number" 
                                                    name="quantity" 
                                                    id="quantity-{{ $id }}" 
                                                    value="{{ $item['quantity'] }}" 
                                                    min="0.5" 
                                                    step="0.5" 
                                                    class="w-14 py-1 text-center focus:outline-none focus:ring-1 focus:ring-green-500 border-0"
                                                    onchange="this.form.submit()"
                                                >
                                                <button type="button" onclick="increaseQuantity('{{ $id }}')" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="py-4 px-6 text-center">{{ number_format($item['price'], 2) }} лв.</td>
                                    <td class="py-4 px-6 text-center font-medium">{{ number_format($item['price'] * $item['quantity'], 2) }} лв.</td>
                                    <td class="py-4 px-6 text-right">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div class="mb-4 sm:mb-0">
                        <a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-700 font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Продължи пазаруването
                        </a>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-gray-600 mb-1">Сума за плащане:</p>
                        <p class="text-3xl font-bold text-green-600 mb-4">{{ number_format($total, 2) }} лв.</p>
                        <a href="{{ route('checkout') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium inline-block transition-colors duration-200">
                            Продължи към поръчка
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="text-xl font-semibold mb-2">Вашата количка е празна</h2>
                <p class="text-gray-600 mb-6">Разгледайте нашите продукти и добавете нещо във вашата количка.</p>
                <a href="{{ route('products.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium inline-block transition-colors duration-200">
                    Разгледай продукти
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    function decreaseQuantity(id) {
        const input = document.getElementById('quantity-' + id);
        const currentValue = parseFloat(input.value) || 0;
        if (currentValue > 0.5) {
            input.value = (currentValue - 0.5).toFixed(1);
            input.form.submit();
        }
    }
    
    function increaseQuantity(id) {
        const input = document.getElementById('quantity-' + id);
        const currentValue = parseFloat(input.value) || 0;
        input.value = (currentValue + 0.5).toFixed(1);
        input.form.submit();
    }
</script>
@endsection