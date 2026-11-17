@extends('layouts.app')

@section('title', 'Verifikasi Destinasi & Label Otentik — Konsol Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">DEWAN AKREDITASI KULINER TOBA</small>
            <h1 class="h3 fw-bold mb-0">Verifikasi Destinasi &amp; Kurasi Label Otentik Batak</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-toba-secondary btn-sm">Rekap Wilayah</a>
            <a href="{{ route('admin.ulasan.index') }}" class="btn btn-toba-secondary btn-sm">Moderasi Ulasan</a>
            <a href="{{ route('admin.master') }}" class="btn btn-toba-secondary btn-sm">Master Data</a>
        </div>
    </div>

    <!-- BAGIAN 1: ANTREAN VERIFIKASI DATA DESTINASI BARU -->
    <div class="card-toba p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-shop-window text-danger me-2"></i>1. Antrean Verifikasi Data Destinasi ({{ $antreanDestinasi->count() }})</h5>
        <div class="table-responsive">
            <table class="table align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Nama Usaha</th>
                        <th>Pemilik</th>
                        <th>Wilayah &amp; Kategori</th>
                        <th class="text-end">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antreanDestinasi as $d)
                        <tr>
                            <td class="fw-bold">{{ $d->nama }}<br><span class="text-muted fw-normal">{{ $d->alamat }}</span></td>
                            <td>{{ $d->pemilik->nama ?? '-' }}</td>
                            <td>{{ $d->wilayah->kabupaten ?? '' }} • <span class="badge bg-light text-dark border">{{ $d->kategori->nama ?? '' }}</span></td>
                            <td class="text-end">
                                <form action="{{ route('admin.verifikasi.setujui', $d) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Setujui Tayang</button>
                                </form>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $d->id }}">
                                    Tolak + Catatan
                                </button>

                                <!-- Modal Form Tolak Pengajuan & Catatan (Screen 3 Admin) -->
                                <div class="modal fade text-start" id="modalTolak{{ $d->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('admin.verifikasi.tolak', $d) }}" method="POST" class="modal-content rounded-4 border-0">
                                            @csrf
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Tolak Pengajuan: {{ $d->nama }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label small fw-bold">Catatan Perbaikan untuk Pemilik UMKM *</label>
                                                <textarea name="catatan_verifikasi" rows="3" class="form-control" placeholder="Contoh: Titik koordinat GPS belum sesuai atau foto menu kurang jelas..." required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger w-100">Kirim Penolakan &amp; Notifikasi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada antrean verifikasi destinasi baru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 2: ANTREAN VERIFIKASI LABEL OTENTIK BATAK (TERPISAH) -->
    <div class="card-toba p-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-award-fill text-warning me-2"></i>2. Antrean Pengajuan Label "Kuliner Otentik Batak" ({{ $antreanLabel->count() }})</h5>
        <div class="table-responsive">
            <table class="table align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Destinasi</th>
                        <th>Pemohon</th>
                        <th>Catatan Pengajuan</th>
                        <th class="text-end">Keputusan Kurator</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antreanLabel as $p)
                        <tr>
                            <td class="fw-bold">{{ $p->destinasi->nama ?? '-' }}<br><span class="text-muted fw-normal">{{ $p->destinasi->wilayah->kabupaten ?? '' }}</span></td>
                            <td>{{ $p->diajukanOleh->nama ?? '-' }}</td>
                            <td>{{ $p->catatan }}</td>
                            <td class="text-end">
                                <form action="{{ route('admin.verifikasi.label', $p->destinasi_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="aksi" value="disetujui">
                                    <button type="submit" class="btn btn-warning btn-sm fw-bold">Terbitkan Label Otentik</button>
                                </form>
                                <form action="{{ route('admin.verifikasi.label', $p->destinasi_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="aksi" value="ditolak">
                                    <input type="hidden" name="catatan" value="Kriteria rempah lokal / resep turun-temurun belum terpenuhi.">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Tolak Label</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada antrean pengajuan Label Otentik Batak.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection