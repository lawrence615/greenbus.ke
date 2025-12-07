<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $testimonials = [
            [
                'tour_id' => 1,
                'author_name' => 'Татьяна',
                'author_location' => 'Kenya',
                'author_date' => '2025-12-03',
                'author_cover' => '',
                'tour_name' => 'Nairobi: Historic and Modern Highlights Walking Tour',
                'content' => 'It was wonderful, I learned a lot of information about the center of Nairobi, visited the local market, took a walk and admired the beautiful views',
                'travel_type' => 'Solo traveller',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'tour_id' => 1,
                'author_name' => 'Jimrey',
                'author_location' => 'Saudi Arabia',
                'author_date' => '2025-12-02',
                'author_cover' => '',
                'tour_name' => '',
                'content' => 'This wasn’t just a tour, it felt like a friendly meetup with our amazing guide, Sharon. She took the time to show us around and explain the beauty of Nairobi’s central district, making the experience truly special.',
                'travel_type' => 'Family with children',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'tour_id' => 1,
                'author_name' => 'Sam',
                'author_location' => '',
                'author_date' => '2025-11-19',
                'author_cover' => '',
                'tour_name' => '',
                'content' => 'We had an amazing day. Simon was lovely and inluded everything from souvenirs to sights with plenty of cultural background and room for plenty of questions. Highly recommend.',
                'travel_type' => 'Friends group',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'tour_id' => 1,
                'author_name' => 'SHAHROL',
                'author_location' => '',
                'author_date' => '2025-11-19',
                'author_cover' => '',
                'tour_name' => '',
                'content' => 'My wife and I joined the walking tour with Jeff and it was an amazing experience. Jeff was friendly, knowledgeable and very enthusiastic throughout the tour. He explained everything clearly, shared interesting stories, and made the whole walk fun and engaging. I really appreciated how he always made sure we were comfortable and had enough time to enjoy each spot Overall, Jeff made the tour memorable and I would definitely recommend him to anyone visiting the city',
                'travel_type' => 'Couple',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'tour_id' => 1,
                'author_name' => 'Riley',
                'author_location' => '',
                'author_date' => '2025-11-14',
                'author_cover' => 'https://cdn.getyourguide.com/img/review/24295e6fe1db59a7291087dd25a35f487d7f18302fd7e885607a7cdf2e924b48.jpg/161.jpg',
                'tour_name' => '',
                'content' => 'We had such a good time with Jeff! Not only did he explain the history and sites, we really felt like we got to learn about real life in Nairobi with him. Would recommend!',
                'travel_type' => 'Couple',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'tour_id' => 1,
                'author_name' => 'adrienne',
                'author_location' => '',
                'author_date' => '2025-11-10',
                'author_cover' => 'https://cdn.getyourguide.com/img/review/b06d98d271e824be3273c2fd4da26213227bcfcffcdd930f87c4a910f522cfce.jpg/161.jpg',
                'tour_name' => '',
                'content' => 'Jeff was a great tour guide. a short vur informative tour of nartobi. would definitely recommend.',
                'travel_type' => 'Solo traveller',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 0,
            ]
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
