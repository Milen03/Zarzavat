@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Поръчка #{{ $order->id }}</h1>

<div class="bg-white p-6 rounded shadow mb-4">
    <p><strong>Име:</strong> {{ $order->user->name ?? $order->name }}</p>
    <p><strong>Телефон:</strong> {{ $order->phone }}</p>
    <p><strong>Адрес:</strong> {{ $order->address }}</p>
    <p><strong>Обща цена:</strong> {{ $order->total_price }} лв</p>

    <div class="flex mt-4 space-x-4">


        <!-- Форма за изтриване на поръчка -->
        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded" 
                onclick="return confirm('Сигурни ли сте, че искате да изтриете тази поръчка?')">
                Изтриване
            </button>
        </form>
    </div>
</div>

<h2 class="text-xl font-semibold mb-2">Продукти в поръчката:</h2>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="p-3">Продукт</th>
            <th class="p-3">Количество</th>
            <th class="p-3">Цена</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
        <tr class="border-t">
            <td class="p-3">{{ $item->product->name }}</td>
            <td class="p-3">{{ $item->quantity }}</td>
            <td class="p-3">{{ $item->price }} лв</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
