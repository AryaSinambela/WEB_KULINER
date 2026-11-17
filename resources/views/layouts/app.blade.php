<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Toba Kuliner — Pemetaan & Kurasi Gastronomi Kaldera Toba')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    @vite(['resources/css/toba.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar Full Layar -->
    <nav class="navbar navbar-expand-lg navbar-toba sticky-top py-3">
        <div class="container-toba">
            <a class="navbar-brand d-flex align-items-center gap-2 font-headline fw-bold fs-4" href="{{ route('home') }}" style="color: var(--toba-primary);">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white" style="width: 34px; height: 34px; background-color: var(--toba-primary);">
                    <i class="bi bi-compass-fill fs-6"></i>
                </span>
                <span>Toba Kuliner</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navToba">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navToba">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link px-2 {{ request()->routeIs('home') ? 'active fw-bold text-danger' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 {{ request()->routeIs('peta*') ? 'active fw-bold text-danger' : '' }}" href="{{ route('peta') }}">Peta Kuliner</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 {{ request()->routeIs('jelajah*') ? 'active fw-bold text-danger' : '' }}" href="{{ route('jelajah') }}">Jelajah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 {{ request()->routeIs('itinerary*') ? 'active fw-bold text-danger' : '' }}" href="{{ route('itinerary.index') }}">Rencana Kunjungan</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-link text-decoration-none fw-semibold text-dark px-3">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-toba-primary px-4 py-2">Daftar</a>
                    @else
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-toba-secondary btn-sm px-3 py-2">
                                <i class="bi bi-shield-check me-1"></i> Konsol Admin
                            </a>
                        @elseif(auth()->user()->role === 'umkm')
                            <a href="{{ route('umkm.dashboard') }}" class="btn btn-toba-secondary btn-sm px-3 py-2">
                                <i class="bi bi-shop me-1"></i> Portal Mitra
                            </a>
                        @endif

                        <a href="{{ route('notifikasi.index') }}" class="btn btn-light rounded-circle position-relative border d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="bi bi-bell"></i>
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-toba-outlined btn-sm dropdown-toggle px-3 py-2 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-6"></i>
                                <span>{{ Str::limit(auth()->user()->nama, 16) }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2 mt-2">
                                @if(auth()->user()->role === 'wisatawan')
                                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('profil.edit') }}"><i class="bi bi-person me-2"></i>Profil &amp; Preferensi</a></li>
                                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('itinerary.index') }}"><i class="bi bi-map me-2"></i>Rencana Kunjungan Saya</a></li>
                                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('profil.ulasan') }}"><i class="bi bi-star me-2"></i>Riwayat Ulasan Saya</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-3 py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Notifikasi Flash Message -->
    @if(session('success') || session('error'))
        <div class="container-toba mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    <!-- Konten Utama -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer Full Layar -->
    <footer class="footer-toba pt-5 pb-4 mt-5">
        <div class="container-toba">
            <div class="row g-4 align-items-center pb-4 border-bottom border-light border-opacity-25">
                <div class="col-lg-5">
                    <h4 class="font-headline text-white fw-bold mb-2">Toba Kuliner</h4>
                    <p class="small text-white-50 mb-0">
                        Pusat kurasi warisan kuliner otentik kawasan Danau Toba. Merajut kearifan cita rasa lokal Batak, menjamin transparansi informasi, serta menghubungkan penikmat kuliner dengan tradisi leluhur.
                    </p>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-white fw-semibold mb-2">Eksplorasi Kawasan</h6>
                    <div class="d-flex flex-wrap gap-2 small text-white-50">
                        <span>Balige &amp; Toba</span> •
                        <span>Pesisir Samosir</span> •
                        <span>Parapat &amp; Simalungun</span> •
                        <span>Tarutung &amp; Taput</span> •
                        <span>Dolok Sanggul</span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.1);">
                        <div class="form-check form-switch d-flex align-items-center justify-content-between ps-0">
                            <div>
                                <label class="form-check-label fw-semibold small text-white d-block" for="globalLowBandwidth">
                                    <i class="bi bi-reception-2 me-1 text-warning"></i> Mode Hemat Data
                                </label>
                                <span class="text-white-50" style="font-size: 11px;">Optimasi peta &amp; gambar jaringan lambat</span>
                            </div>
                            <input class="form-check-input ms-2" type="checkbox" role="switch" id="globalLowBandwidth">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 small text-white-50">
                <span>&copy; {{ date('Y') }} Toba Kuliner — Kelompok 03 Sistem Informasi IT Del.</span>
                <div class="d-flex gap-3 mt-2 mt-md-0">
                    <a href="{{ route('tentang') }}" class="text-white-50 text-decoration-none">Tentang &amp; Bantuan</a>
                    <a href="{{ route('umkm.landing') }}" class="text-white-50 text-decoration-none">Portal Mitra UMKM</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const bwToggle = document.getElementById('globalLowBandwidth');
        if (bwToggle) {
            bwToggle.checked = localStorage.getItem('toba_low_bandwidth') === 'true';
            bwToggle.addEventListener('change', function () {
                localStorage.setItem('toba_low_bandwidth', this.checked);
                window.dispatchEvent(new CustomEvent('lowBandwidthChanged', { detail: this.checked }));
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. Sinkronisasi Mode Hemat Data
        const bwToggle = document.getElementById('globalLowBandwidth');
        if (bwToggle) {
            bwToggle.checked = localStorage.getItem('toba_low_bandwidth') === 'true';
            bwToggle.addEventListener('change', function () {
                localStorage.setItem('toba_low_bandwidth', this.checked);
                window.dispatchEvent(new CustomEvent('lowBandwidthChanged', { detail: this.checked }));
            });
        }

        // 2. Efek Pop-Up Otomatis untuk Semua Kartu di Semua Halaman
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card-toba');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, idx) => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = `${(idx % 4) * 60}ms`;
                        entry.target.classList.add('pop-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08 });

            cards.forEach(card => observer.observe(card));
        });
    </script>
    @stack('scripts')
</body>
</html>