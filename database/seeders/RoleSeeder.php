<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //super admin
        Role::firstOrCreate(['name' => 'Super Admin']);

        //administrator
        $administratorPermissions = Permission::all()
            ->reject(function ($permission){
                return str_starts_with($permission->name, 'roles.')
                    || $permission->name === 'transactions.view-own';
            });

        $administratorRole = Role::firstOrCreate(['name' => 'Administrator']);
        $administratorRole->syncPermissions($administratorPermissions);

        //kasir
        $cashier = [
            'pos.access',
            'transactions.view-own',
            'transactions.print',
            'categories.view',
            'products.view',
        ];

        $cashierRole = Role::firstOrCreate(['name' => 'Cashier']);
        $cashierRole->syncPermissions($cashier);
    }
}
