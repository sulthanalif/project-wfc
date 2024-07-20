<?php

namespace Database\Seeders;

use App\Models\SpendingType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpendingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SpendingType::create([
            ['name' => 'Biaya Transportasi Pembelian'],
            ['name' => 'Biaya Transportasi Penjualan'],
            ['name' => 'Biaya Transportasi'],
            ['name' => 'Belanja'],
            ['name' => 'Biaya Listrik/Telepon'],
            ['name' => 'Perlengkapan'],
            ['name' => 'Peralatan'],
            ['name' => 'Reward'],
            ['name' => 'Gift'],
            ['name' => 'Utang Bank'],
            ['name' => 'Investasi'],
            ['name' => 'Hutang'],
            ['name' => 'Pembayaran Hutang'],
            ['name' => 'Umroh'],
            ['name' => 'Permodalan'],
            ['name' => 'BRI Link/Bukalapak'],
            ['name' => 'Pegadaian'],
            ['name' => 'Kredit Amanah'],
            ['name' => 'Biaya Event'],
            ['name' => 'Biaya Lain-lain'],
        ]);
    }
}
