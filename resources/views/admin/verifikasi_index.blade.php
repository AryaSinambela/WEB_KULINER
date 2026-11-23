@extends('layouts.app')

@section('title', 'Verifikasi Destinasi & Label Otentik — Konsol Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">DEWAN AKREDITASI KULINER TOBA</small>
            <h1 class="h3 fw-bold mb-0">Verifikasi Toko &amp; Kurasi Label Otentik Batak</h1>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-toba-secondary btn-sm">Rekap Wilayah</a>
            <a href="{{ route('admin.peta') }}" class="btn btn-toba-secondary btn-sm"><i class="bi bi-map-fill text-danger me-1"></i> Peta Sebaran</a>
            <a href="{{ route('admin.ulasan.index') }}" class="btn btn-toba-secondary btn-sm">Moderasi Ulasan</a>
            <a href="{{ route('admin.master') }}" class="btn btn-toba-secondary btn-sm">Master Data</a>
        </div>
    </div>

    <!-- BAGIAN 1: ANTREAN VERIFIKASI DATA TOKO / DESTINASI BARU -->
    <div class="card-toba p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-shop-window text-danger me-2"></i>1. Antrean Verifikasi Data Toko Baru ({{ $antreanDestinasi->count() }})</h5>
        <div class="table-responsive">
            <table class="table align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Nama Toko / Usaha</th>
                        <th>Pemilik</th>
                        <th>Wilayah &amp; Kategori</th>
                        <th class="text-end">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antreanDestinasi as $d)
                        <tr>
                            <td class="fw-bold">
                                <a href="{{ route('admin.verifikasi.show', $d) }}" class="text-decoration-none text-dark">{{ $d->nama }}</a>
                                <br><span class="text-muted fw-normal">{{ $d->alamat }}</span>
                            </td>
                            <td>{{ $d->pemilik->nama ?? '-' }}</td>
                            <td>{{ $d->wilayah->kabupaten ?? '' }} • <span class="badge bg-light text-dark border">{{ $d->kategori->nama ?? '' }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.verifikasi.show', $d) }}" class="btn btn-toba-secondary btn-sm">
                                    <i class="bi bi-eye"></i> Tinjau Detail
                                </a>
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
                                                <h6 class="modal-title fw-bold">Tolak / Minta Revisi: {{ $d->nama }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label small fw-bold">Catatan Perbaikan untuk Pemilik Toko *</label>
                                                <textarea name="catatan_verifikasi" rows="3" class="form-control" placeholder="Contoh: Titik koordinat GPS belum sesuai atau foto menu kurang jelas..." required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger w-100">Kirim Catatan Revisi &amp; Notifikasi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada antrean verifikasi toko baru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 2: ANTREAN VERIFIKASI LABEL OTENTIK BATAK (TERPISAH) -->
    <div class="card-toba p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-award-fill text-warning me-2"></i>2. Antrean Pengajuan Label "Kuliner Otentik Batak" ({{ $antreanLabel->count() }})</h5>
        <div class="table-responsive">
            <table class="table align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Nama Toko</th>
                        <th>Pemohon</th>
                        <th>Catatan Pengajuan</th>
                        <th class="text-end">Keputusan Kurator</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antreanLabel as $p)
                        <tr>
                            <td class="fw-bold">
                                <a href="{{ route('admin.verifikasi.show', $p->destinasi_id) }}" class="text-decoration-none text-dark">{{ $p->destinasi->nama ?? '-' }}</a>
                                <br><span class="text-muted fw-normal">{{ $p->destinasi->wilayah->kabupaten ?? '' }}</span>
                            </td>
                            <td>{{ $p->diajukanOleh->nama ?? '-' }}</td>
                            <td>{{ $p->catatan }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.verifikasi.show', $p->destinasi_id) }}" class="btn btn-toba-secondary btn-sm">Tinjau</a>
                                <form action="{{ route('admin.verifikasi.label', $p->destinasi_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="aksi" value="disetujui">
                                    <button type="submit" class="btn btn-warning btn-sm fw-bold">Terbitkan Label Otentik</button>
                                </form>
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolakLabel{{ $p->id }}">
                                    Tolak Label
                                </button>

                                <!-- Modal Tolak Label Otentik dengan Catatan Kurator -->
                                <div class="modal fade text-start" id="modalTolakLabel{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('admin.verifikasi.label', $p->destinasi_id) }}" method="POST" class="modal-content rounded-4 border-0">
                                            @csrf
                                            <input type="hidden" name="aksi" value="ditolak">
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Tolak Pengajuan Label: {{ $p->destinasi->nama ?? '' }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label small fw-bold">Alasan Penolakan Label Otentik *</label>
                                                <textarea name="catatan" rows="3" class="form-control" required>Kriteria penggunaan rempah endemik lokal atau narasi cerita budaya per menu belum lengkap.</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger w-100">Kirim Keputusan Kurator</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada antrean pengajuan Label Otentik Batak.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 3: RIWAYAT TOKO TERVERIFIKASI & KELOLA STATUS LABEL -->
    <div class="card-toba p-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-success me-2"></i>3. Daftar Toko Terverifikasi &amp; Status Label Otentik ({{ $riwayatDestinasi->count() }})</h5>
        <div class="table-responsive">
            <table class="table align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Nama Toko</th>
                        <th>Wilayah</th>
                        <th>Status Publikasi</th>
                        <th>Label Otentik Batak</th>
                        <th class="text-end">Tindakan Kurator</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatDestinasi as $rd)
                        <tr>
                            <td class="fw-bold">
                                <a href="{{ route('admin.verifikasi.show', $rd) }}" class="text-decoration-none text-dark">{{ $rd->nama }}</a>
                            </td>
                            <td>{{ $rd->wilayah->nama_kecamatan ?? '' }}, {{ $rd->wilayah->kabupaten ?? '' }}</td>
                            <td>
                                <span class="badge {{ $rd->status_verifikasi === 'disetujui' ? 'bg-success' : 'bg-danger' }}">
                                    {{ strtoupper($rd->status_verifikasi) }}
                                </span>
                            </td>
                            <td>
                                @if($rd->is_otentik_batak)
                                    <span class="badge-otentik">★ Otentik Batak</span>
                                @else
                                    <span class="badge bg-light text-muted border">Kuliner Umum</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.verifikasi.show', $rd) }}" class="btn btn-sm btn-toba-secondary">Detail Kurasi</a>
                                @if($rd->is_otentik_batak)
                                    <form action="{{ route('admin.verifikasi.label', $rd) }}" method="POST" class="d-inline" onsubmit="return confirm('Cabut Label Otentik Batak dari toko ini?')">
                                        @csrf
                                        <input type="hidden" name="aksi" value="cabut">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Cabut Label</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.verifikasi.label', $rd) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="aksi" value="disetujui">
                                        <button type="submit" class="btn btn-sm btn-outline-warning text-dark">Beri Label Otentik</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection