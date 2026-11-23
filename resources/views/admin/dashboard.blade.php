@extends('layouts.app')

@section('title', 'Dashboard Rekap Wilayah — Konsol Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">PORTAL ADMINISTRATOR &amp; DEWAN KURATOR</small>
            <h1 class="h3 fw-bold mb-0">Dashboard Rekap Wilayah &amp; Analisis Gastronomi</h1>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-toba-primary btn-sm px-3">
                Antrean Verifikasi ({{ $rekap['destinasi_menunggu'] + $rekap['label_menunggu'] }})
            </a>
            <a href="{{ route('admin.peta') }}" class="btn btn-toba-secondary btn-sm px-3"><i class="bi bi-map-fill text-danger me-1"></i> Peta Sebaran</a>
            <a href="{{ route('admin.ulasan.index') }}" class="btn btn-toba-secondary btn-sm px-3">Moderasi Ulasan</a>
            <a href="{{ route('admin.master') }}" class="btn btn-toba-secondary btn-sm px-3">Master Data</a>
            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-toba-secondary btn-sm px-3">Pengguna</a>
            <a href="{{ route('admin.profil') }}" class="btn btn-toba-secondary btn-sm px-3">Log Kurator</a>
        </div>
    </div>

    <!-- 4 Kartu Metrik Utama -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card-toba p-3">
                <small class="text-muted fw-bold text-uppercase">Destinasi Terpublikasi</small>
                <h2 class="fw-bold mt-1 mb-0" style="color: var(--toba-primary);">{{ $rekap['total_destinasi'] }}</h2>
                <small class="text-muted">Aktif pada peta interaktif</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-toba p-3">
                <small class="text-muted fw-bold text-uppercase">Otentik Batak</small>
                <h2 class="fw-bold mt-1 mb-0 text-warning">{{ $rekap['total_otentik'] }}</h2>
                <small class="text-muted">Tersertifikasi dewan adat</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-toba p-3">
                <small class="text-muted fw-bold text-uppercase">Menunggu Kurasi</small>
                <h2 class="fw-bold mt-1 mb-0 text-danger">{{ $rekap['destinasi_menunggu'] + $rekap['label_menunggu'] }}</h2>
                <small class="text-muted">{{ $rekap['destinasi_menunggu'] }} destinasi • {{ $rekap['label_menunggu'] }} label</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-toba p-3">
                <small class="text-muted fw-bold text-uppercase">Rata-rata Rating</small>
                <h2 class="fw-bold mt-1 mb-0 text-dark">★ {{ $rekap['rata_rating'] ?: '4.8' }}</h2>
                <small class="text-muted">Skala kepuasan 5.0</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sebaran Destinasi per Kecamatan -->
        <div class="col-lg-6">
            <div class="card-toba p-4 h-100">
                <h5 class="fw-bold mb-3">Sebaran Destinasi per Kecamatan &amp; Kabupaten</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Kecamatan</th>
                                <th>Kabupaten</th>
                                <th class="text-end">Jumlah Destinasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sebaranWilayah as $w)
                                <tr>
                                    <td class="fw-semibold">{{ $w->nama_kecamatan }}</td>
                                    <td>{{ $w->kabupaten }}</td>
                                    <td class="text-end"><span class="badge bg-dark rounded-pill">{{ $w->destinasi_count }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kategori Kuliner Terpopuler -->
        <div class="col-lg-6">
            <div class="card-toba p-4 h-100">
                <h5 class="fw-bold mb-3">Distribusi Kategori Kuliner</h5>
                @foreach($kategoriPopuler as $kat)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="fw-semibold">{{ $kat->nama }}</span>
                            <span class="text-muted">{{ $kat->destinasi_count }} Destinasi</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ min($kat->destinasi_count * 20, 100) }}%; background-color: var(--toba-primary);"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection