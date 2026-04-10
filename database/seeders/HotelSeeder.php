<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = [
            'Resort' => 'resort',
            'City Hotel' => 'city_hotel',
            'Boutique' => 'boutique',
            'Eco Lodge' => 'eco_lodge',
        ];

        $items = [
            [
                'slug' => 'aryaduta-hotel-manado',
                'name' => 'Aryaduta Hotel Manado',
                'stars' => 5,
                'category' => 'City Hotel',
                'description' => 'Aryaduta Hotel Manado offers elegant accommodations overlooking the bay. This luxurious hotel combines modern comfort with spectacular views of the Minahasa highlands and the ocean, providing top-class amenities, premium dining, and an unforgettable stay right in the heart of Manado.',
                'primary_image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'location' => 'Manado Boulevard',
            ],
            [
                'slug' => 'aston-hotel-manado',
                'name' => 'Aston Hotel Manado',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => 'Enjoy comfort and world-class service right in the heart of Manado city. The Aston Hotel mixes dynamic contemporary designs with classic elegance, making it ideal for business or leisure travel in North Sulawesi.',
                'primary_image' => 'https://images.unsplash.com/photo-1551882547-ff40c0d588fa?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado City Center',
            ],
            [
                'slug' => 'best-western-hotel-manado',
                'name' => 'Best Western Hotel Manado',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => 'Modern amenities and beautiful rooms for the perfect tropical getaway. Providing seamless standards of international quality and local charm.',
                'primary_image' => 'https://images.unsplash.com/photo-1542314831-c6a4d14d8c53?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'location' => 'Manado',
            ],
            [
                'slug' => 'four-points-hotel-manado',
                'name' => 'Four Points Hotel Manado',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => 'Four Points Hotel Manado brings premium facilities and stunning ocean views. Take a dip in the infinity pool while overlooking the beautiful Celebes Sea.',
                'primary_image' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'location' => 'Sario Tumpaan',
            ],
            [
                'slug' => 'grand-luley-manado',
                'name' => 'Grand Luley Manado',
                'stars' => 4,
                'category' => 'Resort',
                'description' => 'Immerse into nature at Grand Luley, a lush sanctuary bordered by a mangrove forest. Reconnect with nature before diving into the magnificent Bunaken National Park.',
                'primary_image' => 'https://images.unsplash.com/photo-1498503182468-3b51cbb6cb24?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'location' => 'Tongkaina, Bunaken',
            ],
            [
                'slug' => 'mercure-manado-tateli',
                'name' => 'Mercure Manado Tateli Beach Resort',
                'stars' => 4,
                'category' => 'Resort',
                'description' => 'A relaxing beachfront resort offering panoramic views of the Pacific Ocean. Perfect for family weekends and romantic getaways.',
                'primary_image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'location' => 'Tateli Beach, Minahasa',
            ],
            [
                'slug' => 'sintesa-peninsula',
                'name' => 'Sintesa Peninsula Hotel Manado',
                'stars' => 5,
                'category' => 'City Hotel',
                'description' => '5-star luxury with unmatched views over the bay and premium dining experiences. This iconic destination dominates the skyline of Manado.',
                'primary_image' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'location' => 'Jalan Jendral Sudirman',
            ],
            [
                'slug' => 'swiss-belhotel-manado',
                'name' => 'Swiss-Belhotel Manado',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => 'Combining contemporary design with local charm for an unforgettable stay. Boasts phenomenal seafood cuisine and stunning harbor views.',
                'primary_image' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'location' => 'Manado Business District',
            ],
            [
                'slug' => 'manado-quality-hotel',
                'name' => 'Manado Quality Hotel',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => 'High-quality accommodations prioritizing guest satisfaction and comfort in Manado. Ideal for those looking for great value without compromising quality.',
                'primary_image' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'location' => 'Manado',
            ],
            [
                'slug' => 'novotel-manado',
                'name' => 'Novotel Manado Golf Resort & Convention Center',
                'stars' => 4,
                'category' => 'Resort',
                'description' => 'Novotel Manado Golf Resort & Convention Center brings top-tier facilities, an expansive golf course, and convention halls perfect for large events.',
                'primary_image' => 'https://images.unsplash.com/photo-1562438668-bcf0ca6578f0?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Grand Kawanua City',
            ],
            [
                'slug' => 'grand-whiz-manado',
                'name' => 'Grand Whiz Manado',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => 'Stay smart and comfortable at the perfectly located Grand Whiz Manado, featuring modern amenities connecting you straight to the beach promenade.',
                'primary_image' => 'https://plus.unsplash.com/premium_photo-1678286770040-3b9af647dc6a?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Megamas Area',
            ],
            [
                'slug' => 'gran-puri-hotel',
                'name' => 'Gran Puri Hotel Manado',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => 'Experience the warm hospitality and elegance of Gran Puri Hotel Manado with a distinctly local Minahasan aesthetic and premium service.',
                'primary_image' => 'https://images.unsplash.com/photo-1596701062351-8c2c14d1fdd0?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 'lion-hotel-plaza',
                'name' => 'Lion Hotel & Plaza Manado',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => "Lion Hotel & Plaza Manado is located steps away from excellent dining and shopping, giving you a front-row seat to the city's vibrant life.",
                'primary_image' => 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 'ndc-resort-spa',
                'name' => 'NDC Resort & Spa Manado',
                'stars' => 4,
                'category' => 'Resort',
                'description' => 'Relax at the NDC Resort & Spa, ideal for nature lovers and diving enthusiasts aiming to explore the northern shores.',
                'primary_image' => 'https://images.unsplash.com/photo-1522798514-97ceb8c4f1c8?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Molas',
            ],
            [
                'slug' => 'luwansa-hotel',
                'name' => 'Luwansa Hotel & Convention Center Manado',
                'stars' => 4,
                'category' => 'City Hotel',
                'description' => 'A massive hub of luxury, Luwansa Hotel & Convention Center Manado has it all for premium events and luxurious high-altitude stays.',
                'primary_image' => 'https://images.unsplash.com/photo-1629140727571-9b5c6f6267b4?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 'siladen-resort',
                'name' => 'Siladen Resort & Spa',
                'stars' => 5,
                'category' => 'Eco Lodge',
                'description' => 'An exclusive boutique eco-resort presenting a blissful escape on Siladen Island. Crystal clear waters, turtles, and perfect sandy beaches.',
                'primary_image' => 'https://images.unsplash.com/photo-1549294413-26f195200c16?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Siladen Island',
            ],
            [
                'slug' => 'yama-resort-tondano',
                'name' => 'Yama Resort Tondano',
                'stars' => 4,
                'category' => 'Resort',
                'description' => 'Yama Resort Tondano offers peaceful surroundings by Lake Tondano, giving guests pure relaxation with an authentic countryside feeling.',
                'primary_image' => 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Tondano',
            ],
            [
                'slug' => 'grand-master-tomohon',
                'name' => 'Grand Master Resort Tomohon',
                'stars' => 4,
                'category' => 'Resort',
                'description' => 'Discover the scenic highlands of North Sulawesi at Grand Master Resort Tomohon. Refreshing mountain breezes and magnificent views await.',
                'primary_image' => 'https://images.unsplash.com/photo-1618773928120-2941da736171?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Tomohon',
            ],
            [
                'slug' => 'jhoanie-hotel-tomohon',
                'name' => 'Jhoanie Hotel Tomohon',
                'stars' => 4,
                'category' => 'Boutique',
                'description' => 'Welcome to Jhoanie Hotel Tomohon, your basecamp to the beautiful flower city surrounded by twin volcanoes.',
                'primary_image' => 'https://images.unsplash.com/photo-1543968332-f99478b1ce57?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Tomohon',
            ],
            [
                'slug' => 'villa-emitta-tomohon',
                'name' => 'Villa Emitta Hotel Tomohon',
                'stars' => 4,
                'category' => 'Boutique',
                'description' => 'Villa Emitta Hotel Tomohon features charming architecture, cool breezes, and lush gardens that transport you to a world of peace.',
                'primary_image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Tomohon',
            ],
            [
                'slug' => 'cocotinos-manado',
                'name' => 'Cocotinos Manado',
                'stars' => 4,
                'category' => 'Resort',
                'description' => 'Cocotinos Manado serves as a beautiful gateway to diving sites around Bunaken with exclusive beachfront chalets.',
                'primary_image' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 'ibis-hotel-manado',
                'name' => 'Ibis Hotel Manado',
                'stars' => 3,
                'category' => 'City Hotel',
                'description' => 'Dependable comfort and vibrant design make Ibis Hotel Manado a top choice for smart travelers on the go.',
                'primary_image' => 'https://images.unsplash.com/photo-1551882547-ff40c0d588fa?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 'casa-de-wanea',
                'name' => 'Casa de Wanea Hotel Manado',
                'stars' => 3,
                'category' => 'Boutique',
                'description' => 'Casa de Wanea Hotel Manado provides a cozy and home-like atmosphere wrapped in a colonial-era style building.',
                'primary_image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 'genio-hotel-manado',
                'name' => 'Genio Hotel Manado',
                'stars' => 3,
                'category' => 'City Hotel',
                'description' => 'Genio Hotel Manado offers smart lodging solutions with great connectivity to key transit hubs and city malls.',
                'primary_image' => 'https://images.unsplash.com/photo-1551882547-ff40c0d588fa?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 's-loft-hotel-manado',
                'name' => 'S Loft Hotel Manado',
                'stars' => 3,
                'category' => 'Boutique',
                'description' => 'S Loft Hotel Manado stands out with its modern industrial design and exceptional comfort in the city center.',
                'primary_image' => 'https://images.unsplash.com/photo-1542314831-c6a4d14d8c53?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 'whiz-prime-manado',
                'name' => 'Whiz Prime Hotel Manado',
                'stars' => 3,
                'category' => 'City Hotel',
                'description' => 'Enjoy sweeping sea scenes directly from the smart rooms at Whiz Prime Hotel, perfect for quick stays.',
                'primary_image' => 'https://images.unsplash.com/photo-1551882547-ff40c0d588fa?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
            [
                'slug' => 'formosa-hotel-manado',
                'name' => 'Formosa Hotel Manado',
                'stars' => 3,
                'category' => 'City Hotel',
                'description' => 'Affordable and highly accessible, Formosa Hotel Manado is centrally located amidst various shopping centers.',
                'primary_image' => 'https://images.unsplash.com/photo-1551882547-ff40c0d588fa?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Manado',
            ],
        ];

        foreach ($items as $item) {
            $slug = $item['slug'];
            $hotel = Hotel::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'location' => $item['location'],
                    'category' => $categoryMap[$item['category']] ?? null,
                    'stars' => (int) ($item['stars'] ?? 0),
                    'description' => $item['description'] ?? null,
                    'primary_image' => $item['primary_image'] ?? null,
                ],
            );

            if ($hotel->primary_image === null && ($item['primary_image'] ?? null)) {
                $hotel->primary_image = $item['primary_image'];
                $hotel->save();
            }

            if ($hotel->images()->count() === 0 && ($item['primary_image'] ?? null)) {
                $hotel->images()->create([
                    'image_path' => $item['primary_image'],
                    'is_primary' => true,
                ]);
            }
        }
    }
}
