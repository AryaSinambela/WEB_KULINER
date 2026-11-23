@extends('layouts.app')

@section('title', 'Peta Sebaran & Kurasi Spasial — Konsol Admin')

@push('styles')
<style>
    #adminMap {
        height: calc(100vh - 200px);
        min-height: 520px;
        border-radius: 16px;
        z-index: 1;
    }
    .pin-admin {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 2px solid #FFFFFF;
        box-shadow: 0 3px 8px rgba(0,0,0,0.35);
    }
    .pin-admin i {
        transform: rotate(45deg);
        color: #FFFFFF;
        font-size: 14px;
    }
    .pin-status-disetujui { background-color: #198754; }
    .pin-status-otentik { background-color: #C9A24B; }
    .pin-status-menunggu { background-color: #fd7e14; }
    .pin-status-ditolak { background-color: #dc3545; }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4 py-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Konsol Admin</a>
            <h1 class="h4 fw-bold mb-0">Peta Sebaran Spasial &amp; Pemantauan Verifikasi Kaldera Toba</h1>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <span class="badge bg-warning text-dark px-3 py-2">★ Otentik Batak: {{ $rekapStatus['otentik'] }}</span>
            <span class="badge bg-success px-3 py-2">Disetujui: {{ $rekapStatus['disetujui'] }}</span>
            <span class="badge bg-orange text-white px-3 py-2" style="background-color:#fd7e14;">Menunggu Kurasi: {{ $rekapStatus['menunggu'] }}</span>
            <span class="badge bg-danger px-3 py-2">Ditolak: {{ $rekapStatus['ditolak'] }}</span>
        </div>
    </div>

    <div class="row g-3">
        <!-- Sidebar Filter Spasial Admin -->
        <div class="col-lg-3">
            <div class="card-toba p-3 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-funnel-fill text-danger me-1"></i> Filter Kurasi Spasial</h6>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">STATUS VERIFIKASI</label>
                    <select id="adminFilterStatus" class="form-select form-select-sm">
                        <option value="">Semua Status ({{ count($destinasis) }})</option>
                        <option value="disetujui">Disetujui ({{ $rekapStatus['disetujui'] }})</option>
                        <option value="menunggu">Menunggu Kurasi ({{ $rekapStatus['menunggu'] }})</option>
                        <option value="ditolak">Ditolak ({{ $rekapStatus['ditolak'] }})</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">WILAYAH KABUPATEN / KECAMATAN</label>
                    <select id="adminFilterWilayah" class="form-select form-select-sm">
                        <option value="">Semua Wilayah</option>
                        @foreach($wilayahs as $w)
                            <option value="{{ $w->id }}">{{ $w->nama_kecamatan }} — {{ $w->kabupaten }} ({{ $w->destinasi_count }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-check form-switch mb-3 small">
                    <input class="form-check-input" type="checkbox" id="adminOnlyOtentik">
                    <label class="form-check-label fw-semibold" for="adminOnlyOtentik">Hanya Bersertifikat Otentik Batak</label>
                </div>

                <hr>
                <h6 class="fw-bold small text-muted text-uppercase mb-2">Kepadatan per Kecamatan</h6>
                <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 290px;">
                    @foreach($wilayahs as $w)
                        <div class="p-2 rounded-3 border bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="small d-block">{{ $w->nama_kecamatan }}</strong>
                                <span class="text-muted" style="font-size: 11px;">Kab. {{ $w->kabupaten }}</span>
                            </div>
                            <span class="badge bg-dark rounded-pill">{{ $w->destinasi_count }} titik</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Peta Spasial Multi-Layer Admin -->
        <div class="col-lg-9">
            <div class="card-toba p-2">
                <div id="adminMap"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const allDestinasi = @json($destinasis);
    const wisataAlams = @json($wisataAlams);

    const map = L.map('adminMap').setView([2.6845, 98.8756], 10);

    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    const satHybrid = L.layerGroup([
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png')
    ]);
    const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png');

    const clusterLayer = L.markerClusterGroup({ maxClusterRadius: 38 }).addTo(map);
    const alamLayer = L.layerGroup().addTo(map);

    L.control.layers({
        "🗺️ Peta Jalan (OSM)": osm,
        "🛰️ Satelit + Jalan (Hybrid)": satHybrid,
        "⛰️ Topografi Kaldera": topo
    }, {
        "📍 Cluster Destinasi UMKM": clusterLayer,
        "🌲 Titik Wisata Alam": alamLayer
    }).addTo(map);

    wisataAlams.forEach(wa => {
        L.circleMarker([parseFloat(wa.latitude), parseFloat(wa.longitude)], {
            radius: 6, fillColor: '#0d6efd', color: '#fff', weight: 2, fillOpacity: 0.9
        }).bindPopup(`<strong>${wa.nama}</strong><br><small>Wisata Alam</small>`).addTo(alamLayer);
    });

    function createAdminPin(item) {
        let cls = 'pin-status-disetujui';
        let ic = 'bi-check-lg';
        if (item.status_verifikasi === 'menunggu') { cls = 'pin-status-menunggu'; ic = 'bi-hourglass-split'; }
        else if (item.status_verifikasi === 'ditolak') { cls = 'pin-status-ditolak'; ic = 'bi-x-lg'; }
        else if (item.is_otentik_batak) { cls = 'pin-status-otentik'; ic = 'bi-award-fill'; }

        return L.divIcon({
            className: '',
            html: `<div class="pin-admin ${cls}"><i class="bi ${ic}"></i></div>`,
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -34]
        });
    }

    function renderAdminPins() {
        clusterLayer.clearLayers();
        const st = document.getElementById('adminFilterStatus').value;
        const wil = document.getElementById('adminFilterWilayah').value;
        const onlyOt = document.getElementById('adminOnlyOtentik').checked;
        const bounds = [];

        allDestinasi.forEach(d => {
            if (st && d.status_verifikasi !== st) return;
            if (wil && String(d.wilayah_id) !== String(wil)) return;
            if (onlyOt && !d.is_otentik_batak) return;

            const lat = parseFloat(d.latitude);
            const lng = parseFloat(d.longitude);
            bounds.push([lat, lng]);

            const marker = L.marker([lat, lng], { icon: createAdminPin(d) }).bindPopup(`
                <div class="p-2">
                    <span class="badge bg-dark mb-1">${d.status_verifikasi.toUpperCase()}</span>
                    ${d.is_otentik_batak ? '<span class="badge bg-warning text-dark mb-1">★ Otentik</span>' : ''}
                    <h6 class="fw-bold mb-1">${d.nama}</h6>
                    <small class="text-muted d-block mb-2">${d.alamat || ''}</small>
                    <div class="d-flex gap-1">
                        <a href="/destinasi/${d.id}" target="_blank" class="btn btn-sm btn-outline-dark flex-grow-1" style="font-size:11px;">Lihat Publik</a>
                        <a href="/admin/verifikasi" class="btn btn-sm btn-danger flex-grow-1 text-white" style="font-size:11px;">Panel Kurasi</a>
                    </div>
                </div>
            `);
            clusterLayer.addLayer(marker);
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [40, 40] });
        }
    }

    document.getElementById('adminFilterStatus').addEventListener('change', renderAdminPins);
    document.getElementById('adminFilterWilayah').addEventListener('change', renderAdminPins);
    document.getElementById('adminOnlyOtentik').addEventListener('change', renderAdminPins);

    renderAdminPins();
});
</script>
@endpush
