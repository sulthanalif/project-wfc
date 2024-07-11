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
        $header = new Header([
            'title' => 'Paket Smart WFC',
            'sub_title' => 'Temukan Kebutuhan Anda dengan Mudah.',
            'description' => 'Alur kerja fleksibel, mudah untuk siapapun, maju bersama dan mampu menjangkau relasi baru.',
            'image' => '',
            'button_title' => 'Lihat Selengkapnya',
            'button_url' => '#profil',
        ]);
        $header->save();
    }
}
