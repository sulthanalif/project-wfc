<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //landingpage
            HeaderSeeder::class,
            ProfileSeeder::class,
            ContactSeeder::class,

            RoleAndPermissionSeeder::class,
            // AdminSeeder::class,
            UserSeeder::class,
            ProductPackageSeeder::class
        ]);
    }
}
