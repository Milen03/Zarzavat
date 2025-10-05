@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Нов продукт</h1>

<form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
    @csrf

    <div>
        <label class="block mb-1 font-semibold">Име:</label>
        <input type="text" name="name" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block mb-1 font-semibold">Описание:</label>
        <textarea name="description" class="w-full border rounded p-2"></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 font-semibold">Цена (лв):</label>
            <input type="number" step="0.01" name="price" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Количество:</label>
            <input type="number" name="stock" class="w-full border rounded p-2" required>
        </div>
    </div>

    <div>
        <label class="block mb-1 font-semibold">Категория:</label>
        <select name="category_id" class="w-full border rounded p-2" required>
            <option value="">Изберете категория</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1 font-semibold">URL на снимка:</label>
        <input type="text" name="image" class="w-full border rounded p-2">
    </div>

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Запази</button>
</form>
@endsection
