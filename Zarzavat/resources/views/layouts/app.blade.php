
<!DOCTYPE html>
<html lang="bg" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зеленчуков магазин</title>
    @vite('resources/css/app.css')
</head>
<body class="h-full m-0 p-0 flex flex-col bg-gray-100 text-gray-900">

    <!-- HEADER - разширен на абсолютно цялата ширина -->
    <nav class="w-full bg-white shadow">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <!-- Лого -->
            <a href="{{ route('products.index') }}" class="text-xl font-bold text-green-600">Зеленчуци</a>
            
            <!-- Навигационни линкове -->
            <div class="flex space-x-4 items-center">
                <a href="{{ route('products.index') }}" class="hover:text-green-600">Продукти</a>
                <a href="{{ route('cart.index') }}" class="hover:text-green-600">Количка</a>

                @auth
                    <span class="text-gray-700 hidden sm:inline">Здравей, {{ auth()->user()->name }}</span>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 font-semibold">
                            Админ панел
                        </a>
                    @endif

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            Изход
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-green-600">Вход</a>
                    <a href="{{ route('register') }}" class="hover:text-green-600">Регистрация</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT - без контейнер, заема цялата ширина -->
    <main class="flex-grow w-full">
        @yield('content')
    </main>
    
    <!-- FOOTER - без margin отдолу -->
    <footer class="w-full bg-white shadow py-4">
        <div class="w-full px-4 sm:px-6 lg:px-8 text-center text-gray-600">
            <p>© {{ date('Y') }} Зеленчуков магазин. Всички права запазени.</p>
        </div>
    </footer>

</body>
</html>