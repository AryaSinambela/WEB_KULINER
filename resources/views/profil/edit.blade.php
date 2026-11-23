@extends('layouts.app')

@section('title', 'Profil & Preferensi Penilai — Toba Kuliner')

@section('content')
<div class="container py-4" style="max-width: 820px;">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 fw-bold mb-0">Profil &amp; Preferensi Penilai</h1>
            <p class="text-muted small mb-0">Kelola identitas akun dan peran penilaian Anda dalam sistem Dual Rating.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('profil.edit') }}" class="btn btn-toba-primary btn-sm px-3">Profil Saya</a>
            <a href="{{ route('profil.keamanan') }}" class="btn btn-toba-secondary btn-sm px-3">Keamanan Sandi</a>
            <a href="{{ route('profil.ulasan') }}" class="btn btn-toba-secondary btn-sm px-3">Riwayat Ulasan</a>
        </div>
    </div>

    <div class="card-toba p-4">
        <form action="{{ route('profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Alamat Email</label>
                    <input type="email" value="{{ $user->email }}" class="form-control bg-light" readonly>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Kategori Penilai Dual Rating *</label>
                    <select name="tipe_penilai" class="form-select">
                        <option value="wisatawan" {{ $user->tipe_penilai === 'wisatawan' ? 'selected' : '' }}>Wisatawan / Pengunjung (Bobot 1.0x)</option>
                        <option value="lokal" {{ $user->tipe_penilai === 'lokal' ? 'selected' : '' }}>Warga Lokal Kawasan Toba (Bobot Adat 1.5x)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-toba-primary px-4 py-2">Simpan Perubahan Profil</button>
        </form>
    </div>
</div>
@endsection
