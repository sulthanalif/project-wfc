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
            DetailProfileSeeder::class,
            GallerySeeder::class,
            ContactSeeder::class,
            ReviewPageSeeder::class,

            RoleAndPermissionSeeder::class,
            AdminSeeder::class,
            SpendingTypeSeeder::class,
            BankOwnerSeeder::class,
            // UserSeeder::class,
            // ProductPackageSeeder::class
        ]);
    }
}
