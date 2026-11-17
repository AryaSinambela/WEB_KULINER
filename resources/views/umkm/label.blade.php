@extends('layouts.app')

@section('title', 'Pengajuan & Status Label Otentik Batak')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('umkm.dashboard') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
        <h1 class="h3 fw-bold mb-0 mt-1">Kurasi &amp; Pengajuan Label "Kuliner Otentik Batak"</h1>
    </div>

    <div class="row g-4">
        <!-- Form Pengajuan & Cerita Budaya (Kiri) -->
        <div class="col-lg-7">
            <div class="card-toba p-4">
                <h5 class="fw-bold mb-1">Formulir Pengajuan Kurasi Adat</h5>
                <p class="text-muted small mb-4">Lengkapi narasi asal-usul resep dan makna budaya hidangan untuk ditinjau Dewan Kurator.</p>

                @php $cerita = $destinasi->ceritaBudaya->first(); @endphp

                <form action="{{ route('umkm.label.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Cerita Budaya / Filosofi Sajian *</label>
                        <input type="text" name="judul_cerita" value="{{ old('judul_cerita', $cerita->judul ?? '') }}" class="form-control" placeholder="Contoh: Filosofi Arsik Ikan Mas dalam Adat Batak Toba" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Asal-Usul Resep &amp; Sejarah Kedai *</label>
                        <textarea name="asal_usul" rows="3" class="form-control" required>{{ old('asal_usul', $cerita->asal_usul ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Makna Adat &amp; Bahan Rempah Endemik (Andaliman / Asam Cikala)</label>
                        <textarea name="makna_adat" rows="2" class="form-control">{{ old('makna_adat', $cerita->makna_adat ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Glosarium Istilah Lokal</label>
                        <input type="text" name="istilah_lokal" value="{{ old('istilah_lokal', $cerita->istilah_lokal ?? '') }}" class="form-control" placeholder="Contoh: Na Niarsik = ikan yang dimasak hingga kuahnya mengering meresap">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Catatan Tambahan untuk Tim Kurator</label>
                        <textarea name="catatan" rows="2" class="form-control" placeholder="Tuliskan kesiapan jadwal visitasi atau bukti pasokan bahan lokal..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-toba-primary w-100 py-2">
                        <i class="bi bi-award-fill me-1"></i> Ajukan Verifikasi Label Otentik Batak
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat & Status Pengajuan (Kanan) -->
        <div class="col-lg-5">
            <div class="card-toba p-4">
                <h5 class="fw-bold mb-3">Status &amp; Riwayat Pengajuan Label</h5>

                @if($destinasi->is_otentik_batak)
                    <div class="p-3 rounded-4 mb-3 border border-warning" style="background-color: var(--toba-secondary-light);">
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-patch-check-fill text-warning me-1"></i> Terakreditasi Otentik Batak</h6>
                        <p class="small text-muted mb-0">Usaha Anda telah menyandang badge emas Otentik Batak pada peta dan halaman direktori publik.</p>
                    </div>
                @endif

                @forelse($destinasi->pengajuanOtentik as $item)
                    <div class="p-3 rounded-3 border mb-3" style="background-color: var(--toba-neutral);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge {{ $item->status === 'disetujui' ? 'bg-success' : ($item->status === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }} text-uppercase">
                                {{ $item->status }}
                            </span>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($item->diajukan_pada)->format('d M Y H:i') }}</small>
                        </div>
                        <p class="small mb-0 text-dark"><strong>Catatan:</strong> {{ $item->catatan ?? 'Sedang dalam peninjauan.' }}</p>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada riwayat pengajuan Label Otentik Batak.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection