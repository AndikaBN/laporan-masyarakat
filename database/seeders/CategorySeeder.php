<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Jangan create jika sudah ada
        if (Category::count() > 0) {
            return;
        }

        // Categories grouped by agency name (akan dicari dari database)
        $categoriesByAgencyName = [
            'Dinas PUPR' => [
                ['name' => 'Infrastruktur Jalan', 'description' => 'Laporan tentang kerusakan jalan, lubang, atau aspal rusak'],
                ['name' => 'Rambu dan Marka Jalan', 'description' => 'Laporan tentang rambu lalu lintas atau marka jalan'],
                ['name' => 'Drainase dan Saluran Air', 'description' => 'Laporan tentang saluran air yang tersumbat atau rusak'],
            ],
            'Dinas Lingkungan Hidup (DLH)' => [
                ['name' => 'Kebersihan Lingkungan', 'description' => 'Laporan tentang sampah atau kebersihan lingkungan'],
                ['name' => 'Pencemaran Udara', 'description' => 'Laporan tentang pencemaran udara dan polusi'],
                ['name' => 'Taman dan Ruang Hijau', 'description' => 'Laporan tentang taman dan ruang hijau yang tidak terawat'],
            ],
            'Dinas Perhubungan (Dishub)' => [
                ['name' => 'Pencahayaan Jalan', 'description' => 'Laporan tentang lampu jalan yang tidak menyala atau rusak'],
                ['name' => 'Kemacetan Lalu Lintas', 'description' => 'Laporan tentang kemacetan dan gangguan lalu lintas'],
                ['name' => 'Rambu Lalu Lintas', 'description' => 'Laporan tentang hilangnya rambu atau marka lalu lintas'],
            ],
            'Satpol PP' => [
                ['name' => 'Parkir Liar', 'description' => 'Laporan tentang parkir liar yang mengganggu'],
                ['name' => 'Pedagang Kaki Lima', 'description' => 'Laporan tentang PKL yang menganggu jalanan'],
            ],
            'PLN (Layanan Listrik)' => [
                ['name' => 'Gangguan Listrik', 'description' => 'Laporan tentang gangguan listrik atau pemadaman'],
                ['name' => 'Kabel Listrik Bahaya', 'description' => 'Laporan tentang kabel listrik yang tergantung atau berbahaya'],
            ],
        ];

        foreach ($categoriesByAgencyName as $agencyName => $categories) {
            // Get agency by name
            $agency = Agency::where('name', $agencyName)->first();
            
            if (!$agency) {
                $this->command->warn("Agency not found: {$agencyName}");
                continue;
            }

            foreach ($categories as $cat) {
                Category::create([
                    'agency_id' => $agency->id,
                    'name' => $cat['name'],
                    'slug' => str()->slug($cat['name']),
                    'description' => $cat['description'],
                ]);
            }
        }

        $this->command->info('✅ Categories created for all agencies!');
    }
}

