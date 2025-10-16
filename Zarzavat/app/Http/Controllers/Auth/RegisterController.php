<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;


class RegisterController extends Controller
{
    
    public function showRegisterForm()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'name.required' => 'Името е задължително',
            'email.required' => 'Имейлът е задължителен',
            'email.email' => 'Невалиден имейл адрес',
            'email.unique' => 'Този имейл вече е регистриран',
            'password.required' => 'Паролата е задължителна',
            'password.min' => 'Паролата трябва да е поне 8 символа',
            'password.confirmed' => 'Паролите не съвпадат',
            'password_confirmation.required' => 'Моля, потвърдете паролата',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // По подразбиране обикновен потребител
        ]);
        
        Auth::login($user);
        
        return redirect()->route('products.index');
    }

 
}