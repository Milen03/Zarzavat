@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex flex-col md:flex-row gap-6">
        {{-- <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-64 h-64 object-cover rounded"> --}}
        
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $products->name }}</h1>
            <p class="text-gray-700 mb-4">{{ $products->description }}</p>
            <p class="text-xl font-semibold">{{ number_format($products->price, 2) }} лв/кг</p>
            
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Добави в количката</button>
            </form>
        </div>
    </div>
</div>
@endsection
