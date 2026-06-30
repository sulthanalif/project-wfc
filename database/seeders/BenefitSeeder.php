<?php

namespace Database\Seeders;

use App\Models\Benefit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Benefit::create([
            'title' => 'Keuntungan',
            'subtitle' => 'Keuntungan yang didapatkan menjadi bagian dari kami',
            'benefit_agen' => 'As an agent, you get exclusive access to our premium products at discounted rates.',
            'benefit_mitra' => 'As a partner, you can leverage our extensive network to expand your business reach.'
        ]);
    }
}
