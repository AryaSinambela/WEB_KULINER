@extends('layouts.app')

@section('title', 'Pilih Peran Pendaftaran — Toba Kuliner')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <span class="badge-otentik mb-2">LANGKAH 1 DARI 2 • EKOSISTEM KULINER KALDERA TOBA</span>
        <h1 class="h2 fw-bold">Bagaimana Anda Ingin Menikmati &amp; Berkontribusi?</h1>
        <p class="text-muted small">Pilih peran Anda untuk menyesuaikan fitur jelajah atau portal pengelolaan usaha kuliner.</p>
    </div>

    <form action="{{ route('register.peran') }}" method="POST" class="row justify-content-center g-4">
        @csrf
        <div class="col-md-5">
            <div class="card-toba p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge bg-light text-dark border mb-2">PENJELAJAH • WARGA LOKAL • PELANCONG</span>
                    <h4 class="fw-bold mb-2">Wisatawan &amp; Penikmat Rasa</h4>
                    <p class="text-muted small mb-3">Jelajahi keautentikan rasa Kaldera Toba, susun rute jelajah kuliner + wisata alam, dan berikan penilaian berbobot budaya lokal.</p>
                </div>
                <button type="submit" name="peran" value="wisatawan" class="btn btn-toba-primary w-100 py-2">
                    Lanjutkan sebagai Wisatawan / Lokal <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card-toba p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge-otentik mb-2 d-inline-block">MITRA WARUNG • LAPAU • KEDAI KOPI</span>
                    <h4 class="fw-bold mb-2">Pemilik Usaha Kuliner (UMKM)</h4>
                    <p class="text-muted small mb-3">Daftarkan gerai warisan rasa Anda di Peta Geospasial Resmi, kelola menu &amp; harga, serta ajukan kurasi Label Otentik Batak.</p>
                </div>
                <button type="submit" name="peran" value="umkm" class="btn btn-toba-secondary w-100 py-2">
                    Daftar sebagai Mitra Usaha <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection