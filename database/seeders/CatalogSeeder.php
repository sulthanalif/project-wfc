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
                'image' => 'sample.jpg'
            ],
            [
                'name' => 'Cookies',
                'description' => 'ini Deskripsi Cookies',
                'image' => 'sample.jpg'
            ],
            [
                'name' => 'PHL',
                'description' => 'ini Deskripsi PHL',
                'image' => 'sample.jpg'
            ],
            [
                'name' => 'TaBaBa',
                'description' => 'ini Deskripsi TaBaBa',
                'image' => 'sample.jpg'
            ],
            [
                'name' => 'THR Smart Home',
                'description' => 'ini Deskripsi THR Smart Home',
                'image' => 'sample.jpg'
            ],
            [
                'name' => 'TMR',
                'description' => 'ini Deskripsi TMR',
                'image' => 'sample.jpg'
            ],
        ];

        foreach ($datas as $data) {
            Catalog::create($data);
        }
    }
}
