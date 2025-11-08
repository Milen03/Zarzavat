@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Всички поръчки</h1>

@if (session('success'))
    <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
@endif

<div class="mb-4 flex gap-2">
    <a href="{{ route('admin.orders.index', array_filter(['status' => request('status')])) }}"
       class="px-3 py-1 rounded {{ request()->boolean('archived') ? 'bg-gray-200' : 'bg-blue-200' }}">
        Активни
    </a>
    <a href="{{ route('admin.orders.index', array_filter(['status' => request('status'), 'archived' => 1])) }}"
       class="px-3 py-1 rounded {{ request()->boolean('archived') ? 'bg-blue-200' : 'bg-gray-200' }}">
        Архив
    </a>
</div>

<div class="mb-4">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex space-x-2">
        @if(request()->boolean('archived'))
            <input type="hidden" name="archived" value="1">
        @endif
        <select name="status" class="border rounded px-3 py-1">
            <option value="">Всички статуси</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>В очакване</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>В обработка</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Изпълнени</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отказани</option>
        </select>
        <button type="submit" class="bg-gray-200 px-3 py-1 rounded">Филтрирай</button>
        @if(request('status'))
            <a href="{{ route('admin.orders.index', request()->boolean('archived') ? ['archived' => 1] : []) }}" class="text-gray-600 px-3 py-1">Изчисти</a>
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
            <td class="p-3">
                {{ $order->id }}
                @if(method_exists($order, 'trashed') && $order->trashed())
                    <span class="text-xs text-red-600 ml-1">[Архив]</span>
                @endif
            </td>
            <td class="p-3">{{ $order->user->name ?? $order->name }}</td>
            <td class="p-3">{{ number_format((float)$order->total_price, 2) }} лв</td>
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
                @if(method_exists($order, 'trashed') && $order->trashed())
                    <form method="POST" action="{{ route('admin.orders.restore', $order->id) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button class="text-green-700 hover:underline">Възстанови</button>
                    </form>
                    <form method="POST" action="{{ route('admin.orders.force', $order->id) }}" class="inline ml-3">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-700 hover:underline">Изтрий окончателно</button>
                    </form>
                @else
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">Детайли</a>
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="inline ml-3">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Архивирай</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $orders->withQueryString()->links() }}
</div>
@endsection