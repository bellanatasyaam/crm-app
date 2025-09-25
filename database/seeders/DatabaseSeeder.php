<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // penting, import model User

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan customer seeder
        $this->call(CustomerSeeder::class);

        // Tambahin akun admin default
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // bisa ganti dengan password yg lebih aman
            'role' => 'admin',
        ]);
    }
}
