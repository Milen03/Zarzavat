<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This is Admin User
        User::create([
            'name'=>'Admin',
            'email'=>'milen.atanasovv03@gmail.com',
            'password'=> Hash::make('71750300qQ'),
            'role'=>'admin'
        ]);

         User::create([
            'name' => 'Client User',
            'email' => 'geri07@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'user',
        ]);
    }
}
