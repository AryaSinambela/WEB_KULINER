@extends('layouts.app')

@section('title', 'Daftar Akun Baru — Toba Kuliner')

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center g-4">
        <!-- Form Pendaftaran Langsung (Semua Peran dalam 1 Halaman) -->
        <div class="col-lg-7">
            <div class="card-toba p-4 p-md-5">
                <span class="badge-otentik mb-2 d-inline-block">PENDAFTARAN AKUN RESMI • TOBA KULINER</span>
                <h1 class="h3 fw-bold mb-1">Buat Akun Toba Kuliner</h1>
                <p class="text-muted small mb-4">
                    Pilih jenis akun Anda (Wisatawan, Warga Lokal, atau Pemilik Toko UMKM) untuk menyimpan rencana kunjungan, memberi ulasan, atau mendaftarkan toko kuliner.
                </p>

                @if($errors->any())
                    <div class="alert alert-danger rounded-3 small py-2 mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <!-- Pilihan Jenis Akun -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Jenis Akun Anda *</label>
                        <div class="row g-2">
                            <div class="col-12 col-sm-4">
                                <input type="radio" class="btn-check" name="jenis_akun" id="peran_wisatawan" value="wisatawan" checked>
                                <label class="btn btn-outline-danger w-100 text-start p-2 rounded-3 h-100" for="peran_wisatawan">
                                    <strong class="d-block small"><i class="bi bi-compass me-1"></i> Wisatawan</strong>
                                    <span class="text-muted d-block" style="font-size: 11px;">Susun rencana &amp; ulasan (1.0x)</span>
                                </label>
                            </div>
                            <div class="col-12 col-sm-4">
                                <input type="radio" class="btn-check" name="jenis_akun" id="peran_lokal" value="lokal">
                                <label class="btn btn-outline-danger w-100 text-start p-2 rounded-3 h-100" for="peran_lokal">
                                    <strong class="d-block small"><i class="bi bi-patch-check-fill text-warning me-1"></i> Warga Lokal</strong>
                                    <span class="text-muted d-block" style="font-size: 11px;">Bobot nilai adat (1.5x)</span>
                                </label>
                            </div>
                            <div class="col-12 col-sm-4">
                                <input type="radio" class="btn-check" name="jenis_akun" id="peran_umkm" value="umkm">
                                <label class="btn btn-outline-danger w-100 text-start p-2 rounded-3 h-100" for="peran_umkm">
                                    <strong class="d-block small"><i class="bi bi-shop me-1"></i> Pemilik Toko</strong>
                                    <span class="text-muted d-block" style="font-size: 11px;">Kelola toko &amp; menu UMKM</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap *</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Contoh: Binsar Situmorang" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nomor WhatsApp / Telepon</label>
                            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" class="form-control" placeholder="0812xxxxxxxx">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nama@email.com" required>
                    </div>

                    <!-- Kolom Tambahan Khusus jika memilih Pemilik Toko UMKM -->
                    <div id="boxExtraUmkm" class="p-3 rounded-4 border mb-3 d-none" style="background-color: var(--toba-neutral);">
                        <h6 class="fw-bold small text-uppercase mb-2" style="color: var(--toba-primary);">
                            <i class="bi bi-shop me-1"></i> Informasi Awal Toko / Rumah Makan Anda
                        </h6>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Nama Toko / Rumah Makan</label>
                            <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" class="form-control form-control-sm" placeholder="Contoh: Kedai Mie Gomak & Kopi Balige">
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Wilayah Kecamatan</label>
                                <select name="wilayah_id" class="form-select form-select-sm">
                                    @foreach($wilayahs as $w)
                                        <option value="{{ $w->id }}">{{ $w->nama_kecamatan }} ({{ $w->kabupaten }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Kategori Kuliner</label>
                                <select name="kategori_id" class="form-select form-select-sm">
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label small fw-bold">Alamat Singkat Toko</label>
                            <input type="text" name="alamat" value="{{ old('alamat') }}" class="form-control form-control-sm" placeholder="Jl. Sisingamangaraja, Balige">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold">Kata Sandi (Min. 6 Karakter) *</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required minlength="6">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold">Ulangi Kata Sandi *</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang kata sandi" required minlength="6">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-toba-primary w-100 py-2 mb-3">
                        <i class="bi bi-person-check-fill me-1"></i> Daftar &amp; Masuk Sekarang
                    </button>

                    <div class="text-center small">
                        Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--toba-primary);">Masuk di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="jenis_akun"]');
    const boxUmkm = document.getElementById('boxExtraUmkm');

    function toggleUmkmFields() {
        const selected = document.querySelector('input[name="jenis_akun"]:checked')?.value;
        if (selected === 'umkm') {
            boxUmkm.classList.remove('d-none');
        } else {
            boxUmkm.classList.add('d-none');
        }
    }

    radios.forEach(r => r.addEventListener('change', toggleUmkmFields));
    toggleUmkmFields();
});
</script>
@endpush