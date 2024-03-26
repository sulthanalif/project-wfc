<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat permission
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'add_order']);
        Permission::create(['name' => 'approve_order']);

        // Buat role
        $superAdminRole = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $financeAdminRole = Role::create(['name' => 'finance_admin', 'guard_name' => 'web']);
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $agentRole = Role::create(['name' => 'agent', 'guard_name' => 'web']);

        // Berikan semua permission ke super_admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Berikan permission yang sesuai ke admin
        $adminRole->givePermissionTo('add_order');
        $adminRole->givePermissionTo('approve_order');

        // Berikan permission yang sesuai ke admin
        $financeAdminRole->givePermissionTo('add_order');
        $financeAdminRole->givePermissionTo('approve_order');

        // Berikan permission yang sesuai ke distributor
        $agentRole->givePermissionTo('add_order');
    }
}
