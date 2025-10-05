@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">📦 Всички поръчки</h1>

@if (session('success'))
    <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
@endif

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="p-3">#</th>
            <th class="p-3">Потребител</th>
            <th class="p-3">Обща сума</th>
            <th class="p-3">Статус</th>
            <th class="p-3">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr class="border-t">
            <td class="p-3">{{ $order->id }}</td>
            <td class="p-3">{{ $order->user->name ?? 'Гост' }}</td>
            <td class="p-3">{{ $order->total_price }} лв</td>
            <td class="p-3">{{ ucfirst($order->status) }}</td>
            <td class="p-3">
                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">Детайли</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
