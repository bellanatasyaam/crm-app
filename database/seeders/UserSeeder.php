<?php

// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Wika', 'email' => 'wika@ipkg.com', 'password' => Hash::make('password')],
            ['name' => 'Leni', 'email' => 'leni@ipkg.com', 'password' => Hash::make('password')],
            ['name' => 'Aulia', 'email' => 'aulia@ipkg.com', 'password' => Hash::make('password')],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
