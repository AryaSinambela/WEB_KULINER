<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // users bawaan Laravel → sesuaikan dengan Tabel 12
        Schema::table('users', function (Blueprint $t) {
            $t->renameColumn('name', 'nama');
            $t->string('nomor_telepon', 20)->nullable();
            $t->enum('role', ['wisatawan', 'umkm', 'admin'])->default('wisatawan');
            $t->enum('tipe_penilai', ['wisatawan', 'lokal'])->nullable();
        });

        Schema::create('wilayah', function (Blueprint $t) {
            $t->id();
            $t->string('nama_kecamatan', 100);
            $t->string('kabupaten', 100);
        });

        Schema::create('kategori_makanan', function (Blueprint $t) {
            $t->id();
            $t->string('nama', 100);
            $t->string('ikon')->nullable();
        });

        Schema::create('destinasi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('pemilik_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('wilayah_id')->constrained('wilayah');
            $t->foreignId('kategori_id')->constrained('kategori_makanan');
            $t->string('nama');
            $t->text('deskripsi')->nullable();
            $t->string('alamat')->nullable();
            $t->decimal('latitude', 10, 7);
            $t->decimal('longitude', 10, 7);
            $t->unsignedInteger('harga_min')->nullable();
            $t->unsignedInteger('harga_max')->nullable();
            $t->time('jam_buka')->nullable();
            $t->time('jam_tutup')->nullable();
            $t->boolean('is_otentik_batak')->default(false);
            $t->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $t->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('diverifikasi_pada')->nullable();
            $t->text('catatan_verifikasi')->nullable();
            $t->timestamps();
        });

        Schema::create('foto_destinasi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destinasi_id')->constrained('destinasi')->cascadeOnDelete();
            $t->string('path_foto');
            $t->boolean('is_utama')->default(false);
        });

        Schema::create('menu_destinasi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destinasi_id')->constrained('destinasi')->cascadeOnDelete();
            $t->string('nama_menu');
            $t->unsignedInteger('harga')->nullable();
            $t->text('deskripsi')->nullable();
            $t->string('path_foto')->nullable();
        });

        Schema::create('cerita_budaya', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destinasi_id')->constrained('destinasi')->cascadeOnDelete();
            $t->string('judul');
            $t->text('asal_usul')->nullable();
            $t->text('makna_adat')->nullable();
            $t->text('istilah_lokal')->nullable();
        });

        Schema::create('pengajuan_otentik', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destinasi_id')->constrained('destinasi')->cascadeOnDelete();
            $t->foreignId('diajukan_oleh')->constrained('users');
            $t->foreignId('ditinjau_oleh')->nullable()->constrained('users');
            $t->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $t->text('catatan')->nullable();
            $t->timestamp('diajukan_pada')->useCurrent();
            $t->timestamp('ditinjau_pada')->nullable();
        });

        Schema::create('ulasan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destinasi_id')->constrained('destinasi')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->enum('tipe_penilai', ['wisatawan', 'lokal']);
            $t->unsignedTinyInteger('rating_umum');
            $t->unsignedTinyInteger('rating_rasa')->nullable();
            $t->unsignedTinyInteger('rating_harga')->nullable();
            $t->unsignedTinyInteger('rating_keotentikan')->nullable();
            $t->text('komentar')->nullable();
            $t->enum('status_moderasi', ['tampil', 'ditahan', 'dihapus'])->default('tampil');
            $t->timestamp('created_at')->useCurrent();
            $t->unique(['destinasi_id', 'user_id']); // 1 user = 1 ulasan per destinasi
        });

        Schema::create('foto_ulasan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('ulasan_id')->constrained('ulasan')->cascadeOnDelete();
            $t->string('path_foto');
        });

        Schema::create('wisata_alam', function (Blueprint $t) {
            $t->id();
            $t->foreignId('wilayah_id')->constrained('wilayah');
            $t->string('nama');
            $t->text('deskripsi')->nullable();
            $t->decimal('latitude', 10, 7);
            $t->decimal('longitude', 10, 7);
        });

        Schema::create('itinerary', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->string('judul');
            $t->date('tanggal_kunjungan')->nullable();
            $t->string('moda', 30)->nullable();                                   // U12 (tambahan)
            $t->enum('status', ['draf', 'aktif', 'selesai'])->default('draf');    // U16 (tambahan)
            $t->timestamps();
        });

        Schema::create('itinerary_item', function (Blueprint $t) {
            $t->id();
            $t->foreignId('itinerary_id')->constrained('itinerary')->cascadeOnDelete();
            $t->foreignId('destinasi_id')->nullable()->constrained('destinasi')->cascadeOnDelete();
            $t->foreignId('wisata_alam_id')->nullable()->constrained('wisata_alam')->cascadeOnDelete();
            $t->unsignedSmallInteger('urutan')->default(1);
            $t->unsignedTinyInteger('hari')->default(1);                          // U10 (tambahan)
            $t->time('waktu_tiba')->nullable();                                   // U10 (tambahan)
            $t->text('catatan')->nullable();
        });

        Schema::create('statistik_kunjungan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destinasi_id')->constrained('destinasi')->cascadeOnDelete();
            $t->date('tanggal');
            $t->unsignedInteger('profil_dilihat')->default(0);
            $t->unsignedInteger('klik_rute')->default(0);
            $t->unique(['destinasi_id', 'tanggal']);
        });

        // Tidak ada spesifikasinya di 4.1, tapi dipakai U18 & M10 → saya definisikan sendiri
        Schema::create('notifikasi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->string('tipe', 50);
            $t->string('judul');
            $t->text('pesan')->nullable();
            $t->timestamp('dibaca_pada')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['notifikasi', 'statistik_kunjungan', 'itinerary_item', 'itinerary', 'wisata_alam', 'foto_ulasan',
                  'ulasan', 'pengajuan_otentik', 'cerita_budaya', 'menu_destinasi', 'foto_destinasi',
                  'destinasi', 'kategori_makanan', 'wilayah'] as $tabel) {
            Schema::dropIfExists($tabel);
        }
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['nomor_telepon', 'role', 'tipe_penilai']);
            $t->renameColumn('nama', 'name');
        });
    }
};