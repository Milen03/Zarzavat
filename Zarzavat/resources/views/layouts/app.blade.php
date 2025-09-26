<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Онлайн Магазин за Зеленчуци</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between">
            <a href="{{ route('home') }}" class="font-bold">Зеленчуци</a>
            <div>
                <a href="{{ route('home') }}" class="mr-4">Продукти</a>
                {{-- <a href="{{ route('cart.index') }}">Количка</a> --}}
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-6">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 mt-10">
        <p>&copy; {{ date('Y') }} Онлайн Магазин за Зеленчуци</p>
    </footer>
</body>
</html>
