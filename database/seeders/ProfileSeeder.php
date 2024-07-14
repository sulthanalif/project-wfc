<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create([
            'title' => 'Paket Smart WFC',
            'description' => 'Paket Smart WFC berdiri sejak tahun 2019, dan alhamdulillah ditahun ini total agent kami hampir 100 agent yang terbagi di berbagai kota, seperti di Sumedang, Bandung, Ciamis, Tasikmalaya, Cianjur, Depok...',
            'image' => '',
            'buttonTitle' => 'Baca Selengkapnya'
        ]);
    }
}
