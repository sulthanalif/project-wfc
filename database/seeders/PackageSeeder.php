<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'Cibaba',
                'description' => 'ini Deskripsi Cibaba',

            ],
            [
                'name' => 'Cookies',
                'description' => 'ini Deskripsi Cookies',

            ],
            [
                'name' => 'PHL',
                'description' => 'ini Deskripsi PHL',

            ],
            [
                'name' => 'TaBaBa',
                'description' => 'ini Deskripsi TaBaBa',

            ],
            [
                'name' => 'THR Smart Home',
                'description' => 'ini Deskripsi THR Smart Home',

            ],
            [
                'name' => 'TMR',
                'description' => 'ini Deskripsi TMR',

            ],
        ];

        foreach ($datas as $data) {
            Package::create($data);
        }
    }
}
