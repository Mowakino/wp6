<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Default Admin User
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'profile_image' => 'default_profile.png',
        ]);

        // Default Test User
        User::create([
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('user123'),
            'profile_image' => 'default_profile.png',
        ]);
    }
}
