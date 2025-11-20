
<!DOCTYPE html>
<html lang="bg" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зеленчуков магазин</title>
    {{-- Vite assets: fallback to prebuilt static files if manifest is missing (production build not executed) --}}
    @php($viteManifest = public_path('build/manifest.json'))
    @if(file_exists($viteManifest))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
    @endif
</head>
<body class="h-full m-0 p-0 flex flex-col bg-gray-100 text-gray-900">

    
    <nav class="w-full bg-white shadow">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between relative">
            <!-- Лого -->
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 group">
                <span class="font-extrabold text-2xl bg-gradient-to-r from-emerald-700 to-lime-600 bg-clip-text text-transparent tracking-tight transition-colors group-hover:from-emerald-600 group-hover:to-lime-500">
                    Зеленчуци
                </span>
            </a>

            <!-- Мобилен бутон (хамбургер) -->
            <button
                type="button"
                class="md:hidden inline-flex items-center justify-center p-2 rounded text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                aria-controls="navLinks"
                aria-expanded="false"
                data-nav-toggle
            >
                <span class="sr-only">Отвори меню</span>
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <!-- Навигационни линкове -->
            <div id="navLinks" class="hidden absolute left-0 right-0 top-full z-20 bg-white shadow-md md:static md:shadow-none md:flex md:items-center">
                <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-6 p-4 md:p-0">
                    <a href="{{ route('products.index') }}" class="block hover:text-green-600">Продукти</a>
                    <a href="{{ route('cart.index') }}" class="block hover:text-green-600">Количка</a>
                    <a href="{{ route('profile.index') }}" class="block hover:text-green-600">
                        {{ Auth::check() ? 'Моите поръчки' : 'Проследи поръчка' }}
                    </a>

                    @auth
                        <span class="text-gray-700 md:hidden">Здравей, {{ auth()->user()->name }}</span>
                        <span class="text-gray-700 hidden md:inline">Здравей, {{ auth()->user()->name }}</span>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block hover:text-blue-600 font-semibold">
                                Админ панел
                            </a>
                        @endif

                        <!-- Logout -->
                        <form action="{{ route('logout') }}" method="POST" class="block md:inline">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                Изход
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block hover:text-green-600">Вход</a>
                        <a href="{{ route('register') }}" class="block hover:text-green-600">Регистрация</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-grow w-full">
        @yield('content')
        @stack('scripts')
    </main>
    
    <!-- FOOTER -->
    <footer class="w-full bg-white shadow py-4">
        <div class="w-full px-4 sm:px-6 lg:px-8 text-center text-gray-600">
            <p>© {{ date('Y') }} Зеленчуков магазин. Всички права запазени.</p>
            <p>Made by Milen Atanasov </p>
        </div>
    </footer>

    <!-- Навигация: скрипт за мобилно меню -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggleBtn = document.querySelector('[data-nav-toggle]');
            var menu = document.getElementById('navLinks');
            if (!toggleBtn || !menu) return;

            function openMenu() {
                menu.classList.remove('hidden');
                toggleBtn.setAttribute('aria-expanded', 'true');
            }

            function closeMenu() {
                menu.classList.add('hidden');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }

            toggleBtn.addEventListener('click', function () {
                if (menu.classList.contains('hidden')) {
                    openMenu();
                } else {
                    closeMenu();
                }
            });

            // На по-големи екрани менюто винаги е видимо
            var mql = window.matchMedia('(min-width: 768px)');
            function syncMenu(e) {
                if (e.matches) {
                    menu.classList.remove('hidden');
                    toggleBtn.setAttribute('aria-expanded', 'true');
                } else {
                    menu.classList.add('hidden');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            }
            if (mql.addEventListener) {
                mql.addEventListener('change', syncMenu);
            } else if (mql.addListener) {
                mql.addListener(syncMenu);
            }
            syncMenu(mql);
        });
    </script>

</body>
</html>