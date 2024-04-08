<?php

namespace Database\Seeders;

use App\Models\AgentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
        ]);

        $superAdmin->assignRole('super_admin');

        // Finance Admin User
        $financeAdmin = User::create([
            // 'name' => 'Finance Admin',
            'email' => 'financeadmin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $financeAdmin->assignRole('finance_admin');

        // Admin User
        $admin = User::create([
            // 'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');

        // Agent User
        $agent = User::create([
            // 'name' => 'Agent',
            'email' => 'agent@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $agent->agentProfile()->create([
            'name' => 'Agent'
        ]);

        $agent->assignRole('agent');

        // AgentProfile::create([
        //     'user_id' => $agent->id,
        //     'address' => 'Jalan Kenangan'
        // ]);
    }
}
