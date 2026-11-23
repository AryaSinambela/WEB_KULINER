@extends('layouts.app')

@section('title', 'Tentang & Metodologi Kurasi Budaya — Toba Kuliner')

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-7">
            <span class="badge-otentik mb-3 d-inline-block"><i class="bi bi-compass-fill text-warning me-1"></i> PELESTARIAN GASTRONOMI KALDERA TOBA</span>
            <h1 class="display-5 fw-bold mb-3">Merajut Warisan Rasa &amp; Cerita Budaya di Setiap Hidangan</h1>
            <p class="text-muted mb-4" style="line-height: 1.8;">
                <strong>Toba Kuliner</strong> adalah platform pemetaan geospasial dan kurasi kuliner tradisional kawasan Danau Toba yang menghubungkan wisatawan maupun warga lokal dengan toko, rumah makan, dan lapau otentik di 6 kabupaten lingkar kaldera.
            </p>
            <div class="row g-3">
                <div class="col-4">
                    <div class="card-toba p-3 text-center">
                        <h3 class="fw-bold mb-0" style="color: var(--toba-primary);">{{ $totalToko }}</h3>
                        <small class="text-muted fw-semibold">TOKO TERVERIFIKASI</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card-toba p-3 text-center">
                        <h3 class="fw-bold mb-0" style="color: var(--toba-primary);">{{ $totalMenu }}</h3>
                        <small class="text-muted fw-semibold">MENU KHAS TOBA</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card-toba p-3 text-center">
                        <h3 class="fw-bold mb-0" style="color: var(--toba-primary);">{{ $totalCerita }}</h3>
                        <small class="text-muted fw-semibold">CERITA BUDAYA</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card-toba p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-award-fill text-warning me-1"></i> 4 Pilar Akreditasi "Otentik Batak"</h5>
                <ul class="list-unstyled d-flex flex-column gap-3 small mb-0">
                    <li class="d-flex gap-3">
                        <i class="bi bi-1-circle-fill text-danger fs-5"></i>
                        <div>
                            <strong class="d-block">Keaslian Rempah Endemik</strong>
                            <span class="text-muted">Menggunakan andaliman segar, asam jungga, asam siala (rias), dan bawang Batak lokal.</span>
                        </div>
                    </li>
                    <li class="d-flex gap-3">
                        <i class="bi bi-2-circle-fill text-danger fs-5"></i>
                        <div>
                            <strong class="d-block">Narasi &amp; Filosofi Budaya per Menu</strong>
                            <span class="text-muted">Setiap sajian memiliki riwayat asal-usul dan makna dalam adat Dalihan Na Tolu.</span>
                        </div>
                    </li>
                    <li class="d-flex gap-3">
                        <i class="bi bi-3-circle-fill text-danger fs-5"></i>
                        <div>
                            <strong class="d-block">Sistem Dual Rating (Lokal 1.5x vs Wisatawan 1.0x)</strong>
                            <span class="text-muted">Menjaga standar keaslian rasa melalui bobot penilaian warga lokal Danau Toba.</span>
                        </div>
                    </li>
                    <li class="d-flex gap-3">
                        <i class="bi bi-4-circle-fill text-danger fs-5"></i>
                        <div>
                            <strong class="d-block">Pemetaan Spasial &amp; Navigasi Rute Nyata</strong>
                            <span class="text-muted">Terhubung dengan multi-layer peta interaktif dan perhitungan rute jalan raya OSRM.</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card-toba p-4 p-lg-5 text-center" style="background-color: var(--toba-neutral);">
        <h4 class="fw-bold mb-2">Mulai Eksplorasi Toko &amp; Kuliner Sekarang</h4>
        <p class="text-muted small mb-4">Jelajahi direktori toko kuliner, baca cerita budaya di setiap menu, atau daftarkan usaha kuliner Anda.</p>
        <div class="d-flex justify-content-center flex-wrap gap-2">
            <a href="{{ route('peta') }}" class="btn btn-toba-primary px-4 py-2"><i class="bi bi-map me-1"></i> Buka Peta Kuliner</a>
            <a href="{{ route('jelajah') }}" class="btn btn-toba-secondary px-4 py-2"><i class="bi bi-compass me-1"></i> Jelajah Semua Toko</a>
            <a href="{{ route('umkm.landing') }}" class="btn btn-toba-outlined px-4 py-2"><i class="bi bi-shop me-1"></i> Portal Mitra UMKM</a>
        </div>
    </div>
</div>
@endsection
