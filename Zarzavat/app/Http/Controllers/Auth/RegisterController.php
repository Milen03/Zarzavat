<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;


class RegisterController extends Controller
{
    
    public function showRegisterForm()
    {
        return view('auth.register');
    }


    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
            'role' => 'user', // По подразбиране обикновен потребител
        ]);
        
        Auth::login($user);
        
        return redirect()->route('products.index');
    }

 
}