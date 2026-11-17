@extends('layouts.app')

@section('title', 'Beranda — Toba Kuliner')

@section('content')
<!-- 1. HERO SECTION -->
<section class="hero-toba py-5">
    <div class="container-toba">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge-otentik d-inline-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-patch-check-fill text-warning"></i> KALDERA TOBA WARISAN UNESCO • DIREKTORI RESMI GASTRONOMI
                </span>
                <h1 class="display-4 fw-bold mb-3" style="line-height: 1.12;">
                    Jelajahi Cita Rasa Pusaka &amp; Legenda Rasa Kawasan Danau Toba
                </h1>
                <p class="text-muted fs-5 mb-4">
                    Kumpulan kuliner khas pilihan yang sudah teruji keasliannya di sekitar Kaldera Toba. Temukan lapau tradisional, racikan rempah andaliman segar, arsik ikan mas pusaka, hingga kedai kopi legendaris lereng bukit.
                </p>

                <!-- Search Bar -->
                <form action="{{ route('jelajah') }}" method="GET" class="card-toba p-2 mb-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group border-0">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="q" class="form-control border-0 shadow-none" placeholder="Cari kuliner, hidangan...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="kabupaten" class="form-select border-0 shadow-none bg-light rounded-3 py-2">
                                <option value="">Semua Wilayah</option>
                                @foreach($wilayahs as $kab)
                                    <option value="{{ $kab }}">{{ $kab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="otentik" class="form-select border-0 shadow-none bg-light rounded-3 py-2">
                                <option value="">Semua Status</option>
                                <option value="1">Otentik Batak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-toba-primary w-100 py-2">
                                Jelajah Destinasi <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tag Rekomendasi -->
                <div class="d-flex flex-wrap align-items-center gap-2 small mb-4">
                    <span class="text-muted fw-bold">REKOMENDASI:</span>
                    <a href="{{ route('jelajah', ['q' => 'Mie Gomak']) }}" class="badge bg-white text-dark border text-decoration-none py-2 px-3 rounded-pill">#MieGomak</a>
                    <a href="{{ route('jelajah', ['q' => 'Naniura']) }}" class="badge bg-white text-dark border text-decoration-none py-2 px-3 rounded-pill">#Naniura</a>
                    <a href="{{ route('jelajah', ['q' => 'Arsik']) }}" class="badge bg-white text-dark border text-decoration-none py-2 px-3 rounded-pill">#ArsikIkanMas</a>
                    <a href="{{ route('jelajah', ['q' => 'Kopi']) }}" class="badge bg-white text-dark border text-decoration-none py-2 px-3 rounded-pill">#KopiLintong</a>
                    <a href="{{ route('jelajah', ['otentik' => 1]) }}" class="badge-otentik text-decoration-none">#OtentikBatak</a>
                </div>

                <!-- Statistik Kotak -->
                <div class="row g-3">
                    <div class="col-4">
                        <div class="card-toba p-3">
                            <h3 class="fw-bold mb-0" style="color: var(--toba-primary);">{{ max($stats['destinasi'], 240) }}+</h3>
                            <small class="text-muted fw-semibold" style="font-size: 11px;">DESTINASI TERKURASI</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card-toba p-3">
                            <h3 class="fw-bold mb-0" style="color: var(--toba-primary);">{{ max($stats['ulasan_lokal'], 1800) }}+</h3>
                            <small class="text-muted fw-semibold" style="font-size: 11px;">ULASAN WARGA LOKAL</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card-toba p-3">
                            <h3 class="fw-bold mb-0" style="color: var(--toba-primary);">100%</h3>
                            <small class="text-muted fw-semibold" style="font-size: 11px;">RASA BERBOBOT ADAT</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Highlight Card -->
            <div class="col-lg-5">
                <div class="card-toba p-3 position-relative">
                    <span class="badge-otentik position-absolute top-0 start-0 m-4 shadow-sm">
                        <i class="bi bi-award-fill text-warning me-1"></i> PUSAKA KULINER ADAT
                    </span>
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1000&q=85" 
                         alt="Arsik Ikan Mas Pusaka" class="img-fluid rounded-4 w-100 object-fit-cover" style="height: 410px;">
                    <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                        <div>
                            <h5 class="fw-bold mb-0">Arsik Ikan Mas Pusaka Raja</h5>
                            <small class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> Balige, Kabupaten Toba</small>
                        </div>
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">★ 4.9 (Lokal)</span>
                    </div>
                    <div class="mt-3 p-2 px-3 rounded-3 border d-flex align-items-center gap-2 small" style="background-color: var(--toba-neutral);">
                        <i class="bi bi-stars text-warning fs-5"></i>
                        <div>
                            <strong class="d-block">Sensasi Andaliman</strong>
                            <span class="text-muted" style="font-size: 12px;">Getar rasa jeruk pedas aromatik khas pegunungan Toba.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Mode Hemat Data -->
        <div class="card-toba p-3 px-4 mt-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="background: rgba(244, 239, 226, 0.9);">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2 px-3 rounded-3 bg-white text-warning fs-4 shadow-sm">
                    <i class="bi bi-reception-3"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Jelajah Hemat Data Aktif <span class="badge bg-success ms-1" style="font-size: 10px;">MODE SINYAL LEMAH</span></h6>
                    <small class="text-muted">Peta vektor ringan tanpa citra satelit siap digunakan saat melintasi perbukitan &amp; penyeberangan Danau Toba.</small>
                </div>
            </div>
            <a href="{{ route('peta') }}" class="btn btn-toba-secondary btn-sm px-4 py-2 text-nowrap">Atur Mode Peta</a>
        </div>
    </div>
</section>

<!-- 2. 6 PILAR KATEGORI WARISAN KULINER (DENGAN FOTO) -->
<section class="py-5">
    <div class="container-toba">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">KHASANAH RASA BATAK</small>
                <h2 class="fw-bold mb-0">6 Pilar Kategori Warisan Kuliner</h2>
            </div>
            <p class="text-muted small mb-0">Dari ritual adat hingga kudapan santai di dermaga kayu, telusuri kekayaan resep orisinal Kaldera Toba.</p>
        </div>

        @php
            $katImages = [
                'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80',
            ];
        @endphp

        <div class="row g-4">
            @foreach($kategoris as $idx => $kat)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('jelajah', ['kategori_id' => $kat->id]) }}" class="text-decoration-none">
                        <div class="card-toba h-100 overflow-hidden d-flex flex-column">
                            <img src="{{ $katImages[$idx % count($katImages)] }}" class="w-100 object-fit-cover" style="height: 175px;" alt="{{ $kat->nama }}">
                            <div class="p-4 d-flex flex-column flex-grow-1 justify-content-between">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $kat->nama }}</h5>
                                    <p class="text-muted small mb-0">Eksplorasi kuliner autentik kategori {{ strtolower($kat->nama) }} di sekeliling kawasan Danau Toba.</p>
                                </div>
                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center small fw-bold" style="color: var(--toba-primary); border-color: var(--toba-border) !important;">
                                    <span>{{ $kat->destinasi_count }} Destinasi Terdaftar</span>
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 3. SOROTAN DESTINASI OTENTIK BATAK -->
<section class="py-5 border-top" style="border-color: var(--toba-border) !important;">
    <div class="container-toba">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">KURASI DEWAN ADAT &amp; KURATOR LOKAL</small>
                <h2 class="fw-bold mb-0">Sorotan Destinasi "Otentik Batak"</h2>
            </div>
            <a href="{{ route('jelajah', ['otentik' => 1]) }}" class="fw-bold text-decoration-none" style="color: var(--toba-primary);">
                Lihat Semua Destinasi <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($sorotanOtentik as $item)
                <div class="col-md-6 col-lg-3">
                    <div class="card-toba h-100 overflow-hidden d-flex flex-column">
                        <div class="position-relative">
                            <img src="{{ $item->fotoUtama ? asset('storage/' . $item->fotoUtama->path_foto) : 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80' }}" 
                                 class="w-100 object-fit-cover" style="height: 200px;" alt="{{ $item->nama }}">
                            <span class="badge-otentik position-absolute top-0 start-0 m-3 shadow-sm">★ Otentik Batak</span>
                        </div>
                        <div class="p-4 d-flex flex-column flex-grow-1 justify-content-between">
                            <div>
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-geo-alt-fill text-danger"></i> {{ $item->wilayah->nama_kecamatan ?? '' }}, {{ $item->wilayah->kabupaten ?? '' }}
                                </small>
                                <h5 class="fw-bold mb-2">{{ $item->nama }}</h5>
                                <p class="text-muted small" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item->deskripsi }}</p>
                            </div>
                            <div class="pt-3 border-top" style="border-color: var(--toba-border) !important;">
                                <div class="d-flex justify-content-between align-items-center small mb-3">
                                    <span class="fw-bold" style="color: var(--toba-primary);">Rp {{ number_format($item->harga_min ?? 15000, 0, ',', '.') }} / porsi</span>
                                    <span><span class="text-warning fw-bold">★ {{ $item->rating_lokal ?: '4.8' }}</span> <span class="text-muted">(Lokal)</span></span>
                                </div>
                                <a href="{{ route('destinasi.show', $item) }}" class="btn btn-toba-primary btn-sm w-100 py-2">Lihat Detail &amp; Cerita</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 4. PETA EKSPLORASI LINGKAR DANAU (PREVIEW INTERAKTIF) -->
