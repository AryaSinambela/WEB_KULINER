@extends('layouts.app')

@section('title', 'Peta Kuliner Interaktif Kaldera Toba')

@push('styles')
<style>
    #mapToba {
        height: calc(100vh - 140px);
        min-height: 580px;
        border-radius: 16px;
        z-index: 1;
    }
    .custom-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 2.5px solid #FFFFFF;
        box-shadow: 0 3px 10px rgba(0,0,0,0.35);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-pin.pin-active {
        transform: rotate(-45deg) scale(1.25);
        box-shadow: 0 0 0 6px rgba(201, 162, 75, 0.4), 0 4px 12px rgba(0,0,0,0.45);
        z-index: 999 !important;
    }
    .custom-pin i {
        transform: rotate(45deg);
        color: #FFFFFF;
        font-size: 15px;
    }
    .pin-otentik { background-color: #C9A24B; }
    .pin-umum { background-color: #8B1E2D; }
    .pin-alam { background-color: #198754; }
    .leaflet-popup-content-wrapper {
        border-radius: 14px;
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        width: 270px !important;
    }
    .dest-list-item {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid var(--toba-border, #e5e7eb);
    }
    .dest-list-item:hover, .dest-list-item.active {
        border-color: var(--toba-primary, #8B1E2D);
        background-color: #fff9f5;
        transform: translateY(-1px);
    }
    .nominatim-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        max-height: 230px;
        overflow-y: auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        display: none;
    }
    .nominatim-item {
        cursor: pointer;
        padding: 8px 12px;
        border-bottom: 1px solid #f1f1f1;
        font-size: 12.5px;
    }
    .nominatim-item:hover {
        background-color: #f8f9fa;
    }
    .marker-cluster-small, .marker-cluster-medium, .marker-cluster-large {
        background-color: rgba(139, 30, 45, 0.25) !important;
    }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div {
        background-color: #8B1E2D !important;
        color: #fff !important;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4 py-3">
    <div class="row g-3">
        <!-- Sidebar Filter & Daftar Interaktif (Kiri) -->
        <div class="col-lg-3">
            <div class="card-toba p-3 d-flex flex-column" style="height: calc(100vh - 140px); min-height: 580px;">
                <!-- Pencarian Kata Kunci Kuliner & Autocomplete Wilayah (Nominatim API) -->
                <div class="mb-2 position-relative">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-danger"></i></span>
                        <input type="text" id="mapSearchInput" value="{{ request('q') }}" class="form-control bg-light border-start-0" placeholder="Cari kuliner atau lokasi di Toba...">
                        <button class="btn btn-outline-secondary" type="button" id="btnClearSearch" title="Bersihkan"><i class="bi bi-x"></i></button>
                    </div>
                    <div id="nominatimResults" class="nominatim-dropdown mt-1 border"></div>
                </div>

                <!-- Tab Navigasi Sidebar: Filter vs Daftar Lokasi -->
                <ul class="nav nav-pills nav-fill bg-light rounded-3 p-1 mb-3 small" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-3 py-1 fw-semibold" id="tab-daftar-btn" data-bs-toggle="pill" data-bs-target="#pane-daftar" type="button">
                            <i class="bi bi-list-ul me-1"></i> Daftar (<span id="countDaftar">0</span>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-3 py-1 fw-semibold" id="tab-filter-btn" data-bs-toggle="pill" data-bs-target="#pane-filter" type="button">
                            <i class="bi bi-sliders me-1"></i> Filter Peta
                        </button>
                    </li>
                </ul>

                <div class="tab-content flex-grow-1 overflow-hidden d-flex flex-column">
                    <!-- PANE 1: DAFTAR DESTINASI INTERAKTIF -->
                    <div class="tab-pane fade show active flex-grow-1 overflow-auto pe-1" id="pane-daftar">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark border" id="badgeTotalPin">Memuat destinasi...</span>
                            <button type="button" id="btnFitAllPins" class="btn btn-link btn-sm text-decoration-none p-0 small" style="color: var(--toba-primary);">
                                <i class="bi bi-arrows-fullscreen"></i> Lihat Semua
                            </button>
                        </div>
                        <div id="sidebarDestList" class="d-flex flex-column gap-2">
                            <!-- Diisi secara dinamis via JS -->
                        </div>
                    </div>

                    <!-- PANE 2: FILTER PENCARIAN -->
                    <div class="tab-pane fade flex-grow-1 overflow-auto pe-1" id="pane-filter">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold text-muted">KRITERIA DESTINASI</span>
                            <button type="button" id="btnResetMap" class="btn btn-link btn-sm text-decoration-none p-0 text-danger">Reset Semua</button>
                        </div>

                        <!-- Toggle Otentik, Buka Sekarang & Wisata Alam -->
                        <div class="p-2 rounded-3 mb-3" style="background-color: var(--toba-neutral);">
                            <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0 mb-2">
                                <label class="form-check-label small fw-semibold" for="mapOtentik">
                                    <i class="bi bi-award-fill text-warning me-1"></i> Hanya Otentik Batak
                                </label>
                                <input class="form-check-input" type="checkbox" id="mapOtentik" {{ request()->boolean('otentik') ? 'checked' : '' }}>
                            </div>
                            <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0 mb-2">
                                <label class="form-check-label small fw-semibold" for="mapBuka">
                                    <i class="bi bi-clock-fill text-success me-1"></i> Buka Sekarang
                                </label>
                                <input class="form-check-input" type="checkbox" id="mapBuka">
                            </div>
                            <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0">
                                <label class="form-check-label small fw-semibold" for="mapShowAlam">
                                    <i class="bi bi-tree-fill text-success me-1"></i> Tampilkan Wisata Alam
                                </label>
                                <input class="form-check-input" type="checkbox" id="mapShowAlam" checked>
                            </div>
                        </div>

                        <!-- Filter Wilayah -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Wilayah / Kecamatan</label>
                            <select id="mapWilayah" class="form-select form-select-sm bg-light border-0">
                                <option value="">Semua Wilayah Danau Toba</option>
                                @foreach($wilayahs as $w)
                                    <option value="{{ $w->id }}">{{ $w->nama_kecamatan }} ({{ $w->kabupaten }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kategori Kuliner -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Kategori Kuliner</label>
                            <div class="d-flex flex-column gap-1" style="max-height: 130px; overflow-y: auto;">
                                @foreach($kategoris as $kat)
                                    <div class="form-check small">
                                        <input class="form-check-input map-kategori" type="checkbox" value="{{ $kat->id }}" id="kat_{{ $kat->id }}"
                                               {{ request('kategori_id') == $kat->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="kat_{{ $kat->id }}">{{ $kat->nama }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Radius Pencarian GPS -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-bold text-muted mb-1">
                                <span>RADIUS DARI TITIK SAYA</span>
                                <span class="text-danger" id="radiusLabel">Semua</span>
                            </div>
                            <input type="range" class="form-range" id="mapRadius" min="0" max="30" step="5" value="0">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 10px;">
                                <span>Semua</span><span>5 km</span><span>15 km</span><span>30 km</span>
                            </div>
                        </div>

                        <!-- Perkiraan Harga Maksimum -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-bold text-muted mb-1">
                                <span>HARGA MAKSIMAL</span>
                                <span class="text-dark" id="hargaLabel">Rp 150.000</span>
                            </div>
                            <input type="range" class="form-range" id="mapHarga" min="15000" max="150000" step="5000" value="150000">
                        </div>

                        <button type="button" id="btnTerapkanFilter" class="btn btn-toba-primary w-100 py-2">
                            <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Peta Multi-Layer & Routing Interaktif (Kanan) -->
        <div class="col-lg-9 position-relative">
            <!-- Floating Toolbar Atas Peta -->
            <div class="position-absolute top-0 start-0 m-3 ms-5 p-2 bg-white rounded-4 shadow-sm border d-flex flex-wrap align-items-center gap-2" style="z-index: 500;">
                <button type="button" id="btnMyLocation" class="btn btn-sm btn-light border rounded-pill fw-semibold">
                    <i class="bi bi-crosshair text-danger"></i> GPS Lokasi Saya
                </button>
                <button type="button" id="btnPickOriginMode" class="btn btn-sm btn-light border rounded-pill fw-semibold" title="Klik di mana saja pada peta untuk menetapkan titik awal">
                    <i class="bi bi-geo-fill text-primary"></i> Pilih Titik Awal di Peta
                </button>
            </div>

            <!-- Panel Informasi Navigasi Rute OSRM (Muncul saat rute aktif) -->
            <div id="routeInfoPanel" class="position-absolute bottom-0 start-0 m-3 p-3 bg-white rounded-4 shadow border d-none" style="z-index: 500; max-width: 340px;">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="badge bg-danger"><i class="bi bi-sign-turn-right-fill me-1"></i> RUTE NAVIGASI OSRM</span>
                    <button type="button" id="btnCloseRoute" class="btn-close btn-sm"></button>
                </div>
                <h6 class="fw-bold mb-1" id="routeTargetName">-</h6>
                <div class="d-flex gap-3 small text-dark mb-2">
                    <span><i class="bi bi-rulers text-danger"></i> Jarak: <strong id="routeDist">-</strong></span>
                    <span><i class="bi bi-stopwatch text-success"></i> Waktu: <strong id="routeTime">-</strong></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="https://www.google.com/maps" id="routeExternalGmaps" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill flex-grow-1" style="font-size: 11px;">
                        <i class="bi bi-box-arrow-up-right"></i> Buka Google Maps
                    </a>
                </div>
            </div>

            <!-- Legenda Pin Bawah Peta -->
            <div class="position-absolute bottom-0 end-0 m-3 px-3 py-2 bg-white rounded-pill shadow-sm border d-flex flex-wrap align-items-center gap-3 small" style="z-index: 500;">
                <span><i class="bi bi-circle-fill" style="color: #C9A24B;"></i> Toko Otentik Batak</span>
                <span><i class="bi bi-circle-fill" style="color: #8B1E2D;"></i> Toko Kuliner Umum</span>
                <span><i class="bi bi-circle-fill" style="color: #198754;"></i> Wisata Alam</span>
                <span><i class="bi bi-circle-fill text-primary"></i> Titik Anda</span>
            </div>

            <div id="mapToba" class="border shadow-sm"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const defaultCenter = [2.5800, 98.8756];
    const map = L.map('mapToba', { zoomControl: true }).setView(defaultCenter, 10);

    // =========================================================================
    // 1. MULTI-PROVIDER FREE TILE LAYERS + OVERLAYS (DITIMPA SALING MELENGKAPI)
    // =========================================================================
    const osmStandard = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    });

    const openTopo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        maxZoom: 17,
        attribution: '&copy; OpenTopoMap (CC-BY-SA)'
    });

    const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: 'Tiles &copy; Esri World Imagery'
    });

    // Hybrid Overlay: Citra Satelit Esri + Timpaan Label Jalan & Tempat Transparan Carto
    const esriHybridGroup = L.layerGroup([
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 18 }),
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            pane: 'shadowPane'
        })
    ]);

    // Overlay Kontur Bayangan Bukit Kaldera (Hillshading) yang bisa ditimpa di atas peta mana saja
    const hillshadeOverlay = L.tileLayer('https://tiles.wmflabs.org/hillshading/{z}/{x}/{y}.png', {
        maxZoom: 15,
        opacity: 0.45,
        attribution: 'Hillshading: NASA SRTM'
    });

    // Layer Groups untuk Pin Destinasi & Rute
    const clusterGroup = L.markerClusterGroup({
        showCoverageOnHover: false,
        maxClusterRadius: 42
    });
    const alamLayer = L.layerGroup();
    const routeLayer = L.layerGroup().addTo(map);
    let radiusCircle = null;

    osmStandard.addTo(map);
    clusterGroup.addTo(map);
    alamLayer.addTo(map);

    // Kontrol Layer Leaflet (Pojok Kanan Atas) untuk menimpa base map & overlay
    const baseMaps = {
        "🗺️ Peta Jalan Standar (OSM)": osmStandard,
        "🛰️ Satelit + Nama Jalan (Hybrid)": esriHybridGroup,
        "⛰️ Topografi Kaldera (OpenTopo)": openTopo,
        "🌍 Citra Satelit Murni (Esri)": esriSatellite
    };
    const overlayMaps = {
        "🍲 Pin Toko & Rumah Makan (Cluster)": clusterGroup,
        "🌲 Titik Wisata Alam Kaldera": alamLayer,
        "🏔️ Timpaan Kontur Bukit (Hillshade)": hillshadeOverlay
    };
    L.control.layers(baseMaps, overlayMaps, { position: 'topright', collapsed: true }).addTo(map);

    // =========================================================================
    // 2. TITIK LOKASI PENGGUNA (GPS ATAU KLIK MANUAL DI PETA) + REVERSE GEOCODE
    // =========================================================================
    let userCoords = null;
    let userMarker = null;
    let pickingOriginMode = false;

    function setUserOrigin(lat, lng, labelText = 'Titik Awal Anda') {
        userCoords = { lat, lng };
        if (userMarker) map.removeLayer(userMarker);

        userMarker = L.circleMarker([lat, lng], {
            radius: 9,
            fillColor: '#0d6efd',
            color: '#ffffff',
            weight: 3,
            fillOpacity: 0.95
        }).addTo(map);

        // Reverse Geocoding Gratis via Nominatim OSM untuk menampilkan nama jalan/desa
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(r => r.json())
            .then(data => {
                const addr = data.display_name ? data.display_name.split(',').slice(0, 3).join(',') : `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                userMarker.bindPopup(`<div class="p-2"><strong>${labelText}</strong><br><small class="text-muted">${addr}</small></div>`).openPopup();
            })
            .catch(() => {
                userMarker.bindPopup(`<div class="p-2"><strong>${labelText}</strong></div>`).openPopup();
            });

        updateRadiusCircle();
        loadMarkers();
    }

    document.getElementById('btnMyLocation').addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolokasi.');
            return;
        }
        navigator.geolocation.getCurrentPosition(pos => {
            map.setView([pos.coords.latitude, pos.coords.longitude], 13);
            setUserOrigin(pos.coords.latitude, pos.coords.longitude, 'Lokasi GPS Saya');
        }, () => alert('Gagal mendapatkan lokasi GPS. Anda juga dapat menekan tombol "Pilih Titik Awal di Peta".'));
    });

    const btnPickOrigin = document.getElementById('btnPickOriginMode');
    btnPickOrigin.addEventListener('click', function () {
        pickingOriginMode = !pickingOriginMode;
        if (pickingOriginMode) {
            this.classList.replace('btn-light', 'btn-primary');
            this.innerHTML = '<i class="bi bi-cursor-fill"></i> Klik Peta Sekarang...';
            document.getElementById('mapToba').style.cursor = 'crosshair';
        } else {
            this.classList.replace('btn-primary', 'btn-light');
            this.innerHTML = '<i class="bi bi-geo-fill text-primary"></i> Pilih Titik Awal di Peta';
            document.getElementById('mapToba').style.cursor = '';
        }
    });

    map.on('click', function (e) {
        if (pickingOriginMode) {
            pickingOriginMode = false;
            btnPickOrigin.classList.replace('btn-primary', 'btn-light');
            btnPickOrigin.innerHTML = '<i class="bi bi-geo-fill text-primary"></i> Pilih Titik Awal di Peta';
            document.getElementById('mapToba').style.cursor = '';
            setUserOrigin(e.latlng.lat, e.latlng.lng, 'Titik Awal Pilihan');
        }
    });

    function updateRadiusCircle() {
        if (radiusCircle) map.removeLayer(radiusCircle);
        const radKm = parseFloat(document.getElementById('mapRadius').value);
        if (radKm > 0 && userCoords) {
            radiusCircle = L.circle([userCoords.lat, userCoords.lng], {
                radius: radKm * 1000,
                color: '#8B1E2D',
                weight: 1.5,
                dashArray: '6, 6',
                fillColor: '#8B1E2D',
                fillOpacity: 0.07
            }).addTo(map);
        }
    }

    // =========================================================================
    // 3. NAVIGASI RUTE JALAN RAYA LANGSUNG DI PETA (OSRM ROUTING API GRATIS)
    // =========================================================================
    window.drawRouteTo = function (destLat, destLng, destName, gmapsUrl) {
        const startLat = userCoords ? userCoords.lat : defaultCenter[0];
        const startLng = userCoords ? userCoords.lng : defaultCenter[1];

        if (!userCoords) {
            setUserOrigin(startLat, startLng, 'Titik Awal (Pusat Danau Toba)');
        }

        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${destLng},${destLat}?overview=full&geometries=geojson`;

        fetch(osrmUrl)
            .then(r => r.json())
            .then(res => {
                routeLayer.clearLayers();
                if (res.routes && res.routes.length > 0) {
                    const route = res.routes[0];
                    const distKm = (route.distance / 1000).toFixed(1) + ' km';
                    const durMin = Math.round(route.duration / 60) + ' menit';

                    const geoLine = L.geoJSON(route.geometry, {
                        style: {
                            color: '#8B1E2D',
                            weight: 5,
                            opacity: 0.85
                        }
                    }).addTo(routeLayer);

                    map.fitBounds(geoLine.getBounds(), { padding: [50, 50] });

                    document.getElementById('routeTargetName').textContent = destName;
                    document.getElementById('routeDist').textContent = distKm;
                    document.getElementById('routeTime').textContent = durMin;
                    document.getElementById('routeExternalGmaps').href = gmapsUrl;
                    document.getElementById('routeInfoPanel').classList.remove('d-none');
                } else {
                    alert('Rute jalan darat tidak ditemukan untuk titik ini.');
                }
            })
            .catch(() => {
                routeLayer.clearLayers();
                const fallbackLine = L.polyline([[startLat, startLng], [destLat, destLng]], {
                    color: '#8B1E2D', weight: 4, dashArray: '8, 8'
                }).addTo(routeLayer);
                map.fitBounds(fallbackLine.getBounds(), { padding: [50, 50] });
            });
    };

    document.getElementById('btnCloseRoute').addEventListener('click', function () {
        routeLayer.clearLayers();
        document.getElementById('routeInfoPanel').classList.add('d-none');
    });

    // =========================================================================
    // 4. PENCARIAN AUTOCOMPLETE GABUNGAN (TOKO KULINER + NOMINATIM GEOCODING)
    // =========================================================================
    const searchInput = document.getElementById('mapSearchInput');
    const nominatimBox = document.getElementById('nominatimResults');
    let searchTimer = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        const q = this.value.trim();
        searchTimer = setTimeout(() => {
            loadMarkers();
            if (q.length >= 3) {
                fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(q + ' Sumatera Utara')}&limit=4`)
                    .then(r => r.json())
                    .then(places => {
                        if (!places.length) {
                            nominatimBox.style.display = 'none';
                            return;
                        }
                        nominatimBox.innerHTML = `<div class="px-2 py-1 bg-light text-muted fw-bold" style="font-size:10px;">LOKASI PETA (OPENSTREETMAP)</div>` +
                            places.map(p => `
                                <div class="nominatim-item" data-lat="${p.lat}" data-lng="${p.lon}">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> ${p.display_name}
                                </div>
                            `).join('');
                        nominatimBox.style.display = 'block';

                        nominatimBox.querySelectorAll('.nominatim-item').forEach(el => {
                            el.addEventListener('click', function () {
                                const lat = parseFloat(this.dataset.lat);
                                const lng = parseFloat(this.dataset.lng);
                                map.flyTo([lat, lng], 14);
                                nominatimBox.style.display = 'none';
                            });
                        });
                    })
                    .catch(() => { nominatimBox.style.display = 'none'; });
            } else {
                nominatimBox.style.display = 'none';
            }
        }, 350);
    });

    document.getElementById('btnClearSearch').addEventListener('click', function () {
        searchInput.value = '';
        nominatimBox.style.display = 'none';
        loadMarkers();
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !nominatimBox.contains(e.target)) {
            nominatimBox.style.display = 'none';
        }
    });

    // =========================================================================
    // 5. PEMUATAN PIN TOKO KULINER, WISATA ALAM, & SINKRONISASI DAFTAR SIDEBAR
    // =========================================================================
    const radiusSlider = document.getElementById('mapRadius');
    const radiusLabel = document.getElementById('radiusLabel');
    radiusSlider.addEventListener('input', () => {
        radiusLabel.textContent = radiusSlider.value == 0 ? 'Semua' : radiusSlider.value + ' km';
        updateRadiusCircle();
    });

    const hargaSlider = document.getElementById('mapHarga');
    const hargaLabel = document.getElementById('hargaLabel');
    hargaSlider.addEventListener('input', () => {
        hargaLabel.textContent = 'Rp ' + Number(hargaSlider.value).toLocaleString('id-ID');
    });

    function createCustomIcon(type, id = '') {
        let cls = 'pin-umum';
        let icon = 'bi-shop';
        if (type === 'otentik') { cls = 'pin-otentik'; icon = 'bi-award-fill'; }
        if (type === 'alam') { cls = 'pin-alam'; icon = 'bi-tree-fill'; }

        return L.divIcon({
            className: '',
            html: `<div class="custom-pin ${cls}" id="pin_dom_${id}">
                     <i class="bi ${icon}"></i>
                   </div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 36],
            popupAnchor: [0, -36]
        });
    }

    let markersMap = {};
    let currentBounds = [];

    function loadMarkers() {
        const params = new URLSearchParams();
        const q = searchInput.value.trim();
        if (q) params.append('q', q);
        if (document.getElementById('mapOtentik').checked) params.append('otentik', '1');
        if (document.getElementById('mapBuka').checked) params.append('buka_sekarang', '1');

        const wilayahId = document.getElementById('mapWilayah').value;
        if (wilayahId) params.append('wilayah_id', wilayahId);

        const selectedKats = Array.from(document.querySelectorAll('.map-kategori:checked')).map(el => el.value);
        if (selectedKats.length) params.append('kategori', selectedKats.join(','));

        params.append('harga_max', hargaSlider.value);

        if (userCoords) {
            params.append('lat', userCoords.lat);
            params.append('lng', userCoords.lng);
            if (radiusSlider.value > 0) {
                params.append('radius', radiusSlider.value);
            }
        }

        fetch(`{{ route('peta.data') }}?${params.toString()}`)
            .then(res => res.json())
            .then(res => {
                clusterGroup.clearLayers();
                alamLayer.clearLayers();
                markersMap = {};
                currentBounds = [];

                document.getElementById('badgeTotalPin').textContent = `${res.total} toko kuliner`;
                document.getElementById('countDaftar').textContent = res.total;
                const listContainer = document.getElementById('sidebarDestList');
                listContainer.innerHTML = '';

                if (res.data.length === 0) {
                    listContainer.innerHTML = `
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-geo-alt fs-3 d-block mb-1"></i>
                            Tidak ada toko/destinasi kuliner yang sesuai dengan filter saat ini.
                        </div>`;
                }

                res.data.forEach(item => {
                    currentBounds.push([item.latitude, item.longitude]);
                    const imgHtml = item.foto
                        ? `<img src="${item.foto}" class="w-100 object-fit-cover" style="height: 115px;" alt="${item.nama}" onerror="this.style.display='none';">`
                        : '';
                    const badgeOtentik = item.is_otentik_batak
                        ? `<span class="badge bg-warning text-dark mb-1" style="font-size:10px;">★ Otentik Batak</span>`
                        : `<span class="badge bg-light text-dark border mb-1" style="font-size:10px;">${item.kategori}</span>`;
                    const jarakBadge = item.jarak_km !== null
                        ? `<span class="badge bg-primary-subtle text-primary ms-1" style="font-size:10px;">${item.jarak_km} km</span>`
                        : '';
                    const menuTags = (item.menu_andalan && item.menu_andalan.length)
                        ? `<div class="small text-muted mb-2" style="font-size:11px;"><i class="bi bi-journal-richtext text-danger"></i> <strong>Menu:</strong> ${item.menu_andalan.join(', ')}</div>`
                        : '';

                    const safeName = item.nama.replace(/'/g, "\\'");
                    const popupHtml = `
                        <div>
                            ${imgHtml}
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    ${badgeOtentik}
                                    ${jarakBadge}
                                </div>
                                <small class="text-uppercase fw-bold text-danger d-block" style="font-size:9.5px;"><i class="bi bi-shop"></i> NAMA TOKO / RUMAH MAKAN</small>
                                <h6 class="fw-bold mb-1">${item.nama}</h6>
                                <p class="text-muted small mb-1"><i class="bi bi-geo-alt text-danger"></i> ${item.alamat} (${item.kecamatan}, ${item.kabupaten})</p>
                                ${menuTags}
                                <div class="d-flex justify-content-between small mb-2 pt-1 border-top">
                                    <span>★ <strong>${item.rating_wisatawan || '4.7'}</strong> (Turis)</span>
                                    <span class="text-danger">★ <strong>${item.rating_lokal || '4.9'}</strong> (Lokal)</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="${item.url_detail}" class="btn btn-toba-primary btn-sm flex-grow-1 text-white" style="font-size:12px;">Buka Toko &amp; Cerita</a>
                                    <button type="button" onclick="window.drawRouteTo(${item.latitude}, ${item.longitude}, '${safeName}', '${item.url_rute}')" class="btn btn-toba-secondary btn-sm" style="font-size:12px;" title="Tampilkan Rute di Peta">
                                        <i class="bi bi-sign-turn-right-fill text-danger"></i> Rute
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    const marker = L.marker([item.latitude, item.longitude], {
                        icon: createCustomIcon(item.is_otentik_batak ? 'otentik' : 'umum', item.id)
                    }).bindPopup(popupHtml);

                    clusterGroup.addLayer(marker);
                    markersMap[item.id] = marker;

                    // Buat kartu toko interaktif di daftar sidebar
                    const cardEl = document.createElement('div');
                    cardEl.className = 'dest-list-item p-2 rounded-3';
                    cardEl.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                ${item.is_otentik_batak ? '<span class="badge bg-warning text-dark mb-1" style="font-size:9px;">★ Otentik Batak</span>' : ''}
                                <h6 class="fw-bold mb-0 small"><i class="bi bi-shop text-danger me-1"></i>${item.nama}</h6>
                                <small class="text-muted d-block" style="font-size:11px;">${item.alamat} • ${item.kecamatan}</small>
                                ${item.menu_andalan && item.menu_andalan.length ? `<small class="text-dark d-block mt-1" style="font-size:10.5px;"><strong>Menu:</strong> ${item.menu_andalan.slice(0,2).join(', ')}</small>` : ''}
                            </div>
                            <span class="badge bg-light text-dark border" style="font-size:10px;">★ ${item.rating_lokal || item.rating_wisatawan || '4.8'}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                            <small class="text-muted" style="font-size:11px;">
                                ${item.jarak_km !== null ? `<i class="bi bi-cursor-fill text-primary"></i> ${item.jarak_km} km` : `<i class="bi bi-clock"></i> ${item.jam_buka || '08:00'} - ${item.jam_tutup || '20:00'}`}
                            </small>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-light border btn-sm py-0 px-2 btn-route-inline" style="font-size:11px;" title="Gambar Rute di Peta">
                                    <i class="bi bi-sign-turn-right-fill text-danger"></i> Rute
                                </button>
                                <a href="${item.url_detail}" class="btn btn-toba-primary btn-sm py-0 px-2 text-white" style="font-size:11px;">Detail Toko</a>
                            </div>
                        </div>
                    `;

                    cardEl.addEventListener('click', function (ev) {
                        if (ev.target.closest('.btn-route-inline') || ev.target.closest('a')) return;
                        document.querySelectorAll('.dest-list-item').forEach(el => el.classList.remove('active'));
                        cardEl.classList.add('active');
                        clusterGroup.zoomToShowLayer(marker, () => {
                            map.flyTo([item.latitude, item.longitude], 15);
                            marker.openPopup();
                        });
                    });

                    cardEl.querySelector('.btn-route-inline').addEventListener('click', function (ev) {
                        ev.stopPropagation();
                        window.drawRouteTo(item.latitude, item.longitude, item.nama, item.url_rute);
                    });

                    listContainer.appendChild(cardEl);
                });

                // Render titik Wisata Alam jika dicentang
                if (document.getElementById('mapShowAlam').checked && res.wisata_alam) {
                    res.wisata_alam.forEach(wa => {
                        const safeWaName = wa.nama.replace(/'/g, "\\'");
                        const gmapsWa = `https://www.google.com/maps/dir/?api=1&destination=${wa.latitude},${wa.longitude}`;
                        const waPopup = `
                            <div class="p-3">
                                <span class="badge bg-success mb-1" style="font-size:10px;"><i class="bi bi-tree-fill"></i> Wisata Alam Kaldera</span>
                                <h6 class="fw-bold mb-1">${wa.nama}</h6>
                                <p class="text-muted small mb-2">${wa.kecamatan}, ${wa.kabupaten}</p>
                                <p class="small text-dark mb-2">${wa.deskripsi || 'Titik panorama alam sekitar kawasan kuliner Danau Toba.'}</p>
                                <button type="button" onclick="window.drawRouteTo(${wa.latitude}, ${wa.longitude}, '${safeWaName}', '${gmapsWa}')" class="btn btn-sm btn-success w-100">
                                    <i class="bi bi-sign-turn-right-fill"></i> Rute ke Wisata Alam Ini
                                </button>
                            </div>
                        `;
                        L.marker([wa.latitude, wa.longitude], {
                            icon: createCustomIcon('alam', 'wa_' + wa.id)
                        }).bindPopup(waPopup).addTo(alamLayer);
                    });
                }
            });
    }

    document.getElementById('btnFitAllPins').addEventListener('click', function () {
        if (currentBounds.length > 0) {
            map.fitBounds(currentBounds, { padding: [40, 40] });
        } else {
            map.setView(defaultCenter, 10);
        }
    });

    document.getElementById('mapShowAlam').addEventListener('change', loadMarkers);
    document.getElementById('btnTerapkanFilter').addEventListener('click', () => {
        loadMarkers();
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-daftar-btn')).show();
    });

    document.getElementById('btnResetMap').addEventListener('click', () => {
        document.getElementById('mapOtentik').checked = false;
        document.getElementById('mapBuka').checked = false;
        document.getElementById('mapShowAlam').checked = true;
        document.getElementById('mapWilayah').value = '';
        searchInput.value = '';
        document.querySelectorAll('.map-kategori').forEach(c => c.checked = false);
        radiusSlider.value = 0; radiusLabel.textContent = 'Semua';
        hargaSlider.value = 150000; hargaLabel.textContent = 'Rp 150.000';
        updateRadiusCircle();
        routeLayer.clearLayers();
        document.getElementById('routeInfoPanel').classList.add('d-none');
        map.setView(defaultCenter, 10);
        loadMarkers();
    });

    // Muat pin awal saat halaman dibuka
    loadMarkers();
});
</script>
@endpush