<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зеленчуков магазин</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- HEADER -->
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('products.index') }}" class="text-xl font-bold text-green-600">Зеленчуци</a>

            <div class="flex space-x-4">
                <a href="{{ route('products.index') }}" class="hover:text-green-600">Продукти</a>
                <a href="{{ route('cart.index') }}" class="hover:text-green-600">Количка</a>

                @auth
                    <span class="text-gray-700">Здравей, {{ auth()->user()->name }}</span>

                    {{-- @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 font-semibold">
                            Админ панел
                        </a>
                    @endif --}}

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            Изход
                        </button>
                    </form>
                @else
                    <a href="{{ route('login.form') }}" class="hover:text-green-600">Вход</a>
                    <a href="{{ route('register.form') }}" class="hover:text-green-600">Регистрация</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4">
        @yield('content')
    </main>

</body>
</html>
