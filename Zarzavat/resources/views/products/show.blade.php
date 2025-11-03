
@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen py-6 bg-gradient-to-br from-green-50 to-emerald-200">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <!-- Лява колона - Снимка -->
            <div class="md:w-1/2">
                <img 
                    src="{{ asset('storage/' . $products->image) }}" 
                    alt="{{ $products->name }}" 
                    class="w-full h-96 object-cover"
                    onerror="this.src='https://via.placeholder.com/400x400?text=Продукт'"
                >
            </div>
            
            <!-- Дясна колона - Детайли -->
            <div class="p-8 md:w-1/2">
                <div class="mb-2 text-sm text-emerald-600 font-semibold">
                    <a href="{{ route('products.index') }}" class="flex items-center hover:text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Всички продукти
                    </a>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-800 mb-3">{{ $products->name }}</h1>
                
                <div class="flex items-center mb-4">
                    <span class="text-2xl font-bold text-green-600">{{ number_format($products->price, 2) }} лв/кг</span>
                   @if($products->stock <= 0)
        <span class="ml-2 text-sm bg-red-100 text-red-800 py-1 px-2 rounded-full">Не е налично</span>
    @else
        <span class="ml-2 text-sm bg-green-100 text-green-800 py-1 px-2 rounded-full">На склад</span>
    @endif
                </div>
                
                <p class="text-gray-600 mb-6 border-t border-b border-gray-100 py-4">{{ $products->description }}</p>
                
                <form action="{{ route('cart.add', $products->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Добави в количката
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Фиксиране на проблема с фона на цялата страница */
    html, body {
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
</style>

{{-- <script>
    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseFloat(input.value) || 0;
        if (currentValue > 0.5) {
            input.value = (currentValue - 0.5).toFixed(1);
        }
    }
    
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseFloat(input.value) || 0;
        input.value = (currentValue + 0.5).toFixed(1);
    }
</script> --}}
@endsection