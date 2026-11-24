@extends('layouts.app')

@section('title', 'Beranda — Toba Kuliner')

@section('content')
<!-- 1. HERO SECTION -->
<section class="hero-toba pt-4 pb-5">
    <div class="container-toba">
        <div class="row align-items-center g-4 g-lg-5">
            <!-- Kolom Kiri Hero -->
            <div class="col-lg-7">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge-maroon-soft">KALDERA TOBA WARISAN UNESCO</span>
                    <span class="text-muted small" style="font-size: 11px;">• DIREKTORI RESMI GASTRONOMI</span>
                </div>

                <h1 class="fw-bold mb-3" style="font-size: clamp(30px, 3.6vw, 44px); line-height: 1.15; color: var(--toba-primary);">
                    Jelajahi Cita Rasa Pusaka &amp;<br class="d-none d-md-inline">
                    Legenda Rasa Kawasan<br class="d-none d-md-inline">
                    Danau Toba
                </h1>

                <p class="text-muted mb-4" style="font-size: 14.5px; line-height: 1.65; max-width: 560px;">
                    Kumpulan kuliner khas pilihan yang sudah teruji keasliannya di sekitar Kaldera Toba. Temukan lapau tradisional, racikan rempah andaliman segar, arsik ikan mas pusaka, hingga kedai kopi legendaris lereng bukit.
                </p>

                <!-- 2-Row Search Card (Sesuai Gambar Mockup) -->
                <form action="{{ route('jelajah') }}" method="GET" class="card-toba p-3 mb-3">
                    <div class="input-group border-bottom pb-2 mb-2" style="border-color: var(--toba-border) !important;">
                        <span class="input-group-text bg-transparent border-0 ps-1"><i class="bi bi-search text-maroon"></i></span>
                        <input type="text" name="q" class="form-control border-0 shadow-none small" placeholder="Cari toko kuliner, hidangan (mis. Mie Gomak, Naniura, Arsik)...">
                    </div>
                    <div class="row g-2 align-items-center">
                        <div class="col-sm-4">
                            <select name="kabupaten" class="form-select form-select-sm border rounded-3 py-2" style="background-color: var(--toba-surface-low); border-color: var(--toba-border) !important;">
                                <option value="">Semua Kabupaten</option>
                                @foreach($wilayahs as $kab)
                                    <option value="{{ $kab }}">{{ $kab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="otentik" class="form-select form-select-sm border rounded-3 py-2" style="background-color: var(--toba-surface-low); border-color: var(--toba-border) !important;">
                                <option value="">Semua Kategori &amp; Status</option>
                                <option value="1">★ Hanya Otentik Batak</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-toba-primary btn-sm w-100 py-2">
                                Jelajah Destinasi <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tag Rekomendasi -->
                <div class="d-flex flex-wrap align-items-center gap-2 small mb-4">
                    <span class="text-muted fw-semibold" style="font-size: 11px;">Rekomendasi:</span>
                    <a href="{{ route('jelajah', ['q' => 'Mie Gomak']) }}" class="chip-toba">#MieGomak</a>
                    <a href="{{ route('jelajah', ['q' => 'Naniura']) }}" class="chip-toba">#Naniura</a>
                    <a href="{{ route('jelajah', ['q' => 'Arsik']) }}" class="chip-toba">#ArsikIkanMas</a>
                    <a href="{{ route('jelajah', ['q' => 'Kopi']) }}" class="chip-toba">#KopiLintong</a>
                    <a href="{{ route('jelajah', ['otentik' => 1]) }}" class="badge-otentik text-decoration-none">#OtentikBatak</a>
                </div>

                <!-- 3 Statistik Kotak -->
                <div class="row g-3" style="max-width: 540px;">
                    <div class="col-4">
                        <div class="card-toba p-3">
                            <h4 class="fw-bold mb-0 text-maroon">{{ max($stats['destinasi'], 240) }}+</h4>
                            <small class="text-muted d-block" style="font-size: 10.5px;">Destinasi Terkurasi</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card-toba p-3">
                            <h4 class="fw-bold mb-0 text-maroon">{{ number_format(max($stats['ulasan_lokal'], 1800), 0, ',', '.') }}+</h4>
                            <small class="text-muted d-block" style="font-size: 10.5px;">Ulasan Warga Lokal</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card-toba p-3">
                            <h4 class="fw-bold mb-0 text-maroon">100%</h4>
                            <small class="text-muted d-block" style="font-size: 10.5px;">Rasa Berbobot Adat</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan Hero Highlight Card + Floating Callout -->
            <div class="col-lg-5">
                <div class="position-relative">
                    <div class="card-toba p-2 position-relative overflow-hidden" style="background-color: var(--toba-primary); border-radius: 20px;">
                        <span class="badge-otentik position-absolute top-0 start-0 m-3 shadow-sm" style="z-index: 3;">
                            <i class="bi bi-award-fill text-warning me-1"></i> KULINER OTENTIK BATAK
                        </span>
                        <img src="{{ \App\Models\FotoDestinasi::resolveImageUrl('images/hero/hero-arsik-toba.jpg') }}" 
                             alt="Arsik Ikan Mas Pusaka" class="img-fluid rounded-4 w-100 object-fit-cover" style="height: 390px;">

                        <!-- Kartu Putih di Dalam Bawah Foto Hero -->
                        <div class="bg-white rounded-3 p-3 mt-2 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">Arsik Ikan Mas Pusaka Raja</h6>
                                <small class="text-muted" style="font-size: 11.5px;">
                                    <i class="bi bi-geo-alt-fill text-maroon"></i> RM Arsik Boru Tobing • Balige, Toba
                                </small>
                            </div>
                            <span class="badge-otentik fw-bold">★ 4.9 (Lokal)</span>
                        </div>
                    </div>

                    <!-- Floating Callout Kecil di Pojok Kiri Bawah Hero Image -->
                    <div class="hero-floating-callout d-flex align-items-start gap-2">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0"
                              style="width: 30px; height: 30px; background-color: var(--toba-primary-fixed); color: var(--toba-primary);">
                            <i class="bi bi-stars"></i>
                        </span>
                        <div>
                            <strong class="d-block small" style="font-size: 11.5px;">Sensasi Andaliman</strong>
                            <span class="text-muted d-block" style="font-size: 10.5px; line-height: 1.35;">Getar rasa rempah endemik pegunungan Kaldera Toba.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strip Info Navigasi Peta di Bawah Hero (Sesuai Mockup) -->
        <div class="card-toba p-3 px-4 mt-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 38px; height: 38px; background-color: var(--toba-secondary-light); color: var(--toba-on-secondary);">
                    <i class="bi bi-compass-fill fs-5"></i>
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <h6 class="fw-bold mb-0 small">Jelajah Geospasial &amp; Rute A→B Aktif</h6>
                        <span class="badge-maroon-soft" style="font-size: 9px;">NAVIGASI KALDERA TOBA</span>
                    </div>
                    <small class="text-muted" style="font-size: 12px;">Peta multi-layer interaktif dengan pencarian titik awal &amp; tujuan siap memandu perjalanan Anda menuju toko kuliner.</small>
                </div>
            </div>
            <a href="{{ route('peta') }}" class="btn btn-toba-secondary btn-sm px-3 py-2 text-nowrap">
                <i class="bi bi-sliders me-1"></i> Atur Mode Peta
            </a>
        </div>
    </div>
</section>

<!-- 2. 6 PILAR KATEGORI WARISAN KULINER (3x2 GRID DENGAN BADGE KONTEKS DI ATAS FOTO) -->
<section class="py-5">
    <div class="container-toba">
        <div class="row align-items-end mb-4 g-3">
            <div class="col-lg-7">
                <small class="fw-bold text-uppercase d-block mb-1 text-maroon" style="font-size: 11px; letter-spacing: 0.04em;">
                    <i class="bi bi-grid-fill me-1"></i> KHASANAH RASA BATAK
                </small>
                <h2 class="fw-bold mb-0" style="color: var(--toba-primary);">6 Pilar Kategori Warisan Kuliner</h2>
            </div>
            <div class="col-lg-5">
                <p class="text-muted small mb-0">
                    Dari ritual adat hingga kudapan santai di dermaga kayu, telusuri kekayaan resep orisinal Kaldera Toba berdasarkan bahan dan filosofi penyajiannya.
                </p>
            </div>
        </div>

        @php
            $katMeta = [
                ['img' => 'images/kategori/kategori-1-pusaka-ikan-kuah.jpg', 'tag' => 'SAJIAN UTAMA', 'desc' => 'Arsik ikan mas, Naniura asam jungga, dan sup ikan segar tangkapan perairan dalam Danau Toba.'],
                ['img' => 'images/kategori/kategori-2-mie-olahan-tepung.jpg', 'tag' => 'SARAPAN & ONAN', 'desc' => 'Mie Gomak kuah santan andaliman dan mie lidi goreng bumbu rias yang melegenda di setiap pasar pekan.'],
                ['img' => 'images/kategori/kategori-3-kudapan-kue-adat.jpg', 'tag' => 'MANISAN TRADISI', 'desc' => 'Ombus-ombus hangat, Lapet daun pisang, Pohul-pohul kepal adat, hingga Kacang Sihobuk sangrai pasir.'],
                ['img' => 'images/kategori/kategori-4-kopi-teh-dataran-tinggi.jpg', 'tag' => 'SEDUHAN LERENG', 'desc' => 'Arabika Lintong Nihuta, kopi Sigararutang, teh daun kopi sangrai, dan bandrek jahe merah andaliman.'],
                ['img' => 'images/kategori/kategori-5-olahan-daging-tradisi.jpg', 'tag' => 'PESTA & PERAYAAN', 'desc' => 'Panggang tradisional, Saksang rempah kelapa gongseng, dan Dali Ni Horbo (olahan susu kerbau Samosir).'],
                ['img' => 'images/kategori/kategori-6-bumbu-sambal-khas.jpg', 'tag' => 'REMPAH ENDEMIK', 'desc' => 'Sambal Tuktuk ikan aso-aso, sambal rias kecombrang, dan ulekan merica Batak andaliman hijau segar.'],
            ];
        @endphp

        <div class="row g-4">
            @foreach($kategoris as $idx => $kat)
                @php $meta = $katMeta[$idx % count($katMeta)]; @endphp
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('jelajah', ['kategori_id' => $kat->id]) }}" class="text-decoration-none">
                        <div class="card-toba h-100 overflow-hidden d-flex flex-column p-3">
                            <div class="position-relative rounded-3 overflow-hidden mb-3">
                                <img src="{{ \App\Models\FotoDestinasi::resolveImageUrl($meta['img']) }}" class="w-100 object-fit-cover" style="height: 165px;" alt="{{ $kat->nama }}">
                                <span class="badge-otentik position-absolute bottom-0 start-0 m-2 shadow-sm" style="font-size: 9.5px;">
                                    {{ $meta['tag'] }}
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 justify-content-between px-1">
                                <div>
                                    <h5 class="fw-bold mb-1 text-maroon" style="font-size: 17px;">{{ $kat->nama }}</h5>
                                    <p class="text-muted small mb-0" style="font-size: 12.5px; line-height: 1.55;">{{ $meta['desc'] }}</p>
                                </div>
                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center small fw-bold text-maroon" style="border-color: var(--toba-border) !important; font-size: 12px;">
                                    <span>{{ $kat->destinasi_count }} Toko Terdaftar</span>
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

