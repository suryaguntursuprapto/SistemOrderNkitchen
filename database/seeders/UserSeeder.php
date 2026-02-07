<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        // Create Admin User
        User::create([
            'name' => 'Admin N-Kitchen',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Musi Palembang'
        ]);

        // Create Sample Customer
        User::create([
            'name' => 'Customer Demo',
            'username' => 'customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081987654321',
            'address' => 'Jl. Sudirman No. 123, Jakarta'
        ]);
    }
}