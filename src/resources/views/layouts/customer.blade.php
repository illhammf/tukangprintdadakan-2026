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
        content="Dashboard pelanggan Tukang Print Dadakan untuk membuat, memantau, dan mengelola pesanan layanan cetak."
    >

    <title>
        @yield(
            'title',
            'Dashboard Pelanggan - Tukang Print Dadakan'
        )
    </title>

    <link
        rel="icon"
        href="{{ asset('favicon.ico') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/frontend.css') }}"
    >

    @stack('styles')

    <style>
        /*
        |--------------------------------------------------------------------------
        | Customer Layout Variables
        |--------------------------------------------------------------------------
        */

        :root {
            --customer-blue: #155eef;
            --customer-blue-dark: #1046b8;
            --customer-blue-light: #eaf2ff;
            --customer-blue-soft: #f5f8ff;

            --customer-orange: #f97316;
            --customer-orange-dark: #c2410c;
            --customer-orange-light: #fff1e8;

            --customer-green: #16a34a;
            --customer-green-light: #ecfdf3;

            --customer-red: #dc2626;
            --customer-red-dark: #b91c1c;
            --customer-red-light: #fff1f2;

            --customer-yellow: #d97706;
            --customer-yellow-light: #fffbeb;

            --customer-dark: #101828;
            --customer-text: #344054;
            --customer-muted: #667085;

            --customer-white: #ffffff;
            --customer-background: #f6f8fc;
            --customer-border: #e4e7ec;
            --customer-border-dark: #d0d5dd;

            --customer-shadow-small:
                0 5px 16px rgba(16, 24, 40, 0.06);

            --customer-shadow-medium:
                0 16px 42px rgba(16, 24, 40, 0.10);

            --customer-shadow-large:
                0 26px 72px rgba(16, 24, 40, 0.16);
        }

        /*
        |--------------------------------------------------------------------------
        | Base Layout
        |--------------------------------------------------------------------------
        */

        html {
            scroll-behavior: smooth;
        }

        body.customer-layout {
            min-height: 100vh;
            margin: 0;
            color: var(--customer-dark);
            background:
                radial-gradient(
                    circle at top left,
                    rgba(21, 94, 239, 0.05),
                    transparent 28%
                ),
                var(--customer-background);
            font-family:
                Inter,
                ui-sans-serif,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }

        body.customer-layout.customer-nav-open {
            overflow: hidden;
        }

        .customer-layout *,
        .customer-layout *::before,
        .customer-layout *::after {
            box-sizing: border-box;
        }

        .customer-layout a {
            text-decoration: none;
        }

        .customer-layout button,
        .customer-layout a,
        .customer-layout input,
        .customer-layout select,
        .customer-layout textarea {
            -webkit-tap-highlight-color: transparent;
        }

        .customer-layout button:focus-visible,
        .customer-layout a:focus-visible,
        .customer-layout input:focus-visible,
        .customer-layout select:focus-visible,
        .customer-layout textarea:focus-visible {
            outline: 3px solid rgba(249, 115, 22, 0.34);
            outline-offset: 3px;
        }

        .customer-skip-link {
            position: fixed;
            top: 12px;
            left: 12px;
            z-index: 9999;
            padding: 10px 16px;
            border-radius: 10px;
            color: var(--customer-white);
            background: var(--customer-blue);
            font-size: 14px;
            font-weight: 800;
            transform: translateY(-160%);
            transition: transform 0.2s ease;
        }

        .customer-skip-link:focus {
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Customer Information Bar
        |--------------------------------------------------------------------------
        */

        .customer-info-bar {
            position: relative;
            z-index: 53;
            color: var(--customer-white);
            background:
                linear-gradient(
                    90deg,
                    var(--customer-blue-dark),
                    var(--customer-blue)
                );
        }

        .customer-info-bar-wrapper {
            min-height: 35px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .customer-info-status {
            display: inline-flex;
            align-items: center;
            gap: 9px;
        }

        .customer-info-status-dot {
            width: 8px;
            height: 8px;
            flex: 0 0 8px;
            border-radius: 999px;
            background: #4ade80;
            box-shadow:
                0 0 0 4px rgba(74, 222, 128, 0.18);
        }

        .customer-info-links {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .customer-info-links a {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--customer-white);
            opacity: 0.92;
            transition: opacity 0.2s ease;
        }

        .customer-info-links a:hover {
            opacity: 1;
        }

        .customer-info-links svg {
            width: 15px;
            height: 15px;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Customer Header
        |--------------------------------------------------------------------------
        */

        .customer-header {
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid var(--customer-border);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(18px);
            box-shadow: 0 2px 8px rgba(16, 24, 40, 0.03);
        }

        .customer-header::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 3px;
            background:
                linear-gradient(
                    90deg,
                    var(--customer-blue) 0%,
                    var(--customer-blue) 76%,
                    var(--customer-orange) 76%,
                    var(--customer-orange) 100%
                );
        }

        .customer-header-wrapper {
            min-height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
        }

        /*
        |--------------------------------------------------------------------------
        | Brand
        |--------------------------------------------------------------------------
        */

        .customer-brand {
            min-width: 0;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .customer-brand-logo {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            padding: 3px;
            object-fit: contain;
            border: 1px solid var(--customer-border);
            border-radius: 16px;
            background: var(--customer-white);
            box-shadow: var(--customer-shadow-small);
        }

        .customer-brand-copy {
            min-width: 0;
            display: block;
        }

        .customer-brand-name {
            display: block;
            max-width: 230px;
            overflow: hidden;
            color: var(--customer-dark);
            font-size: 18px;
            font-weight: 900;
            line-height: 1.2;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .customer-brand-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
            color: var(--customer-muted);
            font-size: 11px;
            font-weight: 700;
        }

        .customer-brand-label::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--customer-orange);
        }

        /*
        |--------------------------------------------------------------------------
        | Desktop Navigation
        |--------------------------------------------------------------------------
        */

        .customer-nav {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .customer-nav-user {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-right: 8px;
            padding: 7px 11px 7px 7px;
            border: 1px solid #cfe0ff;
            border-radius: 14px;
            background: var(--customer-blue-light);
        }

        .customer-nav-avatar {
            width: 37px;
            height: 37px;
            flex: 0 0 37px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--customer-white);
            background:
                linear-gradient(
                    135deg,
                    var(--customer-blue),
                    var(--customer-orange)
                );
            font-size: 15px;
            font-weight: 900;
            box-shadow:
                0 5px 14px rgba(21, 94, 239, 0.18);
        }

        .customer-nav-user-text {
            min-width: 0;
            display: block;
        }

        .customer-nav-user-text small {
            display: block;
            color: var(--customer-muted);
            font-size: 10px;
            font-weight: 700;
            line-height: 1.2;
        }

        .customer-nav-user-text strong {
            display: block;
            max-width: 120px;
            overflow: hidden;
            margin-top: 2px;
            color: var(--customer-dark);
            font-size: 12px;
            font-weight: 900;
            line-height: 1.2;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .customer-nav-link {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 11px;
            border-radius: 12px;
            color: var(--customer-text);
            font-size: 13px;
            font-weight: 800;
            transition:
                color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .customer-nav-link svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
        }

        .customer-nav-link:hover {
            color: var(--customer-blue);
            background: var(--customer-blue-light);
        }

        .customer-nav-link.active {
            color: var(--customer-blue);
            background: var(--customer-blue-light);
        }

        .customer-nav-link.active svg {
            color: var(--customer-orange);
        }

        .customer-nav-separator {
            width: 1px;
            height: 34px;
            margin: 0 6px;
            background: var(--customer-border);
        }

        .customer-website-button {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 13px;
            border: 1px solid #bfd3ff;
            border-radius: 12px;
            color: var(--customer-blue);
            background: var(--customer-white);
            font-size: 13px;
            font-weight: 900;
            transition:
                color 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease,
                transform 0.2s ease;
        }

        .customer-website-button:hover {
            color: var(--customer-blue-dark);
            border-color: var(--customer-blue);
            background: var(--customer-blue-light);
            transform: translateY(-1px);
        }

        .customer-website-button svg {
            width: 17px;
            height: 17px;
        }

        .customer-logout-form {
            margin: 0;
        }

        .customer-logout-button {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 14px;
            border: 1px solid transparent;
            border-radius: 12px;
            color: var(--customer-white);
            background: var(--customer-red);
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
            transition:
                background 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .customer-logout-button:hover {
            background: var(--customer-red-dark);
            transform: translateY(-1px);
            box-shadow:
                0 8px 18px rgba(220, 38, 38, 0.18);
        }

        .customer-logout-button svg {
            width: 17px;
            height: 17px;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile Toggle
        |--------------------------------------------------------------------------
        */

        .customer-nav-toggle {
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
                    var(--customer-blue),
                    #2870ff
                );
            box-shadow:
                0 8px 20px rgba(21, 94, 239, 0.22);
            cursor: pointer;
        }

        .customer-nav-toggle span {
            width: 21px;
            height: 2px;
            display: block;
            border-radius: 999px;
            background: var(--customer-white);
            transition:
                transform 0.2s ease,
                opacity 0.2s ease;
        }

        .customer-nav-toggle.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .customer-nav-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .customer-nav-toggle.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .customer-nav-backdrop {
            position: fixed;
            inset: 0;
            z-index: 44;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            background: rgba(16, 24, 40, 0.58);
            backdrop-filter: blur(4px);
            transition:
                opacity 0.2s ease,
                visibility 0.2s ease;
        }

        .customer-nav.open ~ .customer-nav-backdrop {
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
        }

        /*
        |--------------------------------------------------------------------------
        | Customer Main Content
        |--------------------------------------------------------------------------
        */

        .customer-main {
            position: relative;
            min-height: calc(100vh - 181px);
        }

        .customer-main::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 180px;
            pointer-events: none;
            background:
                linear-gradient(
                    180deg,
                    rgba(234, 242, 255, 0.72),
                    transparent
                );
        }

        .customer-main-content {
            position: relative;
            z-index: 1;
        }

        /*
        |--------------------------------------------------------------------------
        | Flash Messages
        |--------------------------------------------------------------------------
        */

        .customer-flash-wrapper {
            padding-top: 18px;
        }

        .customer-flash {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 48px 14px 15px;
            border: 1px solid transparent;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 750;
            box-shadow: var(--customer-shadow-small);
        }

        .customer-flash-icon {
            width: 26px;
            height: 26px;
            flex: 0 0 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--customer-white);
            font-size: 13px;
            font-weight: 900;
        }

        .customer-flash.success {
            color: #166534;
            border-color: #bbf7d0;
            background: var(--customer-green-light);
        }

        .customer-flash.success .customer-flash-icon {
            background: var(--customer-green);
        }

        .customer-flash.error {
            color: #991b1b;
            border-color: #fecaca;
            background: var(--customer-red-light);
        }

        .customer-flash.error .customer-flash-icon {
            background: var(--customer-red);
        }

        .customer-flash-close {
            position: absolute;
            top: 50%;
            right: 13px;
            width: 29px;
            height: 29px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 0;
            border-radius: 9px;
            color: currentColor;
            background: transparent;
            font-size: 19px;
            cursor: pointer;
            opacity: 0.68;
            transform: translateY(-50%);
            transition:
                opacity 0.2s ease,
                background 0.2s ease;
        }

        .customer-flash-close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.55);
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile Bottom Navigation
        |--------------------------------------------------------------------------
        */

        .customer-mobile-navigation {
            position: fixed;
            right: 12px;
            bottom: 12px;
            left: 12px;
            z-index: 42;
            display: none;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 4px;
            padding: 7px;
            border: 1px solid rgba(228, 231, 236, 0.94);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(18px);
            box-shadow: var(--customer-shadow-large);
        }

        .customer-mobile-nav-link {
            min-width: 0;
            min-height: 57px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 4px;
            padding: 7px 4px;
            border-radius: 15px;
            color: var(--customer-muted);
            font-size: 10px;
            font-weight: 800;
            text-align: center;
            transition:
                color 0.2s ease,
                background 0.2s ease;
        }

        .customer-mobile-nav-link svg {
            width: 21px;
            height: 21px;
        }

        .customer-mobile-nav-link.active {
            color: var(--customer-blue);
            background: var(--customer-blue-light);
        }

        .customer-mobile-nav-link.create {
            color: var(--customer-white);
            background:
                linear-gradient(
                    135deg,
                    var(--customer-blue),
                    #2870ff
                );
            box-shadow:
                0 6px 14px rgba(21, 94, 239, 0.20);
        }

        .customer-mobile-nav-link.create.active {
            color: var(--customer-white);
            background:
                linear-gradient(
                    135deg,
                    var(--customer-orange),
                    #fb923c
                );
        }

        /*
        |--------------------------------------------------------------------------
        | General Customer Component Enhancements
        |--------------------------------------------------------------------------
        */

        .customer-layout .section {
            position: relative;
        }

        .customer-layout .section-title h2 {
            color: var(--customer-dark);
        }

        .customer-layout .badge {
            border: 1px solid #c9dcff;
            color: var(--customer-blue);
            background: var(--customer-blue-light);
        }

        .customer-layout .btn-primary {
            border: 0;
            color: var(--customer-white);
            background:
                linear-gradient(
                    135deg,
                    var(--customer-blue),
                    #2870ff
                );
            box-shadow:
                0 8px 20px rgba(21, 94, 239, 0.20);
        }

        .customer-layout .btn-primary:hover {
            background:
                linear-gradient(
                    135deg,
                    var(--customer-blue-dark),
                    var(--customer-blue)
                );
        }

        .customer-layout .btn-outline {
            color: var(--customer-blue) !important;
            border-color: #b9d0ff;
            background: var(--customer-white);
        }

        .customer-layout .btn-outline:hover {
            border-color: var(--customer-blue);
            background: var(--customer-blue-light);
        }

        .customer-layout .form-card,
        .customer-layout .dashboard-panel,
        .customer-layout .dashboard-stat-card,
        .customer-layout .customer-action-card,
        .customer-layout .order-card,
        .customer-layout .order-summary-card,
        .customer-layout .profile-summary-card {
            border-color: var(--customer-border);
            box-shadow: var(--customer-shadow-small);
        }

        .customer-layout .form-group input,
        .customer-layout .form-group select,
        .customer-layout .form-group textarea {
            min-height: 48px;
            border-color: var(--customer-border-dark);
            border-radius: 13px;
        }

        .customer-layout .form-group textarea {
            min-height: 110px;
        }

        .customer-layout .form-group input:focus,
        .customer-layout .form-group select:focus,
        .customer-layout .form-group textarea:focus {
            border-color: var(--customer-blue);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.13);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive Tablet
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1180px) {
            .customer-brand-label {
                display: none;
            }

            .customer-nav-user {
                display: none;
            }

            .customer-nav-link {
                padding-right: 9px;
                padding-left: 9px;
            }

            .customer-nav-link span {
                display: none;
            }

            .customer-nav-link svg {
                width: 19px;
                height: 19px;
            }
        }

        @media (max-width: 900px) {
            .customer-info-bar {
                display: none;
            }

            .customer-header-wrapper {
                min-height: 74px;
            }

            .customer-nav-toggle {
                display: inline-flex;
            }

            .customer-nav {
                position: fixed;
                top: 88px;
                right: 16px;
                left: 16px;
                z-index: 46;
                max-height: calc(100vh - 108px);
                display: none;
                align-items: stretch;
                flex-direction: column;
                gap: 6px;
                overflow-y: auto;
                padding: 17px;
                border: 1px solid var(--customer-border);
                border-radius: 22px;
                background: var(--customer-white);
                box-shadow: var(--customer-shadow-large);
            }

            .customer-nav.open {
                display: flex;
            }

            .customer-nav-user {
                display: flex;
                width: 100%;
                margin: 0 0 6px;
                padding: 12px;
                border-radius: 16px;
            }

            .customer-nav-avatar {
                width: 44px;
                height: 44px;
                flex-basis: 44px;
                border-radius: 14px;
            }

            .customer-nav-user-text small {
                font-size: 11px;
            }

            .customer-nav-user-text strong {
                max-width: none;
                font-size: 14px;
            }

            .customer-nav-link {
                width: 100%;
                min-height: 49px;
                justify-content: flex-start;
                padding: 12px 14px;
                font-size: 14px;
            }

            .customer-nav-link span {
                display: inline;
            }

            .customer-nav-separator {
                width: 100%;
                height: 1px;
                margin: 8px 0;
            }

            .customer-website-button,
            .customer-logout-form,
            .customer-logout-button {
                width: 100%;
            }

            .customer-website-button,
            .customer-logout-button {
                min-height: 48px;
            }

            .customer-main {
                min-height: calc(100vh - 74px);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive Mobile
        |--------------------------------------------------------------------------
        */

        @media (max-width: 640px) {
            body.customer-layout {
                padding-bottom: 92px;
            }

            .customer-header-wrapper {
                min-height: 70px;
            }

            .customer-brand-logo {
                width: 43px;
                height: 43px;
                flex-basis: 43px;
                border-radius: 14px;
            }

            .customer-brand-name {
                max-width: 190px;
                font-size: 16px;
            }

            .customer-nav {
                top: 82px;
                right: 12px;
                left: 12px;
                max-height: calc(100vh - 98px);
            }

            .customer-main::before {
                height: 130px;
            }

            .customer-flash-wrapper {
                padding-top: 12px;
            }

            .customer-flash {
                padding-top: 12px;
                padding-bottom: 12px;
                font-size: 13px;
            }

            .customer-mobile-navigation {
                display: grid;
            }

            .customer-layout .section {
                padding-top: 42px;
                padding-bottom: 42px;
            }

            .customer-layout .section-title h2 {
                font-size: 30px;
            }

            .customer-layout .form-card,
            .customer-layout .dashboard-panel,
            .customer-layout .dashboard-stat-card,
            .customer-layout .customer-action-card,
            .customer-layout .order-card,
            .customer-layout .order-summary-card,
            .customer-layout .profile-summary-card {
                padding: 20px;
                border-radius: 20px;
            }
        }

        @media (max-width: 390px) {
            .customer-brand-name {
                max-width: 155px;
            }

            .customer-mobile-navigation {
                right: 8px;
                bottom: 8px;
                left: 8px;
                padding: 5px;
                border-radius: 19px;
            }

            .customer-mobile-nav-link {
                min-height: 54px;
                font-size: 9px;
            }
        }
    </style>
</head>

<body class="customer-layout">
    <a
        href="#customer-main-content"
        class="customer-skip-link"
    >
        Lewati ke konten utama
    </a>

    @php
        $namaWebsite = $website?->nama_website
            ?? 'Tukang Print Dadakan';

        $logoUrl = $website?->logo
            ? \Illuminate\Support\Facades\Storage::url($website->logo)
            : asset('images/placeholder.png');

        $user = auth()->user();

        $namaPelanggan = $user?->name ?? 'Pelanggan';

        $emailPelanggan = $user?->email ?? '-';

        $inisialPelanggan = strtoupper(
            mb_substr(trim($namaPelanggan), 0, 1)
        );
    @endphp

    <div class="customer-info-bar">
        <div class="container customer-info-bar-wrapper">
            <div class="customer-info-status">
                <span class="customer-info-status-dot"></span>

                <span>
                    Area pelanggan aktif
                </span>
            </div>

            <div class="customer-info-links">
                <a href="{{ route('customer.pesanan.index') }}">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M9 12h6"/>
                        <path d="M9 16h6"/>
                        <path d="M13 8h2"/>
                        <path d="M4 4h16v16H4z"/>
                    </svg>

                    Pantau pesanan
                </a>

                <a href="{{ route('kontak.index') }}">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                    </svg>

                    Bantuan pelanggan
                </a>
            </div>
        </div>
    </div>

    <header class="customer-header">
        <div class="container customer-header-wrapper">
            <a
                href="{{ route('customer.dashboard') }}"
                class="customer-brand"
                aria-label="Dashboard {{ $namaWebsite }}"
            >
                <img
                    src="{{ $logoUrl }}"
                    alt="Logo {{ $namaWebsite }}"
                    class="customer-brand-logo"
                >

                <span class="customer-brand-copy">
                    <strong class="customer-brand-name">
                        {{ $namaWebsite }}
                    </strong>

                    <small class="customer-brand-label">
                        Dashboard pelanggan
                    </small>
                </span>
            </a>

            <button
                class="nav-toggle customer-nav-toggle"
                type="button"
                aria-label="Buka menu pelanggan"
                aria-controls="siteNav"
                aria-expanded="false"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav
                class="customer-nav"
                id="siteNav"
                aria-label="Navigasi pelanggan"
            >
                <div class="customer-nav-user">
                    <span class="customer-nav-avatar">
                        {{ $inisialPelanggan }}
                    </span>

                    <span class="customer-nav-user-text">
                        <small>
                            Masuk sebagai
                        </small>

                        <strong title="{{ $namaPelanggan }}">
                            {{ \Illuminate\Support\Str::limit(
                                $namaPelanggan,
                                20
                            ) }}
                        </strong>
                    </span>
                </div>

                <a
                    href="{{ route('customer.dashboard') }}"
                    class="customer-nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <rect width="7" height="9" x="3" y="3" rx="1"/>
                        <rect width="7" height="5" x="14" y="3" rx="1"/>
                        <rect width="7" height="9" x="14" y="12" rx="1"/>
                        <rect width="7" height="5" x="3" y="16" rx="1"/>
                    </svg>

                    <span>Dashboard</span>
                </a>

                <a
                    href="{{ route('customer.pesanan.create') }}"
                    class="customer-nav-link {{ request()->routeIs('customer.pesanan.create') ? 'active' : '' }}"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M12 5v14"/>
                        <path d="M5 12h14"/>
                    </svg>

                    <span>Buat Pesanan</span>
                </a>

                <a
                    href="{{ route('customer.pesanan.index') }}"
                    class="customer-nav-link {{
                        request()->routeIs('customer.pesanan.index')
                        || request()->routeIs('customer.pesanan.show')
                            ? 'active'
                            : ''
                    }}"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M6 2h9l5 5v15H6z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M9 13h6"/>
                        <path d="M9 17h6"/>
                    </svg>

                    <span>Pesanan Saya</span>
                </a>

                <a
                    href="{{ route('customer.profil.edit') }}"
                    class="customer-nav-link {{ request()->routeIs('customer.profil.*') ? 'active' : '' }}"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 22a8 8 0 0 1 16 0"/>
                    </svg>

                    <span>Profil</span>
                </a>

                <span
                    class="customer-nav-separator"
                    aria-hidden="true"
                ></span>

                <a
                    href="{{ route('home') }}"
                    class="customer-website-button"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M2 12h20"/>
                        <path d="M12 2a15.3 15.3 0 0 1 0 20"/>
                        <path d="M12 2a15.3 15.3 0 0 0 0 20"/>
                    </svg>

                    Website
                </a>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="customer-logout-form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="customer-logout-button"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M10 17l5-5-5-5"/>
                            <path d="M15 12H3"/>
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        </svg>

                        Logout
                    </button>
                </form>
            </nav>

            <div
                class="customer-nav-backdrop"
                data-customer-nav-close
                aria-hidden="true"
            ></div>
        </div>
    </header>

    <main
        id="customer-main-content"
        class="customer-main"
        tabindex="-1"
    >
        <div class="customer-main-content">
            @if (session('success'))
                <div class="container customer-flash-wrapper">
                    <div
                        class="customer-flash success"
                        data-customer-flash
                    >
                        <span class="customer-flash-icon">
                            ✓
                        </span>

                        <span>
                            {{ session('success') }}
                        </span>

                        <button
                            type="button"
                            class="customer-flash-close"
                            aria-label="Tutup notifikasi"
                            data-customer-flash-close
                        >
                            ×
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container customer-flash-wrapper">
                    <div
                        class="customer-flash error"
                        data-customer-flash
                    >
                        <span class="customer-flash-icon">
                            !
                        </span>

                        <span>
                            {{ session('error') }}
                        </span>

                        <button
                            type="button"
                            class="customer-flash-close"
                            aria-label="Tutup notifikasi"
                            data-customer-flash-close
                        >
                            ×
                        </button>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <nav
        class="customer-mobile-navigation"
        aria-label="Navigasi cepat pelanggan"
    >
        <a
            href="{{ route('customer.dashboard') }}"
            class="customer-mobile-nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <rect width="7" height="9" x="3" y="3" rx="1"/>
                <rect width="7" height="5" x="14" y="3" rx="1"/>
                <rect width="7" height="9" x="14" y="12" rx="1"/>
                <rect width="7" height="5" x="3" y="16" rx="1"/>
            </svg>

            <span>Dashboard</span>
        </a>

        <a
            href="{{ route('customer.pesanan.create') }}"
            class="customer-mobile-nav-link create {{ request()->routeIs('customer.pesanan.create') ? 'active' : '' }}"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M12 5v14"/>
                <path d="M5 12h14"/>
            </svg>

            <span>Buat</span>
        </a>

        <a
            href="{{ route('customer.pesanan.index') }}"
            class="customer-mobile-nav-link {{
                request()->routeIs('customer.pesanan.index')
                || request()->routeIs('customer.pesanan.show')
                    ? 'active'
                    : ''
            }}"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M6 2h9l5 5v15H6z"/>
                <path d="M14 2v6h6"/>
                <path d="M9 13h6"/>
                <path d="M9 17h6"/>
            </svg>

            <span>Pesanan</span>
        </a>

        <a
            href="{{ route('customer.profil.edit') }}"
            class="customer-mobile-nav-link {{ request()->routeIs('customer.profil.*') ? 'active' : '' }}"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <circle cx="12" cy="8" r="4"/>
                <path d="M4 22a8 8 0 0 1 16 0"/>
            </svg>

            <span>Profil</span>
        </a>
    </nav>

    <script
        src="{{ asset('js/frontend.js') }}"
        defer
    ></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navToggle = document.querySelector('.customer-nav-toggle');
            const customerNav = document.querySelector('.customer-nav');
            const navBackdrop = document.querySelector(
                '[data-customer-nav-close]'
            );

            const closeCustomerNavigation = () => {
                customerNav?.classList.remove('open');
                navToggle?.classList.remove('active');

                navToggle?.setAttribute(
                    'aria-expanded',
                    'false'
                );

                navToggle?.setAttribute(
                    'aria-label',
                    'Buka menu pelanggan'
                );

                document.body.classList.remove(
                    'customer-nav-open'
                );
            };

            const updateCustomerNavigationState = () => {
                window.setTimeout(() => {
                    const isOpen = customerNav?.classList.contains(
                        'open'
                    ) ?? false;

                    navToggle?.setAttribute(
                        'aria-expanded',
                        isOpen ? 'true' : 'false'
                    );

                    navToggle?.setAttribute(
                        'aria-label',
                        isOpen
                            ? 'Tutup menu pelanggan'
                            : 'Buka menu pelanggan'
                    );

                    document.body.classList.toggle(
                        'customer-nav-open',
                        isOpen
                    );
                }, 0);
            };

            navToggle?.addEventListener(
                'click',
                updateCustomerNavigationState
            );

            navBackdrop?.addEventListener(
                'click',
                closeCustomerNavigation
            );

            customerNav
                ?.querySelectorAll('a')
                .forEach((link) => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth <= 900) {
                            closeCustomerNavigation();
                        }
                    });
                });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeCustomerNavigation();
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 900) {
                    closeCustomerNavigation();
                }
            });

            document
                .querySelectorAll(
                    '[data-customer-flash-close]'
                )
                .forEach((button) => {
                    button.addEventListener('click', () => {
                        const flash = button.closest(
                            '[data-customer-flash]'
                        );

                        if (!flash) {
                            return;
                        }

                        flash.style.opacity = '0';
                        flash.style.transform =
                            'translateY(-6px)';
                        flash.style.transition =
                            'opacity 0.2s ease, transform 0.2s ease';

                        window.setTimeout(() => {
                            flash.parentElement?.remove();
                        }, 220);
                    });
                });
        });
    </script>

    @stack('scripts')
</body>
</html>