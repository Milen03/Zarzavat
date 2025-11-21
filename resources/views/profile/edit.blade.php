@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-4">Поръчка #{{ $order->id }}</h2>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-medium mb-2">Информация за поръчката</h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm">
                    <p class="mb-2"><span class="font-medium">Номер:</span> {{ $order->id }}</p>
                    <p class="mb-2"><span class="font-medium">Дата:</span> {{ $order->created_at->setTimezone('Europe/Sofia')->format('d.m.Y H:i') }}</p>
                    <p class="mb-2"><span class="font-medium">Статус:</span>
                        @php($s=$order->status)
                        <span class="px-2 py-1 rounded-full text-xs
                            @class([
                                'bg-yellow-100 text-yellow-800'=>$s==='pending',
                                'bg-blue-100 text-blue-800'=>$s==='processing',
                                'bg-green-100 text-green-800'=>$s==='completed',
                                'bg-red-100 text-red-800'=>$s==='cancelled',
                            ])">
                            @switch($s)
                                @case('pending') В очакване @break
                                @case('processing') В обработка @break
                                @case('completed') Изпълнена @break
                                @case('cancelled') Отказана @break
                            @endswitch
                        </span>
                    </p>
                    <p><span class="font-medium">Обща сума:</span> {{ number_format($order->total_price,2) }} лв.</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-medium mb-2">Информация за доставка</h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm">
                    <p class="mb-2"><span class="font-medium">Име:</span> {{ $order->name }}</p>
                    <p class="mb-2"><span class="font-medium">Имейл:</span> {{ $order->email }}</p>
                    <p class="mb-2"><span class="font-medium">Телефон:</span> {{ $order->phone }}</p>
                    <p><span class="font-medium">Адрес:</span> {{ $order->address }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Продукти в поръчката</h3>
            @if($order->status === 'pending')
                <a href="{{ route('profile.add-item.form', $order->id) }}" class="bg-green-500 hover:bg-green-600 text-white py-1 px-4 rounded flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Добави продукт
                </a>
            @endif
        </div>

        {{-- Desktop table --}}
        <div class="hidden sm:block overflow-x-auto mb-6">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Продукт</th>
                        <th class="px-4 py-2 text-center">Цена</th>
                        <th class="px-4 py-2 text-center">Количество</th>
                        <th class="px-4 py-2 text-right">Общо</th>
                        <th class="px-4 py-2 text-center">Действия</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            {{ optional($item->product)->name ?? 'Продуктът не е наличен' }}
                        </td>
                        <td class="px-4 py-3 text-center">{{ number_format($item->price,2) }} лв.</td>
                        <td class="px-4 py-3 text-center">
                            @if($order->status === 'pending')
                                <form action="{{ route('profile.update', $item->id) }}" method="POST" class="flex items-center justify-center">
                                    @csrf @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border rounded px-2 py-1 text-center">
                                    <button class="ml-2 bg-green-500 hover:bg-green-600 text-white rounded px-2 py-1 text-xs">Обнови</button>
                                </form>
                            @else
                                {{ $item->quantity }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->price * $item->quantity,2) }} лв.</td>
                        <td class="px-4 py-3 text-center">
                            @if($order->status === 'pending')
                                <form action="{{ route('profile.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Премахване на продукта?')">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-500 hover:bg-red-600 text-white rounded px-2 py-1 text-xs">Премахни</button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">Заключено</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td colspan="3" class="px-4 py-3 text-right font-medium">Обща сума:</td>
                        <td class="px-4 py-3 text-right font-bold">{{ number_format($order->total_price,2) }} лв.</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="sm:hidden space-y-4 mb-6">
            @foreach($order->items as $item)
                <div class="border border-gray-200 rounded-lg p-4 bg-white text-sm">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">
                            {{ optional($item->product)->name ?? 'Няма продукт' }}
                        </span>
                        <span class="text-gray-500">{{ number_format($item->price,2) }} лв.</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Количество:</span>
                        @if($order->status === 'pending')
                            <form action="{{ route('profile.update', $item->id) }}" method="POST" class="flex items-center">
                                @csrf @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border rounded px-2 py-1 text-center">
                                <button class="ml-2 bg-green-500 hover:bg-green-600 text-white rounded px-2 py-1 text-xs">OK</button>
                            </form>
                        @else
                            <span>{{ $item->quantity }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Общо:</span>
                        <span class="font-semibold">{{ number_format($item->price * $item->quantity,2) }} лв.</span>
                    </div>
                    <div class="mt-2">
                        @if($order->status === 'pending')
                            <form action="{{ route('profile.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Премахване?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white rounded px-3 py-1 text-xs w-full">Премахни</button>
                            </form>
                        @else
                            <span class="block text-center text-gray-400 text-xs">Заключено</span>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="border-t pt-4 text-right font-bold">Общо: {{ number_format($order->total_price,2) }} лв.</div>
        </div>

        <div class="text-center">
            <a href="{{ route('profile.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">Назад</a>
        </div>
    </div>
</div>
@endsection