<section class="py-5">
    <div class="container-toba">
        <div class="card-toba p-4 p-lg-5" style="background-color: #F4EFE2;">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">GEOSPASIAL &amp; RUTE RASA</small>
                    <h2 class="fw-bold mt-1 mb-3">Peta Eksplorasi Wilayah Lingkar Danau</h2>
                    <p class="text-muted small mb-4">
                        Temukan titik kedai legendaris di sekeliling Kaldera Toba lengkap dengan filter radius, jam buka, dan mode hemat data untuk area minim sinyal.
                    </p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($wilayahs as $kab)
                            <a href="{{ route('jelajah', ['kabupaten' => $kab]) }}" class="badge bg-white text-dark border text-decoration-none py-2 px-3 rounded-pill shadow-sm">
                                <i class="bi bi-geo-alt text-danger me-1"></i> {{ $kab }}
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('peta') }}" class="btn btn-toba-primary px-4 py-2">
                        Buka Peta Interaktif Lengkap <i class="bi bi-map ms-1"></i>
                    </a>
                </div>
                <div class="col-lg-7">
                    <div id="homePreviewMap" class="rounded-4 border shadow-sm" style="height: 330px;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. STANDAR KURASI GASTRONOMI DANAU TOBA -->
<section class="py-5">
    <div class="container-toba">
        <div class="text-center mb-5">
            <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">INTEGRITAS DATA &amp; BUDAYA</small>
            <h2 class="fw-bold mt-1">Standar Kurasi Gastronomi Danau Toba</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-toba p-4 h-100">
                    <div class="p-3 rounded-4 d-inline-block mb-3" style="background-color: var(--toba-secondary-light); color: var(--toba-primary);">
                        <i class="bi bi-award-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-2">4 Pilar Kurasi Adat</h5>
                    <p class="text-muted small mb-0">Verifikasi ketat terhadap penggunaan rempah endemik lokal (andaliman liar, asam cikala), transmisi resep turun-temurun, dan kebersihan penyajian.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-toba p-4 h-100">
                    <div class="p-3 rounded-4 d-inline-block mb-3" style="background-color: var(--toba-secondary-light); color: var(--toba-primary);">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Bobot Ulasan Warga Lokal 1.5x</h5>
                    <p class="text-muted small mb-0">Sistem Dual Rating memisahkan skor kenyamanan wisatawan (1.0x) dengan penilaian keaslian rasa dari warga lokal kawasan Danau Toba (1.5x).</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-toba p-4 h-100">
                    <div class="p-3 rounded-4 d-inline-block mb-3" style="background-color: var(--toba-secondary-light); color: var(--toba-primary);">
                        <i class="bi bi-compass-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Navigasi Peta Cerdas &amp; Hemat Data</h5>
                    <p class="text-muted small mb-0">Rancang itinerary kuliner yang terintegrasi langsung dengan titik wisata alam terdekat serta didukung mode peta vektor ringan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. ULASAN & KESAKSIAN RASA TERKINI -->
