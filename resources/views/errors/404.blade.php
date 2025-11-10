
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-200 p-4">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-emerald-700 mb-4">404</h1>
        <p class="text-2xl text-gray-700 mb-6">Страницата не е намерена</p>
        <p class="text-gray-600 mb-6">Страницата, която търсите, не съществува или е била преместена.</p>
        <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-emerald-500 text-white font-medium rounded-lg hover:bg-emerald-600 transition-colors">
            Връщане към началната страница
        </a>
    </div>
</div>
@endsection