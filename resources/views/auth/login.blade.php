@extends('layouts.app')

@section('title', 'Masuk ke Akun — Toba Kuliner')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card-toba overflow-hidden">
                <div class="row g-0">
                    <!-- Panel Kiri Marun -->
                    <div class="col-md-5 p-4 p-lg-5 text-white d-flex flex-column justify-content-between" style="background-color: var(--toba-primary);">
                        <div>
                            <span class="badge bg-warning text-dark mb-3">KURASI WARISAN LELUHUR</span>
                            <h3 class="font-headline text-white fw-bold mb-3">Temukan Rasa Otentik Tanah Batak</h3>
                            <p class="small text-white-50">Jelajahi kuliner tradisional, rute peta interaktif hemat data, dan kurasi berbobot budaya lokal di kawasan Danau Toba.</p>
                        </div>
                        <ul class="list-unstyled small text-white-50 mb-0 d-flex flex-column gap-2">
                            <li><i class="bi bi-check-circle-fill text-warning me-2"></i> Rekomendasi terverifikasi kurator</li>
                            <li><i class="bi bi-check-circle-fill text-warning me-2"></i> Peta rute kuliner hemat data</li>
                            <li><i class="bi bi-check-circle-fill text-warning me-2"></i> Dual rating wisatawan &amp; warga lokal</li>
                        </ul>
                    </div>

                    <!-- Form Login Kanan -->
                    <div class="col-md-7 p-4 p-lg-5 bg-white">
                        <h4 class="fw-bold mb-1">Akses Cita Rasa Toba</h4>
                        <p class="text-muted small mb-4">Masuk untuk menyimpan rencana kunjungan dan memberikan ulasan.</p>

                        @if($errors->any())
                            <div class="alert alert-danger small py-2">{{ $errors->first() }}</div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-light border-0 py-2" placeholder="nama@email.com" required autofocus>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label small fw-bold">Kata Sandi</label>
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none text-muted">Lupa kata sandi?</a>
                                </div>
                                <input type="password" name="password" class="form-control bg-light border-0 py-2" placeholder="••••••••" required>
                            </div>
                            <div class="form-check mb-4 small">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label text-muted" for="remember">Ingat saya di perangkat ini</label>
                            </div>
                            <button type="submit" class="btn btn-toba-primary w-100 py-2 mb-3">Masuk ke Akun <i class="bi bi-arrow-right ms-1"></i></button>
                        </form>

                        <div class="text-center small text-muted">
                            Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: var(--toba-primary);">Daftar Sekarang</a>
                            <hr>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('umkm.login') }}" class="text-decoration-none text-muted">Portal Mitra UMKM</a> •
                                <a href="{{ route('admin.login') }}" class="text-decoration-none text-muted">Konsol Admin</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection