<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $website?->nama_website ?? 'Tukang Print Dadakan')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
</head>
<body>
    @php
        $namaWebsite = $website?->nama_website ?? 'Tukang Print Dadakan';

        $logoUrl = $website?->logo
            ? \Illuminate\Support\Facades\Storage::url($website->logo)
            : asset('images/placeholder.png');

        $nomorWhatsapp = $website?->nomor_whatsapp;
        $nomorWhatsappClean = $nomorWhatsapp ? preg_replace('/[^0-9]/', '', $nomorWhatsapp) : null;

        if ($nomorWhatsappClean && str_starts_with($nomorWhatsappClean, '0')) {
            $nomorWhatsappClean = '62' . substr($nomorWhatsappClean, 1);
        }
    @endphp

    <header class="site-header">
        <div class="container header-wrapper">
            <a href="{{ route('home') }}" class="brand">
                <img src="{{ $logoUrl }}" alt="{{ $namaWebsite }}" class="brand-logo">
                <span>{{ $namaWebsite }}</span>
            </a>

            <button class="nav-toggle" type="button" aria-label="Buka menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="site-nav" id="siteNav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>

                <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">
                    Tentang Kami
                </a>

                <a href="{{ route('layanan.index') }}" class="{{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                    Layanan
                </a>

                <a href="{{ route('kontak.index') }}" class="{{ request()->routeIs('kontak.*') ? 'active' : '' }}">
                    Kontak
                </a>

                @auth
                    <a href="{{ route('customer.dashboard') }}" class="btn-nav">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-nav ghost">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="btn-nav">
                        Registrasi
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @if (session('success'))
            <div class="container">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container">
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @if ($nomorWhatsappClean)
        <a
            href="https://wa.me/{{ $nomorWhatsappClean }}"
            target="_blank"
            class="floating-wa"
            aria-label="Hubungi WhatsApp"
        >
            WA
        </a>
    @endif

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <div class="footer-brand">
                    <img src="{{ $logoUrl }}" alt="{{ $namaWebsite }}" class="footer-logo">
                    <strong>{{ $namaWebsite }}</strong>
                </div>

                <p class="footer-text">
                    Sistem pemesanan layanan cetak mahasiswa berbasis web untuk print dokumen, fotokopi, jilid, laminating, dan kebutuhan dokumen lainnya.
                </p>
            </div>

            <div>
                <h4>Navigasi</h4>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('tentang') }}">Tentang Kami</a>
                <a href="{{ route('layanan.index') }}">Layanan</a>
                <a href="{{ route('kontak.index') }}">Kontak</a>
            </div>

            <div>
                <h4>Kontak</h4>
                <p>{{ $website?->nomor_whatsapp ?? '-' }}</p>
                <p>{{ $website?->email ?? '-' }}</p>
                <p>{{ $website?->jam_operasional ?? '-' }}</p>
                <p>{{ $website?->alamat ?? '-' }}</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $namaWebsite }}. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('js/frontend.js') }}" defer></script>
</body>
</html>