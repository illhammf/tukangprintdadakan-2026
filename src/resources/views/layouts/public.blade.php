<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta name="theme-color" content="#155eef">

    <meta
        name="description"
        content="Tukang Print Dadakan menyediakan layanan print mahasiswa yang cepat, mudah, dan dapat dipesan secara online."
    >

    <title>
        @yield(
            'title',
            $website?->nama_website ?? 'Tukang Print Dadakan'
        )
    </title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link
        rel="stylesheet"
        href="{{ asset('css/frontend.css') }}"
    >

    @stack('styles')

    <style>
        :root {
            --public-blue: #155eef;
            --public-blue-dark: #1046b8;
            --public-blue-soft: #edf4ff;

            --public-orange: #f97316;
            --public-orange-dark: #c2410c;
            --public-orange-soft: #fff1e7;

            --public-dark: #101828;
            --public-text: #344054;
            --public-muted: #667085;

            --public-white: #ffffff;
            --public-background: #f7f9fc;
            --public-border: #e4e7ec;

            --public-success: #16a34a;
            --public-danger: #dc2626;

            --public-shadow:
                0 18px 50px rgba(16, 24, 40, 0.10);
        }

        body.public-layout {
            min-height: 100vh;
            margin: 0;
            color: var(--public-dark);
            background: var(--public-background);
            font-family:
                Inter,
                ui-sans-serif,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        body.public-layout.nav-open {
            overflow: hidden;
        }

        .public-layout *,
        .public-layout *::before,
        .public-layout *::after {
            box-sizing: border-box;
        }

        .public-layout a {
            text-decoration: none;
        }

        .public-skip-link {
            position: fixed;
            top: 12px;
            left: 12px;
            z-index: 9999;
            padding: 10px 16px;
            border-radius: 10px;
            color: var(--public-white);
            background: var(--public-blue);
            transform: translateY(-150%);
            transition: transform 0.2s ease;
        }

        .public-skip-link:focus {
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Top Bar
        |--------------------------------------------------------------------------
        */

        .public-topbar {
            position: relative;
            z-index: 52;
            color: var(--public-white);
            background:
                linear-gradient(
                    90deg,
                    var(--public-blue-dark),
                    var(--public-blue)
                );
        }

        .public-topbar-wrapper {
            min-height: 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .public-topbar-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .public-status-dot {
            width: 8px;
            height: 8px;
            flex: 0 0 8px;
            border-radius: 999px;
            background: #4ade80;
            box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.18);
        }

        .public-topbar-contact {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .public-topbar-contact span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        .public-header {
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid var(--public-border);
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(18px);
            box-shadow: 0 1px 0 rgba(16, 24, 40, 0.03);
        }

        .public-header::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 3px;
            background:
                linear-gradient(
                    90deg,
                    var(--public-blue) 0%,
                    var(--public-blue) 72%,
                    var(--public-orange) 72%,
                    var(--public-orange) 100%
                );
        }

        .public-header-wrapper {
            min-height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
        }

        .public-brand {
            min-width: 0;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .public-brand-logo {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            padding: 3px;
            object-fit: contain;
            border: 1px solid var(--public-border);
            border-radius: 16px;
            background: var(--public-white);
            box-shadow: 0 6px 18px rgba(16, 24, 40, 0.08);
        }

        .public-brand-copy {
            min-width: 0;
        }

        .public-brand-name {
            display: block;
            overflow: hidden;
            color: var(--public-dark);
            font-size: 18px;
            font-weight: 900;
            line-height: 1.2;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .public-brand-tagline {
            display: block;
            margin-top: 3px;
            color: var(--public-muted);
            font-size: 11px;
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | Navigation
        |--------------------------------------------------------------------------
        */

        .public-nav {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .public-nav-link {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 13px;
            border-radius: 12px;
            color: var(--public-text);
            font-size: 14px;
            font-weight: 800;
            transition:
                color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .public-nav-link:hover {
            color: var(--public-blue);
            background: var(--public-blue-soft);
        }

        .public-nav-link.active {
            color: var(--public-blue);
            background: var(--public-blue-soft);
        }

        .public-nav-link.active::after {
            content: "";
            width: 6px;
            height: 6px;
            margin-left: 7px;
            border-radius: 999px;
            background: var(--public-orange);
        }

        .public-nav-actions {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-left: 10px;
            padding-left: 14px;
            border-left: 1px solid var(--public-border);
        }

        .public-user-greeting {
            max-width: 180px;
            overflow: hidden;
            padding: 9px 12px;
            border-radius: 11px;
            color: var(--public-blue);
            background: var(--public-blue-soft);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .public-nav-button {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 17px;
            border: 1px solid transparent;
            border-radius: 13px;
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .public-nav-button:hover {
            transform: translateY(-1px);
        }

        .public-nav-button.primary {
            color: var(--public-white);
            background:
                linear-gradient(
                    135deg,
                    var(--public-blue),
                    #2870ff
                );
            box-shadow: 0 8px 20px rgba(21, 94, 239, 0.22);
        }

        .public-nav-button.primary:hover {
            background:
                linear-gradient(
                    135deg,
                    var(--public-blue-dark),
                    var(--public-blue)
                );
        }

        .public-nav-button.outline {
            color: var(--public-blue);
            border-color: #b9d0ff;
            background: var(--public-white);
        }

        .public-nav-button.outline:hover {
            border-color: var(--public-blue);
            background: var(--public-blue-soft);
        }

        .public-nav-button.danger {
            color: var(--public-white);
            background: var(--public-danger);
        }

        .public-logout-form {
            margin: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Hamburger
        |--------------------------------------------------------------------------
        */

        .public-nav-toggle {
            width: 46px;
            height: 46px;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            padding: 0;
            border: 0;
            border-radius: 14px;
            background:
                linear-gradient(
                    135deg,
                    var(--public-blue),
                    #2870ff
                );
            box-shadow: 0 8px 20px rgba(21, 94, 239, 0.22);
            cursor: pointer;
        }

        .public-nav-toggle span {
            width: 21px;
            height: 2px;
            display: block;
            border-radius: 999px;
            background: var(--public-white);
            transition:
                transform 0.2s ease,
                opacity 0.2s ease;
        }

        .public-nav-toggle.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .public-nav-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .public-nav-toggle.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .public-nav-backdrop {
            position: fixed;
            inset: 0;
            z-index: 44;
            visibility: hidden;
            opacity: 0;
            background: rgba(16, 24, 40, 0.56);
            backdrop-filter: blur(4px);
            transition:
                opacity 0.2s ease,
                visibility 0.2s ease;
        }

        /*
        |--------------------------------------------------------------------------
        | Flash Message
        |--------------------------------------------------------------------------
        */

        .public-flash-wrapper {
            position: relative;
            z-index: 10;
            padding-top: 18px;
        }

        .public-flash {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border: 1px solid transparent;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 750;
            box-shadow: 0 8px 24px rgba(16, 24, 40, 0.07);
        }

        .public-flash-icon {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--public-white);
            font-size: 13px;
            font-weight: 900;
        }

        .public-flash.success {
            color: #166534;
            border-color: #bbf7d0;
            background: #ecfdf3;
        }

        .public-flash.success .public-flash-icon {
            background: var(--public-success);
        }

        .public-flash.error {
            color: #991b1b;
            border-color: #fecaca;
            background: #fff1f2;
        }

        .public-flash.error .public-flash-icon {
            background: var(--public-danger);
        }

        /*
        |--------------------------------------------------------------------------
        | WhatsApp
        |--------------------------------------------------------------------------
        */

        .public-floating-wa {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 60;
            width: 58px;
            height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--public-white);
            border-radius: 999px;
            color: var(--public-white);
            background: #22c55e;
            box-shadow:
                0 18px 40px rgba(34, 197, 94, 0.36),
                0 0 0 1px rgba(16, 24, 40, 0.04);
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .public-floating-wa:hover {
            transform: translateY(-4px) scale(1.04);
            box-shadow:
                0 24px 48px rgba(34, 197, 94, 0.42),
                0 0 0 1px rgba(16, 24, 40, 0.04);
        }

        .public-floating-wa::before {
            content: "Hubungi Admin";
            position: absolute;
            right: 68px;
            padding: 8px 11px;
            border-radius: 9px;
            color: var(--public-white);
            background: var(--public-dark);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
            visibility: hidden;
            opacity: 0;
            transform: translateX(6px);
            transition:
                opacity 0.2s ease,
                transform 0.2s ease,
                visibility 0.2s ease;
        }

        .public-floating-wa:hover::before {
            visibility: visible;
            opacity: 1;
            transform: translateX(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Footer CTA
        |--------------------------------------------------------------------------
        */

        .public-footer {
            position: relative;
            margin-top: 60px;
            color: var(--public-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.20),
                    transparent 30%
                ),
                radial-gradient(
                    circle at bottom left,
                    rgba(21, 94, 239, 0.22),
                    transparent 34%
                ),
                #101828;
        }

        .public-footer-cta-wrapper {
            position: relative;
            top: -38px;
        }

        .public-footer-cta {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 24px;
            padding: 30px 34px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 24px;
            background:
                linear-gradient(
                    135deg,
                    var(--public-blue),
                    #246bfd 68%,
                    var(--public-orange)
                );
            box-shadow: var(--public-shadow);
        }

        .public-footer-cta-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 7px;
            color: #dbeafe;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .public-footer-cta-label::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #fdba74;
        }

        .public-footer-cta h2 {
            margin: 0 0 6px;
            color: var(--public-white);
            font-size: clamp(24px, 4vw, 34px);
            line-height: 1.15;
        }

        .public-footer-cta p {
            max-width: 660px;
            margin: 0;
            color: #eaf2ff;
            font-size: 14px;
        }

        .public-footer-cta-button {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 20px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 14px;
            color: var(--public-blue-dark);
            background: var(--public-white);
            font-size: 14px;
            font-weight: 900;
            white-space: nowrap;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .public-footer-cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(16, 24, 40, 0.22);
        }

        /*
        |--------------------------------------------------------------------------
        | Footer Content
        |--------------------------------------------------------------------------
        */

        .public-footer-grid {
            display: grid;
            grid-template-columns: 1.25fr 0.65fr 1fr;
            gap: 42px;
            padding: 18px 0 46px;
        }

        .public-footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .public-footer-logo {
            width: 48px;
            height: 48px;
            padding: 3px;
            object-fit: contain;
            border-radius: 15px;
            background: var(--public-white);
        }

        .public-footer-brand strong {
            display: block;
            font-size: 18px;
        }

        .public-footer-brand small {
            display: block;
            margin-top: 2px;
            color: #94a3b8;
            font-size: 11px;
        }

        .public-footer-description {
            max-width: 470px;
            margin: 18px 0 0;
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.75;
        }

        .public-footer-heading {
            margin: 0 0 16px;
            color: var(--public-white);
            font-size: 15px;
        }

        .public-footer-links {
            display: grid;
            gap: 10px;
        }

        .public-footer-links a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #cbd5e1;
            font-size: 14px;
            transition:
                color 0.2s ease,
                transform 0.2s ease;
        }

        .public-footer-links a::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 999px;
            background: var(--public-orange);
        }

        .public-footer-links a:hover {
            color: var(--public-white);
            transform: translateX(3px);
        }

        .public-footer-contact {
            display: grid;
            gap: 12px;
        }

        .public-footer-contact-item {
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 13px;
            background: rgba(255, 255, 255, 0.05);
        }

        .public-footer-contact-item span {
            display: block;
            margin-bottom: 3px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .public-footer-contact-item strong {
            display: block;
            color: #e2e8f0;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .public-footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.10);
        }

        .public-footer-bottom-wrapper {
            min-height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            color: #94a3b8;
            font-size: 12px;
        }

        .public-footer-bottom-wrapper p {
            margin: 0;
        }

        .public-footer-accent {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .public-footer-accent::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--public-orange);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1020px) {
            .public-brand-tagline {
                display: none;
            }

            .public-nav {
                gap: 1px;
            }

            .public-nav-link {
                padding-right: 9px;
                padding-left: 9px;
            }

            .public-user-greeting {
                display: none;
            }
        }

        @media (max-width: 900px) {
            .public-topbar {
                display: none;
            }

            .public-header-wrapper {
                min-height: 74px;
            }

            .public-nav-toggle {
                display: inline-flex;
            }

            .public-nav {
                position: fixed;
                top: 88px;
                right: 16px;
                left: 16px;
                z-index: 46;
                max-height: calc(100vh - 110px);
                display: none;
                align-items: stretch;
                flex-direction: column;
                gap: 6px;
                overflow-y: auto;
                padding: 17px;
                border: 1px solid var(--public-border);
                border-radius: 22px;
                background: var(--public-white);
                box-shadow: var(--public-shadow);
            }

            .public-nav.open {
                display: flex;
            }

            body.public-layout.nav-open .public-nav-backdrop {
                visibility: visible;
                opacity: 1;
            }

            .public-nav-link {
                width: 100%;
                min-height: 48px;
                justify-content: flex-start;
                padding: 12px 14px;
            }

            .public-nav-actions {
                width: 100%;
                display: grid;
                gap: 9px;
                margin: 8px 0 0;
                padding: 15px 0 0;
                border-top: 1px solid var(--public-border);
                border-left: 0;
            }

            .public-user-greeting {
                display: block;
                max-width: none;
                text-align: center;
            }

            .public-nav-button,
            .public-logout-form,
            .public-logout-form button {
                width: 100%;
            }

            .public-footer-cta {
                grid-template-columns: 1fr;
            }

            .public-footer-cta-button {
                width: 100%;
            }

            .public-footer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .public-footer-grid > div:first-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 640px) {
            .public-header-wrapper {
                min-height: 70px;
            }

            .public-brand-logo {
                width: 43px;
                height: 43px;
                flex-basis: 43px;
                border-radius: 14px;
            }

            .public-brand-name {
                max-width: 195px;
                font-size: 16px;
            }

            .public-nav {
                top: 82px;
                right: 12px;
                left: 12px;
                max-height: calc(100vh - 96px);
            }

            .public-flash-wrapper {
                padding-top: 12px;
            }

            .public-footer {
                margin-top: 48px;
            }

            .public-footer-cta-wrapper {
                top: -28px;
            }

            .public-footer-cta {
                padding: 24px 20px;
                border-radius: 20px;
            }

            .public-footer-cta h2 {
                font-size: 25px;
            }

            .public-footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
                padding-bottom: 36px;
            }

            .public-footer-grid > div:first-child {
                grid-column: auto;
            }

            .public-footer-bottom-wrapper {
                align-items: flex-start;
                flex-direction: column;
                justify-content: center;
                gap: 7px;
                padding: 16px 0;
            }

            .public-floating-wa {
                right: 15px;
                bottom: 15px;
                width: 54px;
                height: 54px;
            }

            .public-floating-wa::before {
                display: none;
            }
        }
    </style>
</head>

<body class="public-layout">
    <a href="#main-content" class="public-skip-link">
        Lewati ke konten utama
    </a>

    @php
        $namaWebsite = $website?->nama_website
            ?? 'Tukang Print Dadakan';

        $logoUrl = $website?->logo
            ? \Illuminate\Support\Facades\Storage::url($website->logo)
            : asset('images/placeholder.png');

        $nomorWhatsapp = $website?->nomor_whatsapp;

        $nomorWhatsappClean = $nomorWhatsapp
            ? preg_replace('/[^0-9]/', '', $nomorWhatsapp)
            : null;

        if (
            $nomorWhatsappClean
            && str_starts_with($nomorWhatsappClean, '0')
        ) {
            $nomorWhatsappClean =
                '62' . substr($nomorWhatsappClean, 1);
        }

        if (
            $nomorWhatsappClean
            && str_starts_with($nomorWhatsappClean, '8')
        ) {
            $nomorWhatsappClean =
                '62' . $nomorWhatsappClean;
        }

        $tujuanPesanan = auth()->check()
            ? route('customer.pesanan.create')
            : route('login');
    @endphp

    <div
        class="public-nav-backdrop"
        data-nav-close
        aria-hidden="true"
    ></div>

    <div class="public-topbar">
        <div class="container public-topbar-wrapper">
            <div class="public-topbar-status">
                <span class="public-status-dot"></span>

                <span>
                    Pemesanan online sedang aktif
                </span>
            </div>

            <div class="public-topbar-contact">
                @if ($website?->jam_operasional)
                    <span>
                        Jam operasional:
                        {{ $website->jam_operasional }}
                    </span>
                @endif

                @if ($website?->nomor_whatsapp)
                    <span>
                        WhatsApp:
                        {{ $website->nomor_whatsapp }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <header class="public-header">
        <div class="container public-header-wrapper">
            <a
                href="{{ route('home') }}"
                class="public-brand"
                aria-label="Beranda {{ $namaWebsite }}"
            >
                <img
                    src="{{ $logoUrl }}"
                    alt="Logo {{ $namaWebsite }}"
                    class="public-brand-logo"
                >

                <span class="public-brand-copy">
                    <strong class="public-brand-name">
                        {{ $namaWebsite }}
                    </strong>

                    <small class="public-brand-tagline">
                        Print cepat untuk kebutuhan mahasiswa
                    </small>
                </span>
            </a>

            <button
                class="nav-toggle public-nav-toggle"
                type="button"
                aria-label="Buka menu navigasi"
                aria-controls="siteNav"
                aria-expanded="false"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav
                class="public-nav"
                id="siteNav"
                aria-label="Navigasi utama"
            >
                <a
                    href="{{ route('home') }}"
                    class="public-nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                >
                    Beranda
                </a>

                <a
                    href="{{ route('tentang') }}"
                    class="public-nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}"
                >
                    Tentang Kami
                </a>

                <a
                    href="{{ route('layanan.index') }}"
                    class="public-nav-link {{ request()->routeIs('layanan.*') ? 'active' : '' }}"
                >
                    Layanan
                </a>

                <a
                    href="{{ route('kontak.index') }}"
                    class="public-nav-link {{ request()->routeIs('kontak.*') ? 'active' : '' }}"
                >
                    Kontak
                </a>

                <div class="public-nav-actions">
                    @auth
                        <span class="public-user-greeting">
                            Halo,
                            {{ \Illuminate\Support\Str::limit(
                                auth()->user()->name,
                                18
                            ) }}
                        </span>

                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="public-nav-button primary"
                        >
                            Dashboard
                        </a>

                        <form
                            action="{{ route('logout') }}"
                            method="POST"
                            class="public-logout-form"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="public-nav-button danger"
                            >
                                Logout
                            </button>
                        </form>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="public-nav-button outline"
                        >
                            Login
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="public-nav-button primary"
                        >
                            Registrasi
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <main id="main-content" tabindex="-1">
        @if (session('success'))
            <div class="container public-flash-wrapper">
                <div class="public-flash success">
                    <span class="public-flash-icon">
                        ✓
                    </span>

                    <span>
                        {{ session('success') }}
                    </span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container public-flash-wrapper">
                <div class="public-flash error">
                    <span class="public-flash-icon">
                        !
                    </span>

                    <span>
                        {{ session('error') }}
                    </span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @if ($nomorWhatsappClean)
        <a
            href="https://wa.me/{{ $nomorWhatsappClean }}"
            target="_blank"
            rel="noopener noreferrer"
            class="public-floating-wa"
            aria-label="Hubungi admin melalui WhatsApp"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="27"
                height="27"
                viewBox="0 0 24 24"
                fill="currentColor"
                aria-hidden="true"
            >
                <path d="M12.04 2a9.84 9.84 0 0 0-8.45 14.87L2 22l5.28-1.55A9.96 9.96 0 1 0 12.04 2Zm0 17.94a8.08 8.08 0 0 1-4.12-1.13l-.3-.18-3.13.92.93-3.05-.2-.31a8.04 8.04 0 1 1 6.82 3.75Zm4.42-6.03c-.24-.12-1.44-.71-1.66-.79-.22-.08-.38-.12-.54.12-.16.24-.62.79-.76.95-.14.16-.28.18-.52.06-.24-.12-1.02-.38-1.94-1.2-.72-.64-1.2-1.43-1.34-1.67-.14-.24-.01-.37.1-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.19-.47-.39-.41-.54-.42h-.46c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.69 2.58 4.1 3.62.57.25 1.02.4 1.37.51.58.18 1.1.16 1.51.1.46-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28Z"/>
            </svg>
        </a>
    @endif

    <footer class="public-footer">
        <div class="container public-footer-cta-wrapper">
            <div class="public-footer-cta">
                <div>
                    <span class="public-footer-cta-label">
                        Pesan secara online
                    </span>

                    <h2>
                        Ada tugas yang harus segera dicetak?
                    </h2>

                    <p>
                        Unggah file, tentukan kebutuhan cetak, lihat
                        estimasi biaya, lalu pantau status pesanan
                        langsung melalui website.
                    </p>
                </div>

                <a
                    href="{{ $tujuanPesanan }}"
                    class="public-footer-cta-button"
                >
                    Mulai Buat Pesanan

                    <span aria-hidden="true">
                        →
                    </span>
                </a>
            </div>
        </div>

        <div class="container public-footer-grid">
            <div>
                <div class="public-footer-brand">
                    <img
                        src="{{ $logoUrl }}"
                        alt="Logo {{ $namaWebsite }}"
                        class="public-footer-logo"
                    >

                    <span>
                        <strong>{{ $namaWebsite }}</strong>

                        <small>
                            Layanan cetak mahasiswa
                        </small>
                    </span>
                </div>

                <p class="public-footer-description">
                    Sistem pemesanan layanan cetak mahasiswa yang
                    membantu pelanggan mengunggah file, mengetahui
                    estimasi biaya, dan memantau proses pengerjaan
                    secara lebih terstruktur.
                </p>
            </div>

            <div>
                <h4 class="public-footer-heading">
                    Navigasi
                </h4>

                <div class="public-footer-links">
                    <a href="{{ route('home') }}">
                        Beranda
                    </a>

                    <a href="{{ route('tentang') }}">
                        Tentang Kami
                    </a>

                    <a href="{{ route('layanan.index') }}">
                        Layanan
                    </a>

                    <a href="{{ route('kontak.index') }}">
                        Kontak
                    </a>
                </div>
            </div>

            <div>
                <h4 class="public-footer-heading">
                    Informasi Kontak
                </h4>

                <div class="public-footer-contact">
                    <div class="public-footer-contact-item">
                        <span>WhatsApp</span>

                        <strong>
                            {{ $website?->nomor_whatsapp ?? '-' }}
                        </strong>
                    </div>

                    <div class="public-footer-contact-item">
                        <span>Email</span>

                        <strong>
                            {{ $website?->email ?? '-' }}
                        </strong>
                    </div>

                    <div class="public-footer-contact-item">
                        <span>Jam Operasional</span>

                        <strong>
                            {{ $website?->jam_operasional ?? '-' }}
                        </strong>
                    </div>

                    <div class="public-footer-contact-item">
                        <span>Lokasi</span>

                        <strong>
                            {{ $website?->alamat ?? '-' }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="public-footer-bottom">
            <div class="container public-footer-bottom-wrapper">
                <p>
                    &copy; {{ date('Y') }}
                    {{ $namaWebsite }}.
                    Seluruh hak dilindungi.
                </p>

                <span class="public-footer-accent">
                    Dibuat untuk kebutuhan cetak mahasiswa
                </span>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/frontend.js') }}" defer></script>

    @stack('scripts')
</body>
</html>