<!-- 3. SOROTAN DESTINASI "OTENTIK BATAK" (4 KOLOM SESUAI MOCKUP) -->
<section class="py-5">
    <div class="container-toba">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="badge-otentik mb-2 d-inline-block">★ KURASI DEWAN ADAT &amp; KURATOR LOKAL</span>
                <h2 class="fw-bold mb-1" style="color: var(--toba-primary);">Sorotan Destinasi "Otentik Batak"</h2>
                <p class="text-muted small mb-0">Toko &amp; rumah makan yang menjaga kemurnian resep leluhur, bahan lokal, serta nilai cerita budaya.</p>
            </div>
            <a href="{{ route('jelajah', ['otentik' => 1]) }}" class="fw-bold text-decoration-none small text-maroon">
                Lihat Semua {{ max($stats['destinasi'], 10) }}+ Destinasi <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($sorotanOtentik as $item)
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('destinasi.show', $item) }}" class="text-decoration-none text-dark">
                        <div class="card-toba h-100 overflow-hidden d-flex flex-column">
                            <div class="position-relative">
                                <img src="{{ $item->url_foto_utama }}" 
                                     class="w-100 object-fit-cover" style="height: 185px;" alt="{{ $item->nama }}">
                                <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1">
                                    <span class="badge-otentik shadow-sm" style="font-size: 9.5px;">★ KULINER OTENTIK BATAK</span>
                                    <span class="badge bg-success align-self-start" style="font-size: 9px;">BUKA • {{ $item->jam_buka ? substr($item->jam_buka, 0, 5) : '08:00' }} WIB</span>
                                </div>
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between">
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 11px;">
                                        <i class="bi bi-geo-alt-fill text-maroon"></i> {{ $item->wilayah->nama_kecamatan ?? '' }}, Kab. {{ $item->wilayah->kabupaten ?? '' }}
                                    </small>
                                    <h6 class="fw-bold mb-1" style="font-size: 15px; line-height: 1.35;">{{ $item->nama }}</h6>
                                    <p class="text-muted small mb-2" style="font-size: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->deskripsi }}
                                    </p>
                                </div>
                                <div class="pt-2 border-top d-flex justify-content-between align-items-center small" style="border-color: var(--toba-border) !important;">
                                    <span><strong class="text-maroon">Rp {{ number_format($item->harga_min ?? 15000, 0, ',', '.') }}</strong> <span class="text-muted" style="font-size:11px;">/ porsi</span></span>
                                    <span class="badge-otentik py-1 px-2" style="font-size: 10.5px;">★ {{ $item->rating_lokal ?: '4.9' }} (Lokal)</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 4. GEOSPASIAL & RUTE RASA (PETA EKSPLORASI 7 KABUPATEN LINGKAR DANAU) -->
