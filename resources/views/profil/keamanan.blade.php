@extends('layouts.app')

@section('title', 'Keamanan Kata Sandi — Toba Kuliner')

@section('content')
<div class="container py-4" style="max-width: 820px;">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 fw-bold mb-0">Pengaturan Keamanan Akun</h1>
            <p class="text-muted small mb-0">Perbarui kata sandi akun Toba Kuliner Anda secara berkala.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('profil.edit') }}" class="btn btn-toba-secondary btn-sm px-3">Profil Saya</a>
            <a href="{{ route('profil.keamanan') }}" class="btn btn-toba-primary btn-sm px-3">Keamanan Sandi</a>
            <a href="{{ route('profil.ulasan') }}" class="btn btn-toba-secondary btn-sm px-3">Riwayat Ulasan</a>
        </div>
    </div>

    <div class="card-toba p-4">
        <form action="{{ route('profil.sandi') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label small fw-bold">Kata Sandi Saat Ini *</label>
                <input type="password" name="password_lama" class="form-control" required>
                @error('password_lama') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Kata Sandi Baru (Min. 8 Karakter) *</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Konfirmasi Kata Sandi Baru *</label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                </div>
            </div>
            <button type="submit" class="btn btn-toba-primary px-4 py-2">Perbarui Kata Sandi</button>
        </form>
    </div>
</div>
@endsection
