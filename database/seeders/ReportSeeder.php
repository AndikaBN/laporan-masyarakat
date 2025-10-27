<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jangan create jika sudah ada reports
        if (Report::count() > 0) {
            $this->command->info('Reports already exist. Skipping...');
            return;
        }

        // Get users & categories
        $users = User::where('role', 'user')->get();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->error('Need users and categories first!');
            return;
        }

        // Jakarta locations
        $locations = [
            ['name' => 'Jl. Sudirman KM 2', 'lat' => -6.2088, 'lng' => 106.8456],
            ['name' => 'Jl. Gatot Subroto', 'lat' => -6.2153, 'lng' => 106.8154],
            ['name' => 'Jl. Kebayoran Baru', 'lat' => -6.2645, 'lng' => 106.7964],
            ['name' => 'Jl. Imam Bonjol', 'lat' => -6.1937, 'lng' => 106.8260],
            ['name' => 'Halte Bus Blok M', 'lat' => -6.2857, 'lng' => 106.8054],
            ['name' => 'Jl. Rasuna Said', 'lat' => -6.2199, 'lng' => 106.8419],
            ['name' => 'Jl. Diponegoro', 'lat' => -6.1905, 'lng' => 106.8378],
            ['name' => 'Jl. Puncak', 'lat' => -6.3457, 'lng' => 106.8234],
            ['name' => 'Jl. Pancoran', 'lat' => -6.2410, 'lng' => 106.8103],
            ['name' => 'Jl. Wahid Hasyim', 'lat' => -6.1938, 'lng' => 106.8222],
        ];

        $titles = [
            'Jalan Rusak dengan Lubang Besar',
            'Lampu Jalan Mati',
            'Sampah Menumpuk di Pinggir Jalan',
            'Saluran Air Tersumbat',
            'Pohon Tumbang Memblokir Jalan',
            'Trotoar Rusak dan Tidak Aman',
            'Rumput Liar Menggerogoti Jalan',
            'Pagar Pembatas Hilang',
            'Manhole Rusak',
            'Rambu Lalu Lintas Hilang',
        ];

        $descriptions = [
            'Jalan rusak parah dengan banyak lubang. Sangat berbahaya terutama saat hujan.',
            'Lampu jalan sudah tidak menyala selama 2 minggu. Malam hari sangat gelap.',
            'Sampah menumpuk di pinggir jalan yang sangat mengganggu lingkungan.',
            'Saluran air tersumbat oleh sampah dan daun. Saat hujan air meluap dan genang.',
            'Pohon besar tumbang di tengah jalan akibat angin kencang.',
            'Trotoar rusak, tidak rata, dan berbahaya bagi pejalan kaki.',
            'Rumput liar dan akar tanaman tumbuh menembus aspal jalan.',
            'Pagar pembatas jalan hilang di tikungan berbahaya.',
            'Manhole di tengah jalan rusak dan terbuka membahayakan pengendara.',
            'Rambu lalu lintas di persimpangan sudah tidak ada.',
        ];

        $statuses = ['submitted', 'under_review', 'approved', 'rejected', 'completed'];

        // Create 30 reports
        for ($i = 0; $i < 30; $i++) {
            $location = $locations[$i % count($locations)];
            $user = $users->random();
            $category = $categories->random();

            Report::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => $titles[$i % count($titles)] . ' #' . ($i + 1),
                'description' => $descriptions[$i % count($descriptions)],
                'location' => $location['name'],
                'latitude' => $location['lat'] + (rand(-50, 50) / 1000),
                'longitude' => $location['lng'] + (rand(-50, 50) / 1000),
                'status' => $statuses[$i % count($statuses)],
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }

        $this->command->info('✅ 30 reports created successfully!');
    }
}
