<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\ContactNumber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contact = Contact::create([
            'title' => 'Ayo Hubungi Kami!',
            'subTitle' => 'Dapatkan informasi lebih lanjut dan bantuan dari tim kami.',
            'address' => 'Jl. Cipareuag No. 5, Cihanjuang, Cimanggung, Sumedang',
            'email' => 'paketsmartwfc@gmail.com',
            // 'phoneNumber' => '082218799050',
            'mapUrl' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.456736833378!2d107.8129644!3d-6.955326200000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68c50070eb5e11%3A0xef2bec3bc1599522!2sPAKET%20SMART%20WFC!5e0!3m2!1sid!2sid!4v1722649583950!5m2!1sid!2sid'
        ]);

        ContactNumber::create([
            'contact_id' => $contact->id,
            'description' => 'WA (Admin)',
            'number' => '082218799050'
        ]);
    }
}
