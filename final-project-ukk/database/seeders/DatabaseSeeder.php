<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Added this use statement.

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Categories
        $categories = [
            'Jaringan',
            'Hardware',
            'Software/Aplikasi',
            'Akun/Login',
            'Sistem Operasi',
            'Keamanan',
            'Email',
            'Website',
            'Database',
            'Lain-lain'
        ];

        foreach($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}
