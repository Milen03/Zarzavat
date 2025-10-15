@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Всички поръчки</h1>

@if (session('success'))
    <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
@endif

<div class="mb-4">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex space-x-2">
        <select name="status" class="border rounded px-3 py-1">
            <option value="">Всички статуси</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>В очакване</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>В обработка</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Изпълнени</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отказани</option>
        </select>
        <button type="submit" class="bg-gray-200 px-3 py-1 rounded">Филтрирай</button>
        @if(request('status'))
            <a href="{{ route('admin.orders.index') }}" class="text-gray-600 px-3 py-1">Изчисти</a>
        @endif
    </form>
</div>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="p-3">#</th>
            <th class="p-3">Потребител</th>
            <th class="p-3">Обща сума</th>
            <th class="p-3">Статус</th>
            <th class="p-3">Дата</th>
            <th class="p-3">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr class="border-t">
            <td class="p-3">{{ $order->id }}</td>
            <td class="p-3">{{ $order->user->name ?? $order->name }}</td>
            <td class="p-3">{{ $order->total_price }} лв</td>
            <td class="p-3">
                <span class="px-2 py-1 rounded text-xs
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                @elseif($order->status == 'completed') bg-green-100 text-green-800
                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                @endif">
                    {{ $order->status == 'pending' ? 'В очакване' :
                       ($order->status == 'processing' ? 'В обработка' :
                       ($order->status == 'completed' ? 'Изпълнена' : 'Отказана')) }}
                </span>
            </td>
            <td class="p-3">{{ $order->created_at->format('d.m.Y H:i') }}</td>
            <td class="p-3">
                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">Детайли</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection