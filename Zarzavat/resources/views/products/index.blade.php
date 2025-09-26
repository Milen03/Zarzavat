@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Всички продукти</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <div class="border rounded-lg shadow p-4">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover mb-2">
                <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                <p class="text-gray-600">{{ number_format($product->price, 2) }} лв/кг</p>
                <a href="{{ route('products.show', $product->id) }}" class="mt-2 inline-block px-3 py-1 bg-green-600 text-white rounded">Виж</a>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
