@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Моята количка</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($cart && count($cart) > 0)
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="border p-2">Продукт</th>
                    <th class="border p-2">Количество</th>
                    <th class="border p-2">Цена</th>
                    <th class="border p-2">Общо</th>
                    <th class="border p-2">Действие</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $id => $item)
                    @php $total += $item['price'] * $item['quantity']; @endphp
                    <tr>
                        <td class="border p-2">{{ $item['name'] }}</td>
                        <td class="border p-2">
                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border">
                                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Обнови</button>
                            </form>
                        </td>
                        <td class="border p-2">{{ $item['price'] }} лв.</td>
                        <td class="border p-2">{{ $item['price'] * $item['quantity'] }} лв.</td>
                        <td class="border p-2">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Премахни</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <h2 class="text-xl font-bold">Общо: {{ $total }} лв.</h2>
          <a href="{{ route('checkout') }}" class="bg-green-600 text-white px-4 py-2 rounded">Към поръчка</a>
        </div>
    @else
        <p>Количката е празна.</p>
    @endif
</div>
@endsection
