<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@kafetaria.test',
            'password' => 'password',
            'is_active' => true,
        ]);
        $superAdmin->assignRole('Super Admin');

        $administrator = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'administrator@kafetaria.test',
            'password' => 'password',
            'is_active' => true,
        ]);
        $administrator->assignRole('Administrator');

        $cashier = User::factory()->create([
            'name' => 'Kasir Satu',
            'email' => 'kasir@kafetaria.test',
            'password' => 'password',
            'is_active' => true,
        ]);
        $cashier->assignRole('Cashier');
    }
}
