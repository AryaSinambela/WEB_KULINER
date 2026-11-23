@extends('layouts.app')

@section('title', 'Masuk Portal Mitra UMKM — Toba Kuliner')

@section('content')
<div class="container py-5" style="max-width: 460px;">
    <div class="card-toba p-4 p-md-5">
        <span class="badge-otentik mb-2 d-inline-block"><i class="bi bi-shop me-1"></i> PORTAL MITRA UMKM</span>
        <h4 class="fw-bold mb-1">Masuk ke Dashboard Toko</h4>
        <p class="text-muted small mb-4">Kelola profil toko, titik peta GPS, daftar menu, dan cerita budaya kuliner Anda.</p>

        <form action="{{ route('umkm.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Email Mitra *</label>
                <input type="email" name="email" value="{{ old('email', 'mitra@tobakuliner.id') }}" class="form-control" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kata Sandi *</label>
                <input type="password" name="password" value="password123" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-toba-primary w-100 py-2 mb-3">Masuk Dashboard Mitra</button>
            <div class="text-center small">
                Belum mendaftarkan toko? <a href="{{ route('umkm.register') }}" class="fw-bold text-decoration-none" style="color: var(--toba-primary);">Daftar Mitra Baru</a>
            </div>
        </form>
    </div>
</div>
@endsection
