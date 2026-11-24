@extends('layouts.app')

@section('title', 'Pendaftaran Akun — Toba Kuliner')

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card-toba p-4 p-md-5">
                <span class="badge-otentik mb-2 d-inline-block">IDENTITAS AKUN • TOBA KULINER</span>
                <h3 class="fw-bold mb-1">Pendaftaran Akun Penjelajah &amp; Warga Lokal</h3>
                <p class="text-muted small mb-4">Tentukan status Anda untuk kalibrasi bobot Dual Rating (1.0x Wisatawan / 1.5x Warga Lokal) dan fitur Rencana Kunjungan.</p>

                @if($errors->any())
                    <div class="alert alert-danger small py-2">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap *</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="cth. Binsar Situmorang" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="binsar@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor WhatsApp / Telepon</label>
                        <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" class="form-control" placeholder="0812xxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Asal Daerah &amp; Status Penilai *</label>
                        <select name="tipe_penilai" class="form-select" required>
                            <option value="wisatawan" {{ old('tipe_penilai', $defaultTipe ?? 'wisatawan') === 'wisatawan' ? 'selected' : '' }}>Wisatawan / Pendatang (Bobot Rating 1.0x)</option>
                            <option value="lokal" {{ old('tipe_penilai', $defaultTipe ?? '') === 'lokal' ? 'selected' : '' }}>Warga Lokal Kawasan Danau Toba (Bobot Rating 1.5x)</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold">Kata Sandi (Min. 6 Karakter) *</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold">Konfirmasi Kata Sandi *</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required minlength="6">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-toba-primary w-100 py-2">Selesaikan Pendaftaran <i class="bi bi-check-lg ms-1"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection