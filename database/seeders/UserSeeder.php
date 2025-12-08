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
            'profile_image' => 'profile_img/default_profile.png',
        ]);

        $names = [
            'Sarah Smith',
            'Kenzie Rolland',
            'Dimas Afradika',
            'Vlooga Sutanto',
            'Zasshu Ke'
        ];

        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name'          => $names[$i - 1],
                'email'         => "testuser{$i}@example.com",
                'password'      => Hash::make('user123'),
                'profile_image' => "profile_img/profile{$i}.png",
            ]);
        }
    }
}
