<?php

namespace Database\Seeders;

use App\Models\CeritaBudaya;
use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\MenuDestinasi;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\WisataAlam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna (Admin, UMKM, Warga Lokal, Wisatawan)
        $admin = User::create([
            'nama'          => 'Ir. Togar Simanjuntak (Kurator)',
            'email'         => 'admin@tobakuliner.id',
            'password'      => Hash::make('password123'),
            'nomor_telepon' => '081268001001',
            'role'          => 'admin',
            'tipe_penilai'  => 'lokal',
        ]);

        $umkm1 = User::create([
            'nama'          => 'Ompu David Marbun',
            'email'         => 'mitra@tobakuliner.id',
            'password'      => Hash::make('password123'),
            'nomor_telepon' => '081375218890',
            'role'          => 'umkm',
            'tipe_penilai'  => 'lokal',
        ]);

        $lokal = User::create([
            'nama'          => 'Binsar Situmorang',
            'email'         => 'lokal@tobakuliner.id',
            'password'      => Hash::make('password123'),
            'nomor_telepon' => '081234567890',
            'role'          => 'wisatawan',
            'tipe_penilai'  => 'lokal',
        ]);

        $wisatawan = User::create([
            'nama'          => 'Sarah Siregar',
            'email'         => 'wisatawan@tobakuliner.id',
            'password'      => Hash::make('password123'),
            'nomor_telepon' => '082199881209',
            'role'          => 'wisatawan',
            'tipe_penilai'  => 'wisatawan',
        ]);

        // 2. Data Wilayah Kaldera Toba
        $balige = Wilayah::create(['nama_kecamatan' => 'Balige', 'kabupaten' => 'Toba']);
        $laguboti = Wilayah::create(['nama_kecamatan' => 'Laguboti', 'kabupaten' => 'Toba']);
        $pangururan = Wilayah::create(['nama_kecamatan' => 'Pangururan', 'kabupaten' => 'Samosir']);
        $parapat = Wilayah::create(['nama_kecamatan' => 'Girsang Sipangan Bolon', 'kabupaten' => 'Simalungun']);
        $tarutung = Wilayah::create(['nama_kecamatan' => 'Tarutung', 'kabupaten' => 'Tapanuli Utara']);
        $doloksanggul = Wilayah::create(['nama_kecamatan' => 'Dolok Sanggul', 'kabupaten' => 'Humbang Hasundutan']);

        // 3. 6 Pilar Kategori Warisan Kuliner
        $katIkan = KategoriMakanan::create(['nama' => 'Pusaka Ikan & Kuah']);
        $katMie = KategoriMakanan::create(['nama' => 'Mie & Olahan Tepung']);
        $katKudapan = KategoriMakanan::create(['nama' => 'Kudapan & Kue Adat']);
        $katKopi = KategoriMakanan::create(['nama' => 'Kopi & Teh Dataran Tinggi']);
        $katDaging = KategoriMakanan::create(['nama' => 'Olahan Daging Tradisi']);
        $katSambal = KategoriMakanan::create(['nama' => 'Bumbu & Sambal Khas']);

        // 4. Destinasi Kuliner Contoh (Terverifikasi & Otentik Batak)
        $dest1 = Destinasi::create([
            'pemilik_id'        => $umkm1->id,
            'wilayah_id'        => $balige->id,
            'kategori_id'       => $katMie->id,
            'nama'              => 'Mie Gomak Kuah Andaliman Pak Marbun',
            'deskripsi'         => 'Spesialis mie lidi kuah santan kuning kental dengan sentuhan getir khas andaliman pegunungan Toba, diracik di atas tungku kayu aren sejak 1982.',
            'alamat'            => 'Jl. Bukit Barisan No. 14, Pasar Onan Balige',
            'latitude'          => 2.3338000,
            'longitude'         => 99.0664000,
            'harga_min'         => 15000,
            'harga_max'         => 25000,
            'jam_buka'          => '07:00:00',
            'jam_tutup'         => '17:00:00',
            'is_otentik_batak'  => true,
            'status_verifikasi' => 'disetujui',
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_pada' => now(),
        ]);

        MenuDestinasi::create([
            'destinasi_id' => $dest1->id,
            'nama_menu'    => 'Mie Gomak Kuah Andaliman Spesial',
            'harga'        => 18000,
            'deskripsi'    => 'Mie lidi kenyal khas Toba diguyur kuah santan kunyit andaliman segar dan taburan bawang goreng lokal.',
        ]);
        MenuDestinasi::create([
            'destinasi_id' => $dest1->id,
            'nama_menu'    => 'Mie Gomak Goreng Bumbu Rias',
            'harga'        => 18000,
            'deskripsi'    => 'Mie lidi ditumis di atas wajan besi panas dengan sawi lokal dan ulekan andaliman harum asap.',
        ]);

        CeritaBudaya::create([
            'destinasi_id'  => $dest1->id,
            'judul'         => 'Spageti Batak: Filosofi di Balik Mie Gomak Kuah Santan Andaliman',
            'asal_usul'     => 'Mie Gomak lahir sebagai santapan pagi para saudagar di hari pekan (onan) Balige sejak dekade 1980-an, memadukan tekstur mie lidi dengan rempah pegunungan Kaldera Toba.',
            'makna_adat'    => 'Andaliman bukan sekadar bumbu pedas, melainkan lambang kehangatan persaudaraan saat keluarga berkumpul melingkar di jabu.',
            'istilah_lokal' => 'Gomak = menggenggam/mengambil mie secara langsung dengan tangan bersih sebelum disiram kuah panas.',
        ]);

        $dest2 = Destinasi::create([
            'pemilik_id'        => $umkm1->id,
            'wilayah_id'        => $pangururan->id,
            'kategori_id'       => $katIkan->id,
            'nama'              => 'Lapau Naniura Nainggolan',
            'deskripsi'         => 'Sashimi khas Batak dari ikan mas segar Danau Toba yang dimatangkan secara dingin menggunakan asam jungga dan 10 rempah pilihan.',
            'alamat'            => 'Jl. Putri Lopian, Pangururan, Samosir',
            'latitude'          => 2.6085000,
            'longitude'         => 98.6942000,
            'harga_min'         => 55000,
            'harga_max'         => 85000,
            'jam_buka'          => '10:00:00',
            'jam_tutup'         => '20:00:00',
            'is_otentik_batak'  => true,
            'status_verifikasi' => 'disetujui',
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_pada' => now(),
        ]);

        // 5. Ulasan Dual Rating (Warga Lokal & Wisatawan)
        Ulasan::create([
            'destinasi_id'       => $dest1->id,
            'user_id'            => $lokal->id,
            'tipe_penilai'       => 'lokal',
            'rating_umum'        => 5,
            'rating_rasa'        => 5,
            'rating_harga'       => 5,
            'rating_keotentikan' => 5,
            'komentar'           => 'Takaran andalimannya pas dan dimasak pakai kayu bakar, aromanya otentik seperti masakan di huta.',
            'status_moderasi'    => 'tampil',
        ]);

        Ulasan::create([
            'destinasi_id'       => $dest1->id,
            'user_id'            => $wisatawan->id,
            'tipe_penilai'       => 'wisatawan',
            'rating_umum'        => 4,
            'rating_rasa'        => 5,
            'rating_harga'       => 4,
            'rating_keotentikan' => 5,
            'komentar'           => 'Pengalaman kuliner wajib kalau mampir ke Balige! Sensasi getir andalimannya unik sekali.',
            'status_moderasi'    => 'tampil',
        ]);

        // 6. Titik Wisata Alam untuk Itinerary Builder
        WisataAlam::create([
            'wilayah_id' => $pangururan->id,
            'nama'       => 'Bukit Holbung Samosir',
            'deskripsi'  => 'Gugusan bukit padang ilalang hijau dengan panorama 360 derajat Kaldera Danau Toba.',
            'latitude'   => 2.5364000,
            'longitude'  => 98.7189000,
        ]);

        WisataAlam::create([
            'wilayah_id' => $balige->id,
            'nama'       => 'Pantai Lumban Bulbul Balige',
            'deskripsi'  => 'Pantai pasir putih air tawar di tepian Danau Toba, cocok untuk bersantai usai menyantap kuliner.',
            'latitude'   => 2.3458000,
            'longitude'  => 99.0691000,
        ]);
    }
}