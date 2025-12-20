<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin DUKCAPIL',
            'email' => 'admin@dukcapil.ponorogo.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Petugas DUKCAPIL',
            'email' => 'officer@dukcapil.ponorogo.go.id',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
