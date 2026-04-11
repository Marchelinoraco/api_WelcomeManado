<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@welcomemanado.com'],
            ['name' => 'Admin WelcomeManado'],
        );

        $this->call([
            TourSeeder::class,
            HotelSeeder::class,
            TransportationSeeder::class,
        ]);

        if (Schema::hasTable('gallery_items')) {
            $items = [
                [
                    'title' => 'Sunset Bunaken',
                    'image_path' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1600&q=80',
                    'video_name' => 'Bunaken Highlights',
                    'youtube_url' => 'https://www.youtube.com/watch?v=4Wrc4fHSCpw',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
                [
                    'title' => 'City Lights Manado',
                    'image_path' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&w=1600&q=80',
                    'video_name' => 'Night Walk',
                    'youtube_url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
                    'sort_order' => 2,
                    'is_active' => true,
                ],
                [
                    'title' => 'Tomohon Highland',
                    'image_path' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80',
                    'video_name' => 'Highland Vibes',
                    'youtube_url' => 'https://www.youtube.com/watch?v=ysz5S6PUM-U',
                    'sort_order' => 3,
                    'is_active' => true,
                ],
                [
                    'title' => 'Kuliner Manado',
                    'image_path' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1600&q=80',
                    'video_name' => 'Food Trip',
                    'youtube_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                    'sort_order' => 4,
                    'is_active' => true,
                ],
                [
                    'title' => 'Explore Manado (Video Only)',
                    'image_path' => null,
                    'video_name' => 'Explore Manado',
                    'youtube_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                    'sort_order' => 5,
                    'is_active' => true,
                ],
                [
                    'title' => 'Bunaken Underwater (Video Only)',
                    'image_path' => null,
                    'video_name' => 'Underwater World',
                    'youtube_url' => 'https://www.youtube.com/watch?v=4mVxwOJbQeU',
                    'sort_order' => 6,
                    'is_active' => true,
                ],
                [
                    'title' => 'Pantai Tropis',
                    'image_path' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=80',
                    'video_name' => null,
                    'youtube_url' => null,
                    'sort_order' => 7,
                    'is_active' => true,
                ],
                [
                    'title' => 'Pasar Tradisional',
                    'image_path' => 'https://images.unsplash.com/photo-1520975681995-2e4a0a5e3231?auto=format&fit=crop&w=1600&q=80',
                    'video_name' => null,
                    'youtube_url' => null,
                    'sort_order' => 8,
                    'is_active' => true,
                ],
                [
                    'title' => 'Hidden Gem (Nonaktif)',
                    'image_path' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1600&q=80',
                    'video_name' => 'Coming Soon',
                    'youtube_url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
                    'sort_order' => 999,
                    'is_active' => false,
                ],
            ];

            foreach ($items as $it) {
                DB::table('gallery_items')->updateOrInsert(
                    ['title' => $it['title']],
                    array_merge($it, ['created_at' => now(), 'updated_at' => now()]),
                );
            }
        }
    }
}
