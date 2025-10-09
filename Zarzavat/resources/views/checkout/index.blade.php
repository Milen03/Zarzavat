
@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen py-6 bg-gradient-to-br from-green-50 to-emerald-200">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Завършване на поръчка</h1>
            <p class="mt-2 text-gray-600">Моля, попълнете вашите данни за доставка</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="text-red-800 font-medium">Имаме няколко проблема с вашите данни:</div>
                        <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Лични данни -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-100">Лични данни</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Име и фамилия</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            name="name" 
                                            value="{{ old('name') }}"
                                            class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-green-500 focus:border-green-500" 
                                            placeholder="Име на фирмата"
                                            required 
                                        >
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Имейл адрес</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="email" 
                                            id="email" 
                                            name="email" 
                                            value="{{ old('email') }}"
                                            class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-green-500 focus:border-green-500" 
                                            placeholder="your@email.com"
                                            required 
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Данни за доставка -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-100">Данни за доставка</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="phone" 
                                            name="phone" 
                                            value="{{ old('phone') }}"
                                            class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-green-500 focus:border-green-500" 
                                            placeholder="0888 123 456"
                                            required 
                                        >
                                    </div>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Адрес за доставка</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <textarea 
                                            id="address" 
                                            name="address" 
                                            rows="2"
                                            class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-green-500 focus:border-green-500" 
                                            placeholder="Пълен адрес за доставка"
                                            required
                                        >{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Бутон за потвърждаване -->
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-green-600 text-white py-3 rounded-lg font-medium hover:from-emerald-600 hover:to-green-700 transition duration-200 shadow-md flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Потвърди поръчката
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('cart.index') }}" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Обратно към количката
            </a>
        </div>
    </div>
</div>
@endsection