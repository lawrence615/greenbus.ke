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
            // Booking permissions
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            
            // Payment permissions
            'view payments',
            'refund payments',
            
            // User management permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Tour management permissions
            'view tours',
            'create tours',
            'edit tours',
            'delete tours',
            
            // Dashboard permissions
            'view dashboard',
            'view admin dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Customer role - dashboard and view bookings only
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerRole->syncPermissions([
            'view dashboard',
            'view bookings',
        ]);

        // Manager role - all permissions except user management
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            // Booking permissions
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            // Payment permissions
            'view payments',
            'refund payments',
            // Tour management permissions
            'view tours',
            'create tours',
            'edit tours',
            'delete tours',
            // Dashboard permissions
            'view dashboard',
            'view admin dashboard',
        ]);

        // Admin role - all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);
    }
}
