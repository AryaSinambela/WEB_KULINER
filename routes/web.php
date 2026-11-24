<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, JelajahController, PetaController, DestinasiController,
    HalamanController, AuthController, RegisterController, PasswordResetController,
    UlasanController, ItineraryController, ProfilController, NotifikasiController};
use App\Http\Controllers\Umkm;
use App\Http\Controllers\Admin;

/* ============ PUBLIK ============ */
Route::get('/', [HomeController::class, 'index'])->name('home');                       // U0
Route::get('/jelajah', [JelajahController::class, 'index'])->name('jelajah');         // U6
Route::get('/peta', [PetaController::class, 'index'])->name('peta');                  // U7
Route::get('/peta/data', [PetaController::class, 'data'])->name('peta.data');         // JSON pin
Route::get('/destinasi/{destinasi}', [DestinasiController::class, 'show'])->name('destinasi.show'); // U8
Route::get('/destinasi/{destinasi}/rute', [DestinasiController::class, 'rute'])->name('destinasi.rute');
Route::get('/tentang', [HalamanController::class, 'tentang'])->name('tentang');       // U19

/* ============ AUTH (Users) ============ */
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'create'])->name('login');            // U1
    Route::post('/masuk', [AuthController::class, 'store']);
    Route::get('/lupa-sandi', [PasswordResetController::class, 'request'])->name('password.request'); // U2
    Route::post('/lupa-sandi', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/atur-sandi/{token}', [PasswordResetController::class, 'form'])->name('password.reset'); // U3
    Route::post('/atur-sandi', [PasswordResetController::class, 'update'])->name('password.update');
    Route::get('/daftar', [RegisterController::class, 'peran'])->name('register');     // U4
    Route::post('/daftar', [RegisterController::class, 'simpanPeran'])->name('register.peran');
    Route::get('/daftar/akun', [RegisterController::class, 'akun'])->name('register.akun'); // U5
    Route::post('/daftar/akun', [RegisterController::class, 'store'])->name('register.store');
});
Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/daftar/verifikasi', [RegisterController::class, 'verifikasi'])->name('register.verifikasi'); // U5 langkah 3
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');     // U18
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.baca-semua');
});

