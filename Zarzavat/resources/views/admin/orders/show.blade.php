@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Поръчка #{{ $order->id }}</h1>

<div class="bg-white p-6 rounded shadow mb-4">
    <p><strong>Име:</strong> {{ $order->user->name ?? $order->name }}</p>
    <p><strong>Телефон:</strong> {{ $order->phone }}</p>
    <p><strong>Адрес:</strong> {{ $order->address }}</p>
    <p><strong>Обща цена:</strong> {{ $order->total_price }} лв</p>
    <p><strong>Текущ статус:</strong> 
        <span class="px-2 py-1 rounded text-sm
        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
        @elseif($order->status == 'completed') bg-green-100 text-green-800
        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
        @endif">
            {{ ucfirst($order->status) }}
        </span>
    </p>

    <div class="mt-4">
        <form action="{{ route('admin.orders.update.status', $order) }}" method="POST" class="flex items-center space-x-2">
            @csrf
            @method('PATCH')
            
            <select name="status" class="border rounded px-2 py-1">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>В очакване</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработка</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Изпълнена</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отказана</option>
            </select>
            
            <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">
                Обнови статус
            </button>
        </form>
    </div>

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