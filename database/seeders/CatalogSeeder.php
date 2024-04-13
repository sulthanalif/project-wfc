<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CatalogSeeder extends Seeder
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
            Catalog::create($data);
        }
    }
}
