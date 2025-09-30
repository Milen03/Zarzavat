@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Завършване на поръчка</h1>

    <form action="{{ route('checkout.place') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Име" class="w-full border p-2" required>
        <input type="email" name="email" placeholder="Имейл" class="w-full border p-2" required>
        <input type="text" name="phone" placeholder="Телефон" class="w-full border p-2" required>
        <input type="text" name="address" placeholder="Адрес" class="w-full border p-2" required>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
            Потвърди поръчката
        </button>
    </form>
</div>
@endsection
