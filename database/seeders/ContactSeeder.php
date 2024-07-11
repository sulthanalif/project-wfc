<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contact = new Contact([
            'title' => 'Ayo Hubungi Kami!',
            'sub_title' => 'Dapatkan informasi lebih lanjut dan bantuan dari tim kami.',
            'address' => 'Jl. Cipareuag No. 5, Cihanjuang, Cimanggung, Sumedang',
            'email' => 'paketsmartwfc@gmail.com',
            'phone_number' => '082218799050',
            'map_link' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.4626033254904!2d107.81037477442035!3d-6.954630468087016!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68c521f856593f%3A0xda1dc7320ea63c2b!2sCV.%20Wida%20Nugraha!5e0!3m2!1sen!2sid!4v1712995312675!5m2!1sen!2sid'
        ]);

        $contact->save();
    }
}
