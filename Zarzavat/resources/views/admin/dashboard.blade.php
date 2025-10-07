@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Админ панел</h1>

    <div class="grid grid-cols-3 gap-6">
        <div class="p-6 bg-white shadow rounded">
            <h2 class="text-lg font-semibold">Продукти</h2>
            <p class="text-gray-600">Общо: {{ $productsCount }}</p>
            <a href="{{ route('admin.products.index') }}" class="text-green-600 hover:underline">Управление</a>
        </div>

        <div class="p-6 bg-white shadow rounded">
            <h2 class="text-lg font-semibold">Поръчки</h2>
            <p class="text-gray-600">Общо: {{ $ordersCount }}</p>
            <a href="{{ route('admin.orders.index') }}" class="text-green-600 hover:underline">Виж всички</a>
        </div>

        <div class="p-6 bg-white shadow rounded">
            <h2 class="text-lg font-semibold">Потребители</h2>
            <p class="text-gray-600">Общо: {{ $usersCount }}</p>
        </div>
    </div>
@endsection
