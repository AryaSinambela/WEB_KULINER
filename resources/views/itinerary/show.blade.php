@extends('layouts.app')

@section('title', $itinerary->judul . ' — Rancang Rute Rasa Toba')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('itinerary.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Rencana</a>
            <h1 class="h3 fw-bold mb-0 mt-1">{{ $itinerary->judul }}</h1>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-toba-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahKuliner">
                <i class="bi bi-shop me-1"></i> + Destinasi Kuliner
            </button>
            <button class="btn btn-toba-secondary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahAlam">
                <i class="bi bi-tree-fill text-success me-1"></i> + Wisata Alam
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Daftar Urutan Persinggahan (Kiri) -->
        <div class="col-lg-6">
            <div class="card-toba p-4">
                <h6 class="fw-bold mb-3">Urutan Rangkaian Kunjungan ({{ $itinerary->items->count() }} Stop)</h6>
                @forelse($itinerary->items as $idx => $item)
                    @php
                        $isKuliner = !is_null($item->destinasi_id);
                        $tempat = $isKuliner ? $item->destinasi : $item->wisataAlam;
                    @endphp
                    <div class="p-3 rounded-4 border mb-3 d-flex justify-content-between align-items-start" style="background-color: var(--toba-neutral);">
                        <div class="d-flex gap-3">
                            <span class="badge rounded-circle d-flex align-items-center justify-content-center" 
                                  style="width: 30px; height: 30px; background-color: {{ $isKuliner ? 'var(--toba-primary)' : '#198754' }};">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge {{ $isKuliner ? 'bg-danger' : 'bg-success' }}" style="font-size: 10px;">
                                        {{ $isKuliner ? 'KULINER' : 'WISATA ALAM' }}
                                    </span>
                                    <small class="text-muted fw-semibold">Hari {{ $item->hari }} • {{ $item->waktu_tiba ? substr($item->waktu_tiba, 0, 5) . ' WIB' : 'Fleksibel' }}</small>
                                </div>
                                <h6 class="fw-bold mb-1">{{ $tempat->nama ?? 'Lokasi' }}</h6>
                                <small class="text-muted d-block">{{ $tempat->wilayah->nama_kecamatan ?? '' }}, {{ $tempat->wilayah->kabupaten ?? '' }}</small>
                                @if($item->catatan)
                                    <small class="text-dark fst-italic d-block mt-1">"{{ $item->catatan }}"</small>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('itinerary.stop.hapus', $item) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus Stop"><i class="bi bi-x-circle-fill"></i></button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        Belum ada persinggahan. Tambahkan destinasi kuliner atau titik wisata alam menggunakan tombol di atas.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Peta Lintasan Interaktif (Kanan) -->
        <div class="col-lg-6">
            <div class="card-toba p-3">
                <h6 class="fw-bold mb-2"><i class="bi bi-map-fill text-danger me-1"></i> Peta Lintasan Perjalanan</h6>
                <div id="itineraryMap" style="height: 430px; border-radius: 12px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Destinasi Kuliner -->
<div class="modal fade" id="modalTambahKuliner" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('itinerary.stop', $itinerary) }}" method="POST" class="modal-content rounded-4 border-0">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Persinggahan Kuliner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Destinasi Kuliner *</label>
                    <select name="destinasi_id" class="form-select" required>
                        @foreach($destinasiList as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }} ({{ $d->wilayah->kabupaten ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Hari Ke-</label>
                        <input type="number" name="hari" value="1" min="1" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Waktu Tiba</label>
                        <input type="time" name="waktu_tiba" value="12:00" class="form-control">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Catatan</label>
                    <input type="text" name="catatan" class="form-control" placeholder="Contoh: Makan siang arsik ikan mas">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-toba-primary w-100">Tambahkan ke Rute</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Screen 13: Tambah Persinggahan Wisata Alam -->
<div class="modal fade" id="modalTambahAlam" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('itinerary.stop', $itinerary) }}" method="POST" class="modal-content rounded-4 border-0">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Persinggahan Wisata Alam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Titik Wisata Alam Kaldera Toba *</label>
                    <select name="wisata_alam_id" class="form-select" required>
                        @foreach($wisataAlamList as $wa)
                            <option value="{{ $wa->id }}">{{ $wa->nama }} ({{ $wa->wilayah->kabupaten ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Hari Ke-</label>
                        <input type="number" name="hari" value="1" min="1" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Waktu Singgah</label>
                        <input type="time" name="waktu_tiba" value="14:30" class="form-control">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Catatan Singgah</label>
                    <input type="text" name="catatan" class="form-control" placeholder="Contoh: Istirahat & foto panorama setelah makan">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-toba-primary w-100">Tambahkan Wisata Alam</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stops = @json($itinerary->items->map(function($i) {
        $t = $i->destinasi ?? $i->wisataAlam;
        return $t ? ['nama' => $t->nama, 'lat' => (float)$t->latitude, 'lng' => (float)$t->longitude, 'tipe' => $i->destinasi_id ? 'Kuliner' : 'Wisata Alam'] : null;
    })->filter()->values());

    const map = L.map('itineraryMap').setView([2.6845, 98.8756], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    if (stops.length > 0) {
        const latlngs = [];
        stops.forEach((s, idx) => {
            latlngs.push([s.lat, s.lng]);
            L.marker([s.lat, s.lng]).addTo(map).bindPopup(`<strong>${idx + 1}. ${s.nama}</strong><br><small>${s.tipe}</small>`);
        });
        const polyline = L.polyline(latlngs, { color: '#8B1E2D', weight: 4, dashArray: '8, 8' }).addTo(map);
        map.fitBounds(polyline.getBounds(), { padding: [30, 30] });
    }
});
</script>
@endpush