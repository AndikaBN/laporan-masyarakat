<?php

namespace Database\Seeders;

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

        $categories = [
            ['name' => 'Infrastruktur Jalan', 'description' => 'Laporan tentang kerusakan jalan, lubang, atau aspal rusak'],
            ['name' => 'Pencahayaan Jalan', 'description' => 'Laporan tentang lampu jalan yang tidak menyala atau rusak'],
            ['name' => 'Kebersihan Lingkungan', 'description' => 'Laporan tentang sampah atau kebersihan lingkungan'],
            ['name' => 'Rambu dan Marka Jalan', 'description' => 'Laporan tentang rambu lalu lintas atau marka jalan'],
            ['name' => 'Drainase dan Saluran Air', 'description' => 'Laporan tentang saluran air yang tersumbat atau rusak'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'agency_id' => 1,
                'name' => $cat['name'],
                'slug' => str()->slug($cat['name']),
                'description' => $cat['description'],
            ]);
        }

        $this->command->info('✅ 5 categories created!');
    }
}
