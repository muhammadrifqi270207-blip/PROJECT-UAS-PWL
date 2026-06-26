<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun khusus untuk Admin
        User::create([
            'name'     => 'Atmin Embud',
            'email'    => 'admin@konserhub.com',
            'password' => Hash::make('270207'),
            'role'     => 'admin',
        ]);

        // 2. Akun untuk User Jelata
        User::create([
            'name'     => 'User Jelata',
            'email'    => 'user@konserhub.com',
            'password' => Hash::make('12345678'),
            'role'     => 'user',
        ]);
    }
}