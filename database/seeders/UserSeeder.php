<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user - full access
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@greenbus.ke',
        ]);
        $admin->assignRole('admin');

        // Manager user - all except user management
        $manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@greenbus.ke',
        ]);
        $manager->assignRole('manager');

        // Customer user - dashboard and view bookings
        $customer = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@greenbus.ke',
        ]);
        $customer->assignRole('customer');
    }
}
