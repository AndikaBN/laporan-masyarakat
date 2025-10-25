<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        // Create Agency Admin
        User::factory()->create([
            'name' => 'Agency Admin',
            'email' => 'agency@admin.com',
            'password' => Hash::make('password123'),
            'role' => 'agency_admin',
        ]);

        // Create Regular User (for testing blocked login)
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Create additional test users
        User::factory(5)->create([
            'role' => 'user',
        ]);
    }
}
