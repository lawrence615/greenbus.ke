<?php

namespace Database\Seeders;

use App\Interfaces\FaqRepositoryInterface;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(FaqRepositoryInterface $fAQRepositoryInterface): void
    {
        $faqs = [
            [
                'tour_category_id' => 1,
                'question' => 'What do i do incase am not able to do the tour or i want a refund?',
                'answer' => '<p>Kindly write to us through happytribetravel@gmail.com or whatsapp us through +254 726 455 000</p>',
                'category' => 'Booking',
                'is_active' => 1,
                'sort_order' => 1
            ]
        ];

        foreach ($faqs as $faq) {
            $fAQRepositoryInterface->store($faq);
        }
    }
}
