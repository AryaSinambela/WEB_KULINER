<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        Destination::create([
            'name' => 'RM Islam Omar Balige',
            'slug' => 'rm-islam-omar-balige',
            'description' => 'Menyediakan mi gomak kuah dan goreng khas Toba serta aneka masakan Nusantara halal.',
            'cultural_story' => 'Mie gomak adalah mi khas Batak Toba yang dinamai dari cara penyajian aslinya yang di-gomak (diremas/diambil dengan tangan langsung).',
            'regency' => 'Toba',
            'address' => 'Jl. Sisingamangaraja No. 12, Balige',
            'latitude' => 2.33310000,
            'longitude' => 99.06640000,
            'is_authentic_batak' => true,
            'opening_time' => '07:00:00',
            'closing_time' => '21:00:00'
        ]);

        Destination::create([
            'name' => 'Waroeng Kopi Sabat',
            'slug' => 'waroeng-kopi-sabat',
            'description' => 'Tempat santai menikmati Kopi Arabika Lintong dan cemilan itak gurgur.',
            'cultural_story' => 'Itak gurgur merupakan makanan tradisional simbol doa keberkahan dan semangat (gurgur) dalam budaya Batak.',
            'regency' => 'Toba',
            'address' => 'Sitoluama, Laguboti',
            'latitude' => 2.38330000,
            'longitude' => 99.14170000,
            'is_authentic_batak' => true,
            'opening_time' => '08:00:00',
            'closing_time' => '22:00:00'
        ]);
    }
}