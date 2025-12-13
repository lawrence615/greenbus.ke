<?php

namespace Database\Seeders;

use App\Models\TourCategory;
use Illuminate\Database\Seeder;

class TourCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'hourly-tours',
                'name' => 'Hourly Tours',
                'description' => 'Short tours lasting 1-5 hours',
                'duration_type' => 'hourly',
            ],
            [
                'slug' => 'half-day-tours',
                'name' => 'Half Day Tours',
                'description' => 'Tours lasting approximately 6 hours',
                'duration_type' => 'half_day',
            ],
            [
                'slug' => 'full-day-tours',
                'name' => 'Full Day Tours',
                'description' => 'Full day tours and experiences',
                'duration_type' => 'full_day',
            ],
            [
                'slug' => 'multiple-day-tours',
                'name' => 'Multiple Day Tours',
                'description' => 'Extended tours spanning multiple days',
                'duration_type' => 'multiple_days',
            ],
        ];

        foreach ($categories as $category) {
            TourCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
