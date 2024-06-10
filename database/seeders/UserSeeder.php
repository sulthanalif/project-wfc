<?php

namespace Database\Seeders;

use App\Models\AgentProfile;
use App\Models\SubAgent;
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

        // Agent User
        $agent = User::create([
            // 'name' => 'Agent',
            'email' => 'agent1@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'active' => 0
        ]);

        $agent->agentProfile()->create([
            'name' => 'Agent1'
        ]);
        $agent->active = true;

        $agent->assignRole('agent');

        $agent->administration()->create([
            'ktp' => 'default.png',
            'kk' => 'default.png',
            'sPerjanjian' => 'default.png',
        ]);

        // Agent User
        $agent2 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent2@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'active' => 0
        ]);

        $agent2->agentProfile()->create([
            'name' => 'Agent2'
        ]);

        $agent2->assignRole('agent');

        $agent2->administration()->create([
            'ktp' => 'default.png',
            'kk' => 'default.png',
            'sPerjanjian' => 'default.png',
        ]);

        // Agent User
        $agent3 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent3@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'active' => 1
        ]);

        $agent3->agentProfile()->create([
            'name' => 'Agent3'
        ]);

        $agent3->assignRole('agent');

        $subAgent3 = [
            [
                'agent_id' => $agent3->id,
                'name' => 'Sub Agent 1',
                'address' => 'Jl. Cempaka No. 6, Jakarta 12130',
                'phone_number' => '081234567890',
            ],
            [
                'agent_id' => $agent3->id,
                'name' => 'Sub Agent 2',
                'address' => 'Jl. Kenangan No. 5, Bandung 12130',
                'phone_number' => '0812345832484',
            ]
        ];

        foreach ($subAgent3 as $value) {
            SubAgent::create($value);
        }

        // Agent User
        $agent4 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent4@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'active' => 1
        ]);

        $agent4->agentProfile()->create([
            'name' => 'Agent4'
        ]);

        $agent4->assignRole('agent');

        $subAgent4 = [
            [
                'agent_id' => $agent4->id,
                'name' => 'Sub Agent 1',
                'address' => 'Jl. Cempaka No. 6, Jakarta 12130',
                'phone_number' => '081234567890',
            ],
            [
                'agent_id' => $agent4->id,
                'name' => 'Sub Agent 2',
                'address' => 'Jl. Kenangan No. 5, Bandung 12130',
                'phone_number' => '0812345832484',
            ]
        ];

        foreach ($subAgent4 as $value) {
            SubAgent::create($value);
        }

        // Agent User
        $agent5 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent5@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'active' => 1
        ]);

        $agent5->agentProfile()->create([
            'name' => 'Agent5'
        ]);

        $agent5->assignRole('agent');

        $subAgent5 = [
            [
                'agent_id' => $agent5->id,
                'name' => 'Sub Agent 1',
                'address' => 'Jl. Cempaka No. 6, Jakarta 12130',
                'phone_number' => '081234567890',
            ],
            [
                'agent_id' => $agent5->id,
                'name' => 'Sub Agent 2',
                'address' => 'Jl. Kenangan No. 5, Bandung 12130',
                'phone_number' => '0812345832484',
            ]
        ];

        foreach ($subAgent5 as $value) {
            SubAgent::create($value);
        }

        // Agent User
        $agent6 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent6@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'active' => 1
        ]);

        $agent6->agentProfile()->create([
            'name' => 'Agent6'
        ]);

        $agent6->assignRole('agent');

        $subAgent6 = [
            [
                'agent_id' => $agent6->id,
                'name' => 'Sub Agent 1',
                'address' => 'Jl. Cempaka No. 6, Jakarta 12130',
                'phone_number' => '081234567890',
            ],
            [
                'agent_id' => $agent6->id,
                'name' => 'Sub Agent 2',
                'address' => 'Jl. Kenangan No. 5, Bandung 12130',
                'phone_number' => '0812345832484',
            ]
        ];

        foreach ($subAgent6 as $value) {
            SubAgent::create($value);
        }

        // Agent User
        $agent7 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent7@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'active' => 1
        ]);

        $agent7->agentProfile()->create([
            'name' => 'Agent7'
        ]);

        $agent7->assignRole('agent');

        $subAgent7 = [
            [
                'agent_id' => $agent7->id,
                'name' => 'Sub Agent 1',
                'address' => 'Jl. Cempaka No. 6, Jakarta 12130',
                'phone_number' => '081234567890',
            ],
            [
                'agent_id' => $agent7->id,
                'name' => 'Sub Agent 2',
                'address' => 'Jl. Kenangan No. 5, Bandung 12130',
                'phone_number' => '0812345832484',
            ]
        ];

        foreach ($subAgent7 as $value) {
            SubAgent::create($value);
        }

        // Agent User
        $agent8 = User::create([
            // 'name' => 'Agent',
            'email' => 'agent8@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'active' => 1
        ]);

        $agent8->agentProfile()->create([
            'name' => 'Agent8'
        ]);

        $agent8->assignRole('agent');

        $subAgent8 = [
            [
                'agent_id' => $agent8->id,
                'name' => 'Sub Agent 1',
                'address' => 'Jl. Cempaka No. 6, Jakarta 12130',
                'phone_number' => '081234567890',
            ],
            [
                'agent_id' => $agent8->id,
                'name' => 'Sub Agent 2',
                'address' => 'Jl. Kenangan No. 5, Bandung 12130',
                'phone_number' => '0812345832484',
            ]
        ];

        foreach ($subAgent8 as $value) {
            SubAgent::create($value);
        }
    }
}
