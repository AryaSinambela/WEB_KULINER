@extends('layouts.app')

@section('title', 'Peta Kuliner Interaktif & Navigasi Rute Kaldera Toba')

@push('styles')
<style>
    #mapToba {
        height: calc(100vh - 135px);
        min-height: 610px;
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
    .custom-pin i {
        transform: rotate(45deg);
        color: #FFFFFF;
        font-size: 15px;
    }
    .pin-otentik { background-color: #C9A24B; }
    .pin-umum { background-color: #8B1E2D; }
    .pin-alam { background-color: #198754; }

    .pin-ab {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        color: #fff;
        font-weight: 800;
        font-size: 14px;
        border: 3px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    }
    .pin-origin-a { background-color: #0d6efd; }
    .pin-dest-b { background-color: #dc3545; }

    .leaflet-popup-content-wrapper {
        border-radius: 14px;
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        width: 280px !important;
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
        z-index: 1060;
        max-height: 220px;
        overflow-y: auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        display: none;
    }
    .nominatim-item {
        cursor: pointer;
        padding: 8px 12px;
        border-bottom: 1px solid #f1f1f1;
        font-size: 12px;
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
    .step-item {
        font-size: 12px;
        padding: 7px 10px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background-color 0.15s;
    }
    .step-item:hover {
        background-color: #fff5f5;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4 py-3">
    <div class="row g-3">
        <!-- Sidebar Kiri: Navigasi Rute (A ke B), Daftar Toko, & Filter -->
        <div class="col-lg-4 col-xl-3 order-2 order-lg-1">
            <div class="card-toba peta-sidebar-card p-3 d-flex flex-column" style="height: calc(100vh - 135px); min-height: 610px;">
                <!-- Pencarian Kata Kunci Kuliner & Autocomplete Wilayah (Nominatim API) -->
                <div class="mb-2 position-relative">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-danger"></i></span>
                        <input type="text" id="mapSearchInput" value="{{ request('q') }}" class="form-control bg-light border-start-0" placeholder="Cari toko, kuliner, atau lokasi...">
                        <button class="btn btn-outline-secondary" type="button" id="btnClearSearch" title="Bersihkan"><i class="bi bi-x"></i></button>
                    </div>
                    <div id="nominatimResults" class="nominatim-dropdown mt-1 border"></div>
                </div>

                <!-- 3 Tab Navigasi Sidebar: Daftar Toko | Rute (A -> B) | Filter -->
                <ul class="nav nav-pills nav-fill bg-light rounded-3 p-1 mb-3 small" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-3 py-1 px-2 fw-semibold" id="tab-daftar-btn" data-bs-toggle="pill" data-bs-target="#pane-daftar" type="button">
                            <i class="bi bi-shop me-1"></i> Toko (<span id="countDaftar">0</span>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-3 py-1 px-2 fw-semibold" id="tab-rute-btn" data-bs-toggle="pill" data-bs-target="#pane-rute" type="button">
                            <i class="bi bi-sign-turn-right-fill me-1"></i> Rute A→B
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-3 py-1 px-2 fw-semibold" id="tab-filter-btn" data-bs-toggle="pill" data-bs-target="#pane-filter" type="button">
                            <i class="bi bi-sliders me-1"></i> Filter
                        </button>
                    </li>
                </ul>

                <div class="tab-content flex-grow-1 overflow-hidden d-flex flex-column">
                    <!-- PANE 1: DAFTAR TOKO KULINER INTERAKTIF -->
                    <div class="tab-pane fade show active flex-grow-1 overflow-auto pe-1" id="pane-daftar">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark border" id="badgeTotalPin">Memuat toko...</span>
                            <button type="button" id="btnFitAllPins" class="btn btn-link btn-sm text-decoration-none p-0 small" style="color: var(--toba-primary);">
                                <i class="bi bi-arrows-fullscreen"></i> Lihat Semua
                            </button>
                        </div>
                        <div id="sidebarDestList" class="d-flex flex-column gap-2"></div>
                    </div>

                    <!-- PANE 2: PANEL NAVIGASI RUTE GOOGLE MAPS STYLE (TITIK AWAL A & TUJUAN B) -->
                    <div class="tab-pane fade flex-grow-1 overflow-auto pe-1" id="pane-rute">
                        <div class="p-3 rounded-4 border mb-3" style="background-color: var(--toba-neutral);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold text-uppercase" style="color: var(--toba-primary);">
                                    <i class="bi bi-compass-fill me-1"></i> Pencari Rute Perjalanan
                                </span>
                                <button type="button" id="btnSwapAB" class="btn btn-sm btn-light border rounded-pill py-0 px-2" title="Tukar Titik Awal & Tujuan" style="font-size: 11px;">
                                    <i class="bi bi-arrow-down-up"></i> Tukar A↔B
                                </button>
                            </div>

                            <!-- Input Titik Awal (A) -->
                            <div class="mb-2 position-relative">
                                <label class="form-label small fw-bold mb-1 text-primary">
                                    <span class="badge bg-primary rounded-circle me-1">A</span> Titik Awal Keberangkatan *
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" id="inputOriginText" class="form-control" placeholder="Ketik alamat/desa atau pilih di peta...">
                                    <button type="button" id="btnOriginGps" class="btn btn-outline-primary" title="Gunakan GPS Saya">
                                        <i class="bi bi-crosshair"></i>
                                    </button>
                                    <button type="button" id="btnOriginPickMap" class="btn btn-outline-secondary" title="Klik di Peta untuk Titik Awal">
                                        <i class="bi bi-geo-alt"></i>
                                    </button>
                                </div>
                                <div id="originAutocomplete" class="nominatim-dropdown mt-1 border"></div>
                                <!-- Pilihan Cepat Titik Awal Populer di Toba -->
                                <select id="quickOriginSelect" class="form-select form-select-sm mt-1 bg-white text-muted" style="font-size: 11.5px;">
                                    <option value="">-- Atau pilih titik awal cepat di Kaldera Toba --</option>
                                    <option value="2.3338,99.0664,Pusat Kota Balige (Toba)">Pusat Kota Balige (Kab. Toba)</option>
                                    <option value="2.6632,98.9341,Pelabuhan & Pantai Bebas Parapat">Pelabuhan &amp; Pantai Bebas Parapat</option>
                                    <option value="2.6085,98.6942,Pusat Kota Pangururan (Samosir)">Pusat Kota Pangururan (Samosir)</option>
                                    <option value="2.6718,98.8574,Dermaga Wisata Tuktuk Siadong">Dermaga Wisata Tuktuk Siadong</option>
                                    <option value="2.2571,98.9912,Bandara Silangit (Siborongborong)">Bandara Internasional Silangit</option>
                                    <option value="2.0235,98.9612,Pusat Kota Tarutung (Taput)">Pusat Kota Tarutung (Tapanuli Utara)</option>
                                    <option value="2.2684,98.8645,Simpang Dolok Sanggul / Lintong">Simpang Dolok Sanggul (Humbahas)</option>
                                </select>
                            </div>

                            <!-- Input Titik Tujuan (B) -->
                            <div class="mb-3 position-relative">
                                <label class="form-label small fw-bold mb-1 text-danger">
                                    <span class="badge bg-danger rounded-circle me-1">B</span> Titik Tujuan (Toko / Lokasi) *
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" id="inputDestText" class="form-control" placeholder="Ketik tujuan atau pilih toko di bawah...">
                                    <button type="button" id="btnDestPickMap" class="btn btn-outline-danger" title="Klik di Peta untuk Titik Tujuan">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </button>
                                </div>
                                <div id="destAutocomplete" class="nominatim-dropdown mt-1 border"></div>
                                <!-- Dropdown Pilih Toko Kuliner / Wisata Alam sebagai Tujuan -->
                                <select id="quickDestSelect" class="form-select form-select-sm mt-1 bg-white" style="font-size: 11.5px;">
                                    <option value="">-- Pilih Toko Kuliner / Wisata Alam Tujuan --</option>
                                </select>
                            </div>

                            <!-- Moda Kendaraan & Tombol Hitung Rute -->
                            <div class="d-flex gap-2 mb-2">
                                <select id="routeProfileMode" class="form-select form-select-sm w-auto">
                                    <option value="driving">🚗 Mobil / Motor</option>
                                    <option value="cycling">🚲 Sepeda</option>
                                    <option value="walking">🚶 Jalan Kaki</option>
                                </select>
                                <button type="button" id="btnCalculateRouteAB" class="btn btn-toba-primary btn-sm flex-grow-1 fw-semibold">
                                    <i class="bi bi-sign-turn-right-fill me-1"></i> Tampilkan Rute
                                </button>
                            </div>
                            <button type="button" id="btnClearRouteAB" class="btn btn-outline-secondary btn-sm w-100 py-1" style="font-size: 11.5px;">
                                <i class="bi bi-trash me-1"></i> Bersihkan Rute A→B
                            </button>
                        </div>

                        <!-- Hasil Ringkasan Rute & Panduan Langkah-demi-Langkah (Turn-by-Turn) -->
                        <div id="routeResultBox" class="d-none">
                            <div class="p-3 rounded-4 border bg-white shadow-sm mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> RUTE DITEMUKAN</span>
                                    <a href="https://www.google.com/maps" id="linkOpenGoogleMapsAB" target="_blank" class="small text-decoration-none fw-bold" style="color: var(--toba-primary); font-size: 11.5px;">
                                        Buka Google Maps <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                                <div class="row g-2 text-center my-2">
                                    <div class="col-6">
                                        <div class="p-2 rounded-3 bg-light border">
                                            <small class="text-muted d-block" style="font-size: 10px;">JARAK TEMPUH</small>
                                            <strong class="text-danger fs-6" id="resDistanceKm">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded-3 bg-light border">
                                            <small class="text-muted d-block" style="font-size: 10px;">ESTIMASI WAKTU</small>
                                            <strong class="text-success fs-6" id="resDurationMin">-</strong>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block" style="font-size: 11px;">
                                    <i class="bi bi-info-circle me-1"></i> Anda dapat menggeser (*drag*) pin <strong>A</strong> atau <strong>B</strong> di peta untuk mengubah jalur secara langsung.
                                </small>
                            </div>

                            <h6 class="fw-bold small text-muted text-uppercase mb-2 px-1">Petunjuk Arah Jalan (Turn-by-Turn)</h6>
                            <div id="routeStepsContainer" class="rounded-3 border bg-white overflow-auto" style="max-height: 210px;"></div>
                        </div>
                    </div>

                    <!-- PANE 3: FILTER PENCARIAN -->
                    <div class="tab-pane fade flex-grow-1 overflow-auto pe-1" id="pane-filter">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold text-muted">KRITERIA TOKO KULINER</span>
                            <button type="button" id="btnResetMap" class="btn btn-link btn-sm text-decoration-none p-0 text-danger">Reset Semua</button>
                        </div>

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

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Wilayah / Kecamatan</label>
                            <select id="mapWilayah" class="form-select form-select-sm bg-light border-0">
                                <option value="">Semua Wilayah Danau Toba</option>
                                @foreach($wilayahs as $w)
                                    <option value="{{ $w->id }}">{{ $w->nama_kecamatan }} ({{ $w->kabupaten }})</option>
                                @endforeach
                            </select>
                        </div>

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

                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-bold text-muted mb-1">
                                <span>RADIUS DARI TITIK AWAL (A)</span>
                                <span class="text-danger" id="radiusLabel">Semua</span>
                            </div>
                            <input type="range" class="form-range" id="mapRadius" min="0" max="30" step="5" value="0">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 10px;">
                                <span>Semua</span><span>5 km</span><span>15 km</span><span>30 km</span>
                            </div>
                        </div>

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

        <!-- Area Peta Multi-Layer & Navigasi Rute A -> B (Kanan di Desktop, Atas di HP/Tablet) -->
        <div class="col-lg-8 col-xl-9 position-relative order-1 order-lg-2">
            <!-- Floating Toolbar Atas Peta -->
            <div class="map-floating-toolbar position-absolute top-0 start-0 m-3 ms-5 p-2 bg-white rounded-4 shadow-sm border d-flex flex-wrap align-items-center gap-2" style="z-index: 500;">
                <button type="button" id="btnMyLocation" class="btn btn-sm btn-light border rounded-pill fw-semibold">
                    <i class="bi bi-crosshair text-primary"></i> GPS Saya (Titik A)
                </button>
                <button type="button" id="btnPickOriginMode" class="btn btn-sm btn-light border rounded-pill fw-semibold" title="Klik pada peta untuk memilih Titik Awal (A)">
                    <span class="badge bg-primary rounded-circle me-1">A</span> Pilih Titik Awal
                </button>
                <button type="button" id="btnPickDestMode" class="btn btn-sm btn-light border rounded-pill fw-semibold" title="Klik pada peta untuk memilih Titik Tujuan (B)">
                    <span class="badge bg-danger rounded-circle me-1">B</span> Pilih Titik Tujuan
                </button>
            </div>

            <!-- Floating Banner Saat Rute Aktif -->
            <div id="routeInfoPanel" class="position-absolute bottom-0 start-0 m-3 p-3 bg-white rounded-4 shadow border d-none" style="z-index: 500; max-width: 360px;">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="badge bg-danger"><i class="bi bi-sign-turn-right-fill me-1"></i> NAVIGASI RUTE A → B</span>
                    <button type="button" id="btnCloseRoute" class="btn-close btn-sm"></button>
                </div>
                <div class="small mb-2">
                    <div class="text-truncate"><span class="badge bg-primary me-1">A</span> <strong id="floatOriginName">-</strong></div>
                    <div class="text-truncate mt-1"><span class="badge bg-danger me-1">B</span> <strong id="routeTargetName">-</strong></div>
                </div>
                <div class="d-flex gap-3 small text-dark mb-2 pt-1 border-top">
                    <span><i class="bi bi-rulers text-danger"></i> Jarak: <strong id="routeDist">-</strong></span>
                    <span><i class="bi bi-stopwatch text-success"></i> Waktu: <strong id="routeTime">-</strong></span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="btnOpenStepsTab" class="btn btn-sm btn-toba-primary rounded-pill flex-grow-1" style="font-size: 11px;">
                        <i class="bi bi-list-ol"></i> Detail Arah Jalan
                    </button>
                    <a href="https://www.google.com/maps" id="routeExternalGmaps" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill" style="font-size: 11px;">
                        <i class="bi bi-box-arrow-up-right"></i> Google Maps
                    </a>
                </div>
            </div>

            <!-- Legenda Pin Bawah Peta -->
            <div class="map-floating-legend position-absolute bottom-0 end-0 m-3 px-3 py-2 bg-white rounded-pill shadow-sm border d-flex flex-wrap align-items-center gap-3 small" style="z-index: 500;">
                <span><i class="bi bi-circle-fill" style="color: #C9A24B;"></i> Toko Otentik</span>
                <span><i class="bi bi-circle-fill" style="color: #8B1E2D;"></i> Toko Kuliner</span>
                <span><i class="bi bi-circle-fill" style="color: #198754;"></i> Wisata Alam</span>
                <span><span class="badge bg-primary rounded-circle" style="font-size:9px;">A</span> Awal</span>
                <span><span class="badge bg-danger rounded-circle" style="font-size:9px;">B</span> Tujuan</span>
            </div>

            <div id="mapToba" class="border shadow-sm"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const defaultCenter = [2.4800, 98.9000];
    const map = L.map('mapToba', { zoomControl: true }).setView(defaultCenter, 10);

    // =========================================================================
    // 1. MULTI-PROVIDER FREE TILE LAYERS + OVERLAYS
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

    const esriHybridGroup = L.layerGroup([
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 18 }),
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            pane: 'shadowPane'
        })
    ]);

    const hillshadeOverlay = L.tileLayer('https://tiles.wmflabs.org/hillshading/{z}/{x}/{y}.png', {
        maxZoom: 15,
        opacity: 0.45,
        attribution: 'Hillshading: NASA SRTM'
    });

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
    // 2. SISTEM TITIK AWAL (A) & TITIK TUJUAN (B) SEPERTI GOOGLE MAPS
    // =========================================================================
    let originPoint = null; // { lat, lng, name }
    let destPoint   = null; // { lat, lng, name }
    let originMarker = null;
    let destMarker   = null;
    let pickMode     = null; // 'origin' | 'dest' | null

    function createABIcon(letter, cls) {
        return L.divIcon({
            className: '',
            html: `<div class="pin-ab ${cls}">${letter}</div>`,
            iconSize: [34, 34],
            iconAnchor: [17, 17],
            popupAnchor: [0, -17]
        });
    }

    window.setOriginPoint = function (lat, lng, labelName = null, autoRoute = true) {
        originPoint = { lat: parseFloat(lat), lng: parseFloat(lng), name: labelName || `${ parseFloat(lat).toFixed(4) }, ${ parseFloat(lng).toFixed(4) }` };
        document.getElementById('inputOriginText').value = originPoint.name;

        if (originMarker) map.removeLayer(originMarker);
        originMarker = L.marker([originPoint.lat, originPoint.lng], {
            icon: createABIcon('A', 'pin-origin-a'),
            draggable: true
        }).addTo(map);

        originMarker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            window.setOriginPoint(pos.lat, pos.lng, null, true);
        });

        // Reverse geocode via Nominatim jika belum ada nama
        if (!labelName) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${originPoint.lat}&lon=${originPoint.lng}`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.display_name) {
                        originPoint.name = data.display_name.split(',').slice(0, 3).join(',').trim();
                        document.getElementById('inputOriginText').value = originPoint.name;
                    }
                    originMarker.bindPopup(`<div class="p-2"><span class="badge bg-primary mb-1">TITIK AWAL (A)</span><br><strong>${originPoint.name}</strong><br><small class="text-muted">Geser pin A untuk mengubah titik awal</small></div>`);
                })
                .catch(() => {});
        } else {
            originMarker.bindPopup(`<div class="p-2"><span class="badge bg-primary mb-1">TITIK AWAL (A)</span><br><strong>${originPoint.name}</strong><br><small class="text-muted">Geser pin A untuk mengubah titik awal</small></div>`);
        }

        updateRadiusCircle();
        loadMarkers();
        if (autoRoute && destPoint) {
            calculateAndDrawRoute();
        }
    };

    window.setDestinationPoint = function (lat, lng, labelName = null, autoRoute = true) {
        destPoint = { lat: parseFloat(lat), lng: parseFloat(lng), name: labelName || `${ parseFloat(lat).toFixed(4) }, ${ parseFloat(lng).toFixed(4) }` };
        document.getElementById('inputDestText').value = destPoint.name;

        if (destMarker) map.removeLayer(destMarker);
        destMarker = L.marker([destPoint.lat, destPoint.lng], {
            icon: createABIcon('B', 'pin-dest-b'),
            draggable: true
        }).addTo(map);

        destMarker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            window.setDestinationPoint(pos.lat, pos.lng, null, true);
        });

        if (!labelName) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${destPoint.lat}&lon=${destPoint.lng}`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.display_name) {
                        destPoint.name = data.display_name.split(',').slice(0, 3).join(',').trim();
                        document.getElementById('inputDestText').value = destPoint.name;
                    }
                    destMarker.bindPopup(`<div class="p-2"><span class="badge bg-danger mb-1">TITIK TUJUAN (B)</span><br><strong>${destPoint.name}</strong><br><small class="text-muted">Geser pin B untuk mengubah tujuan</small></div>`);
                })
                .catch(() => {});
        } else {
            destMarker.bindPopup(`<div class="p-2"><span class="badge bg-danger mb-1">TITIK TUJUAN (B)</span><br><strong>${destPoint.name}</strong><br><small class="text-muted">Geser pin B untuk mengubah tujuan</small></div>`);
        }

        if (autoRoute && originPoint) {
            calculateAndDrawRoute();
        }
    };

    // Mode Klik di Peta untuk Memilih Titik A atau Titik B
    const btnPickOrigin = document.getElementById('btnPickOriginMode');
    const btnPickDest   = document.getElementById('btnPickDestMode');

    function setMapPickMode(mode) {
        pickMode = (pickMode === mode) ? null : mode;
        btnPickOrigin.classList.replace('btn-primary', 'btn-light');
        btnPickDest.classList.replace('btn-danger', 'btn-light');
        document.getElementById('mapToba').style.cursor = '';

        if (pickMode === 'origin') {
            btnPickOrigin.classList.replace('btn-light', 'btn-primary');
            document.getElementById('mapToba').style.cursor = 'crosshair';
        } else if (pickMode === 'dest') {
            btnPickDest.classList.replace('btn-light', 'btn-danger');
            document.getElementById('mapToba').style.cursor = 'crosshair';
        }
    }

    btnPickOrigin.addEventListener('click', () => setMapPickMode('origin'));
    btnPickDest.addEventListener('click', () => setMapPickMode('dest'));
    document.getElementById('btnOriginPickMap').addEventListener('click', () => setMapPickMode('origin'));
    document.getElementById('btnDestPickMap').addEventListener('click', () => setMapPickMode('dest'));

    map.on('click', function (e) {
        if (pickMode === 'origin') {
            setMapPickMode(null);
            window.setOriginPoint(e.latlng.lat, e.latlng.lng, null, true);
        } else if (pickMode === 'dest') {
            setMapPickMode(null);
            window.setDestinationPoint(e.latlng.lat, e.latlng.lng, null, true);
        }
    });

    // Tombol GPS Lokasi Saya (Set sebagai Titik A)
    function useGpsAsOrigin() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolokasi. Silakan pilih titik awal di peta atau dari daftar.');
            return;
        }
        navigator.geolocation.getCurrentPosition(pos => {
            map.flyTo([pos.coords.latitude, pos.coords.longitude], 13);
            window.setOriginPoint(pos.coords.latitude, pos.coords.longitude, 'Lokasi GPS Saya', true);
        }, () => {
            alert('Izin GPS tidak aktif. Silakan klik tombol "Pilih Titik Awal" lalu klik di peta, atau pilih dari dropdown Titik Awal Cepat.');
        });
    }

    document.getElementById('btnMyLocation').addEventListener('click', useGpsAsOrigin);
    document.getElementById('btnOriginGps').addEventListener('click', useGpsAsOrigin);

    // Dropdown Pilihan Cepat Titik Awal (A) & Tujuan (B)
    document.getElementById('quickOriginSelect').addEventListener('change', function () {
        if (!this.value) return;
        const [lat, lng, label] = this.value.split(',');
        map.flyTo([parseFloat(lat), parseFloat(lng)], 13);
        window.setOriginPoint(parseFloat(lat), parseFloat(lng), label, true);
    });

    document.getElementById('quickDestSelect').addEventListener('change', function () {
        if (!this.value) return;
        const [lat, lng, label] = this.value.split('|');
        map.flyTo([parseFloat(lat), parseFloat(lng)], 14);
        window.setDestinationPoint(parseFloat(lat), parseFloat(lng), label, true);
    });

    // Tukar Titik Awal (A) <-> Titik Tujuan (B)
    document.getElementById('btnSwapAB').addEventListener('click', function () {
        if (!originPoint && !destPoint) return;
        const tempOrigin = originPoint ? { ...originPoint } : null;
        const tempDest   = destPoint ? { ...destPoint } : null;

        if (tempDest) window.setOriginPoint(tempDest.lat, tempDest.lng, tempDest.name, false);
        if (tempOrigin) window.setDestinationPoint(tempOrigin.lat, tempOrigin.lng, tempOrigin.name, false);

        if (originPoint && destPoint) {
            calculateAndDrawRoute();
        }
    });

    function updateRadiusCircle() {
        if (radiusCircle) map.removeLayer(radiusCircle);
        const radKm = parseFloat(document.getElementById('mapRadius').value);
        if (radKm > 0 && originPoint) {
            radiusCircle = L.circle([originPoint.lat, originPoint.lng], {
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
    // 3. PERHITUNGAN RUTE JALAN RAYA OSRM (A -> B) + TURN-BY-TURN DIRECTIONS
    // =========================================================================
    function formatManeuverInstruction(step) {
        const road = step.name ? ` ke <strong>${step.name}</strong>` : '';
        const type = step.maneuver?.type || '';
        const mod  = step.maneuver?.modifier || '';

        if (type === 'depart') return `🚩 Mulai perjalanan dari Titik Awal (A)${road}`;
        if (type === 'arrive') return `🏁 Tiba di Titik Tujuan (B)`;
        if (mod.includes('left')) return `⬅️ Belok kiri${road}`;
        if (mod.includes('right')) return `➡️ Belok kanan${road}`;
        if (type === 'roundabout') return `🔄 Masuk bundaran lalu keluar${road}`;
        return `⬆️ Lurus ikuti jalan${road}`;
    }

    function calculateAndDrawRoute() {
        // Buka tab Rute A->B di sidebar agar pengguna melihat titik awal & tujuan
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-rute-btn')).show();

        if (!originPoint) {
            // Jika pengguna belum memilih titik A, gunakan Pusat Kota Balige sebagai titik A default yang langsung bisa diubah/digeser
            window.setOriginPoint(2.3338, 99.0664, 'Pusat Kota Balige (Geser Pin A / Ubah)', false);
        }

        if (!destPoint) {
            alert('Silakan pilih Titik Tujuan (B) terlebih dahulu dari daftar toko atau klik di peta.');
            return;
        }

        const profile = document.getElementById('routeProfileMode').value || 'driving';
        const osrmUrl = `https://router.project-osrm.org/route/v1/${profile}/${originPoint.lng},${originPoint.lat};${destPoint.lng},${destPoint.lat}?overview=full&geometries=geojson&steps=true`;

        const gmapsDirUrl = `https://www.google.com/maps/dir/?api=1&origin=${originPoint.lat},${originPoint.lng}&destination=${destPoint.lat},${destPoint.lng}`;

        fetch(osrmUrl)
            .then(r => r.json())
            .then(res => {
                routeLayer.clearLayers();
                if (res.routes && res.routes.length > 0) {
                    const route = res.routes[0];
                    const distKm = (route.distance / 1000).toFixed(1) + ' km';
                    const durMin = Math.max(1, Math.round(route.duration / 60)) + ' menit';

                    // Garis tepi putih + jalur utama biru/merah ala Google Maps
                    L.geoJSON(route.geometry, {
                        style: { color: '#ffffff', weight: 9, opacity: 0.9 }
                    }).addTo(routeLayer);

                    const geoLine = L.geoJSON(route.geometry, {
                        style: { color: '#0d6efd', weight: 5, opacity: 0.95 }
                    }).addTo(routeLayer);

                    map.fitBounds(geoLine.getBounds(), { padding: [55, 55] });

                    // Update Panel Floating & Box Hasil Sidebar
                    document.getElementById('floatOriginName').textContent = originPoint.name;
                    document.getElementById('routeTargetName').textContent = destPoint.name;
                    document.getElementById('routeDist').textContent = distKm;
                    document.getElementById('routeTime').textContent = durMin;
                    document.getElementById('routeExternalGmaps').href = gmapsDirUrl;
                    document.getElementById('routeInfoPanel').classList.remove('d-none');

                    document.getElementById('resDistanceKm').textContent = distKm;
                    document.getElementById('resDurationMin').textContent = durMin;
                    document.getElementById('linkOpenGoogleMapsAB').href = gmapsDirUrl;
                    document.getElementById('routeResultBox').classList.remove('d-none');

                    // Render Langkah-langkah Jalan (Turn-by-Turn)
                    const stepsBox = document.getElementById('routeStepsContainer');
                    const steps = route.legs && route.legs[0] && route.legs[0].steps ? route.legs[0].steps : [];
                    if (steps.length > 0) {
                        stepsBox.innerHTML = steps.map((st, idx) => {
                            const dText = st.distance >= 1000 ? (st.distance / 1000).toFixed(1) + ' km' : Math.round(st.distance) + ' m';
                            const loc = st.maneuver?.location || [originPoint.lng, originPoint.lat];
                            return `
                                <div class="step-item d-flex justify-content-between align-items-center" data-lat="${loc[1]}" data-lng="${loc[0]}">
                                    <span>${idx + 1}. ${formatManeuverInstruction(st)}</span>
                                    <span class="badge bg-light text-dark border ms-2">${dText}</span>
                                </div>
                            `;
                        }).join('');

                        stepsBox.querySelectorAll('.step-item').forEach(el => {
                            el.addEventListener('click', function () {
                                map.flyTo([parseFloat(this.dataset.lat), parseFloat(this.dataset.lng)], 16);
                            });
                        });
                    } else {
                        stepsBox.innerHTML = '<div class="p-2 text-muted small">Ikuti jalur biru pada peta menuju tujuan.</div>';
                    }
                } else {
                    alert('Rute jalan darat tidak ditemukan antara dua titik tersebut.');
                }
            })
            .catch(() => {
                routeLayer.clearLayers();
                const fallbackLine = L.polyline([[originPoint.lat, originPoint.lng], [destPoint.lat, destPoint.lng]], {
                    color: '#0d6efd', weight: 4, dashArray: '8, 8'
                }).addTo(routeLayer);
                map.fitBounds(fallbackLine.getBounds(), { padding: [50, 50] });
            });
    }

    // Fungsi global saat tombol "Rute ke Sini" pada popup toko / daftar toko diklik
    window.drawRouteTo = function (destLat, destLng, destName) {
        window.setDestinationPoint(destLat, destLng, destName, false);
        calculateAndDrawRoute();
    };

    window.setAsOriginFromPopup = function (origLat, origLng, origName) {
        window.setOriginPoint(origLat, origLng, origName, true);
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-rute-btn')).show();
    };

    document.getElementById('btnCalculateRouteAB').addEventListener('click', calculateAndDrawRoute);
    document.getElementById('routeProfileMode').addEventListener('change', () => {
        if (originPoint && destPoint) calculateAndDrawRoute();
    });

    function clearRouteAll() {
        routeLayer.clearLayers();
        if (originMarker) { map.removeLayer(originMarker); originMarker = null; }
        if (destMarker) { map.removeLayer(destMarker); destMarker = null; }
        originPoint = null;
        destPoint   = null;
        document.getElementById('inputOriginText').value = '';
        document.getElementById('inputDestText').value = '';
        document.getElementById('quickOriginSelect').value = '';
        document.getElementById('quickDestSelect').value = '';
        document.getElementById('routeInfoPanel').classList.add('d-none');
        document.getElementById('routeResultBox').classList.add('d-none');
        updateRadiusCircle();
    }

    document.getElementById('btnCloseRoute').addEventListener('click', clearRouteAll);
    document.getElementById('btnClearRouteAB').addEventListener('click', clearRouteAll);
    document.getElementById('btnOpenStepsTab').addEventListener('click', () => {
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-rute-btn')).show();
    });

    // =========================================================================
    // 4. AUTOCOMPLETE NOMINATIM UNTUK INPUT TITIK AWAL (A), TUJUAN (B), & PENCARIAN
    // =========================================================================
    function attachNominatimAutocomplete(inputEl, dropdownEl, onSelectCallback) {
        let timer = null;
        inputEl.addEventListener('input', function () {
            clearTimeout(timer);
            const q = this.value.trim();
            if (q.length < 3) {
                dropdownEl.style.display = 'none';
                return;
            }
            timer = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(q + ' Sumatera Utara')}&limit=5`)
                    .then(r => r.json())
                    .then(places => {
                        if (!places.length) {
                            dropdownEl.style.display = 'none';
                            return;
                        }
                        dropdownEl.innerHTML = places.map(p => {
                            const shortName = p.display_name.split(',').slice(0, 3).join(',').trim();
                            return `
                                <div class="nominatim-item" data-lat="${p.lat}" data-lng="${p.lon}" data-name="${shortName.replace(/"/g, '&quot;')}">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> ${p.display_name}
                                </div>
                            `;
                        }).join('');
                        dropdownEl.style.display = 'block';

                        dropdownEl.querySelectorAll('.nominatim-item').forEach(item => {
                            item.addEventListener('click', function () {
                                const lat  = parseFloat(this.dataset.lat);
                                const lng  = parseFloat(this.dataset.lng);
                                const name = this.dataset.name;
                                dropdownEl.style.display = 'none';
                                onSelectCallback(lat, lng, name);
                            });
                        });
                    })
                    .catch(() => { dropdownEl.style.display = 'none'; });
            }, 320);
        });

        document.addEventListener('click', function (e) {
            if (!inputEl.contains(e.target) && !dropdownEl.contains(e.target)) {
                dropdownEl.style.display = 'none';
            }
        });
    }

    attachNominatimAutocomplete(
        document.getElementById('inputOriginText'),
        document.getElementById('originAutocomplete'),
        (lat, lng, name) => {
            map.flyTo([lat, lng], 14);
            window.setOriginPoint(lat, lng, name, true);
        }
    );

    attachNominatimAutocomplete(
        document.getElementById('inputDestText'),
        document.getElementById('destAutocomplete'),
        (lat, lng, name) => {
            map.flyTo([lat, lng], 14);
            window.setDestinationPoint(lat, lng, name, true);
        }
    );

    const searchInput = document.getElementById('mapSearchInput');
    const nominatimBox = document.getElementById('nominatimResults');
    let searchTimer = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => loadMarkers(), 300);
    });
    attachNominatimAutocomplete(searchInput, nominatimBox, (lat, lng) => {
        map.flyTo([lat, lng], 14);
    });

    document.getElementById('btnClearSearch').addEventListener('click', function () {
        searchInput.value = '';
        nominatimBox.style.display = 'none';
        loadMarkers();
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

        if (originPoint) {
            params.append('lat', originPoint.lat);
            params.append('lng', originPoint.lng);
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

                // Isi juga dropdown Quick Destination (Titik B)
                const quickDest = document.getElementById('quickDestSelect');
                const currentDestVal = quickDest.value;
                let destOptionsHtml = '<option value="">-- Pilih Toko Kuliner / Wisata Alam Tujuan (B) --</option>';

                if (res.data.length === 0) {
                    listContainer.innerHTML = `
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-geo-alt fs-3 d-block mb-1"></i>
                            Tidak ada toko/destinasi kuliner yang sesuai dengan filter saat ini.
                        </div>`;
                }

                res.data.forEach(item => {
                    currentBounds.push([item.latitude, item.longitude]);
                    destOptionsHtml += `<option value="${item.latitude}|${item.longitude}|${item.nama}">🍲 ${item.nama} (${item.kecamatan})</option>`;

                    const imgHtml = item.foto
                        ? `<img src="${item.foto}" class="w-100 object-fit-cover" style="height: 115px;" alt="${item.nama}" onerror="this.style.display='none';">`
                        : '';
                    const badgeOtentik = item.is_otentik_batak
                        ? `<span class="badge bg-warning text-dark mb-1" style="font-size:10px;">★ Otentik Batak</span>`
                        : `<span class="badge bg-light text-dark border mb-1" style="font-size:10px;">${item.kategori}</span>`;
                    const jarakBadge = item.jarak_km !== null
                        ? `<span class="badge bg-primary-subtle text-primary ms-1" style="font-size:10px;">${item.jarak_km} km dari A</span>`
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
                                <div class="d-flex gap-1 mb-1">
                                    <button type="button" onclick="window.drawRouteTo(${item.latitude}, ${item.longitude}, '${safeName}')" class="btn btn-toba-primary btn-sm flex-grow-1 text-white" style="font-size:11.5px;">
                                        <span class="badge bg-danger me-1">B</span> Jadikan Tujuan Rute
                                    </button>
                                    <button type="button" onclick="window.setAsOriginFromPopup(${item.latitude}, ${item.longitude}, '${safeName}')" class="btn btn-outline-primary btn-sm" style="font-size:11.5px;" title="Jadikan Titik Awal (A)">
                                        <span class="badge bg-primary">A</span> Awal
                                    </button>
                                </div>
                                <a href="${item.url_detail}" class="btn btn-toba-secondary btn-sm w-100" style="font-size:11.5px;">Buka Detail Toko &amp; Cerita Menu</a>
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
                                ${item.jarak_km !== null ? `<i class="bi bi-cursor-fill text-primary"></i> ${item.jarak_km} km dari A` : `<i class="bi bi-clock"></i> ${item.jam_buka || '08:00'} - ${item.jam_tutup || '20:00'}`}
                            </small>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-light border btn-sm py-0 px-2 btn-route-inline" style="font-size:11px;" title="Rute ke Toko Ini (B)">
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
                        window.drawRouteTo(item.latitude, item.longitude, item.nama);
                    });

                    listContainer.appendChild(cardEl);
                });

                // Render titik Wisata Alam jika dicentang
                if (document.getElementById('mapShowAlam').checked && res.wisata_alam) {
                    res.wisata_alam.forEach(wa => {
                        destOptionsHtml += `<option value="${wa.latitude}|${wa.longitude}|${wa.nama}">🌲 ${wa.nama} (Wisata Alam)</option>`;
                        const safeWaName = wa.nama.replace(/'/g, "\\'");
                        const waPopup = `
                            <div class="p-3">
                                <span class="badge bg-success mb-1" style="font-size:10px;"><i class="bi bi-tree-fill"></i> Wisata Alam Kaldera</span>
                                <h6 class="fw-bold mb-1">${wa.nama}</h6>
                                <p class="text-muted small mb-2">${wa.kecamatan}, ${wa.kabupaten}</p>
                                <p class="small text-dark mb-2">${wa.deskripsi || 'Titik panorama alam sekitar kawasan kuliner Danau Toba.'}</p>
                                <div class="d-flex gap-1">
                                    <button type="button" onclick="window.drawRouteTo(${wa.latitude}, ${wa.longitude}, '${safeWaName}')" class="btn btn-sm btn-success flex-grow-1" style="font-size:11.5px;">
                                        <span class="badge bg-danger me-1">B</span> Rute ke Sini
                                    </button>
                                    <button type="button" onclick="window.setAsOriginFromPopup(${wa.latitude}, ${wa.longitude}, '${safeWaName}')" class="btn btn-sm btn-outline-primary" style="font-size:11.5px;">
                                        <span class="badge bg-primary">A</span> Awal
                                    </button>
                                </div>
                            </div>
                        `;
                        L.marker([wa.latitude, wa.longitude], {
                            icon: createCustomIcon('alam', 'wa_' + wa.id)
                        }).bindPopup(waPopup).addTo(alamLayer);
                    });
                }

                quickDest.innerHTML = destOptionsHtml;
                if (currentDestVal) quickDest.value = currentDestVal;
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
        clearRouteAll();
        map.setView(defaultCenter, 10);
        loadMarkers();
    });

    // Muat pin awal saat halaman dibuka
    loadMarkers();
});
</script>
@endpush