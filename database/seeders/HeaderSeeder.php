<?php

namespace Database\Seeders;

use App\Models\Header;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Header::create([
            'title' => 'Paket Smart WFC',
            'subTitle' => 'Temukan Kebutuhan Anda dengan Mudah.',
            'description' => 'Alur kerja fleksibel, mudah untuk siapapun, maju bersama dan mampu menjangkau relasi baru.',
            'image' => '',
            'buttonTitle' => 'Lihat Selengkapnya',
            'buttonUrl' => 'profile'
        ]);
    }
}
