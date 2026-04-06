<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\IndonesiaDestination;
use App\Models\InternationalTour;
use App\Models\ManadoTour;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Manado Local Categories
        $manadoCity = Category::updateOrCreate(
            ['slug' => 'manado-city-tour'],
            [
                'name' => 'Manado City Tour',
                'description' => 'City exploration and culture in Manado',
                'type' => 'local',
            ],
        );
        $manadoMarine = Category::updateOrCreate(
            ['slug' => 'marine-bunaken'],
            [
                'name' => 'Marine & Bunaken',
                'description' => 'Diving and snorkeling in Bunaken Marine Park',
                'type' => 'local',
            ],
        );
        $manadoHighland = Category::updateOrCreate(
            ['slug' => 'highland-tomohon'],
            [
                'name' => 'Highland & Tomohon',
                'description' => 'Cool air and volcanoes in Tomohon highlands',
                'type' => 'local',
            ],
        );
        $manadoLikupang = Category::updateOrCreate(
            ['slug' => 'likupang-beaches'],
            [
                'name' => 'Likupang & Beaches',
                'description' => 'White sands and tropical beach escapes around Likupang',
                'type' => 'local',
            ],
        );

        // 2. Indonesia National Categories
        $bali = Category::updateOrCreate(
            ['slug' => 'bali-lombok'],
            [
                'name' => 'Bali & Lombok',
                'description' => 'The ultimate island escape',
                'type' => 'national',
            ],
        );
        $komodo = Category::updateOrCreate(
            ['slug' => 'komodo-flores'],
            [
                'name' => 'Komodo Flores',
                'description' => 'Adventure to see the dragon',
                'type' => 'national',
            ],
        );
        $rajaAmpat = Category::updateOrCreate(
            ['slug' => 'raja-ampat-papua'],
            [
                'name' => 'Raja Ampat Papua',
                'description' => 'The last paradise on earth',
                'type' => 'national',
            ],
        );
        $yogyakarta = Category::updateOrCreate(
            ['slug' => 'yogyakarta'],
            [
                'name' => 'Yogyakarta',
                'description' => 'Heritage, culinary, and cultural stories of Java',
                'type' => 'national',
            ],
        );

        // 3. International Categories
        $australia = Category::updateOrCreate(
            ['slug' => 'australia'],
            [
                'name' => 'Australia',
                'description' => 'Down under adventure',
                'type' => 'international',
            ],
        );
        $europe = Category::updateOrCreate(
            ['slug' => 'europe'],
            [
                'name' => 'Europe',
                'description' => 'Classical and cultural heritage',
                'type' => 'international',
            ],
        );
        $asia = Category::updateOrCreate(
            ['slug' => 'asia'],
            [
                'name' => 'Asia',
                'description' => 'Vibrant cities and oriental charm',
                'type' => 'international',
            ],
        );

        // --- MANADO TOURS ---
        $tourManado = ManadoTour::firstOrCreate(
            ['slug' => 'manado-city-culinary-tour'],
            [
                'category_id' => $manadoCity->id,
                'title' => 'Manado City & Culinary Tour',
                'description' => 'A day tour exploring Manado landmarks and authentic cuisine.',
                'base_price' => 750000,
                'tour_type' => 'daily',
                'duration_days' => 1,
                'duration_nights' => 0,
                'duration_hours' => null,
                'duration_hours_min' => 6,
                'duration_hours_max' => 8,
                'highlights' => "Jesus Blessing Monument\nBan Hin Kiong Temple\nWakeke Culinary Street",
                'inclusions' => 'Transport, Guide, Lunch, Entrance fees',
                'exclusions' => 'Personal expenses, Tipping',
                'terms_conditions' => 'Minimum 2 persons, non-refundable',
            ],
        );

        if (! $tourManado->itineraries()->exists()) {
            $tourManado->itineraries()->create([
                'day_number' => 1,
                'title' => 'Manado Exploration',
                'description' => 'Visit Jesus Blessing Monument, Ban Hin Kiong Temple, and enjoy Tinutuan lunch.',
                'meals_info' => 'L',
            ]);
        }
        if (! $tourManado->prices()->exists()) {
            $tourManado->prices()->create(['type' => 'adult_twin', 'price' => 750000]);
        }
        if (! $tourManado->galleries()->exists()) {
            $tourManado->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tourManado->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tourManado->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1559054663-1d077513ff05?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        $tourBunaken = ManadoTour::firstOrCreate(
            ['slug' => 'bunaken-snorkeling-day-trip'],
            [
                'category_id' => $manadoMarine->id,
                'title' => 'Bunaken Snorkeling Day Trip',
                'description' => 'Snorkeling adventure in Bunaken Marine Park with crystal-clear water.',
                'base_price' => 1100000,
                'tour_type' => 'daily',
                'duration_days' => 1,
                'duration_nights' => 0,
                'duration_hours' => 8,
                'duration_hours_min' => null,
                'duration_hours_max' => null,
                'highlights' => "Bunaken Coral Garden\nSnorkeling Spots\nBoat Ride from Manado",
                'inclusions' => 'Boat, Snorkeling gear, Guide, Lunch',
                'exclusions' => 'Personal expenses, Tipping',
                'terms_conditions' => 'Minimum 2 persons, weather dependent',
            ],
        );
        if (! $tourBunaken->itineraries()->exists()) {
            $tourBunaken->itineraries()->create([
                'day_number' => 1,
                'title' => 'Bunaken Marine Adventure',
                'description' => 'Boat transfer to Bunaken, snorkeling at two spots, lunch by the beach.',
                'meals_info' => 'L',
            ]);
        }
        if (! $tourBunaken->prices()->exists()) {
            $tourBunaken->prices()->create(['type' => 'adult_twin', 'price' => 1100000]);
        }
        if (! $tourBunaken->galleries()->exists()) {
            $tourBunaken->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tourBunaken->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tourBunaken->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1559494007-9f5847c49d94?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        $tourTomohon = ManadoTour::firstOrCreate(
            ['slug' => 'tomohon-highland-lake-linow'],
            [
                'category_id' => $manadoHighland->id,
                'title' => 'Tomohon Highland & Lake Linow',
                'description' => 'Cool mountain air, flower market, and the stunning colors of Lake Linow.',
                'base_price' => 950000,
                'tour_type' => 'daily',
                'duration_days' => 1,
                'duration_nights' => 0,
                'duration_hours' => null,
                'duration_hours_min' => 7,
                'duration_hours_max' => 9,
                'highlights' => "Tomohon Traditional Market\nLake Linow\nPanoramic Highland View",
                'inclusions' => 'Transport, Guide, Entrance fees',
                'exclusions' => 'Meals, Personal expenses',
                'terms_conditions' => 'Minimum 2 persons, non-refundable',
            ],
        );
        if (! $tourTomohon->itineraries()->exists()) {
            $tourTomohon->itineraries()->create([
                'day_number' => 1,
                'title' => 'Highland Day Tour',
                'description' => 'Visit Tomohon market, stop at Lake Linow, and enjoy coffee with a view.',
                'meals_info' => null,
            ]);
        }
        if (! $tourTomohon->prices()->exists()) {
            $tourTomohon->prices()->create(['type' => 'adult_twin', 'price' => 950000]);
        }
        if (! $tourTomohon->galleries()->exists()) {
            $tourTomohon->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tourTomohon->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tourTomohon->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1441829266145-6d4bfbd38eb4?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        $tour3d2n = ManadoTour::firstOrCreate(
            ['slug' => 'manado-bunaken-tomohon-3d2n-package'],
            [
                'category_id' => $manadoLikupang->id,
                'title' => 'Manado – Bunaken – Tomohon 3D2N Package',
                'description' => 'A 3-day package combining city, marine, and highland experiences.',
                'base_price' => 4200000,
                'tour_type' => 'package',
                'duration_days' => 3,
                'duration_nights' => 2,
                'duration_hours' => null,
                'duration_hours_min' => null,
                'duration_hours_max' => null,
                'highlights' => "Manado City Highlights\nBunaken Snorkeling\nTomohon Highland",
                'inclusions' => 'Hotel, Transport, Guide, Entrance fees',
                'exclusions' => 'Flight tickets, Personal expenses',
                'terms_conditions' => 'Booking fee non-refundable',
            ],
        );
        if (! $tour3d2n->itineraries()->exists()) {
            $tour3d2n->itineraries()->create([
                'day_number' => 1,
                'title' => 'Arrival & City Tour',
                'description' => 'Pick up, city highlights, culinary stop.',
                'meals_info' => 'L',
            ]);
            $tour3d2n->itineraries()->create([
                'day_number' => 2,
                'title' => 'Bunaken Day Trip',
                'description' => 'Snorkeling in Bunaken, beach lunch.',
                'meals_info' => 'B,L',
            ]);
            $tour3d2n->itineraries()->create([
                'day_number' => 3,
                'title' => 'Tomohon & Departure',
                'description' => 'Tomohon market, Lake Linow, drop off.',
                'meals_info' => 'B',
            ]);
        }
        if (! $tour3d2n->prices()->exists()) {
            $tour3d2n->prices()->create(['type' => 'adult_twin', 'price' => 4200000]);
        }
        if (! $tour3d2n->galleries()->exists()) {
            $tour3d2n->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1493558103817-58b2924bce98?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tour3d2n->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tour3d2n->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        // --- INDONESIA DESTINATIONS ---
        $tourBali = IndonesiaDestination::firstOrCreate(
            ['slug' => 'bali-cultural-beach-escape'],
            [
                'category_id' => $bali->id,
                'title' => 'Bali Cultural & Beach Escape',
                'description' => 'Explore iconic temples and beautiful beaches across Bali.',
                'base_price' => 3500000,
                'duration_days' => 4,
                'duration_nights' => 3,
                'highlights' => "Uluwatu Temple\nTegalalang Rice Terrace\nNusa Penida Day Trip",
                'inclusions' => 'Hotel 4*, Transport, Guide, Daily Breakfast',
                'exclusions' => 'Flight tickets, Personal expenses',
                'terms_conditions' => 'Standard cancelation policy applies',
                'interest_tags' => ['Beach', 'Culture', 'Family'],
            ],
        );
        if (! $tourBali->prices()->exists()) {
            $tourBali->prices()->create(['type' => 'adult_twin', 'price' => 3500000]);
        }
        if (! $tourBali->galleries()->exists()) {
            $tourBali->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tourBali->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tourBali->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        $tourKomodo = IndonesiaDestination::firstOrCreate(
            ['slug' => 'komodo-sailing-adventure-3d2n'],
            [
                'category_id' => $komodo->id,
                'title' => 'Komodo Sailing Adventure 3D2N',
                'description' => 'Sailing trip to Komodo, Padar Island sunrise, and pink beach.',
                'base_price' => 5900000,
                'duration_days' => 3,
                'duration_nights' => 2,
                'airline_info' => null,
                'highlights' => "Padar Island\nKomodo National Park\nPink Beach",
                'inclusions' => 'Boat cabin, Meals on board, Local guide',
                'exclusions' => 'Flight tickets, Park fees, Personal expenses',
                'terms_conditions' => 'Limited seats, booking fee non-refundable',
                'interest_tags' => ['Adventure', 'Nature'],
            ],
        );
        if (! $tourKomodo->prices()->exists()) {
            $tourKomodo->prices()->create(['type' => 'adult_twin', 'price' => 5900000]);
        }
        if (! $tourKomodo->galleries()->exists()) {
            $tourKomodo->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tourKomodo->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tourKomodo->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        $tourRajaAmpat = IndonesiaDestination::firstOrCreate(
            ['slug' => 'raja-ampat-diving-paradise-5d4n'],
            [
                'category_id' => $rajaAmpat->id,
                'title' => 'Raja Ampat Diving Paradise 5D4N',
                'description' => 'Discover the last paradise with island hopping and snorkeling.',
                'base_price' => 12900000,
                'duration_days' => 5,
                'duration_nights' => 4,
                'highlights' => "Piaynemo Viewpoint\nIsland Hopping\nSnorkeling Spots",
                'inclusions' => 'Homestay, Boat, Guide, Daily meals',
                'exclusions' => 'Flight tickets, Personal expenses',
                'terms_conditions' => 'Weather dependent, limited availability',
                'interest_tags' => ['Nature', 'Adventure'],
            ],
        );
        if (! $tourRajaAmpat->prices()->exists()) {
            $tourRajaAmpat->prices()->create(['type' => 'adult_twin', 'price' => 12900000]);
        }
        if (! $tourRajaAmpat->galleries()->exists()) {
            $tourRajaAmpat->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tourRajaAmpat->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tourRajaAmpat->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        $tourJogja = IndonesiaDestination::firstOrCreate(
            ['slug' => 'yogyakarta-heritage-culinary-3d2n'],
            [
                'category_id' => $yogyakarta->id,
                'title' => 'Yogyakarta Heritage & Culinary 3D2N',
                'description' => 'Borobudur sunrise, Prambanan, Malioboro, and local culinary gems.',
                'base_price' => 4100000,
                'duration_days' => 3,
                'duration_nights' => 2,
                'highlights' => "Borobudur\nPrambanan\nMalioboro Night Walk",
                'inclusions' => 'Hotel 3*, Transport, Guide, Breakfast',
                'exclusions' => 'Flight tickets, Personal expenses',
                'terms_conditions' => 'Standard cancelation policy applies',
                'interest_tags' => ['Culture', 'City', 'Family'],
            ],
        );
        if (! $tourJogja->prices()->exists()) {
            $tourJogja->prices()->create(['type' => 'adult_twin', 'price' => 4100000]);
        }
        if (! $tourJogja->galleries()->exists()) {
            $tourJogja->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1528150177508-7cc0c36cda5c?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tourJogja->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1546531130-0f0c8cf8c1fe?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tourJogja->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        // --- INTERNATIONAL TOURS ---
        $tourSydney = InternationalTour::firstOrCreate(
            ['slug' => 'vivid-sydney-winter-australia'],
            [
                'category_id' => $australia->id,
                'title' => 'VIVID SYDNEY – SUPERSALE WINTER AUSTRALIA',
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
                'passport_validity' => 'Minimal 6 bulan dari tanggal kepulangan',
            ],
        );

        $sydneyItineraries = [
            ['day' => 1, 'title' => 'JAKARTA – MELBOURNE', 'desc' => 'Berkumpul di Soekarno-Hatta, terbang ke Melbourne.'],
            ['day' => 2, 'title' => 'MELBOURNE CITY TOUR', 'desc' => 'Visit Brighton Bathing Box, Fitzroy Garden, St. Patricks Cathedral.'],
            ['day' => 3, 'title' => 'MELBOURNE – PUFFING BILLY', 'desc' => 'Naik kereta uap Puffing Billy, belanja di Chadstone.'],
            ['day' => 4, 'title' => 'MELBOURNE – SYDNEY (VIVID SYDNEY)', 'desc' => 'Terbang ke Sydney, Opera House photo stop, malam nikmati Vivid Sydney.'],
            ['day' => 5, 'title' => 'SYDNEY FREE DAY', 'desc' => 'Acara bebas atau optional tour.'],
            ['day' => 6, 'title' => 'SYDNEY – JAKARTA', 'desc' => 'Transfer ke Airport, terbang kembali ke Jakarta.'],
        ];

        if (! $tourSydney->itineraries()->exists()) {
            foreach ($sydneyItineraries as $itin) {
                $tourSydney->itineraries()->create([
                    'day_number' => $itin['day'],
                    'title' => $itin['title'],
                    'description' => $itin['desc'],
                    'hotel_info' => 'Hotel 3*',
                    'meals_info' => $itin['day'] > 1 ? 'B' : null,
                ]);
            }
        }
        if (! $tourSydney->prices()->exists()) {
            $tourSydney->prices()->create([
                'type' => 'adult_twin',
                'price' => 19900000,
                'visa_fee' => 3000000,
                'insurance' => 450000,
                'tipping' => 1200000,
            ]);
        }
        if (! $tourSydney->galleries()->exists()) {
            $tourSydney->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => true,
            ]);
            $tourSydney->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
            $tourSydney->galleries()->create([
                'image_path' => 'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?auto=format&fit=crop&q=80&w=1200',
                'is_primary' => false,
            ]);
        }

        $tourJapan = InternationalTour::firstOrCreate(
            ['slug' => 'japan-sakura-tokyo-kyoto-osaka'],
            [
                'category_id' => $asia->id,
                'title' => 'Japan Sakura Experience (Tokyo – Kyoto – Osaka)',
                'description' => 'Spring sakura highlights, culture, shopping, and iconic food spots.',
                'base_price' => 26800000,
                'duration_days' => 7,
                'duration_nights' => 5,
                'airline_info' => 'JAPAN AIRLINES',
                'highlights' => "Tokyo City Tour\nKyoto Temples\nOsaka Night Market",
                'inclusions' => 'Flight tickets, Hotel 3*, Transport, Tour Leader',
                'exclusions' => 'Visa, Personal expenses, Tipping',
                'terms_conditions' => 'Booking fee non-refundable',
                'visa_requirements' => 'Paspor berlaku minimal 6 bulan, Foto, Rekening Koran 3 bulan terakhir',
                'passport_validity' => 'Minimal 6 bulan dari tanggal kepulangan',
            ],
        );
        if (! $tourJapan->itineraries()->exists()) {
            $tourJapan->itineraries()->create(['day_number' => 1, 'title' => 'JAKARTA – TOKYO', 'description' => 'Departure and arrival in Tokyo.', 'hotel_info' => 'Hotel 3*']);
            $tourJapan->itineraries()->create(['day_number' => 2, 'title' => 'TOKYO CITY TOUR', 'description' => 'Asakusa, Shibuya, and shopping.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourJapan->itineraries()->create(['day_number' => 3, 'title' => 'TOKYO – MT. FUJI', 'description' => 'Lake Kawaguchi and scenic views.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourJapan->itineraries()->create(['day_number' => 4, 'title' => 'TOKYO – KYOTO', 'description' => 'Shinkansen to Kyoto, temple visit.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourJapan->itineraries()->create(['day_number' => 5, 'title' => 'KYOTO – OSAKA', 'description' => 'Fushimi Inari and Dotonbori.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourJapan->itineraries()->create(['day_number' => 6, 'title' => 'OSAKA FREE DAY', 'description' => 'Free day or optional tour.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourJapan->itineraries()->create(['day_number' => 7, 'title' => 'OSAKA – JAKARTA', 'description' => 'Return flight.', 'meals_info' => 'B']);
        }
        if (! $tourJapan->prices()->exists()) {
            $tourJapan->prices()->create([
                'type' => 'adult_twin',
                'price' => 26800000,
                'tax' => 0,
                'insurance' => 550000,
                'visa_fee' => 900000,
                'tipping' => 1200000,
            ]);
        }
        if (! $tourJapan->galleries()->exists()) {
            $tourJapan->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1526481280695-3c687fd5432c?auto=format&fit=crop&q=80&w=1200', 'is_primary' => true]);
            $tourJapan->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1554797589-7241bb691973?auto=format&fit=crop&q=80&w=1200', 'is_primary' => false]);
            $tourJapan->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1505060891314-8f0fca316c09?auto=format&fit=crop&q=80&w=1200', 'is_primary' => false]);
        }

        $tourEurope = InternationalTour::firstOrCreate(
            ['slug' => 'europe-highlights-paris-swiss-milan'],
            [
                'category_id' => $europe->id,
                'title' => 'Europe Highlights (Paris – Swiss – Milan)',
                'description' => 'Classic Europe route with iconic cities and breathtaking alpine scenery.',
                'base_price' => 45900000,
                'duration_days' => 10,
                'duration_nights' => 7,
                'airline_info' => 'EMIRATES',
                'highlights' => "Eiffel Tower Photo Stop\nSwiss Alps Scenic Train\nMilan City Walk",
                'inclusions' => 'Flight tickets, Hotel 3*, Transport, Tour Leader',
                'exclusions' => 'Visa, Personal expenses, Tipping',
                'terms_conditions' => 'Booking fee non-refundable',
                'visa_requirements' => 'Schengen requirements apply, passport validity minimum 6 months',
                'passport_validity' => 'Minimal 6 bulan dari tanggal kepulangan',
            ],
        );
        if (! $tourEurope->itineraries()->exists()) {
            $tourEurope->itineraries()->create(['day_number' => 1, 'title' => 'JAKARTA – PARIS', 'description' => 'Departure to Paris.', 'hotel_info' => 'Hotel 3*']);
            $tourEurope->itineraries()->create(['day_number' => 2, 'title' => 'PARIS CITY TOUR', 'description' => 'Louvre area, Eiffel photo stop.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourEurope->itineraries()->create(['day_number' => 3, 'title' => 'PARIS – LUCERNE', 'description' => 'Transfer to Switzerland.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourEurope->itineraries()->create(['day_number' => 4, 'title' => 'SWISS ALPS', 'description' => 'Jungfrau optional, scenic spots.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourEurope->itineraries()->create(['day_number' => 5, 'title' => 'LUCERNE – MILAN', 'description' => 'Transfer to Milan, city walk.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourEurope->itineraries()->create(['day_number' => 6, 'title' => 'MILAN FREE DAY', 'description' => 'Free day for shopping.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourEurope->itineraries()->create(['day_number' => 7, 'title' => 'MILAN – ZURICH', 'description' => 'Return to Zurich.', 'hotel_info' => 'Hotel 3*', 'meals_info' => 'B']);
            $tourEurope->itineraries()->create(['day_number' => 8, 'title' => 'ZURICH – JAKARTA', 'description' => 'Return flight.', 'meals_info' => 'B']);
        }
        if (! $tourEurope->prices()->exists()) {
            $tourEurope->prices()->create([
                'type' => 'adult_twin',
                'price' => 45900000,
                'insurance' => 750000,
                'visa_fee' => 3200000,
                'tipping' => 1600000,
            ]);
        }
        if (! $tourEurope->galleries()->exists()) {
            $tourEurope->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=1200', 'is_primary' => true]);
            $tourEurope->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1528909514045-2fa4ac7a08ba?auto=format&fit=crop&q=80&w=1200', 'is_primary' => false]);
            $tourEurope->galleries()->create(['image_path' => 'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?auto=format&fit=crop&q=80&w=1200', 'is_primary' => false]);
        }
    }
}
