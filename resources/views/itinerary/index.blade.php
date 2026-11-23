@extends('layouts.app')

@section('title', 'Daftar Rencana Kunjungan Saya')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">GORGA PATHFINDER</small>
            <h1 class="h2 fw-bold mb-0">Daftar Rencana Kunjungan Kuliner</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('itinerary.create') }}" class="btn btn-toba-secondary px-3">
                <i class="bi bi-compass me-1"></i> Form Lengkap
            </a>
            <button class="btn btn-toba-primary px-4" data-bs-toggle="modal" data-bs-target="#modalBuatRencana">
                <i class="bi bi-plus-lg me-1"></i> Buat Rencana Cepat
            </button>
        </div>
    </div>

    <div class="row g-4">
        @forelse($itineraries as $itin)
            <div class="col-md-6">
                <div class="card-toba p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-success text-uppercase">{{ $itin->status }}</span>
                            <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> {{ $itin->tanggal_kunjungan ?? 'Tanggal fleksibel' }} • {{ $itin->moda ?? 'Mobil' }}</small>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $itin->judul }}</h5>
                        <p class="small text-muted mb-3">
                            <strong>{{ $itin->items->count() }} Titik Persinggahan:</strong>
                            {{ $itin->items->map(fn($i) => $i->destinasi->nama ?? $i->wisataAlam->nama ?? 'Stop')->implode(' → ') ?: 'Belum ada titik singgah' }}
                        </p>
                    </div>
                    <div class="d-flex gap-2 pt-3 border-top">
                        <a href="{{ route('itinerary.show', $itin) }}" class="btn btn-toba-primary btn-sm flex-grow-1">Buka &amp; Kelola Rute</a>
                        <form action="{{ route('itinerary.destroy', $itin) }}" method="POST" onsubmit="return confirm('Hapus rencana ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card-toba p-5 text-center">
                    <h5 class="fw-bold">Belum Ada Rencana Kunjungan</h5>
                    <p class="text-muted small">Susun rute jelajah kuliner khas Toba dipadukan dengan destinasi wisata alam terdekat.</p>
                    <button class="btn btn-toba-primary px-4" data-bs-toggle="modal" data-bs-target="#modalBuatRencana">Mulai Rancang Rute</button>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Buat Rencana Baru -->
<div class="modal fade" id="modalBuatRencana" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('itinerary.store') }}" method="POST" class="modal-content rounded-4 border-0">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Buat Rencana Kunjungan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Judul Rencana Perjalanan *</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Eksplorasi Rasa Balige & Samosir" required>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal Kunjungan</label>
                        <input type="date" name="tanggal_kunjungan" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Moda Kendaraan</label>
                        <select name="moda" class="form-select">
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                            <option value="Kapal Feri & Darat">Kapal Feri &amp; Darat</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-toba-primary w-100">Simpan &amp; Susun Rute</button>
            </div>
        </form>
    </div>
</div>
@endsection