/* ============ PENGGUNA TERAUTENTIKASI (RENCANA KUNJUNGAN, ULASAN, & PROFIL) ============ */
Route::middleware(['auth'])->group(function () {
    Route::get('/destinasi/{destinasi}/ulasan/baru', [UlasanController::class, 'create'])->name('ulasan.create'); // U11
    Route::post('/destinasi/{destinasi}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::get('/ulasan/{ulasan}/edit', [UlasanController::class, 'edit'])->name('ulasan.edit');
    Route::put('/ulasan/{ulasan}', [UlasanController::class, 'update'])->name('ulasan.update');
    Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');

    Route::get('/rencana', [ItineraryController::class, 'index'])->name('itinerary.index');          // U16
    Route::get('/rencana/baru', [ItineraryController::class, 'create'])->name('itinerary.create');  // U12
    Route::post('/rencana', [ItineraryController::class, 'store'])->name('itinerary.store');
    Route::get('/rencana/{itinerary}', [ItineraryController::class, 'show'])->name('itinerary.show');
    Route::put('/rencana/{itinerary}', [ItineraryController::class, 'update'])->name('itinerary.update');
    Route::delete('/rencana/{itinerary}', [ItineraryController::class, 'destroy'])->name('itinerary.destroy');
    Route::post('/rencana/{itinerary}/stop', [ItineraryController::class, 'tambahStop'])->name('itinerary.stop');   // modal U10 & U13
    Route::delete('/rencana/stop/{item}', [ItineraryController::class, 'hapusStop'])->name('itinerary.stop.hapus');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');                  // U14
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/profil/keamanan', [ProfilController::class, 'keamanan'])->name('profil.keamanan'); // U15
    Route::put('/profil/keamanan', [ProfilController::class, 'ubahSandi'])->name('profil.sandi');
    Route::get('/profil/ulasan', [ProfilController::class, 'ulasanSaya'])->name('profil.ulasan');   // U17
});

/* ============ PORTAL MITRA / UMKM ============ */
Route::prefix('mitra')->name('umkm.')->group(function () {
    Route::get('/', [Umkm\AuthController::class, 'landing'])->name('landing');                      // M0
    Route::middleware('guest')->group(function () {
        Route::get('/daftar', [Umkm\AuthController::class, 'register'])->name('register');          // M1
        Route::post('/daftar', [Umkm\AuthController::class, 'storeRegister']);
        Route::get('/masuk', [Umkm\AuthController::class, 'login'])->name('login');                 // M2
        Route::post('/masuk', [Umkm\AuthController::class, 'storeLogin']);
    });
    Route::middleware(['auth', 'role:umkm'])->group(function () {
        Route::get('/menunggu', [Umkm\DashboardController::class, 'menunggu'])->name('menunggu');   // M3
        Route::get('/dashboard', [Umkm\DashboardController::class, 'index'])->name('dashboard');    // M4
        Route::get('/usaha', [Umkm\ProfilUsahaController::class, 'edit'])->name('usaha.edit');      // M5
        Route::put('/usaha', [Umkm\ProfilUsahaController::class, 'update'])->name('usaha.update');
        Route::resource('menu', Umkm\MenuController::class)->except(['show']);                      // M6
        Route::get('/label', [Umkm\LabelController::class, 'index'])->name('label');                // M7
        Route::post('/label', [Umkm\LabelController::class, 'store'])->name('label.store');
        Route::get('/peta', [Umkm\PetaController::class, 'index'])->name('peta');                   // M8
        Route::get('/pratinjau', [Umkm\PratinjauController::class, 'index'])->name('pratinjau');    // M9
        Route::get('/notifikasi', [Umkm\NotifikasiController::class, 'index'])->name('notifikasi'); // M10
        Route::get('/akun', [Umkm\AkunController::class, 'edit'])->name('akun');                    // M11
        Route::put('/akun', [Umkm\AkunController::class, 'update'])->name('akun.update');
    });
});

/* ============ ADMIN ============ */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/masuk', [Admin\AuthController::class, 'login'])->name('login');                // A0
        Route::post('/masuk', [Admin\AuthController::class, 'store']);
    });
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');            // A1
        Route::get('/verifikasi', [Admin\VerifikasiController::class, 'index'])->name('verifikasi.index'); // A2
        Route::get('/verifikasi/{destinasi}', [Admin\VerifikasiController::class, 'show'])->name('verifikasi.show'); // A4
        Route::post('/verifikasi/{destinasi}/setujui', [Admin\VerifikasiController::class, 'setujui'])->name('verifikasi.setujui');
        Route::post('/verifikasi/{destinasi}/tolak', [Admin\VerifikasiController::class, 'tolak'])->name('verifikasi.tolak'); // modal A3
        Route::post('/verifikasi/{destinasi}/revisi', [Admin\VerifikasiController::class, 'revisi'])->name('verifikasi.revisi');
        Route::post('/verifikasi/{destinasi}/label', [Admin\VerifikasiController::class, 'label'])->name('verifikasi.label');
        Route::get('/peta', [Admin\PetaController::class, 'index'])->name('peta');                  // A5
        Route::get('/ulasan', [Admin\UlasanController::class, 'index'])->name('ulasan.index');      // A6
        Route::patch('/ulasan/{ulasan}', [Admin\UlasanController::class, 'moderasi'])->name('ulasan.moderasi');
        Route::get('/master', [Admin\MasterController::class, 'index'])->name('master');            // A7
        Route::resource('kategori', Admin\KategoriController::class)->only(['store', 'update', 'destroy']); // A8
        Route::resource('wilayah', Admin\WilayahController::class)->only(['store', 'update', 'destroy']);   // A8
        Route::resource('pengguna', Admin\PenggunaController::class)
            ->parameters(['pengguna' => 'user'])->except(['show']);                                 // A9 & A10
        Route::patch('/pengguna/{user}/blokir', [Admin\PenggunaController::class, 'blokir'])->name('pengguna.blokir');
        Route::get('/profil', [Admin\ProfilController::class, 'index'])->name('profil');            // A11
        Route::get('/profil/log/export', [Admin\ProfilController::class, 'exportLog'])->name('profil.log');
    });
});