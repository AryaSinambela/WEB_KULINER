<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Destinasi Kuliner Toba</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        #map { height: 500px; border-radius: 12px; }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="fw-bold mb-1">Pemetaan Kuliner Toba</h2>
    <p class="text-muted mb-4">Temukan kuliner khas dan cerita budayanya di kawasan Danau Toba</p>

    <!-- Form Filter -->
    <form method="GET" action="{{ route('destinations.index') }}" class="row g-3 mb-4 bg-white p-3 rounded shadow-sm">
        <div class="col-md-4">
            <label class="form-label font-weight-bold">Kabupaten</label>
            <select name="regency" class="form-select">
                <option value="">Semua Kabupaten</option>
                <option value="Toba" {{ request('regency') == 'Toba' ? 'selected' : '' }}>Toba</option>
                <option value="Samosir" {{ request('regency') == 'Samosir' ? 'selected' : '' }}>Samosir</option>
                <option value="Tapanuli Utara" {{ request('regency') == 'Tapanuli Utara' ? 'selected' : '' }}>Tapanuli Utara</option>
                <option value="Simalungun" {{ request('regency') == 'Simalungun' ? 'selected' : '' }}>Simalungun</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="authentic" value="1" id="authentic" {{ request('authentic') == '1' ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="authentic">
                    Hanya Kuliner Otentik Batak
                </label>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Cari & Filter</button>
        </div>
    </form>

    <!-- Container Peta -->
    <div id="map" class="shadow-sm mb-4"></div>

    <!-- Daftar Destinasi -->
    <div class="row">
        @forelse($destinations as$item)
            <div class="col-md-6 mb-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title fw-bold">{{ $item->name }}</h5>
                            @if($item->is_authentic_batak)
                                <span class="badge bg-success">Otentik Batak</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-2">📍 {{ $item->address }} ({{$item->regency }})</p>
                        <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-primary fw-bold">⭐ Tourist: {{ $item->tourist_rating }}</small> | 
                                <small class="text-success fw-bold">⭐ Lokal: {{ $item->local_rating }}</small>
                            </div>
                            <a href="{{ route('destinations.show', $item->slug) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Belum ada destinasi kuliner yang terdaftar untuk filter ini.</div>
            </div>
        @endforelse
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inisialisasi Peta titik pusat Danau Toba
    var map = L.map('map').setView([2.33310000, 99.06640000], 10);

    // Layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Data Destinasi dari Laravel Controller
    var destinations = @json($destinations);

    // Tambahkan Marker ke Peta
    destinations.forEach(function(dest) {
        var marker = L.marker([dest.latitude, dest.longitude]).addTo(map);
        
        var popupContent = `
            <div style="max-width: 200px;">
                <h6><b>${dest.name}</b></h6>
                <p style="font-size: 12px; margin-bottom: 5px;">${dest.address}</p>
                <a href="/destinasi/${dest.slug}" class="btn btn-xs btn-primary" style="color:white; text-decoration:none; font-size:11px; padding:3px 8px;">Detail</a>
            </div>
        `;
        marker.bindPopup(popupContent);
    });
</script>
</body>
</html>