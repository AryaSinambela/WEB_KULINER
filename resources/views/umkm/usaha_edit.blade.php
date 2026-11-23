@extends('layouts.app')

@section('title', 'Kelola Data Destinasi Usaha — Portal Mitra')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('umkm.dashboard') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
            <h1 class="h3 fw-bold mb-0 mt-1">Kelola Data Destinasi &amp; Profil Usaha</h1>
        </div>
    </div>

    @if($destinasi && $destinasi->status_verifikasi === 'ditolak' && $destinasi->catatan_verifikasi)
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Catatan Penolakan / Revisi dari Kurator:</h6>
            <p class="small mb-0">{{ $destinasi->catatan_verifikasi }}</p>
        </div>
    @endif

    <form action="{{ route('umkm.usaha.update') }}" method="POST" enctype="multipart/form-data" class="row g-4">
        @csrf
        @method('PUT')

        <div class="col-lg-7">
            <div class="card-toba p-4">
                <h5 class="fw-bold mb-3">Informasi Utama Destinasi</h5>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Usaha / Destinasi Kuliner *</label>
                    <input type="text" name="nama" value="{{ old('nama', $destinasi->nama ?? '') }}" class="form-control" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Kategori Kuliner *</label>
                        <select name="kategori_id" class="form-select" required>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id', $destinasi->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Wilayah Kecamatan &amp; Kabupaten *</label>
                        <select name="wilayah_id" class="form-select" required>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}" {{ old('wilayah_id', $destinasi->wilayah_id ?? '') == $w->id ? 'selected' : '' }}>
                                    {{ $w->nama_kecamatan }} — Kab. {{ $w->kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Deskripsi Singkat Usaha &amp; Keunikan Racikan</label>
                    <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $destinasi->deskripsi ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold mb-0">Alamat Lengkap *</label>
                        <span id="reverseGeocodeStatus" class="text-muted" style="font-size: 11px;"></span>
                    </div>
                    <input type="text" id="inputAlamatUsaha" name="alamat" value="{{ old('alamat', $destinasi->alamat ?? '') }}" class="form-control" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-bold">Harga Min (Rp)</label>
                        <input type="number" name="harga_min" value="{{ old('harga_min', $destinasi->harga_min ?? 15000) }}" class="form-control">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-bold">Harga Max (Rp)</label>
                        <input type="number" name="harga_max" value="{{ old('harga_max', $destinasi->harga_max ?? 50000) }}" class="form-control">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-bold">Jam Buka</label>
                        <input type="time" name="jam_buka" value="{{ old('jam_buka', $destinasi->jam_buka ? substr($destinasi->jam_buka, 0, 5) : '08:00') }}" class="form-control">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-bold">Jam Tutup</label>
                        <input type="time" name="jam_tutup" value="{{ old('jam_tutup', $destinasi->jam_tutup ? substr($destinasi->jam_tutup, 0, 5) : '20:00') }}" class="form-control">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Foto Utama Kedai / Hidangan</label>
                    <input type="file" name="foto_utama" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-toba-primary w-100 py-2">Simpan Perubahan &amp; Ajukan Verifikasi</button>
            </div>
        </div>

        <!-- Pemilih Koordinat GPS & Reverse Geocoding Nominatim (Kanan) -->
        <div class="col-lg-5">
            <div class="card-toba p-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h5 class="fw-bold mb-0">Titik Lokasi pada Peta</h5>
                    <button type="button" id="btnGpsUsaha" class="btn btn-sm btn-outline-danger rounded-pill">
                        <i class="bi bi-crosshair"></i> Gunakan GPS Saya
                    </button>
                </div>
                <p class="text-muted small mb-2">Cari nama jalan/desa atau geser pin merah untuk mengisi koordinat &amp; alamat secara otomatis (Nominatim API).</p>

                <!-- Pencarian Lokasi Nominatim -->
                <div class="input-group input-group-sm mb-2">
                    <input type="text" id="pickerSearchInput" class="form-control" placeholder="Cari desa/kecamatan (mis. Balige, Tuktuk)...">
                    <button class="btn btn-toba-secondary" type="button" id="btnPickerSearch"><i class="bi bi-search"></i> Cari</button>
                </div>

                <div id="pickerMap" style="height: 310px; border-radius: 12px;" class="mb-3 border"></div>

                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Latitude *</label>
                        <input type="text" id="inputLat" name="latitude" value="{{ old('latitude', $destinasi->latitude ?? '2.3338000') }}" class="form-control form-control-sm bg-light" readonly required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Longitude *</label>
                        <input type="text" id="inputLng" name="longitude" value="{{ old('longitude', $destinasi->longitude ?? '99.0664000') }}" class="form-control form-control-sm bg-light" readonly required>
                    </div>
                </div>
                <div class="form-check form-switch small">
                    <input class="form-check-input" type="checkbox" id="autoFillAddress" checked>
                    <label class="form-check-label text-muted" for="autoFillAddress">Isi kolom Alamat Lengkap otomatis saat pin digeser</label>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const initLat = parseFloat(document.getElementById('inputLat').value) || 2.3338;
    const initLng = parseFloat(document.getElementById('inputLng').value) || 99.0664;

    const map = L.map('pickerMap').setView([initLat, initLng], 15);
    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    const satHybrid = L.layerGroup([
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png')
    ]);
    L.control.layers({
        "🗺️ Peta Jalan (OSM)": osm,
        "🛰️ Satelit + Jalan (Hybrid)": satHybrid
    }).addTo(map);

    const marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

    function updateCoords(lat, lng, doReverse = true) {
        document.getElementById('inputLat').value = lat.toFixed(7);
        document.getElementById('inputLng').value = lng.toFixed(7);

        if (doReverse && document.getElementById('autoFillAddress').checked) {
            const statusEl = document.getElementById('reverseGeocodeStatus');
            statusEl.textContent = 'Mendeteksi alamat dari peta...';
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('inputAlamatUsaha').value = data.display_name;
                        statusEl.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> Alamat disinkronkan dari Nominatim OSM';
                    } else {
                        statusEl.textContent = '';
                    }
                })
                .catch(() => { statusEl.textContent = ''; });
        }
    }

    marker.on('dragend', function (e) {
        const pos = e.target.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('btnGpsUsaha').addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Geolokasi tidak didukung oleh browser Anda.');
            return;
        }
        navigator.geolocation.getCurrentPosition(pos => {
            const { latitude, longitude } = pos.coords;
            map.flyTo([latitude, longitude], 16);
            marker.setLatLng([latitude, longitude]);
            updateCoords(latitude, longitude);
        }, () => alert('Gagal membaca GPS. Pastikan izin lokasi browser diaktifkan.'));
    });

    function searchNominatimLocation() {
        const q = document.getElementById('pickerSearchInput').value.trim();
        if (!q) return;
        fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(q + ' Sumatera Utara')}&limit=1`)
            .then(r => r.json())
            .then(res => {
                if (res.length > 0) {
                    const lat = parseFloat(res[0].lat);
                    const lng = parseFloat(res[0].lon);
                    map.flyTo([lat, lng], 16);
                    marker.setLatLng([lat, lng]);
                    updateCoords(lat, lng);
                } else {
                    alert('Lokasi tidak ditemukan di kawasan Sumatera Utara.');
                }
            });
    }

    document.getElementById('btnPickerSearch').addEventListener('click', searchNominatimLocation);
    document.getElementById('pickerSearchInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchNominatimLocation();
        }
    });
});
</script>
@endpush