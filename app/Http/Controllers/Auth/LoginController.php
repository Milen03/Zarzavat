<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request) : RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

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
