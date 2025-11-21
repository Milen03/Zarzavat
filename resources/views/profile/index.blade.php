@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6">Моите поръчки</h2>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (count($orders) > 0)
            {{-- Table (desktop) --}}
            <div class="hidden sm:block bg-white rounded-lg shadow overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2">№</th>
                                <th class="px-4 py-2">Дата</th>
                                <th class="px-4 py-2">Статус</th>
                                <th class="px-4 py-2">Сума</th>
                                <th class="px-4 py-2 text-center">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-3">{{ $order->id }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    @if($order->status == 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">В очакване</span>
                                    @elseif($order->status == 'processing')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">В обработка</span>
                                    @elseif($order->status == 'completed')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Изпълнена</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Отказана</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ number_format($order->total_price, 2) }} лв.</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('profile.edit', $order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-xs sm:text-sm">
                                        Детайли
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Card list (mobile) --}}
            <div class="sm:hidden space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white shadow rounded-lg p-4 text-sm">
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">№ {{ $order->id }}</span>
                            <span class="text-gray-500">{{ $order->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div class="mb-2">
                            @if($order->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">В очакване</span>
                            @elseif($order->status == 'processing')
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">В обработка</span>
                            @elseif($order->status == 'completed')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Изпълнена</span>
                            @elseif($order->status == 'cancelled')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Отказана</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">{{ number_format($order->total_price, 2) }} лв.</span>
                        </div>
                        <div>
                            <a href="{{ route('profile.edit', $order->id) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-xs">
                                Детайли
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                <p class="font-medium">Нямате направени поръчки.</p>
                <p>Разгледайте нашите продукти и направете вашата първа поръчка.</p>
            </div>
            <div class="text-center mt-6">
                <a href="{{ route('products.index') }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                    Към продуктите
                </a>
            </div>
        @endif

        @if (!Auth::check())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-6 text-sm sm:text-base" role="alert">
                <h4 class="font-medium mb-2">Важна информация за гости</h4>
                <p>Като гост, вашите поръчки се запазват само в този браузър.</p>
                <p>За постоянен достъп регистрирайте се <a href="{{ route('register.form') }}" class="text-blue-600 underline">тук</a>.</p>
            </div>
        @endif
    </div>
</div>
@endsection