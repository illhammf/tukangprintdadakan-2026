<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Pelanggan - Tukang Print Dadakan')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
</head>
<body>
    @php
        $namaWebsite = $website?->nama_website ?? 'Tukang Print Dadakan';

        $logoUrl = $website?->logo
            ? \Illuminate\Support\Facades\Storage::url($website->logo)
            : asset('images/placeholder.png');
    @endphp

    <header class="customer-header">
        <div class="container customer-header-wrapper">
            <a href="{{ route('home') }}" class="brand">
                <img src="{{ $logoUrl }}" alt="{{ $namaWebsite }}" class="brand-logo">
                <span>{{ $namaWebsite }}</span>
            </a>

            <button class="nav-toggle" type="button" aria-label="Buka menu pelanggan">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="customer-nav" id="siteNav">
                <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('customer.pesanan.create') }}" class="{{ request()->routeIs('customer.pesanan.create') ? 'active' : '' }}">
                    Buat Pesanan
                </a>

                <a href="{{ route('customer.pesanan.index') }}" class="{{ request()->routeIs('customer.pesanan.index') || request()->routeIs('customer.pesanan.show') ? 'active' : '' }}">
                    Pesanan Saya
                </a>

                <a href="{{ route('customer.profil.edit') }}" class="{{ request()->routeIs('customer.profil.*') ? 'active' : '' }}">
                    Profil
                </a>

                <a href="{{ route('home') }}">
                    Website
                </a>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-nav danger">
                        Logout
                    </button>
                </form>
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

    <script src="{{ asset('js/frontend.js') }}" defer></script>
</body>
</html>