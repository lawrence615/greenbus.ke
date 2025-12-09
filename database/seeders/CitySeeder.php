<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        City::firstOrCreate(
            ['slug' => 'nairobi'],
            [
                'name' => 'Nairobi',
                'code' => 'NRB',
                'country' => 'Kenya',
                'is_active' => true,
            ],
        );
    }
}
