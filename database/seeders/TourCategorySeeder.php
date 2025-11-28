<?php

namespace Database\Seeders;

use App\Models\TourCategory;
use Illuminate\Database\Seeder;

class TourCategorySeeder extends Seeder
{
    public function run(): void
    {
        TourCategory::firstOrCreate(
            ['slug' => 'single-day-trip'],
            [
                'name' => 'Single day trip',
                'description' => 'Tours that can be completed within a single day.',
            ],
        );
    }
}
