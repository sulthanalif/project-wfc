<?php

namespace Database\Seeders;

use App\Models\BankOwner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'BNI',
                'account_name' => 'WFC',
                'account_number' => rand(10000000, 99999999),
            ],
            [
                'name' => 'BRI',
                'account_name' => 'WFC',
                'account_number' => rand(10000000, 99999999),
            ],
            [
                'name' => 'Mandiri',
                'account_name' => 'WFC',
                'account_number' => rand(10000000, 99999999),
            ],
        ];

        foreach ($datas as $data) {
            BankOwner::create($data);
        }
    }
}
