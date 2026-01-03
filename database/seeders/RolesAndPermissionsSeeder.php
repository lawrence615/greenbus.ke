<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard permissions
            'view console dashboard',

            // Tour management permissions
            'list tours',
            'delete tours',
            'toggle tours status',
            'toggle tours bus',
            'list standard tours',
            'create standard tours',
            'store standard tours',
            'view standard tours',
            'edit standard tours',
            'update standard tours',
            'list bespoke tours',
            'create bespoke tours',
            'store bespoke tours',
            'view bespoke tours',
            'edit bespoke tours',
            'update bespoke tours',
            'share bespoke tours',
            'list tours trash',
            'restore tours trash',
            'delete tours trash',
            'list tours itinerary',
            'create tours itinerary',
            'store tours itinerary',
            'edit tours itinerary',
            'update tours itinerary',
            'delete tours itinerary',
            'reorder tours itinerary',
            'list tours multimedia',
            'upload tours multimedia',
            'update tours multimedia',
            'delete tours multimedia',
            'set-cover tours multimedia',
            'create tours pricing',
            'store tours pricing',
            'delete tours pricing',

            // Booking permissions
            'list bookings',
            'view bookings',
            'update bookings status',
            'update bookings date',
            'update bookings notes',
            'refund bookings',

            // Payment permissions
            'list payments',
            'view payments',

            // User management permissions
            'list users',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage users',
            'invite users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Customer role - dashboard and view bookings only
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerRole->syncPermissions([
            'view customer dashboard',
            'list customer bookings',
            'view customer bookings',
        ]);

        // Manager role - all permissions except user management
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $excludedFromManager = [
            'manage users',
            'delete users',
            'invite users',
            'delete tours trash',
            'refund bookings',
        ];
        $managerPermissions = collect($permissions)
            ->filter(fn($permission) => !in_array($permission, $excludedFromManager))
            ->values()
            ->all();
        $managerRole->syncPermissions($managerPermissions);


        // Admin role - all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);
    }
}