@if($ulasanTerkini->count() > 0)
<section class="py-4">
    <div class="container-toba">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">SUARA PENJELAJAH &amp; TETUA LOKAL</small>
                <h2 class="fw-bold mb-0">Ulasan &amp; Kesaksian Rasa Terkini</h2>
            </div>
        </div>

        <div class="row g-4">
            @foreach($ulasanTerkini as $ul)
                <div class="col-md-6">
                    <div class="card-toba p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $ul->user->nama ?? 'Penjelajah Rasa' }}</h6>
                                <small class="text-muted">Mengulas <strong>{{ $ul->destinasi->nama ?? '' }}</strong></small>
                            </div>
                            <span class="badge-otentik">★ {{ $ul->rating_umum }}.0 ({{ ucfirst($ul->tipe_penilai) }})</span>
                        </div>
                        <p class="text-muted small fst-italic mb-0">"{{ $ul->komentar }}"</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 7. DUAL CTA BANNER (WISATAWAN & MITRA UMKM) -->
<section class="py-5">
    <div class="container-toba">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-toba p-4 p-lg-5 text-white h-100 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #8B1E2D 0%, #58101A 100%);">
                    <div>
                        <span class="badge bg-warning text-dark mb-3">GORGA PATHFINDER</span>
                        <h3 class="font-headline text-white fw-bold mb-2">Mulai Rancang Petualangan Rasa Danau Toba Anda</h3>
                        <p class="text-white-50 small mb-4">Gabungkan destinasi kuliner legendaris dengan titik wisata alam kaldera dalam satu rencana perjalanan terstruktur.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('itinerary.index') }}" class="btn btn-warning fw-bold px-4 py-2">Susun Rencana Kunjungan</a>
                        <a href="{{ route('jelajah') }}" class="btn btn-outline-light px-4 py-2">Jelajah Direktori</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card-toba p-4 p-lg-5 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge-otentik mb-3 d-inline-block">PORTAL KEMITRAAN UMKM</span>
                        <h4 class="fw-bold mb-2">Miliki Kedai Warisan Kuliner Toba?</h4>
                        <p class="text-muted small mb-4">Daftarkan usaha kuliner Anda secara gratis, tampil di peta resmi wisatawan, dan ajukan akreditasi Label Otentik Batak.</p>
                    </div>
                    <a href="{{ route('umkm.register') }}" class="btn btn-toba-secondary w-100 py-2">Daftarkan Usaha Kuliner <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const previewMapEl = document.getElementById('homePreviewMap');
    if (previewMapEl) {
        const pMap = L.map('homePreviewMap', { scrollWheelZoom: false }).setView([2.6500, 98.8500], 9);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(pMap);

        fetch("{{ route('peta.data') }}")
            .then(r => r.json())
            .then(res => {
                res.data.forEach(item => {
                    L.circleMarker([item.latitude, item.longitude], {
                        radius: 8,
                        fillColor: item.is_otentik_batak ? '#C9A24B' : '#8B1E2D',
                        color: '#fff',
                        weight: 2,
                        fillOpacity: 0.95
                    }).addTo(pMap).bindPopup(`<strong>${item.nama}</strong><br><small>${item.kabupaten}</small>`);
                });
            });
    }
});
</script>
@endpush