
@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen py-6 bg-gradient-to-br from-green-50 to-emerald-200">
    <h1 class="text-3xl font-bold mb-6 px-4 sm:px-6 lg:px-8">Всички продукти</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 sm:px-6 lg:px-8">
        @foreach ($products as $product)
            <div class="bg-white border rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                <img 
                    src="{{ Storage::disk(config('filesystems.default'))->url($product->image) }}" 
                    alt="{{ $product->name }}" 
                    class="w-full h-48 object-cover"
                >
                <div class="p-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h2>
                    <p class="text-green-600 font-semibold text-lg mb-3">{{ number_format($product->price, 2) }} лв/кг</p>
                    <a href="{{ route('products.show', $product->id) }}" 
                       class="inline-block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Виж детайли
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 px-4 sm:px-6 lg:px-8">
        {{ $products->links() }}
    </div>
</div>
@endsection