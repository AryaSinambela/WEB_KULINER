@extends('layouts.app')

@section('title', 'Dashboard Mitra — ' . $destinasi->nama)

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <span class="badge bg-success mb-1">STATUS USAHA: {{ strtoupper($destinasi->status_verifikasi) }}</span>
            @if($destinasi->is_otentik_batak)
                <span class="badge-otentik ms-1">★ Otentik Batak Terverifikasi</span>
            @endif
            <h1 class="h3 fw-bold mb-0 mt-1">Horas, {{ auth()->user()->nama }}!</h1>
            <p class="text-muted small mb-0">{{ $destinasi->nama }} • {{ $destinasi->wilayah->nama_kecamatan ?? '' }}, {{ $destinasi->wilayah->kabupaten ?? '' }}</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('umkm.usaha.edit') }}" class="btn btn-toba-secondary btn-sm px-3"><i class="bi bi-shop me-1"></i> Profil Destinasi</a>
            <a href="{{ route('umkm.menu.index') }}" class="btn btn-toba-secondary btn-sm px-3"><i class="bi bi-journal-text me-1"></i> Kelola Menu ({{ $destinasi->menus->count() }})</a>
            <a href="{{ route('umkm.label') }}" class="btn btn-toba-primary btn-sm px-3"><i class="bi bi-award me-1"></i> Status Label Otentik</a>
        </div>
    </div>

    <!-- Kartu Statistik Kunjungan (ERD: statistik_kunjungan) -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card-toba p-4">
                <small class="text-muted fw-bold text-uppercase">Kunjungan Profil Usaha</small>
                <h2 class="display-6 fw-bold mt-1 mb-0" style="color: var(--toba-primary);">{{ number_format($totalDilihat) }}</h2>
                <small class="text-success"><i class="bi bi-eye-fill"></i> Total halaman detail dibuka</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-toba p-4">
                <small class="text-muted fw-bold text-uppercase">Klik Petunjuk Rute (Maps)</small>
                <h2 class="display-6 fw-bold mt-1 mb-0" style="color: var(--toba-primary);">{{ number_format($totalKlikRute) }}</h2>
                <small class="text-muted"><i class="bi bi-sign-turn-right-fill text-warning"></i> Calon pengunjung menavigasi lokasi</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-toba p-4">
                <small class="text-muted fw-bold text-uppercase">Total Ulasan Masuk</small>
                <h2 class="display-6 fw-bold mt-1 mb-0" style="color: var(--toba-primary);">{{ number_format($totalUlasan) }}</h2>
                <small class="text-muted">Skor Lokal: <strong>{{ $destinasi->rating_lokal ?: '-' }}</strong> • Turis: <strong>{{ $destinasi->rating_wisatawan ?: '-' }}</strong></small>
            </div>
        </div>
    </div>

    <!-- Ulasan Pelanggan Terbaru -->
    <div class="card-toba p-4">
        <h5 class="fw-bold mb-3">Ulasan &amp; Suara Pelanggan Terbaru</h5>
        @forelse($destinasi->ulasans->take(5) as $u)
            <div class="p-3 rounded-3 border mb-2" style="background-color: var(--toba-neutral);">
                <div class="d-flex justify-content-between small mb-1">
                    <strong>{{ $u->user->nama ?? 'Pengunjung' }} ({{ ucfirst($u->tipe_penilai) }})</strong>
                    <span class="text-warning fw-bold">★ {{ $u->rating_umum }}.0</span>
                </div>
                <p class="small mb-0 text-muted">{{ $u->komentar }}</p>
            </div>
        @empty
            <p class="text-muted small mb-0">Belum ada ulasan dari pengunjung.</p>
        @endforelse
    </div>
</div>
@endsection