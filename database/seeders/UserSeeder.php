<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user idempotent
        User::firstOrCreate(
            ['email' => 'milen.atanasovv03@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('71750300qQ'),
                'role' => 'admin',
            ]
        );

        // Client user idempotent
        User::firstOrCreate(
            ['email' => 'geri07@gmail.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );
    }
}
