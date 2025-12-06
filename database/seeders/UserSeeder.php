<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@greenbus.ke',
        ]);

        // Assign super-admin role
        $user->assignRole('super-admin');
    }
}
