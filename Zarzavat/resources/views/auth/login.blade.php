
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-200 p-4">
    <div class="w-full max-w-md">
        <!-- Лого и заглавие -->
        <div class="text-center mb-6">
            <div class="inline-block p-4 bg-white rounded-full shadow-lg mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-800">Добре дошли отново</h2>
            <p class="text-gray-600 mt-1">Радваме се да Ви видим отново</p>
        </div>

        <!-- Карта на формата -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Форма за вход -->
            <div class="p-8">
                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Имейл адрес</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" 
                                class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Парола</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password" placeholder="••••••••" 
                                class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                    </div>
                    
                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Запомни ме</label>
                    </div>
                    
                    <button type="submit" class="w-full flex justify-center items-center bg-gradient-to-r from-emerald-500 to-green-600 text-white py-3 rounded-lg font-medium hover:from-emerald-600 hover:to-green-700 transition duration-200 shadow-md">
                        <span>Вход</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
            
            <!-- Разделителна линия -->
            <div class="border-t border-gray-200 px-8 py-4 bg-gray-50">
                <p class="text-center text-gray-600">
                    Нямате акаунт? 
                    <a href="{{ route('register') }}" class="text-emerald-600 font-medium hover:text-emerald-700 transition-colors">
                        Регистрирайте се сега
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script src="{{ asset('js/validation.js') }}"></script>
@endsection