@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Вход</h1>

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Имейл" class="w-full border p-2" required>
        <input type="password" name="password" placeholder="Парола" class="w-full border p-2" required>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Вход
        </button>
    </form>
</div>
@endsection
