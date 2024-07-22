<?php

namespace Database\Seeders;

use App\Models\ReviewPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReviewPage::create([
            'title' => 'Review Page',
            'subTitle' => 'Review dari pelanggan kami'
        ]);
    }
}
