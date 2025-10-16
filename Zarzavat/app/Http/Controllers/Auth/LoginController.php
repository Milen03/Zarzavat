<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;



class LoginController extends Controller{
    public function showLoginForm()
    {
        return view('auth.login');
    }

   
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

}