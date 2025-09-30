@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Регистрация</h1>

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Име" class="w-full border p-2" required>
        <input type="email" name="email" placeholder="Имейл" class="w-full border p-2" required>
        <input type="password" name="password" placeholder="Парола" class="w-full border p-2" required>
        <input type="password" name="password_confirmation" placeholder="Повтори паролата" class="w-full border p-2" required>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
            Регистрация
        </button>
    </form>
</div>
@endsection
