@extends('layouts.app')

@section('title', 'Peta Kuliner Interaktif Kaldera Toba')

@push('styles')
<style>
    #mapToba {
        height: calc(100vh - 145px);
        min-height: 540px;
        border-radius: 16px;
        z-index: 1;
    }
    .custom-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 2px solid #FFFFFF;
        box-shadow: 0 3px 8px rgba(0,0,0,0.3);
    }
    .custom-pin i {
        transform: rotate(45deg);
        color: #FFFFFF;
        font-size: 14px;
    }
    .pin-otentik { background-color: #C9A24B; }
    .pin-umum { background-color: #8B1E2D; }
    .leaflet-popup-content-wrapper {
        border-radius: 14px;
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        width: 260px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4 py-3">
    <div class="row g-3">
        <!-- Sidebar Filter Pencarian (Kiri) -->
        <div class="col-lg-3">
            <div class="card-toba p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-sliders me-1 text-danger"></i> Filter Pencarian</h6>
                    <button type="button" id="btnResetMap" class="btn btn-link btn-sm text-decoration-none p-0 text-muted">Reset Semua</button>
                </div>
                <div class="mb-3">
                    <span class="badge bg-light text-dark border" id="badgeTotalPin">Memuat destinasi...</span>
                </div>

                <!-- Toggle Otentik & Buka Sekarang -->
                <div class="p-2 rounded-3 mb-3" style="background-color: var(--toba-neutral);">
                    <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0 mb-2">
                        <label class="form-check-label small fw-semibold" for="mapOtentik">
                            <i class="bi bi-award-fill text-warning me-1"></i> Hanya Otentik Batak
                        </label>
                        <input class="form-check-input" type="checkbox" id="mapOtentik" {{ request()->boolean('otentik') ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0">
                        <label class="form-check-label small fw-semibold" for="mapBuka">
                            <i class="bi bi-clock-fill text-success me-1"></i> Buka Sekarang
                        </label>
                        <input class="form-check-input" type="checkbox" id="mapBuka">
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
                    <div class="d-flex flex-column gap-1" style="max-height: 150px; overflow-y: auto;">
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
                        <span>RADIUS DARI LOKASI SAYA</span>
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

        <!-- Area Peta Interaktif (Kanan) -->
        <div class="col-lg-9 position-relative">
            <!-- Floating Toolbar Atas Peta -->
            <div class="position-absolute top-0 end-0 m-3 p-2 bg-white rounded-4 shadow-sm border d-flex align-items-center gap-3" style="z-index: 500;">
                <button type="button" id="btnMyLocation" class="btn btn-sm btn-light border rounded-pill">
                    <i class="bi bi-crosshair text-danger"></i> Lokasi Saya
                </button>
                <div class="form-check form-switch mb-0 small">
                    <input class="form-check-input" type="checkbox" id="mapLowBandwidth">
                    <label class="form-check-label fw-semibold" for="mapLowBandwidth">Hemat Data (Vektor Ringan)</label>
                </div>
            </div>

            <!-- Legenda Pin Bawah Peta -->
            <div class="position-absolute bottom-0 end-0 m-3 px-3 py-2 bg-white rounded-pill shadow-sm border d-flex align-items-center gap-3 small" style="z-index: 500;">
                <span><i class="bi bi-circle-fill" style="color: #C9A24B;"></i> Otentik Batak</span>
                <span><i class="bi bi-circle-fill" style="color: #8B1E2D;"></i> Kuliner Umum</span>
            </div>

            <div id="mapToba" class="border shadow-sm"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Koordinat Pusat Danau Toba
    const defaultCenter = [2.6845, 98.8756];
    const map = L.map('mapToba').setView(defaultCenter, 10);

    // Tile Layer Standard (OSM) & Low-Bandwidth (Carto Positron Light)
    const standardTile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap'
    });
    const liteTile = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 18,
        attribution: '&copy; CARTO Lite'
    });

    const mapBwToggle = document.getElementById('mapLowBandwidth');
    const isLiteInit = localStorage.getItem('toba_low_bandwidth') === 'true';
    mapBwToggle.checked = isLiteInit;
    (isLiteInit ? liteTile : standardTile).addTo(map);

    mapBwToggle.addEventListener('change', function () {
        localStorage.setItem('toba_low_bandwidth', this.checked);
        if (this.checked) {
            map.removeLayer(standardTile);
            liteTile.addTo(map);
        } else {
            map.removeLayer(liteTile);
            standardTile.addTo(map);
        }
        loadMarkers();
    });

    let markersLayer = L.layerGroup().addTo(map);
    let userCoords = null;
    let userMarker = null;

    // Lokasi Pengguna (GPS)
    document.getElementById('btnMyLocation').addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolokasi.');
            return;
        }
        navigator.geolocation.getCurrentPosition(pos => {
            userCoords = { lat: pos.coords.latitude, lng: pos.coords.longitude };
            map.setView([userCoords.lat, userCoords.lng], 13);
            if (userMarker) map.removeLayer(userMarker);
            userMarker = L.circleMarker([userCoords.lat, userCoords.lng], {
                radius: 8, fillColor: '#0d6efd', color: '#fff', weight: 2, fillOpacity: 0.9
            }).addTo(map).bindPopup('Lokasi Anda Saat Ini').openPopup();
            loadMarkers();
        }, () => alert('Gagal mendapatkan lokasi GPS. Pastikan izin lokasi aktif.'));
    });

    // Label Slider
    const radiusSlider = document.getElementById('mapRadius');
    const radiusLabel = document.getElementById('radiusLabel');
    radiusSlider.addEventListener('input', () => {
        radiusLabel.textContent = radiusSlider.value == 0 ? 'Semua' : radiusSlider.value + ' km';
    });

    const hargaSlider = document.getElementById('mapHarga');
    const hargaLabel = document.getElementById('hargaLabel');
    hargaSlider.addEventListener('input', () => {
        hargaLabel.textContent = 'Rp ' + Number(hargaSlider.value).toLocaleString('id-ID');
    });

    function createCustomIcon(isOtentik) {
        return L.divIcon({
            className: '',
            html: `<div class="custom-pin ${isOtentik ? 'pin-otentik' : 'pin-umum'}">
                     <i class="bi ${isOtentik ? 'bi-award-fill' : 'bi-shop'}"></i>
                   </div>`,
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -34]
        });
    }

    function loadMarkers() {
        const params = new URLSearchParams();
        if (document.getElementById('mapOtentik').checked) params.append('otentik', '1');
        if (document.getElementById('mapBuka').checked) params.append('buka_sekarang', '1');
        
        const wilayahId = document.getElementById('mapWilayah').value;
        if (wilayahId) params.append('wilayah_id', wilayahId);

        const selectedKats = Array.from(document.querySelectorAll('.map-kategori:checked')).map(el => el.value);
        if (selectedKats.length) params.append('kategori', selectedKats.join(','));

        params.append('harga_max', hargaSlider.value);

        if (radiusSlider.value > 0 && userCoords) {
            params.append('lat', userCoords.lat);
            params.append('lng', userCoords.lng);
            params.append('radius', radiusSlider.value);
        }

        fetch(`{{ route('peta.data') }}?${params.toString()}`)
            .then(res => res.json())
            .then(res => {
                markersLayer.clearLayers();
                document.getElementById('badgeTotalPin').textContent = `${res.total} destinasi ditemukan`;
                const lowBw = mapBwToggle.checked;

                res.data.forEach(item => {
                    const imgHtml = (!lowBw && item.foto)
                        ? `<img src="${item.foto}" class="w-100 object-fit-cover" style="height: 110px;" alt="${item.nama}">`
                        : '';
                    const badgeOtentik = item.is_otentik_batak
                        ? `<span class="badge bg-warning text-dark mb-1" style="font-size:10px;">★ Otentik Batak</span>`
                        : '';

                    const popupHtml = `
                        <div>
                            ${imgHtml}
                            <div class="p-3">
                                ${badgeOtentik}
                                <h6 class="fw-bold mb-1">${item.nama}</h6>
                                <p class="text-muted small mb-2"><i class="bi bi-geo-alt text-danger"></i> ${item.kecamatan}, ${item.kabupaten}</p>
                                <div class="d-flex justify-content-between small mb-2">
                                    <span>★ <strong>${item.rating_wisatawan || '-'}</strong> (Wisatawan)</span>
                                    <span class="text-danger">★ <strong>${item.rating_lokal || '-'}</strong> (Lokal)</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="${item.url_detail}" class="btn btn-toba-primary btn-sm flex-grow-1 text-white">Lihat Detail</a>
                                    <a href="${item.url_rute}" target="_blank" class="btn btn-toba-secondary btn-sm" title="Petunjuk Rute">
                                        <i class="bi bi-sign-turn-right-fill"></i> Rute
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;

                    L.marker([item.latitude, item.longitude], { icon: createCustomIcon(item.is_otentik_batak) })
                        .bindPopup(popupHtml)
                        .addTo(markersLayer);
                });
            });
    }

    document.getElementById('btnTerapkanFilter').addEventListener('click', loadMarkers);
    document.getElementById('btnResetMap').addEventListener('click', () => {
        document.getElementById('mapOtentik').checked = false;
        document.getElementById('mapBuka').checked = false;
        document.getElementById('mapWilayah').value = '';
        document.querySelectorAll('.map-kategori').forEach(c => c.checked = false);
        radiusSlider.value = 0; radiusLabel.textContent = 'Semua';
        hargaSlider.value = 150000; hargaLabel.textContent = 'Rp 150.000';
        map.setView(defaultCenter, 10);
        loadMarkers();
    });

    // Muat pin awal saat halaman dibuka
    loadMarkers();
});
</script>
@endpush