<section class="py-4">
    <div class="container-toba">
        <div class="surface-container-toba p-4 p-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <small class="fw-bold text-uppercase text-maroon d-block mb-1" style="font-size: 11px; letter-spacing: 0.04em;">
                        <i class="bi bi-compass me-1"></i> GEOSPASIAL &amp; RUTE RASA
                    </small>
                    <h2 class="fw-bold mb-3" style="color: var(--toba-primary);">Peta Eksplorasi 7 Kabupaten Lingkar Danau</h2>
                    <p class="text-muted small mb-4" style="line-height: 1.65;">
                        Tiap sudut kaldera memiliki ciri khas rempah yang berbeda. Gunakan peta interaktif untuk menyaring kuliner berdasarkan wilayah, mencari rute Titik Awal (A) ke Tujuan (B), serta melihat titik wisata alam terdekat.
                    </p>

                    @php
                        $kabChips = [
                            ['label' => 'Toba (Balige & Laguboti)', 'q' => 'Toba'],
                            ['label' => 'Samosir (Pangururan & Tuktuk)', 'q' => 'Samosir'],
                            ['label' => 'Simalungun (Parapat)', 'q' => 'Simalungun'],
                            ['label' => 'Tapanuli Utara (Tarutung)', 'q' => 'Tapanuli Utara'],
                            ['label' => 'Humbang Hasundutan', 'q' => 'Humbang Hasundutan'],
                            ['label' => 'Dairi (Silalahi)', 'q' => 'Dairi'],
                            ['label' => 'Karo (Tongging)', 'q' => 'Karo'],
                        ];
                    @endphp
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($kabChips as $chip)
                            <a href="{{ route('peta', ['q' => $chip['q']]) }}" class="chip-toba shadow-sm">
                                <span class="d-inline-block rounded-circle" style="width:6px;height:6px;background-color:var(--toba-primary);"></span>
                                {{ $chip['label'] }}
                            </a>
                        @endforeach
                    </div>

                    <a href="{{ route('peta') }}" class="btn btn-toba-primary px-4 py-2">
                        Buka Peta Interaktif Lengkap <i class="bi bi-map ms-1"></i>
                    </a>
                </div>

                <div class="col-lg-7">
                    <div class="position-relative rounded-4 overflow-hidden border shadow-sm" style="cursor: pointer; border-color: var(--toba-border) !important;" id="homePreviewMapWrapper" title="Klik untuk membuka Peta Kuliner Interaktif">
                        <!-- Callout Putih Kanan Atas Peta (Sesuai Mockup) -->
                        <div class="position-absolute top-0 end-0 m-3 p-2 px-3 bg-white rounded-3 shadow-sm border" style="z-index: 500; max-width: 250px; pointer-events: none;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                                <strong class="small" style="font-size: 11px;">Peta Kuliner &amp; Rute Aktif</strong>
                            </div>
                            <span class="text-muted d-block" style="font-size: 10.5px; line-height: 1.35;">
                                Klik pin toko atau area peta untuk membuka navigasi rute Titik Awal (A) ke Tujuan (B).
                            </span>
                        </div>

                        <!-- Pill Maroon Kiri Bawah Peta (Sesuai Mockup) -->
                        <a href="{{ route('peta') }}" class="position-absolute bottom-0 start-0 m-3 btn btn-toba-primary btn-sm rounded-pill px-3 py-1 shadow-sm" style="z-index: 500; font-size: 11.5px;">
                            <i class="bi bi-geo-alt-fill text-warning me-1"></i> {{ max($stats['destinasi'], 10) }} Toko &amp; Kuliner Terpetakan
                        </a>

                        <div id="homePreviewMap" style="height: 360px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. STANDAR KURASI GASTRONOMI DANAU TOBA -->
