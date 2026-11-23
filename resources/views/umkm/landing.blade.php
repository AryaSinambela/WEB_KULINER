@extends('layouts.app')

@section('title', 'Portal Kemitraan Toko & UMKM Kuliner — Toba Kuliner')

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-7">
            <span class="badge-otentik mb-3 d-inline-block">PORTAL RESMI MITRA KULINER KALDERA TOBA</span>
            <h1 class="display-5 fw-bold mb-3">Tampilkan Toko, Lapau, &amp; Rumah Makan Anda di Peta Wisatawan</h1>
            <p class="text-muted mb-4" style="line-height: 1.8;">
                Bergabunglah bersama jaringan pelestari warisan kuliner Batak. Kelola lokasi GPS toko, tampilkan daftar menu beserta cerita budaya di setiap hidangan, dan ajukan sertifikasi <strong>Label Otentik Batak</strong>.
            </p>
            <div class="d-flex flex-wrap gap-3">
                @auth
                    @if(auth()->user()->role === 'umkm')
                        <a href="{{ route('umkm.dashboard') }}" class="btn btn-toba-primary px-4 py-2">Buka Dashboard Mitra Saya</a>
                    @endif
                @else
                    <a href="{{ route('umkm.register') }}" class="btn btn-toba-primary px-4 py-2">Daftarkan Toko / Usaha Baru</a>
                    <a href="{{ route('umkm.login') }}" class="btn btn-toba-secondary px-4 py-2">Masuk Akun Mitra</a>
                @endauth
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card-toba p-4">
                <h5 class="fw-bold mb-3">Manfaat Menjadi Mitra Terverifikasi</h5>
                <div class="d-flex flex-column gap-3 small">
                    <div class="d-flex gap-3">
                        <i class="bi bi-geo-alt-fill text-danger fs-5"></i>
                        <div>
                            <strong class="d-block">Terhubung dengan Navigasi Rute OSRM</strong>
                            <span class="text-muted">Wisatawan dapat langsung melihat rute jalan raya menuju pintu toko Anda.</span>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <i class="bi bi-journal-richtext text-danger fs-5"></i>
                        <div>
                            <strong class="d-block">Katalog Menu &amp; Cerita Budaya per Hidangan</strong>
                            <span class="text-muted">Ceritakan sejarah dan resep keluarga di balik setiap menu andalan toko Anda.</span>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <i class="bi bi-award-fill text-warning fs-5"></i>
                        <div>
                            <strong class="d-block">Lencana Akreditasi "Otentik Batak"</strong>
                            <span class="text-muted">Pin emas khusus pada peta yang meningkatkan kepercayaan wisatawan &amp; warga lokal.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
