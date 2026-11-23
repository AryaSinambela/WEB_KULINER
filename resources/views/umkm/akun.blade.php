@extends('layouts.app')

@section('title', 'Pengaturan Akun Mitra — Toba Kuliner')

@section('content')
<div class="container py-4" style="max-width: 720px;">
    <div class="mb-4">
        <a href="{{ route('umkm.dashboard') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard Mitra</a>
        <h1 class="h3 fw-bold mb-0 mt-1">Pengaturan Akun Pemilik Toko</h1>
    </div>

    <div class="card-toba p-4">
        <form action="{{ route('umkm.akun.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Nama Pemilik / Pengelola *</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Email Akun</label>
                    <input type="email" value="{{ $user->email }}" class="form-control bg-light" readonly>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Nomor Telepon / WhatsApp *</label>
                    <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password_baru" class="form-control" minlength="8">
                </div>
            </div>
            <button type="submit" class="btn btn-toba-primary px-4 py-2">Simpan Pengaturan Akun</button>
        </form>
    </div>
</div>
@endsection
