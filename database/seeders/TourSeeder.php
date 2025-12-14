<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourImage;
use App\Models\TourItineraryItem;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $nairobi = Location::where('name', 'Nairobi')->first();
        $singleDay = TourCategory::where('slug', 'hourly-tours')->first();

        if (! $nairobi || ! $singleDay) {
            return;
        }

        if (! Tour::where('location_id', $nairobi->id)->exists()) {
            $cityHighlights = Tour::create([
                'location_id' => $nairobi->id,
                'tour_category_id' => $singleDay->id,
                'code' => Tour::generateCode(1),
                'title' => 'Nairobi: Historic and Modern Highlights Location Walking Tour',
                'slug' => 'nairobi-historic-and-modern-highlights-location-walking-tour',
                'short_description' => '<p>See the key sights of Nairobi in a comfortable coach with a local guide.</p>',
                'description' => "<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(56, 68, 58);\">Ideal as an introduction for first-time visitors to Nairobi (or anyone looking to enhance their understanding of the Kenyan capital), this tour ditches the dry historical information and gives travelers an insider’s view of the location. Highlights include Jamia Mosque, souvenir shopping at Location Market, and a location-wide panorama from KICC. Bottled water and door-to-door, air-conditioned vehicle transfers are provided.</span></p>",
                'included' => "<ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Guided location tour with an English-speaking local guide.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Transport in a comfortable coach or minibus.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Short photo stops at key viewpoints. </li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Learn some popular Swahili words used by locals. </li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Buy souvenirs without negotiating at the cheapest fixed prices stores.</li></ol>",
                'important_information' => "<ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Hotel pickup is available from central Nairobi on request.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Please bring a passport/valid ID and your digital ticket.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Wear comfortable shoes and be prepared for changing weather.</li></ol>",
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
                'location_id' => $nairobi->id,
                'tour_category_id' => $singleDay->id,
                'code' => Tour::generateCode(1),
                'title' => 'Nairobi: National Museum & Location Markets Tour',
                'slug' => 'nairobi-national-museum-and-location-markets-tour',
                'short_description' => 'Combine culture and local life with a visit to the National Museum and bustling location markets.',
                'description' => 'Discover Kenya’s history at the National Museum, then walk through colorful location markets with your guide.',
                'included' => "Guided visit of the National Museum of Kenya.\nGuided walk through selected central markets.\nTransport between museum and markets.",
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

            $cityTourImages = [
                ['tour_id' => $museumMarket->id,'path'=>'greenbus/images/NRB-001/nairobi-historic-and-modern-highlights-location-walking-tour-0WZHDvLy.jpg', 'is_cover' => false, 'sort_order' => 1],
                ['tour_id' => $museumMarket->id,'path'=>'greenbus/images/NRB-001/nairobi-historic-and-modern-highlights-location-walking-tour-mLrZtq7e.jpg', 'is_cover' => false, 'sort_order' => 2],
                ['tour_id' => $museumMarket->id,'path'=>'greenbus/images/NRB-001/nairobi-historic-and-modern-highlights-location-walking-tour-ArbtClKd.jpg', 'is_cover' => false, 'sort_order' => 3],
                ['tour_id' => $museumMarket->id,'path'=>'greenbus/images/NRB-001/nairobi-historic-and-modern-highlights-location-walking-tour-IVG5NskM.jpg', 'is_cover' => false, 'sort_order' => 4],
                ['tour_id' => $museumMarket->id,'path'=>'greenbus/images/NRB-001/nairobi-historic-and-modern-highlights-location-walking-tour-HLAhl0U5.jpg', 'is_cover' => false, 'sort_order' => 5],
                ['tour_id' => $museumMarket->id,'path'=>'greenbus/images/NRB-001/nairobi-historic-and-modern-highlights-location-walking-tour-fbScbiiW.jpg', 'is_cover' => false, 'sort_order' => 6],
            ];

            // TourImage::create([
            //     'tour_id' => $museumMarket->id,
            //     'path' => 'https://rightchoicesafaris.com/wp-content/uploads/2020/08/nairobi_museum.jpg',
            //     'is_cover' => true,
            //     'sort_order' => 1,
            // ]);

            foreach ($cityTourImages as $image) {
                TourImage::create($image);
            }

            $cityHighlightsItems = [
                ['type' => 'start', 'time_label' => '10:00 AM', 'title' => 'Kencom / Location Square – Meet your guide', 'description' => "Meet your guide at a central pickup point in Nairobi, get a short briefing about the route and what to expect.", 'sort_order' => 1],
                ['type' => 'activity', 'time_label' => '10:30 AM', 'title' => 'Kenyatta Avenue & CBD – Location walk', 'description' => "Walk past key landmarks in the location centre while your guide shares stories about Nairobi's history and everyday life.", 'sort_order' => 2],
                ['type' => 'activity', 'time_label' => '11:15 AM', 'title' => 'Jamia Mosque area – Exterior visit', 'description' => 'Pause near Jamia Mosque or a similar landmark for photos and a short explanation from your guide (exterior only).', 'sort_order' => 3],
                ['type' => 'activity', 'time_label' => '12:00 PM', 'title' => 'Location Market or craft area – Market walk', 'description' => 'Explore a local market or craft area to see how people shop and work, with time for questions and optional browsing.', 'sort_order' => 4],
                ['type' => 'activity', 'time_label' => '12:45 PM', 'title' => 'Café or park – Short break', 'description' => 'Optional refreshment break in a café or nearby park where you can buy drinks or snacks.', 'sort_order' => 5],
                ['type' => 'end', 'time_label' => '1:15 PM', 'title' => 'CBD drop-off – End of tour', 'description' => 'Return to the original meeting point or nearby CBD drop-off location and get suggestions for what to do next in Nairobi.', 'sort_order' => 6],
            ];

            foreach ($cityHighlightsItems as $data) {
                TourItineraryItem::firstOrCreate(
                    [
                        'tour_id' => $cityHighlights->id,
                        'sort_order' => $data['sort_order'],
                    ],
                    array_merge($data, ['tour_id' => $cityHighlights->id])
                );
            }

            $museumMarketItems = [
                ['type' => 'start','time_label' => '9:00 AM', 'title' => 'National Museum – Arrival & tickets', 'description' => 'Meet your guide at the National Museum entrance, organise tickets and get an overview of the visit.', 'sort_order' => 1],
                ['type' => 'activity','time_label' => '9:30 AM', 'title' => 'National Museum – Galleries tour', 'description' => 'Guided walk through selected galleries covering Kenya’s history, culture and wildlife exhibits.', 'sort_order' => 2],
                ['type' => 'activity','time_label' => '11:00 AM', 'title' => 'Museum café or courtyard – Short break', 'description' => 'Take a short break where you can purchase tea, coffee or snacks.', 'sort_order' => 3],
                ['type' => 'activity','time_label' => '11:30 AM', 'title' => 'Transfer to location markets', 'description' => 'Travel with your guide from the museum area to the location markets.', 'sort_order' => 4],
                ['type' => 'activity','time_label' => '12:00 PM', 'title' => 'Location markets – Guided walk', 'description' => 'Walk through one or more central markets to see everyday shopping, fruits, vegetables and local products.', 'sort_order' => 5],
                ['type' => 'activity','time_label' => '1:00 PM', 'title' => 'Souvenir and craft stalls', 'description' => 'Optional time to browse craft and souvenir stalls with guidance on prices and etiquette.', 'sort_order' => 6],
                ['type' => 'end','time_label' => '1:30 PM', 'title' => 'CBD drop-off – End of tour', 'description' => 'End of the tour with drop-off near the museum or a central CBD point, plus tips for the rest of your day.', 'sort_order' => 7],
            ];

            foreach ($museumMarketItems as $data) {
                TourItineraryItem::firstOrCreate(
                    [
                        'tour_id' => $museumMarket->id,
                        'sort_order' => $data['sort_order'],
                    ],
                    array_merge($data, ['tour_id' => $museumMarket->id])
                );
            }
        }
    }
}
