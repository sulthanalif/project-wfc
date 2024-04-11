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
            'email' => 'agent1@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $agent->agentProfile()->create([
            'name' => 'Agent1'
        ]);
        $agent->active = true;

        $agent->assignRole('agent');

        // Agent User
        $agent2 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent2@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $agent2->agentProfile()->create([
            'name' => 'Agent2'
        ]);

        $agent2->assignRole('agent');

        // Agent User
        $agent3 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent3@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $agent3->agentProfile()->create([
            'name' => 'Agent3'
        ]);

        $agent3->assignRole('agent');

        // Agent User
        $agent4 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent4@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $agent4->agentProfile()->create([
            'name' => 'Agent4'
        ]);

        $agent4->assignRole('agent');

        // Agent User
        $agent5 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent5@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $agent5->agentProfile()->create([
            'name' => 'Agent5'
        ]);

        $agent5->assignRole('agent');

        // Agent User
        $agent6 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent6@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $agent6->agentProfile()->create([
            'name' => 'Agent6'
        ]);

        $agent6->assignRole('agent');

        // Agent User
        $agent7 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent7@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $agent7->agentProfile()->create([
            'name' => 'Agent7'
        ]);

        $agent7->assignRole('agent');

        // Agent User
        $agent8 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent8@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $agent8->agentProfile()->create([
            'name' => 'Agent8'
        ]);

        $agent8->assignRole('agent');

        // AgentProfile::create([
        //     'user_id' => $agent->id,
        //     'address' => 'Jalan Kenangan'
        // ]);
    }
}
