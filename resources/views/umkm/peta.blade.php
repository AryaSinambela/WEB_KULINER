@extends('layouts.app')

@section('title', 'Peta Lokasi & Analisis Strategis Usaha — Portal Mitra')

@push('styles')
<style>
    #umkmMap {
        height: 520px;
        border-radius: 16px;
        z-index: 1;
    }
    .pin-mitra-utama {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        background-color: #8B1E2D;
        border: 3px solid #FFD700;
        box-shadow: 0 0 0 6px rgba(139, 30, 45, 0.25), 0 4px 12px rgba(0,0,0,0.4);
    }
    .pin-mitra-utama i {
        transform: rotate(45deg);
        color: #FFFFFF;
        font-size: 18px;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <a href="{{ route('umkm.dashboard') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard Mitra</a>
            <h1 class="h3 fw-bold mb-0 mt-1">Peta Strategis &amp; Jangkauan Lokasi Usaha</h1>
            <p class="text-muted small mb-0">{{ $destinasi->nama }} • {{ $destinasi->alamat }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('umkm.usaha.edit') }}" class="btn btn-toba-primary btn-sm px-3">
                <i class="bi bi-geo-alt-fill me-1"></i> Ubah Titik GPS / Alamat
            </a>
            <a href="{{ route('destinasi.show', $destinasi) }}" target="_blank" class="btn btn-toba-secondary btn-sm px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Halaman Publik
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card-toba p-4 mb-3">
                <h6 class="fw-bold mb-2"><i class="bi bi-shop text-danger me-1"></i> Koordinat Toko Usaha Anda</h6>
                <div class="p-3 rounded-3 border mb-3" style="background-color: var(--toba-neutral);">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Status Peta:</span>
                        <span class="badge {{ $destinasi->status_verifikasi === 'disetujui' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ strtoupper($destinasi->status_verifikasi) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Latitude:</span>
                        <strong>{{ $destinasi->latitude }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Longitude:</span>
                        <strong>{{ $destinasi->longitude }}</strong>
                    </div>
                </div>

                <label class="form-label small fw-bold text-muted">RADIUS ANALISIS SEKITAR (KM)</label>
                <div class="d-flex gap-2 mb-3">
                    <button type="button" class="btn btn-sm btn-outline-danger flex-grow-1 btn-rad" data-km="2">2 km</button>
                    <button type="button" class="btn btn-sm btn-danger flex-grow-1 btn-rad" data-km="5">5 km</button>
                    <button type="button" class="btn btn-sm btn-outline-danger flex-grow-1 btn-rad" data-km="10">10 km</button>
                </div>

                <h6 class="fw-bold small text-uppercase text-muted mb-2">Simulasi Rute dari Wisata Alam Terdekat</h6>
                <p class="text-muted" style="font-size: 12px;">Klik salah satu titik wisata alam di bawah untuk menguji rute perjalanan wisatawan (OSRM) menuju kedai Anda:</p>
                <div class="d-flex flex-column gap-2" style="max-height: 220px; overflow-y: auto;">
                    @foreach($wisataAlams as $wa)
                        <div class="p-2 rounded-3 border d-flex justify-content-between align-items-center bg-light">
                            <div>
                                <strong class="d-block small">{{ $wa->nama }}</strong>
                                <span class="text-muted" style="font-size: 11px;">{{ $wa->wilayah->nama_kecamatan ?? '' }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-toba-secondary py-1 px-2"
                                    onclick="window.simulateRouteFromAlam({{ (float)$wa->latitude }}, {{ (float)$wa->longitude }}, '{{ addslashes($wa->nama) }}')">
                                <i class="bi bi-sign-turn-right-fill text-success"></i> Uji Rute
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-8 position-relative">
            <div class="card-toba p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                    <div class="d-flex align-items-center gap-3 small">
                        <span><i class="bi bi-circle-fill text-danger"></i> Usaha Anda</span>
                        <span><i class="bi bi-circle-fill text-success"></i> Wisata Alam</span>
                        <span><i class="bi bi-circle-fill text-secondary"></i> Kuliner Lain</span>
                    </div>
                    <div id="umkmRouteStats" class="d-none small">
                        <span class="badge bg-danger"><i class="bi bi-car-front-fill me-1"></i> <span id="umkmRouteLabel">-</span></span>
                    </div>
                </div>
                <div id="umkmMap" class="border"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const myLat = {{ (float) $destinasi->latitude }};
    const myLng = {{ (float) $destinasi->longitude }};
    const sekitarKuliner = @json($sekitarKuliner);
    const wisataAlams = @json($wisataAlams);

    const map = L.map('umkmMap').setView([myLat, myLng], 13);

    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    const satHybrid = L.layerGroup([
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png')
    ]);
    const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png');

    const kulinerLayer = L.layerGroup().addTo(map);
    const alamLayer = L.layerGroup().addTo(map);
    const routeLayer = L.layerGroup().addTo(map);

    L.control.layers({
        "🗺️ Peta Jalan (OSM)": osm,
        "🛰️ Satelit + Jalan (Hybrid)": satHybrid,
        "⛰️ Topografi Kaldera": topo
    }, {
        "🌲 Wisata Alam Sekitar": alamLayer,
        "🍲 Mitra Kuliner Lain": kulinerLayer
    }).addTo(map);

    // Pin Utama Usaha Mitra
    const myIcon = L.divIcon({
        className: '',
        html: `<div class="pin-mitra-utama"><i class="bi bi-shop"></i></div>`,
        iconSize: [42, 42],
        iconAnchor: [21, 42],
        popupAnchor: [0, -42]
    });

    L.marker([myLat, myLng], { icon: myIcon }).addTo(map)
        .bindPopup(`<div class="p-2"><strong>{{ addslashes($destinasi->nama) }}</strong><br><small class="text-muted">Titik Lokasi Usaha Anda</small></div>`)
        .openPopup();

    // Lingkaran Radius Jangkauan
    let radCircle = L.circle([myLat, myLng], {
        radius: 5000,
        color: '#8B1E2D',
        weight: 2,
        dashArray: '6, 6',
        fillColor: '#8B1E2D',
        fillOpacity: 0.08
    }).addTo(map);

    document.querySelectorAll('.btn-rad').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.btn-rad').forEach(b => b.classList.replace('btn-danger', 'btn-outline-danger'));
            this.classList.replace('btn-outline-danger', 'btn-danger');
            const km = parseFloat(this.dataset.km);
            radCircle.setRadius(km * 1000);
            map.fitBounds(radCircle.getBounds(), { padding: [20, 20] });
        });
    });

    // Plot Wisata Alam
    wisataAlams.forEach(wa => {
        L.circleMarker([parseFloat(wa.latitude), parseFloat(wa.longitude)], {
            radius: 7, fillColor: '#198754', color: '#fff', weight: 2, fillOpacity: 0.9
        }).bindPopup(`<strong>${wa.nama}</strong><br><small>Wisata Alam</small>`).addTo(alamLayer);
    });

    // Plot Kuliner Lain
    sekitarKuliner.forEach(k => {
        L.circleMarker([parseFloat(k.latitude), parseFloat(k.longitude)], {
            radius: 6, fillColor: '#6c757d', color: '#fff', weight: 1.5, fillOpacity: 0.85
        }).bindPopup(`<strong>${k.nama}</strong><br><small>Mitra Kuliner</small>`).addTo(kulinerLayer);
    });

    // Simulasi Rute OSRM dari Wisata Alam ke Usaha Mitra
    window.simulateRouteFromAlam = function (fromLat, fromLng, alamName) {
        fetch(`https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${myLng},${myLat}?overview=full&geometries=geojson`)
            .then(r => r.json())
            .then(res => {
                routeLayer.clearLayers();
                if (res.routes && res.routes.length > 0) {
                    const route = res.routes[0];
                    const line = L.geoJSON(route.geometry, {
                        style: { color: '#198754', weight: 5, opacity: 0.85 }
                    }).addTo(routeLayer);
                    map.fitBounds(line.getBounds(), { padding: [40, 40] });
                    const km = (route.distance / 1000).toFixed(1);
                    const min = Math.round(route.duration / 60);
                    document.getElementById('umkmRouteLabel').textContent = `${alamName} → Kedai Anda: ${km} km (${min} mnt)`;
                    document.getElementById('umkmRouteStats').classList.remove('d-none');
                }
            });
    };
});
</script>
@endpush
