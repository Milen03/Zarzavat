
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-200 p-4">
    <div class="w-full max-w-md">
        <!-- Лого и заглавие -->
        <div class="text-center mb-6">
            <div class="inline-block p-4 bg-white rounded-full shadow-lg mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-800">Създайте акаунт</h2>
            <p class="text-gray-600 mt-1">Присъединете се към нашата общност</p>
        </div>

        <!-- Карта на формата -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Форма за регистрация -->
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

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Име</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Вашето пълно име" 
                                class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Имейл адрес</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" 
                                class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Парола</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password" placeholder="Минимум 8 символа" 
                                class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Минимум 8 символа с поне една буква и цифра</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Потвърдете паролата</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation" placeholder="Повторете паролата" 
                                class="pl-10 w-full py-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full flex justify-center items-center bg-gradient-to-r from-emerald-500 to-green-600 text-white py-3 rounded-lg font-medium hover:from-emerald-600 hover:to-green-700 transition duration-200 shadow-md">
                        <span>Регистрация</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
            
            <!-- Разделителна линия -->
            <div class="border-t border-gray-200 px-8 py-4 bg-gray-50">
                <p class="text-center text-gray-600">
                    Вече имате акаунт? 
                    <a href="{{ route('login') }}" class="text-emerald-600 font-medium hover:text-emerald-700 transition-colors">
                        Влезте сега
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection