<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TenantPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage products',
            'manage orders',
            'manage categories',
            'manage users',
            'manage settings',
            'view reports',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        Role::firstOrCreate(['name' => 'admin'])->syncPermissions($permissions);

        Role::firstOrCreate(['name' => 'staff'])->syncPermissions([
            'manage products',
        ]);

        Role::firstOrCreate(['name' => 'inventory manager'])->syncPermissions([
            'manage products',
            'manage categories',
        ]);

        Role::firstOrCreate(['name' => 'order manager'])->syncPermissions([
            'manage orders',
        ]);

        Role::firstOrCreate(['name' => 'analyst'])->syncPermissions([
            'view reports',
            'view dashboard',
        ]);
    }
}