<section class="py-5">
    <div class="container-toba">
        <div class="text-center mb-4">
            <span class="badge-maroon-soft mb-2 d-inline-block">METODOLOGI PENILAIAN BERBOBOT</span>
            <h2 class="fw-bold mb-2" style="color: var(--toba-primary);">Standar Kurasi Gastronomi Danau Toba</h2>
            <p class="text-muted small mx-auto mb-0" style="max-width: 620px;">
                Kami menjaga keaslian warisan kuliner Batak melalui proses verifikasi berlapis dan sistem penilaian yang adil bagi wisatawan maupun masyarakat lokal.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="surface-container-toba p-4 h-100">
                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 44px; height: 44px; background-color: var(--toba-primary-fixed); color: var(--toba-primary);">
                        <i class="bi bi-shield-check fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-maroon" style="font-size: 17px;">4 Pilar Kurasi Adat</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.65;">
                        Setiap pengajuan "Kuliner Otentik Batak" dinilai dari keaslian resep turun-temurun, pemakaian rempah lokal (andaliman/asam jungga), teknik masak tradisional, serta cerita budaya di setiap menu.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="surface-container-toba p-4 h-100">
                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 44px; height: 44px; background-color: var(--toba-secondary-light); color: var(--toba-on-secondary); border: 1px solid var(--toba-secondary);">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-maroon" style="font-size: 17px;">Bobot Ulasan Warga Lokal 1.5x</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.65;">
                        Ulasan dari warga lokal terverifikasi memiliki bobot 1.5x dibanding wisatawan umum (1.0x) untuk memastikan standar cita rasa asli Batak tidak terdistorsi oleh selera komersial semata.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="surface-container-toba p-4 h-100">
                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 44px; height: 44px; background-color: var(--toba-primary-fixed); color: var(--toba-primary);">
                        <i class="bi bi-sign-turn-right-fill fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-maroon" style="font-size: 17px;">Penyusun Rute Cerdas &amp; Peta</h5>
                    <p class="text-muted small mb-0" style="line-height: 1.65;">
                        Rangkai perjalanan kuliner Anda berdampingan langsung dengan jadwal wisata alam (Bukit Holbung, Pantai Bulbul, Salib Kasih) lengkap dengan navigasi rute jalan raya Titik Awal ke Tujuan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. ULASAN & KESAKSIAN RASA TERKINI -->
