<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Create permissions
        $manageProducts = Permission::firstOrCreate(['name' => 'manage products']);
        $manageOrders = Permission::firstOrCreate(['name' => 'manage orders']);

        // Assign permissions to roles
        $adminRole->givePermissionTo([$manageProducts, $manageOrders]);
        $customerRole->syncPermissions([]);

        // Seed admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@liveshop.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles([$adminRole]);

        // Seed customer user
        $customer = User::firstOrCreate(
            ['email' => 'user@liveshop.test'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );
        $customer->syncRoles([$customerRole]);
    }
}
