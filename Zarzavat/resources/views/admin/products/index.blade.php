@extends('layouts.app')

@section('content')
<div class="flex justify-between mb-4">
    <h1 class="text-2xl font-bold">🛒 Продукти</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">+ Нов продукт</a>
</div>

@if (session('success'))
    <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
@endif

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="p-3">#</th>
            <th class="p-3">Име</th>
            <th class="p-3">Цена</th>
            <th class="p-3">Количество</th>
            <th class="p-3">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr class="border-t">
            <td class="p-3">{{ $product->id }}</td>
            <td class="p-3">{{ $product->name }}</td>
            <td class="p-3">{{ $product->price }} лв</td>
            <td class="p-3">{{ $product->stock }}</td>
            <td class="p-3 flex space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">Редактирай</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline" onclick="return confirm('Сигурен ли си?')">Изтрий</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
