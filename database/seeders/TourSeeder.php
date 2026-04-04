<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\ManadoTour;
use App\Models\IndonesiaDestination;
use App\Models\InternationalTour;
use App\Models\TourPrice;
use App\Models\Itinerary;
use App\Models\Gallery;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Manado Local Categories
        $manadoCity = Category::create([
            'name' => 'Manado City Tour',
            'slug' => 'manado-city-tour',
            'description' => 'City exploration and culture in Manado',
            'type' => 'local'
        ]);
        $manadoMarine = Category::create([
            'name' => 'Marine & Bunaken',
            'slug' => 'marine-bunaken',
            'description' => 'Diving and snorkeling in Bunaken Marine Park',
            'type' => 'local'
        ]);
        $manadoHighland = Category::create([
            'name' => 'Highland & Tomohon',
            'slug' => 'highland-tomohon',
            'description' => 'Cool air and volcanoes in Tomohon highlands',
            'type' => 'local'
        ]);

        // 2. Indonesia National Categories
        $bali = Category::create([
            'name' => 'Bali & Lombok',
            'slug' => 'bali-lombok',
            'description' => 'The ultimate island escape',
            'type' => 'national'
        ]);
        $komodo = Category::create([
            'name' => 'Komodo Flores',
            'slug' => 'komodo-flores',
            'description' => 'Adventure to see the dragon',
            'type' => 'national'
        ]);
        $rajaAmpat = Category::create([
            'name' => 'Raja Ampat Papua',
            'slug' => 'raja-ampat-papua',
            'description' => 'The last paradise on earth',
            'type' => 'national'
        ]);

        // 3. International Categories
        $australia = Category::create([
            'name' => 'Australia',
            'slug' => 'australia',
            'description' => 'Down under adventure',
            'type' => 'international'
        ]);
        $europe = Category::create([
            'name' => 'Europe',
            'slug' => 'europe',
            'description' => 'Classical and cultural heritage',
            'type' => 'international'
        ]);
        $asia = Category::create([
            'name' => 'Asia',
            'slug' => 'asia',
            'description' => 'Vibrant cities and oriental charm',
            'type' => 'international'
        ]);

        // --- MANADO TOURS ---
        $tourManado = ManadoTour::create([
            'category_id' => $manadoCity->id,
            'title' => 'Manado City & Culinary Tour',
            'slug' => 'manado-city-culinary-tour',
            'description' => 'A full day tour exploring Manado city landmarks and authentic cuisine.',
            'base_price' => 750000,
            'duration_days' => 1,
            'duration_nights' => 0,
            'highlights' => "Jesus Blessing Monument\nBan Hin Kiong Temple\nCulinary experience at Wakeke Street",
            'inclusions' => 'Transport, Guide, Lunch, Entrance fees',
            'exclusions' => 'Personal expenses, Tipping',
            'terms_conditions' => 'Minimum 2 persons, non-refundable'
        ]);

        $tourManado->itineraries()->create([
            'day_number' => 1, 'title' => 'Manado Exploration',
            'description' => 'Visit Jesus Blessing Monument, Ban Hin Kiong Temple, and enjoy Tinutuan lunch.',
            'meals_info' => 'L'
        ]);

        $tourManado->prices()->create(['type' => 'adult_twin', 'price' => 750000]);
        $tourManado->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&q=80&w=800', 'is_primary' => true]);

        // --- INDONESIA DESTINATIONS ---
        $tourBali = IndonesiaDestination::create([
            'category_id' => $bali->id,
            'title' => 'Bali Cultural & Beach Escape',
            'slug' => 'bali-cultural-beach-escape',
            'description' => 'Explore the iconic temples and beautiful beaches of Bali.',
            'base_price' => 3500000,
            'duration_days' => 4,
            'duration_nights' => 3,
            'highlights' => "Uluwatu Temple\nTegalalang Rice Terrace\nNusa Penida Day Trip",
            'inclusions' => 'Hotel 4*, Transport, Guide, Daily Breakfast',
            'exclusions' => 'Flight tickets, Personal expenses',
            'terms_conditions' => 'Standard cancelation policy applies'
        ]);

        $tourBali->prices()->create(['type' => 'adult_twin', 'price' => 3500000]);
        $tourBali->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800', 'is_primary' => true]);

        // --- INTERNATIONAL TOURS ---
        $tourSydney = InternationalTour::create([
            'category_id' => $australia->id,
            'title' => 'VIVID SYDNEY – SUPERSALE WINTER AUSTRALIA',
            'slug' => 'vivid-sydney-winter-australia',
            'description' => 'Nikmati pesona musim dingin Australia dengan festival cahaya Vivid Sydney.',
            'base_price' => 19900000,
            'duration_days' => 6,
            'duration_nights' => 4,
            'airline_info' => 'QANTAS AIRWAYS',
            'highlights' => "Vivid Sydney Festival\nPuffing Billy Steam Train\nChadstone Outlet Shopping",
            'inclusions' => 'Flight tickets, Hotel 3*, Private Bus, Tour Leader',
            'exclusions' => 'Visa, Insurance, Tipping',
            'terms_conditions' => 'Booking fee non-refundable',
            'visa_requirements' => 'Paspor berlaku minimal 6 bulan, Foto 4x6, Rekening Koran 3 bulan terakhir',
            'passport_validity' => 'Minimal 6 bulan dari tanggal kepulangan'
        ]);

        $sydneyItineraries = [
            ['day' => 1, 'title' => 'JAKARTA – MELBOURNE', 'desc' => 'Berkumpul di Soekarno-Hatta, terbang ke Melbourne.'],
            ['day' => 2, 'title' => 'MELBOURNE CITY TOUR', 'desc' => 'Visit Brighton Bathing Box, Fitzroy Garden, St. Patricks Cathedral.'],
            ['day' => 3, 'title' => 'MELBOURNE – PUFFING BILLY', 'desc' => 'Naik kereta uap Puffing Billy, belanja di Chadstone.'],
            ['day' => 4, 'title' => 'MELBOURNE – SYDNEY (VIVID SYDNEY)', 'desc' => 'Terbang ke Sydney, Opera House photo stop, malam nikmati Vivid Sydney.'],
            ['day' => 5, 'title' => 'SYDNEY FREE DAY', 'desc' => 'Acara bebas atau optional tour.'],
            ['day' => 6, 'title' => 'SYDNEY – JAKARTA', 'desc' => 'Transfer ke Airport, terbang kembali ke Jakarta.']
        ];

        foreach ($sydneyItineraries as $itin) {
            $tourSydney->itineraries()->create([
                'day_number' => $itin['day'], 'title' => $itin['title'],
                'description' => $itin['desc'], 'hotel_info' => 'Hotel 3*', 'meals_info' => $itin['day'] > 1 ? 'B' : null
            ]);
        }

        $tourSydney->prices()->create(['type' => 'adult_twin', 'price' => 19900000, 'visa_fee' => 3000000, 'insurance' => 450000, 'tipping' => 1200000]);
        $tourSydney->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?auto=format&fit=crop&q=80&w=800', 'is_primary' => true]);
    }
}
