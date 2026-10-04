<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //users
            'users.view',
            'users.create',
            'users.edit',
            'users.deactivate',
            'users.reset-password',

            //roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            //categories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            //products
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            //pos
            'pos.access',

            //transactions
            'transactions.view-own',
            'transactions.view-all',
            'transactions.print',

            //reports
            'reports.view',
            'reports.export',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
