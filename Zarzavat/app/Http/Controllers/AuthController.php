<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Показва формата за регистрация
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Обработва регистрацията на нов потребител
     */
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

    /**
     * Показва формата за вход
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработва влизането на потребител
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Моля, въведете имейл',
            'email.email' => 'Невалиден имейл адрес',
            'password.required' => 'Моля, въведете парола',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            // Ако потребителят е админ, пренасочваме към админ панела
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended(route('products.index'));
        }

        return back()->withErrors([
            'email' => 'Грешен имейл или парола',
        ])->withInput($request->except('password'));
    }

 
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('products.index');
    }
}