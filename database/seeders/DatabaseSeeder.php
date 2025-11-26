<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\City;
use App\Models\Tour;
use App\Models\TourImage;
use App\Models\TourCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $nairobi = City::firstOrCreate(
            ['slug' => 'nairobi'],
            [
                'name' => 'Nairobi',
                'country' => 'Kenya',
                'is_active' => true,
            ],
        );

        $singleDay = TourCategory::firstOrCreate(
            ['slug' => 'single-day-trip'],
            [
                'name' => 'Single day trip',
                'description' => 'Tours that can be completed within a single day.',
            ],
        );

        if (! Tour::where('city_id', $nairobi->id)->exists()) {
            $cityHighlights = Tour::create([
                'city_id' => $nairobi->id,
                'tour_category_id' => $singleDay->id,
                'title' => 'Nairobi: Historic and Modern Highlights City Walking Tour',
                'slug' => 'nairobi-historic-and-modern-highlights-city-walking-tour',
                'short_description' => 'See the key sights of Nairobi in a comfortable coach with a local guide.',
                'description' => "Perfect for first-time visitors, this guided tour gives you an overview of Nairobi's history, culture, and everyday life.",
                'includes' => "Guided city tour with an English-speaking local guide.\nTransport in a comfortable coach or minibus.\nShort photo stops at key viewpoints. \nLearn some popular Swahili words used by locals. \nBuy souvenirs without negotiating at the cheapest fixed prices stores.",
                'important_information' => "Hotel pickup is available from central Nairobi on request.\nPlease bring a passport/valid ID and your digital ticket.\nWear comfortable shoes and be prepared for changing weather.",
                'duration_text' => '4 hours',
                'meeting_point' => 'Pick up from central Nairobi hotels or Kenyatta Avenue meeting point',
                'starts_at_time' => '10:00',
                'is_daily' => true,
                'featured' => true,
                'base_price_adult' => 4500,
                'base_price_child' => 2500,
                'base_price_infant' => 0,
                'status' => 'published',
            ]);

            TourImage::create([
                'tour_id' => $cityHighlights->id,
                'path' => 'https://res.klook.com/image/upload/fl_lossy.progressive,q_65/w_1080/w_80,x_15,y_15,g_south_west,l_Klook_water_br_trans_yhcmh3/activities/xqf21xjrx48hpzk9r3fl.webp',
                'is_cover' => true,
                'sort_order' => 1,
            ]);

            $museumMarket = Tour::create([
                'city_id' => $nairobi->id,
                'tour_category_id' => $singleDay->id,
                'title' => 'Nairobi: National Museum & City Markets Tour',
                'slug' => 'nairobi-national-museum-and-city-markets-tour',
                'short_description' => 'Combine culture and local life with a visit to the National Museum and bustling city markets.',
                'description' => 'Discover Kenya’s history at the National Museum, then walk through colorful city markets with your guide.',
                'includes' => "Guided visit of the National Museum of Kenya.\nGuided walk through selected central markets.\nTransport between museum and markets.",
                'important_information' => "Entrance fees to the National Museum are not included unless specified on your ticket.\nMarkets can be busy – keep valuables secure and follow your guide’s instructions.\nSuitable for most fitness levels, with some walking involved.",
                'duration_text' => '5 hours',
                'meeting_point' => 'National Museum entrance or central hotel pickup',
                'starts_at_time' => '09:00',
                'is_daily' => true,
                'featured' => true,
                'base_price_adult' => 5500,
                'base_price_child' => 3000,
                'base_price_infant' => 0,
                'status' => 'published',
            ]);

            TourImage::create([
                'tour_id' => $museumMarket->id,
                'path' => 'https://rightchoicesafaris.com/wp-content/uploads/2020/08/nairobi_museum.jpg',
                'is_cover' => true,
                'sort_order' => 1,
            ]);
        }
    }
}