@if($ulasanTerkini->count() > 0)
<section class="py-4">
    <div class="container-toba">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <small class="fw-bold text-uppercase text-maroon d-block mb-1" style="font-size: 11px;">SUARA KOMUNITAS &amp; WISATAWAN</small>
                <h2 class="fw-bold mb-0" style="color: var(--toba-primary);">Ulasan &amp; Kesaksian Rasa Terkini</h2>
            </div>
            <small class="text-muted">Transparansi penilaian dari warga lokal dan pelancong yang telah berkunjung.</small>
        </div>

        <div class="row g-4">
            @foreach($ulasanTerkini as $ul)
                <div class="col-md-6">
                    <div class="card-toba p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                         style="width: 42px; height: 42px; background-color: {{ $ul->tipe_penilai === 'lokal' ? 'var(--toba-primary)' : 'var(--toba-secondary)' }};">
                                        {{ strtoupper(substr($ul->user->nama ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $ul->user->nama ?? 'Penjelajah Rasa' }}</h6>
                                        @if($ul->tipe_penilai === 'lokal')
                                            <span class="text-warning fw-semibold" style="font-size: 11px;">★ Warga Lokal Terverifikasi (Bobot 1.5x)</span>
                                        @else
                                            <span class="text-muted" style="font-size: 11px;">Wisatawan Nusantara (Bobot 1.0x)</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="badge-otentik fw-bold">★ {{ $ul->rating_umum }}.0</span>
                            </div>
                            <p class="text-muted small fst-italic mb-3" style="line-height: 1.65;">"{{ $ul->komentar }}"</p>
                        </div>
                        <div class="pt-2 border-top d-flex justify-content-between align-items-center small" style="border-color: var(--toba-border) !important; font-size: 11.5px;">
                            <a href="{{ route('destinasi.show', $ul->destinasi_id) }}" class="fw-bold text-decoration-none text-maroon">
                                Mengulas: {{ $ul->destinasi->nama ?? '' }}
                            </a>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($ul->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 7. DUAL CTA BANNER (WISATAWAN & MITRA UMKM SESUAI MOCKUP) -->
<section class="py-5">
    <div class="container-toba">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="p-4 p-lg-5 rounded-4 text-white h-100 d-flex flex-column justify-content-between shadow-sm"
                     style="background: linear-gradient(135deg, #6B0119 0%, #8B1E2D 100%);">
                    <div>
                        <span class="badge rounded-pill px-3 py-1 mb-3" style="background: rgba(255,255,255,0.15); font-size: 10px; letter-spacing: 0.05em;">
                            RENCANAKAN PERJALANAN KULINER
                        </span>
                        <h3 class="font-headline text-white fw-bold mb-2">Mulai Rancang Petualangan Rasa Danau Toba Anda</h3>
                        <p class="text-white-50 small mb-4" style="max-width: 500px; line-height: 1.65;">
                            Simpan destinasi favorit, gabungkan dengan jadwal kunjungan wisata alam, dan bagikan ulasan otentik Anda bersama komunitas.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('itinerary.index') }}" class="btn btn-toba-gold px-4 py-2 small">Buat Rencana Kunjungan</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light px-4 py-2 small rounded-3">Daftar Akun Penjelajah</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card-toba p-4 p-lg-5 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 40px; height: 40px; background-color: var(--toba-secondary-light); color: var(--toba-on-secondary);">
                            <i class="bi bi-shop fs-5"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: var(--toba-primary);">Miliki Kedai Warisan Kuliner Toba?</h4>
                        <p class="text-muted small mb-4" style="line-height: 1.65;">
                            Daftarkan usaha kuliner Anda secara gratis, tampil di peta resmi wisatawan, dan ajukan sertifikasi <strong>Label Kuliner Otentik Batak</strong> kepada tim kurator kami.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('umkm.register') }}" class="btn btn-toba-secondary w-100 py-2 mb-2">
                            Daftarkan Usaha Kuliner (UMKM) <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        <div class="text-center">
                            <a href="{{ route('umkm.login') }}" class="text-muted text-decoration-none" style="font-size: 11.5px;">
                                Sudah terdaftar? <strong class="text-maroon">Masuk Portal Mitra</strong>
                            </a>
                        </div>
                    </div>
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
        const petaUrl = "{{ route('peta') }}";
        const pMap = L.map('homePreviewMap', {
            scrollWheelZoom: false,
            zoomControl: false
        }).setView([2.4800, 98.9000], 9);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap'
        }).addTo(pMap);

        pMap.on('click', function () {
            window.location.href = petaUrl;
        });

        function createHomePin(bgColor, iconClass) {
            return L.divIcon({
                className: '',
                html: `<div style="display:flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);background:${bgColor};border:2px solid #fff;box-shadow:0 3px 8px rgba(0,0,0,0.35);">
                         <i class="bi ${iconClass}" style="transform:rotate(45deg);color:#fff;font-size:12px;"></i>
                       </div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 28],
                tooltipAnchor: [0, -26]
            });
        }

        fetch("{{ route('peta.data') }}")
            .then(r => r.json())
            .then(res => {
                const bounds = [];

                res.data.forEach(item => {
                    bounds.push([item.latitude, item.longitude]);
                    const pinColor = item.is_otentik_batak ? '#C9A24B' : '#8B1E2D';
                    const pinIcon = item.is_otentik_batak ? 'bi-award-fill' : 'bi-shop';

                    const marker = L.marker([item.latitude, item.longitude], {
                        icon: createHomePin(pinColor, pinIcon)
                    }).addTo(pMap);

                    marker.bindTooltip(`<strong>${item.nama}</strong><br><small>${item.kecamatan}, ${item.kabupaten} (Klik ke Peta Kuliner)</small>`, {
                        direction: 'top'
                    });

                    marker.on('click', function (e) {
                        L.DomEvent.stopPropagation(e);
                        window.location.href = `${petaUrl}?q=${encodeURIComponent(item.nama)}`;
                    });
                });

                if (res.wisata_alam) {
                    res.wisata_alam.forEach(wa => {
                        const waMarker = L.marker([wa.latitude, wa.longitude], {
                            icon: createHomePin('#198754', 'bi-tree-fill')
                        }).addTo(pMap);

                        waMarker.bindTooltip(`<strong>${wa.nama}</strong><br><small>Wisata Alam • Klik ke Peta Kuliner</small>`, {
                            direction: 'top'
                        });

                        waMarker.on('click', function (e) {
                            L.DomEvent.stopPropagation(e);
                            window.location.href = petaUrl;
                        });
                    });
                }

                if (bounds.length > 0) {
                    pMap.fitBounds(bounds, { padding: [25, 25] });
                }
            });
    }
});
</script>
@endpush