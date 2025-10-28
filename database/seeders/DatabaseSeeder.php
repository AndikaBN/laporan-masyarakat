<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Agency;
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

        $agencies = [
            ['name' => 'Dinas PUPR', 'contact' => 'pupr@pemda.local'],
            ['name' => 'Dinas Lingkungan Hidup (DLH)', 'contact' => 'dlh@pemda.local'],
            ['name' => 'Dinas Perhubungan (Dishub)', 'contact' => 'dishub@pemda.local'],
            ['name' => 'Satpol PP', 'contact' => 'satpolpp@pemda.local'],
            ['name' => 'PLN (Layanan Listrik)', 'contact' => 'pln@pemda.local'],
        ];

        foreach ($agencies as $agencyData) {
            // Create agency first
            $agency = Agency::create([
                'name' => $agencyData['name'],
                'contact' => $agencyData['contact'],
            ]);

            // create agency admin user
            User::factory()->create([
                'name' => "{$agencyData['name']} Admin",
                'email' => "{$agencyData['contact']}",
                'password' => Hash::make('password123'),
                'role' => 'agency_admin',
                'agency_id' => $agency->id,
            ]);
        }

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

        // Seed categories & reports
        $this->call([
            CategorySeeder::class,
            ReportSeeder::class,
        ]);
    }
}
