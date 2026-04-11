<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransportationSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('transportations')) {
            return;
        }

        $items = [
            [
                'name' => 'Toyota Avanza',
                'type' => 'MPV',
                'price' => 450000,
                'available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1542362567-b07e54358753?auto=format&fit=crop&w=1600&q=80',
                'description' => 'Mobil keluarga nyaman untuk city tour dan perjalanan luar kota (muat 6–7 penumpang).',
                'description_en' => 'A comfortable family car for city tours and out-of-town trips (seats 6–7 passengers).',
                'description_ko' => '도심 투어와 근교 이동에 적합한 편안한 패밀리 차량(6~7인 탑승).',
                'description_zh' => '适合城市观光与城外出行的舒适家用车（可乘坐 6–7 人）。',
            ],
            [
                'name' => 'Toyota Innova Reborn',
                'type' => 'MPV',
                'price' => 750000,
                'available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?auto=format&fit=crop&w=1600&q=80',
                'description' => 'Kabin luas dan nyaman, cocok untuk rombongan kecil dan perjalanan jarak jauh.',
                'description_en' => 'Spacious and comfortable cabin, ideal for small groups and long-distance trips.',
                'description_ko' => '넓고 편안한 실내로 소규모 그룹 및 장거리 이동에 적합합니다.',
                'description_zh' => '车厢宽敞舒适，适合小团队与长途出行。',
            ],
            [
                'name' => 'Honda Brio',
                'type' => 'Hatchback',
                'price' => 350000,
                'available' => false,
                'image_url' => 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=1600&q=80',
                'description' => 'Mobil irit dan lincah untuk mobilitas dalam kota. Cocok untuk 2–4 penumpang.',
                'description_en' => 'Fuel-efficient and agile for city mobility. Suitable for 2–4 passengers.',
                'description_ko' => '연비가 좋고 민첩해 도심 이동에 적합합니다(2~4인 탑승).',
                'description_zh' => '省油灵活，适合城市出行（可乘坐 2–4 人）。',
            ],
            [
                'name' => 'Hiace Commuter',
                'type' => 'Minibus',
                'price' => 1400000,
                'available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1603386329225-868f9b1ee6ae?auto=format&fit=crop&w=1600&q=80',
                'description' => 'Pilihan terbaik untuk rombongan (10–15 penumpang). Nyaman untuk itinerary harian.',
                'description_en' => 'Best choice for groups (10–15 passengers). Comfortable for daily itineraries.',
                'description_ko' => '단체(10~15인)에 최적의 선택. 하루 일정 이동에 편안합니다.',
                'description_zh' => '团体出行首选（10–15 人）。适合日程安排的舒适用车。',
            ],
        ];

        foreach ($items as $it) {
            DB::table('transportations')->updateOrInsert(
                ['name' => $it['name']],
                array_merge($it, ['created_at' => now(), 'updated_at' => now()]),
            );
        }
    }
}
