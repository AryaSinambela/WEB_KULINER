@extends('layouts.app')

@section('title', 'Pendaftaran Mitra Toko Kuliner — Toba Kuliner')

@section('content')
<div class="container py-5" style="max-width: 720px;">
    <div class="card-toba p-4 p-md-5">
        <span class="badge-otentik mb-2 d-inline-block">REGISTRASI MITRA UMKM TOBA</span>
        <h3 class="fw-bold mb-1">Daftarkan Toko / Rumah Makan Kuliner Anda</h3>
        <p class="text-muted small mb-4">Lengkapi informasi pemilik dan identitas awal toko untuk tampil di direktori dan peta interaktif Kaldera Toba.</p>

        <form action="{{ route('umkm.register') }}" method="POST">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Nama Pemilik / Pengelola *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Contoh: Ompu David Marbun" required>
                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Nama Toko / Rumah Makan *</label>
                    <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" class="form-control" placeholder="Contoh: Kedai Mie Gomak Pak Marbun" required>
                    @error('nama_usaha') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Alamat Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Nomor Telepon / WhatsApp *</label>
                    <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" class="form-control" required>
                    @error('nomor_telepon') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Wilayah Kecamatan &amp; Kabupaten *</label>
                    <select name="wilayah_id" class="form-select" required>
                        @foreach($wilayahs as $w)
                            <option value="{{ $w->id }}">{{ $w->nama_kecamatan }} — Kab. {{ $w->kabupaten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Kategori Kuliner Utama *</label>
                    <select name="kategori_id" class="form-select" required>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Alamat Lengkap Toko *</label>
                <input type="text" name="alamat" value="{{ old('alamat') }}" class="form-control" placeholder="Jl. Bukit Barisan No. 14, Pasar Balige" required>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Kata Sandi (Min. 8 Karakter) *</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Konfirmasi Kata Sandi *</label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                </div>
            </div>

            <button type="submit" class="btn btn-toba-primary w-100 py-2 mb-3">Daftarkan Toko &amp; Lanjut ke Pengaturan Peta</button>
            <div class="text-center small">
                Sudah memiliki akun mitra? <a href="{{ route('umkm.login') }}" class="fw-bold text-decoration-none" style="color: var(--toba-primary);">Masuk Portal Mitra</a>
            </div>
        </form>
    </div>
</div>
@endsection
