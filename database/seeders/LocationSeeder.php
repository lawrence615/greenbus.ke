<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $nairobi = Location::where('name', 'Nairobi')->first();
        
        if (!$nairobi) {
            $nairobi = Location::create([
                'name' => 'Nairobi',
                'code' => 'NRB',
                'type' => 'location',
                'country' => 'Kenya',
                'description' => 'Kenya\'s capital city and major business hub',
                'is_active' => true,
            ]);
        }
    }
}
