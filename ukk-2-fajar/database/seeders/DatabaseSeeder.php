<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@localhost',
            'password' => Hash::make('password'),
        ]);

        // Categories
        $categories = [
            'Masalah Hardware (Perangkat Keras)',
            'Masalah Software (Aplikasi/Sistem Operasi)',
            'Masalah Jaringan (Internet/WiFi)',
            'Permintaan Akun/Akses',
            'Lain-lain',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
