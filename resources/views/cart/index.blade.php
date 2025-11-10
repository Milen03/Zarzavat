
@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen py-6 bg-gradient-to-br from-green-50 to-emerald-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Моята количка</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button class="text-green-700" onclick="this.parentElement.remove()" aria-label="Затвори известието">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        @if($cart && count($cart) > 0)
            @php
                $total = 0;
                foreach ($cart as $id => $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            @endphp

            <!-- Desktop/table (md+) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 hidden md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full whitespace-nowrap">
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
                            @foreach($cart as $id => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div>
                                                <span class="font-medium">{{ $item['name'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center justify-center space-x-1">
                                            @csrf
                                            <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                                                <button type="button" onclick="decreaseQuantity('{{ $id }}')" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 focus:outline-none" aria-label="Намали">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <label for="quantity-{{ $id }}" class="sr-only">Количество</label>
                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    id="quantity-{{ $id }}"
                                                    value="{{ $item['quantity'] }}"
                                                    min="0.5"
                                                    step="0.5"
                                                    class="w-16 py-1 text-center focus:outline-none focus:ring-1 focus:ring-green-500 border-0"
                                                    onchange="this.form.submit()"
                                                >
                                                <button type="button" onclick="increaseQuantity('{{ $id }}')" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 focus:outline-none" aria-label="Увеличи">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
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
                                            <button type="submit" class="text-red-500 hover:text-red-700 focus:outline-none">Премахни</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile/cards (< md) -->
            <div class="space-y-4 md:hidden">
                @foreach($cart as $id => $item)
                    <div class="bg-white rounded-xl shadow p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="font-semibold">{{ $item['name'] }}</div>
                                <div class="text-sm text-gray-600 mt-1">
                                    Цена: {{ number_format($item['price'], 2) }} лв.
                                </div>
                                <div class="text-sm font-medium mt-1">
                                    Общо: {{ number_format($item['price'] * $item['quantity'], 2) }} лв.
                                </div>
                            </div>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Премахни</button>
                            </form>
                        </div>

                        <form action="{{ route('cart.update', $id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="decreaseQuantity('{{ $id }}')" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200" aria-label="Намали">−</button>
                                <label for="quantity-{{ $id }}" class="sr-only">Количество</label>
                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity-{{ $id }}"
                                    value="{{ $item['quantity'] }}"
                                    min="0.5"
                                    step="0.5"
                                    inputmode="decimal"
                                    class="w-24 py-2 text-center border rounded focus:outline-none focus:ring-1 focus:ring-green-500"
                                    onchange="this.form.submit()"
                                >
                                <button type="button" onclick="increaseQuantity('{{ $id }}')" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200" aria-label="Увеличи">+</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Summary / Actions -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 mt-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-700 font-medium flex items-center">
                            
                           Продължи пазаруването
                        </a>
                    </div>
                    <div class="text-right w-full sm:w-auto">
                        <p class="text-gray-600 mb-1">Сума за плащане:</p>
                        <p class="text-3xl font-bold text-green-600 mb-4">{{ number_format($total, 2) }} лв.</p>
                        <a href="{{ route('checkout') }}" class="block sm:inline-block text-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                            Продължи към поръчка
                        </a>
                        
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13Л5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0а2 2 0 100 4 2 2 0 000-4zm-8 2а2 2 0 11-4 0 2 2 0 014 0з" />
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
@endsection
@push('scripts')
<script>
    window.decreaseQuantity = function(id) {
        const input = document.getElementById('quantity-' + id);
        const currentValue = parseFloat(input.value) || 0;
        if (currentValue > 0.5) {
            input.value = (currentValue - 0.5).toFixed(1);
            input.form.submit();
        }
    }

    window.increaseQuantity = function(id) {
        const input = document.getElementById('quantity-' + id);
        const currentValue = parseFloat(input.value) || 0;
        input.value = (currentValue + 0.5).toFixed(1);
        input.form.submit();
    }
</script>
@endpush
