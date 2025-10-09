@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen py-6 bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Административен панел
            </h1>
            <p class="mt-2 text-gray-600">Управление на продукти, поръчки и потребители</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Карта Продукти -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Продукти</h2>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <span class="text-3xl font-bold text-gray-800">{{ $productsCount }}</span>
                        <span class="text-gray-600 ml-1">общо</span>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <a href="{{ route('admin.products.index') }}" class="flex items-center justify-between text-blue-600 font-medium hover:text-blue-800 transition-colors">
                            <span>Управление на продукти</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Карта Поръчки -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Поръчки</h2>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <span class="text-3xl font-bold text-gray-800">{{ $ordersCount }}</span>
                        <span class="text-gray-600 ml-1">общо</span>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between text-blue-600 font-medium hover:text-blue-800 transition-colors">
                            <span>Преглед на всички поръчки</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Карта Потребители -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Потребители</h2>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <span class="text-3xl font-bold text-gray-800">{{ $usersCount }}</span>
                        <span class="text-gray-600 ml-1">регистрирани</span>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <a href="#" class="flex items-center justify-between text-blue-600 font-medium hover:text-blue-800 transition-colors">
                            <span>Управление на потребители</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
      
@endsection