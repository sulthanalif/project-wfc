<?php

namespace Database\Seeders;

use App\Models\DetailProfile;
use App\Models\Mission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detailProfile = DetailProfile::create([
            'titleHistory' => 'Sejarah Singkat Perusahaan',
            'bodyHistory' => 'Paket Smart WFC berdiri sejak tahun 2019, dan alhamdulillah ditahun ini
                        total agent kami hampir 100 agent yang terbagi di berbagai kota, seperti di Sumedang, Bandung,
                        Ciamis, Tasikmalaya, Cianjur, Depok.
                        <br> <br>
                        Alhamdulillah karena antusiasnya yang sangat besar hingga selalu ada ribuan peserta yang
                        terdaftar. Alur kerja yang sangat fleksibel, bisa untuk siapapun yang ingin berkembang, maju
                        bersama dan mampu menggandeng relasi baru.
                        <br> <br>
                        Cara untuk bergabung bersama kami pun cukup mudah dan ada beberapa syarat yang wajib dipenuhi.',
            'image' => '',
            'titleVM' => 'Visi Misi Perusahaan',
            'vision' => 'Menjadi pusat penyediaan kebutuhan Hari Raya yang amanah, mudah dan murah'
        ]);

        $missions = [
            [
                'detail_profile_id' => $detailProfile->id,
                'content' => 'Kepuasan pelanggan adalah tujuan kami',
            ],
            [
                'detail_profile_id' => $detailProfile->id,
                'content' => 'Membangun dan mengembangkan kerjasama kemitraan yang amanah',
            ],
            [
                'detail_profile_id' => $detailProfile->id,
                'content' => 'Mewujudkan dan memfasilitasi masyarakat akan kebutuhan hari raya',
            ],
            [
                'detail_profile_id' => $detailProfile->id,
                'content' => 'Terus mengembangkan jaringan dan relasi',
            ],
        ];

        foreach ($missions as $mission) {
            Mission::create($mission);
        }
    }
}
