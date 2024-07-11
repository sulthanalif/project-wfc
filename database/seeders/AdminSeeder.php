<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin User
        $superAdmin = User::create([
            // 'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'active' => 1
        ]);

        $superAdmin->assignRole('super_admin');

        $superAdmin->adminProfile()->create([
            'name' => 'Owner'
        ]);

        // Finance Admin User
        $financeAdmin = User::create([
            // 'name' => 'Finance Admin',
            'email' => 'financeadmin@gmail.com',
            'password' => Hash::make('password'),
            'active' => 1
        ]);

        $financeAdmin->assignRole('finance_admin');

        $financeAdmin->adminProfile()->create([
            'name' => 'Admin Keuangan'
        ]);

        // Admin User
        $admin = User::create([
            // 'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'active' => 1
        ]);

        $admin->assignRole('admin');

        $admin->adminProfile()->create([
            'name' => 'Salwa'
        ]);
    }
}
