@extends('layouts.public')

@section(
    'title',
    ($website?->nama_website ?? 'Tukang Print Dadakan')
    . ' - Layanan Print Mahasiswa'
)

@php
    $namaWebsite = $website?->nama_website
        ?? 'Tukang Print Dadakan';

    $heroImage = $website?->hero_image
        ? \Illuminate\Support\Facades\Storage::url($website->hero_image)
        : asset('images/placeholder.png');

    $heroTitle = $website?->hero_title
        ?? 'Cetak tugas, laporan, dan dokumen jadi lebih mudah.';

    $heroSubtitle = $website?->hero_subtitle
        ?? 'Pilih layanan, unggah file, lihat estimasi biaya, lalu pantau status pesanan tanpa harus menghubungi admin berulang kali.';

    $tujuanPesanan = auth()->check()
        ? route('customer.pesanan.create')
        : route('register');

    $labelPesanan = auth()->check()
        ? 'Buat Pesanan'
        : 'Mulai Pesan';

    $jumlahLayanan = $layanans->count();

    $jumlahKategori = $kategoriLayanans->count();
@endphp

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Home Page
        |--------------------------------------------------------------------------
        */

        .home-page {
            --home-blue: var(--public-blue, #155eef);
            --home-blue-dark: var(--public-blue-dark, #1046b8);
            --home-blue-soft: var(--public-blue-soft, #edf4ff);

            --home-orange: var(--public-orange, #f97316);
            --home-orange-dark: var(--public-orange-dark, #c2410c);
            --home-orange-soft: var(--public-orange-soft, #fff1e7);

            --home-dark: var(--public-dark, #101828);
            --home-text: var(--public-text, #344054);
            --home-muted: var(--public-muted, #667085);

            --home-white: #ffffff;
            --home-soft: #f7f9fc;
            --home-border: #e4e7ec;

            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Shared Components
        |--------------------------------------------------------------------------
        */

        .home-section {
            position: relative;
            padding: 86px 0;
        }

        .home-section-white {
            background: var(--home-white);
        }

        .home-section-soft {
            background:
                radial-gradient(
                    circle at top left,
                    rgba(21, 94, 239, 0.06),
                    transparent 30%
                ),
                #f8faff;
        }

        .home-section-heading {
            max-width: 720px;
            margin-bottom: 38px;
        }

        .home-section-heading.center {
            margin-right: auto;
            margin-left: auto;
            text-align: center;
        }

        .home-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 14px;
            color: var(--home-blue);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.09em;
        }

        .home-eyebrow::before {
            content: "";
            width: 26px;
            height: 3px;
            border-radius: 999px;
            background:
                linear-gradient(
                    90deg,
                    var(--home-blue),
                    var(--home-orange)
                );
        }

        .home-section-heading h2 {
            margin: 0 0 14px;
            color: var(--home-dark);
            font-size: clamp(32px, 4vw, 48px);
            line-height: 1.12;
            letter-spacing: -1.2px;
        }

        .home-section-heading p {
            margin: 0;
            color: var(--home-muted);
            font-size: 16px;
            line-height: 1.8;
        }

        .home-button {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 21px;
            border: 1px solid transparent;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .home-button:hover {
            transform: translateY(-2px);
        }

        .home-button svg {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
        }

        .home-button-primary {
            color: var(--home-white);
            background:
                linear-gradient(
                    135deg,
                    var(--home-blue),
                    #2b70ff
                );
            box-shadow:
                0 12px 26px rgba(21, 94, 239, 0.24);
        }

        .home-button-primary:hover {
            color: var(--home-white);
            background:
                linear-gradient(
                    135deg,
                    var(--home-blue-dark),
                    var(--home-blue)
                );
            box-shadow:
                0 16px 32px rgba(21, 94, 239, 0.30);
        }

        .home-button-secondary {
            color: var(--home-orange-dark);
            border-color: #fed7aa;
            background: var(--home-white);
        }

        .home-button-secondary:hover {
            color: var(--home-orange-dark);
            border-color: var(--home-orange);
            background: var(--home-orange-soft);
        }

        .home-button-white {
            color: var(--home-blue-dark);
            border-color: rgba(255, 255, 255, 0.72);
            background: var(--home-white);
            box-shadow:
                0 12px 26px rgba(16, 24, 40, 0.18);
        }

        .home-button-white:hover {
            color: var(--home-blue-dark);
            box-shadow:
                0 16px 34px rgba(16, 24, 40, 0.24);
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .home-hero {
            position: relative;
            min-height: 680px;
            display: flex;
            align-items: center;
            padding: 80px 0 92px;
            background:
                radial-gradient(
                    circle at 8% 18%,
                    rgba(21, 94, 239, 0.15),
                    transparent 27%
                ),
                radial-gradient(
                    circle at 92% 8%,
                    rgba(249, 115, 22, 0.13),
                    transparent 24%
                ),
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #f7f9fd 100%
                );
            border-bottom: 1px solid var(--home-border);
        }

        .home-hero::before {
            content: "";
            position: absolute;
            top: 80px;
            left: -95px;
            width: 210px;
            height: 210px;
            border: 34px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .home-hero::after {
            content: "";
            position: absolute;
            right: -110px;
            bottom: 42px;
            width: 240px;
            height: 240px;
            border: 38px solid rgba(249, 115, 22, 0.06);
            border-radius: 999px;
        }

        .home-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, 1.03fr) minmax(420px, 0.97fr);
            gap: 64px;
            align-items: center;
        }

        .home-hero-content {
            max-width: 680px;
        }

        .home-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--home-orange-dark);
            background: var(--home-orange-soft);
            font-size: 13px;
            font-weight: 900;
        }

        .home-hero-badge-icon {
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--home-white);
            background: var(--home-orange);
        }

        .home-hero-badge-icon svg {
            width: 14px;
            height: 14px;
        }

        .home-hero h1 {
            margin: 24px 0 20px;
            color: var(--home-dark);
            font-size: clamp(44px, 5.6vw, 72px);
            line-height: 1.03;
            letter-spacing: -2.7px;
        }

        .home-hero h1 .home-highlight {
            position: relative;
            display: inline-block;
            color: var(--home-blue);
        }

        .home-hero h1 .home-highlight::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: 1px;
            left: 0;
            height: 8px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.23);
            transform: rotate(-1.5deg);
        }

        .home-hero-description {
            max-width: 640px;
            margin: 0;
            color: var(--home-muted);
            font-size: 17px;
            line-height: 1.8;
        }

        .home-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 13px;
            margin-top: 30px;
        }

        .home-hero-benefits {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin-top: 30px;
        }

        .home-hero-benefit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--home-text);
            font-size: 13px;
            font-weight: 800;
        }

        .home-hero-benefit-icon {
            width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--home-blue);
            background: var(--home-blue-soft);
        }

        .home-hero-benefit-icon svg {
            width: 14px;
            height: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Visual
        |--------------------------------------------------------------------------
        */

        .home-hero-visual {
            position: relative;
        }

        .home-hero-card {
            position: relative;
            padding: 22px;
            border: 1px solid rgba(228, 231, 236, 0.9);
            border-radius: 34px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            box-shadow:
                0 30px 85px rgba(16, 24, 40, 0.14);
        }

        .home-hero-card::before {
            content: "";
            position: absolute;
            top: -14px;
            right: 34px;
            width: 85px;
            height: 28px;
            border-radius: 10px 10px 4px 4px;
            background:
                linear-gradient(
                    90deg,
                    var(--home-blue),
                    var(--home-orange)
                );
            transform: rotate(3deg);
        }

        .home-hero-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 25px;
            background:
                linear-gradient(
                    135deg,
                    var(--home-blue-soft),
                    var(--home-orange-soft)
                );
        }

        .home-hero-image {
            width: 100%;
            height: 420px;
            display: block;
            object-fit: cover;
            object-position: center;
        }

        .home-hero-image-overlay {
            position: absolute;
            inset: auto 0 0;
            padding: 44px 20px 18px;
            color: var(--home-white);
            background:
                linear-gradient(
                    180deg,
                    transparent,
                    rgba(16, 24, 40, 0.76)
                );
        }

        .home-hero-image-overlay strong {
            display: block;
            font-size: 17px;
        }

        .home-hero-image-overlay span {
            display: block;
            margin-top: 3px;
            color: #e2e8f0;
            font-size: 12px;
        }

        .home-hero-status {
            position: absolute;
            top: 44px;
            left: -25px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            border: 1px solid var(--home-border);
            border-radius: 16px;
            background: var(--home-white);
            box-shadow:
                0 14px 34px rgba(16, 24, 40, 0.14);
        }

        .home-hero-status-icon {
            width: 39px;
            height: 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--home-white);
            background:
                linear-gradient(
                    135deg,
                    var(--home-orange),
                    #fb923c
                );
        }

        .home-hero-status-icon svg {
            width: 20px;
            height: 20px;
        }

        .home-hero-status strong,
        .home-hero-status span {
            display: block;
        }

        .home-hero-status strong {
            color: var(--home-dark);
            font-size: 13px;
        }

        .home-hero-status span {
            margin-top: 1px;
            color: var(--home-muted);
            font-size: 11px;
        }

        .home-hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 15px;
        }

        .home-hero-stat {
            min-width: 0;
            padding: 17px 14px;
            border: 1px solid var(--home-border);
            border-radius: 18px;
            background: var(--home-white);
        }

        .home-hero-stat strong {
            display: block;
            color: var(--home-blue);
            font-size: 26px;
            line-height: 1;
        }

        .home-hero-stat span {
            display: block;
            margin-top: 7px;
            color: var(--home-muted);
            font-size: 11px;
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | Trust Strip
        |--------------------------------------------------------------------------
        */

        .home-trust-strip {
            position: relative;
            z-index: 4;
            margin-top: -38px;
        }

        .home-trust-wrapper {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            overflow: hidden;
            border: 1px solid var(--home-border);
            border-radius: 22px;
            background: var(--home-white);
            box-shadow:
                0 18px 46px rgba(16, 24, 40, 0.09);
        }

        .home-trust-item {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 21px;
            border-right: 1px solid var(--home-border);
        }

        .home-trust-item:last-child {
            border-right: 0;
        }

        .home-trust-icon {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--home-blue);
            background: var(--home-blue-soft);
        }

        .home-trust-item:nth-child(even) .home-trust-icon {
            color: var(--home-orange-dark);
            background: var(--home-orange-soft);
        }

        .home-trust-icon svg {
            width: 21px;
            height: 21px;
        }

        .home-trust-item strong,
        .home-trust-item span {
            display: block;
        }

        .home-trust-item strong {
            color: var(--home-dark);
            font-size: 13px;
        }

        .home-trust-item span {
            margin-top: 3px;
            color: var(--home-muted);
            font-size: 11px;
            line-height: 1.45;
        }

        /*
        |--------------------------------------------------------------------------
        | Advantages
        |--------------------------------------------------------------------------
        */

        .home-feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .home-feature-card {
            position: relative;
            overflow: hidden;
            padding: 29px;
            border: 1px solid var(--home-border);
            border-radius: 24px;
            background: var(--home-white);
            box-shadow:
                0 8px 28px rgba(16, 24, 40, 0.05);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .home-feature-card:hover {
            border-color: #bdd1ff;
            box-shadow:
                0 18px 44px rgba(16, 24, 40, 0.10);
            transform: translateY(-6px);
        }

        .home-feature-card::before {
            content: "";
            position: absolute;
            top: -45px;
            right: -45px;
            width: 120px;
            height: 120px;
            border-radius: 999px;
            background: rgba(21, 94, 239, 0.05);
        }

        .home-feature-card:nth-child(2)::before {
            background: rgba(249, 115, 22, 0.06);
        }

        .home-feature-icon {
            position: relative;
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 21px;
            border-radius: 18px;
            color: var(--home-white);
            background:
                linear-gradient(
                    135deg,
                    var(--home-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 22px rgba(21, 94, 239, 0.20);
        }

        .home-feature-card:nth-child(2) .home-feature-icon {
            background:
                linear-gradient(
                    135deg,
                    var(--home-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 22px rgba(249, 115, 22, 0.20);
        }

        .home-feature-icon svg {
            width: 27px;
            height: 27px;
        }

        .home-feature-card h3 {
            position: relative;
            margin: 0 0 11px;
            color: var(--home-dark);
            font-size: 21px;
        }

        .home-feature-card p {
            position: relative;
            margin: 0;
            color: var(--home-muted);
            font-size: 14px;
            line-height: 1.75;
        }

        .home-feature-number {
            position: absolute;
            right: 22px;
            bottom: 14px;
            color: rgba(21, 94, 239, 0.07);
            font-size: 72px;
            font-weight: 900;
            line-height: 1;
        }

        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        .home-section-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 38px;
        }

        .home-section-row .home-section-heading {
            margin-bottom: 0;
        }

        .home-service-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 23px;
        }

        .home-service-card {
            position: relative;
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--home-border);
            border-radius: 25px;
            background: var(--home-white);
            box-shadow:
                0 8px 28px rgba(16, 24, 40, 0.05);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .home-service-card:hover {
            border-color: #bed2ff;
            box-shadow:
                0 20px 48px rgba(16, 24, 40, 0.11);
            transform: translateY(-6px);
        }

        .home-service-image-wrapper {
            position: relative;
            height: 215px;
            overflow: hidden;
            background:
                linear-gradient(
                    135deg,
                    var(--home-blue-soft),
                    var(--home-orange-soft)
                );
        }

        .home-service-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform 0.45s ease;
        }

        .home-service-card:hover .home-service-image {
            transform: scale(1.055);
        }

        .home-service-category {
            position: absolute;
            top: 15px;
            left: 15px;
            max-width: calc(100% - 30px);
            overflow: hidden;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.68);
            border-radius: 999px;
            color: var(--home-dark);
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(10px);
            font-size: 11px;
            font-weight: 900;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .home-service-content {
            padding: 23px;
        }

        .home-service-content h3 {
            margin: 0 0 9px;
            color: var(--home-dark);
            font-size: 20px;
            line-height: 1.3;
        }

        .home-service-description {
            min-height: 67px;
            margin: 0;
            color: var(--home-muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .home-service-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid var(--home-border);
        }

        .home-service-price span,
        .home-service-price strong {
            display: block;
        }

        .home-service-price span {
            color: var(--home-muted);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .home-service-price strong {
            margin-top: 3px;
            color: var(--home-blue);
            font-size: 18px;
        }

        .home-service-detail {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--home-orange-dark);
            font-size: 12px;
            font-weight: 900;
        }

        .home-service-detail svg {
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
        }

        .home-service-detail:hover {
            color: var(--home-orange-dark);
        }

        .home-service-detail:hover svg {
            transform: translateX(3px);
        }

        .home-empty-state {
            grid-column: 1 / -1;
            padding: 54px 24px;
            border: 1px dashed #b9d0ff;
            border-radius: 24px;
            background: var(--home-blue-soft);
            text-align: center;
        }

        .home-empty-icon {
            width: 62px;
            height: 62px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 17px;
            border-radius: 20px;
            color: var(--home-blue);
            background: var(--home-white);
        }

        .home-empty-icon svg {
            width: 31px;
            height: 31px;
        }

        .home-empty-state h3 {
            margin: 0 0 8px;
            color: var(--home-dark);
            font-size: 22px;
        }

        .home-empty-state p {
            max-width: 520px;
            margin: 0 auto;
            color: var(--home-muted);
            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Process
        |--------------------------------------------------------------------------
        */

        .home-process-wrapper {
            position: relative;
        }

        .home-process-line {
            position: absolute;
            top: 44px;
            right: 16.66%;
            left: 16.66%;
            height: 2px;
            background:
                linear-gradient(
                    90deg,
                    var(--home-blue),
                    var(--home-orange),
                    var(--home-blue)
                );
        }

        .home-process-grid {
            position: relative;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 34px;
        }

        .home-process-card {
            text-align: center;
        }

        .home-process-number-wrapper {
            position: relative;
            width: 88px;
            height: 88px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            border-radius: 999px;
            border: 10px solid var(--home-white);
            background:
                linear-gradient(
                    135deg,
                    var(--home-blue),
                    #2c70ff
                );
            box-shadow:
                0 10px 30px rgba(21, 94, 239, 0.23);
        }

        .home-process-card:nth-child(2)
        .home-process-number-wrapper {
            background:
                linear-gradient(
                    135deg,
                    var(--home-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 30px rgba(249, 115, 22, 0.22);
        }

        .home-process-number {
            color: var(--home-white);
            font-size: 22px;
            font-weight: 900;
        }

        .home-process-card h3 {
            margin: 0 0 10px;
            color: var(--home-dark);
            font-size: 20px;
        }

        .home-process-card p {
            max-width: 330px;
            margin: 0 auto;
            color: var(--home-muted);
            font-size: 14px;
            line-height: 1.75;
        }

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        .home-category-layout {
            display: grid;
            grid-template-columns: minmax(290px, 0.72fr) minmax(0, 1.28fr);
            gap: 42px;
            align-items: start;
        }

        .home-category-intro {
            position: sticky;
            top: 125px;
            padding: 32px;
            border: 1px solid var(--home-border);
            border-radius: 26px;
            color: var(--home-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.32),
                    transparent 34%
                ),
                linear-gradient(
                    145deg,
                    var(--home-blue-dark),
                    var(--home-blue)
                );
            box-shadow:
                0 20px 54px rgba(21, 94, 239, 0.23);
        }

        .home-category-intro-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            color: #eaf2ff;
            background: rgba(255, 255, 255, 0.11);
            font-size: 11px;
            font-weight: 900;
        }

        .home-category-intro h2 {
            margin: 18px 0 12px;
            color: var(--home-white);
            font-size: 35px;
            line-height: 1.12;
            letter-spacing: -0.9px;
        }

        .home-category-intro p {
            margin: 0;
            color: #dbeafe;
            font-size: 14px;
            line-height: 1.75;
        }

        .home-category-summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 11px;
            margin-top: 25px;
        }

        .home-category-summary-item {
            padding: 14px;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.09);
        }

        .home-category-summary-item strong,
        .home-category-summary-item span {
            display: block;
        }

        .home-category-summary-item strong {
            color: var(--home-white);
            font-size: 24px;
            line-height: 1;
        }

        .home-category-summary-item span {
            margin-top: 5px;
            color: #dbeafe;
            font-size: 10px;
            font-weight: 700;
        }

        .home-category-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
        }

        .home-category-card {
            min-width: 0;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 21px;
            border: 1px solid var(--home-border);
            border-radius: 21px;
            background: var(--home-white);
            box-shadow:
                0 6px 22px rgba(16, 24, 40, 0.04);
            transition:
                transform 0.2s ease,
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .home-category-card:hover {
            border-color: #bfd3ff;
            box-shadow:
                0 14px 34px rgba(16, 24, 40, 0.08);
            transform: translateY(-4px);
        }

        .home-category-icon {
            width: 50px;
            height: 50px;
            flex: 0 0 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: var(--home-blue);
            background: var(--home-blue-soft);
        }

        .home-category-card:nth-child(even)
        .home-category-icon {
            color: var(--home-orange-dark);
            background: var(--home-orange-soft);
        }

        .home-category-icon svg {
            width: 24px;
            height: 24px;
        }

        .home-category-content {
            min-width: 0;
        }

        .home-category-content h3 {
            margin: 0 0 7px;
            color: var(--home-dark);
            font-size: 17px;
        }

        .home-category-content p {
            margin: 0;
            color: var(--home-muted);
            font-size: 12px;
            line-height: 1.65;
        }

        .home-category-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            color: var(--home-blue);
            font-size: 11px;
            font-weight: 900;
        }

        /*
        |--------------------------------------------------------------------------
        | Final CTA
        |--------------------------------------------------------------------------
        */

        .home-final-cta {
            position: relative;
            overflow: hidden;
            padding: 50px;
            border-radius: 30px;
            color: var(--home-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.42),
                    transparent 32%
                ),
                radial-gradient(
                    circle at bottom left,
                    rgba(255, 255, 255, 0.12),
                    transparent 36%
                ),
                linear-gradient(
                    135deg,
                    var(--home-blue-dark),
                    var(--home-blue)
                );
            box-shadow:
                0 26px 70px rgba(21, 94, 239, 0.25);
        }

        .home-final-cta::before {
            content: "";
            position: absolute;
            top: -75px;
            right: -70px;
            width: 210px;
            height: 210px;
            border: 35px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .home-final-cta-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 34px;
            align-items: center;
        }

        .home-final-cta-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 13px;
            color: #dbeafe;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .home-final-cta-badge::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #fdba74;
        }

        .home-final-cta h2 {
            margin: 0 0 11px;
            color: var(--home-white);
            font-size: clamp(31px, 4vw, 47px);
            line-height: 1.1;
            letter-spacing: -1.2px;
        }

        .home-final-cta p {
            max-width: 700px;
            margin: 0;
            color: #dbeafe;
            font-size: 15px;
            line-height: 1.75;
        }

        .home-final-cta-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .home-final-secondary-link {
            color: #dbeafe;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
        }

        .home-final-secondary-link:hover {
            color: var(--home-white);
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .home-reveal {
            opacity: 0;
            transform: translateY(22px);
            transition:
                opacity 0.6s ease,
                transform 0.6s ease;
        }

        .home-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1100px) {
            .home-hero-grid {
                grid-template-columns: minmax(0, 1fr) minmax(370px, 0.9fr);
                gap: 40px;
            }

            .home-hero h1 {
                font-size: clamp(43px, 5vw, 62px);
            }

            .home-trust-wrapper {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .home-trust-item:nth-child(2) {
                border-right: 0;
            }

            .home-trust-item:nth-child(-n + 2) {
                border-bottom: 1px solid var(--home-border);
            }
        }

        @media (max-width: 900px) {
            .home-section {
                padding: 70px 0;
            }

            .home-hero {
                min-height: auto;
                padding: 64px 0 90px;
            }

            .home-hero-grid {
                grid-template-columns: 1fr;
                gap: 46px;
            }

            .home-hero-content {
                max-width: 760px;
            }

            .home-hero-visual {
                max-width: 660px;
                margin: 0 auto;
            }

            .home-hero-status {
                left: -12px;
            }

            .home-feature-grid,
            .home-service-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .home-process-line {
                display: none;
            }

            .home-process-grid {
                gap: 24px;
            }

            .home-category-layout {
                grid-template-columns: 1fr;
            }

            .home-category-intro {
                position: static;
            }

            .home-final-cta-grid {
                grid-template-columns: 1fr;
            }

            .home-final-cta-actions {
                align-items: flex-start;
            }
        }

        @media (max-width: 700px) {
            .home-trust-wrapper,
            .home-feature-grid,
            .home-service-grid,
            .home-process-grid,
            .home-category-grid {
                grid-template-columns: 1fr;
            }

            .home-trust-item {
                border-right: 0;
                border-bottom: 1px solid var(--home-border);
            }

            .home-trust-item:last-child {
                border-bottom: 0;
            }

            .home-section-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .home-section-row .home-button {
                width: 100%;
            }

            .home-process-card {
                display: grid;
                grid-template-columns: 70px minmax(0, 1fr);
                column-gap: 17px;
                text-align: left;
            }

            .home-process-number-wrapper {
                width: 70px;
                height: 70px;
                grid-row: 1 / span 2;
                margin: 0;
                border-width: 7px;
            }

            .home-process-card h3 {
                align-self: end;
            }

            .home-process-card p {
                max-width: none;
                margin: 0;
            }
        }

        @media (max-width: 640px) {
            .home-section {
                padding: 56px 0;
            }

            .home-section-heading {
                margin-bottom: 28px;
            }

            .home-section-heading h2 {
                font-size: 31px;
            }

            .home-section-heading p {
                font-size: 14px;
            }

            .home-hero {
                padding: 50px 0 74px;
            }

            .home-hero::before,
            .home-hero::after {
                display: none;
            }

            .home-hero-grid {
                gap: 38px;
            }

            .home-hero-badge {
                font-size: 11px;
            }

            .home-hero h1 {
                margin-top: 19px;
                font-size: 39px;
                letter-spacing: -1.5px;
            }

            .home-hero-description {
                font-size: 15px;
            }

            .home-hero-actions {
                flex-direction: column;
            }

            .home-hero-actions .home-button {
                width: 100%;
            }

            .home-hero-benefits {
                display: grid;
                grid-template-columns: 1fr;
                gap: 11px;
            }

            .home-hero-card {
                padding: 14px;
                border-radius: 25px;
            }

            .home-hero-card::before {
                right: 26px;
                width: 70px;
            }

            .home-hero-image-wrapper {
                border-radius: 19px;
            }

            .home-hero-image {
                height: 315px;
            }

            .home-hero-status {
                position: static;
                margin-bottom: 13px;
            }

            .home-hero-stats {
                gap: 8px;
            }

            .home-hero-stat {
                padding: 14px 9px;
                border-radius: 14px;
            }

            .home-hero-stat strong {
                font-size: 21px;
            }

            .home-hero-stat span {
                font-size: 9px;
            }

            .home-trust-strip {
                margin-top: -28px;
            }

            .home-trust-wrapper {
                border-radius: 18px;
            }

            .home-trust-item {
                padding: 17px;
            }

            .home-feature-card,
            .home-service-content,
            .home-category-intro {
                padding: 22px;
            }

            .home-service-image-wrapper {
                height: 195px;
            }

            .home-category-intro h2 {
                font-size: 30px;
            }

            .home-final-cta {
                padding: 31px 23px;
                border-radius: 23px;
            }

            .home-final-cta h2 {
                font-size: 32px;
            }

            .home-final-cta-actions,
            .home-final-cta-actions .home-button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .home-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .home-service-image,
            .home-feature-card,
            .home-service-card,
            .home-category-card,
            .home-button {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="home-page">
        {{-- Hero --}}
        <section class="home-hero">
            <div class="container home-hero-grid">
                <div class="home-hero-content">
                    <span class="home-hero-badge">
                        <span class="home-hero-badge-icon">
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
                                <path d="M6 9V2h12v7"/>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                <rect width="12" height="8" x="6" y="14"/>
                            </svg>
                        </span>

                        Print cepat untuk mahasiswa
                    </span>

                    <h1>
                        {{ $heroTitle }}
                    </h1>

                    <p class="home-hero-description">
                        {{ $heroSubtitle }}
                    </p>

                    <div class="home-hero-actions">
                        <a
                            href="{{ $tujuanPesanan }}"
                            class="home-button home-button-primary"
                        >
                            {{ $labelPesanan }}

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
                                <path d="M5 12h14"/>
                                <path d="m13 6 6 6-6 6"/>
                            </svg>
                        </a>

                        <a
                            href="{{ route('layanan.index') }}"
                            class="home-button home-button-secondary"
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
                                <path d="M6 9V2h12v7"/>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                <rect width="12" height="8" x="6" y="14"/>
                            </svg>

                            Lihat Layanan
                        </a>
                    </div>

                    <div class="home-hero-benefits">
                        <span class="home-hero-benefit">
                            <span class="home-hero-benefit-icon">
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
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>

                            Estimasi biaya langsung
                        </span>

                        <span class="home-hero-benefit">
                            <span class="home-hero-benefit-icon">
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
                                    <path d="M12 3v12"/>
                                    <path d="m7 10 5 5 5-5"/>
                                    <path d="M5 21h14"/>
                                </svg>
                            </span>

                            Upload hingga 5 file
                        </span>

                        <span class="home-hero-benefit">
                            <span class="home-hero-benefit-icon">
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
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 7v5l3 2"/>
                                </svg>
                            </span>

                            Status pesanan transparan
                        </span>
                    </div>
                </div>

                <div class="home-hero-visual">
                    <div class="home-hero-status">
                        <span class="home-hero-status-icon">
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
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </span>

                        <span>
                            <strong>Pemesanan online aktif</strong>
                            <span>Siap menerima file pelanggan</span>
                        </span>
                    </div>

                    <div class="home-hero-card">
                        <div class="home-hero-image-wrapper">
                            <img
                                src="{{ $heroImage }}"
                                alt="{{ $namaWebsite }}"
                                class="home-hero-image"
                                fetchpriority="high"
                            >

                            <div class="home-hero-image-overlay">
                                <strong>{{ $namaWebsite }}</strong>

                                <span>
                                    Solusi cetak praktis untuk kebutuhan akademik
                                </span>
                            </div>
                        </div>

                        <div class="home-hero-stats">
                            <div class="home-hero-stat">
                                <strong>{{ $jumlahLayanan }}</strong>
                                <span>Layanan aktif</span>
                            </div>

                            <div class="home-hero-stat">
                                <strong>5</strong>
                                <span>Status pesanan</span>
                            </div>

                            <div class="home-hero-stat">
                                <strong>50 MB</strong>
                                <span>Total upload</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Trust Strip --}}
        <section class="home-trust-strip">
            <div class="container">
                <div class="home-trust-wrapper">
                    <div class="home-trust-item">
                        <span class="home-trust-icon">
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
                                <path d="M12 2 3 7v6c0 5 3.5 8.5 9 9 5.5-.5 9-4 9-9V7z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </span>

                        <span>
                            <strong>File tersimpan aman</strong>
                            <span>File hanya diakses sesuai hak akses.</span>
                        </span>
                    </div>

                    <div class="home-trust-item">
                        <span class="home-trust-icon">
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
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2"/>
                            </svg>
                        </span>

                        <span>
                            <strong>Pemesanan terjadwal</strong>
                            <span>Pilih waktu pengambilan yang tersedia.</span>
                        </span>
                    </div>

                    <div class="home-trust-item">
                        <span class="home-trust-icon">
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
                                <path d="M4 19V5"/>
                                <path d="M4 19h16"/>
                                <path d="m7 15 4-4 3 3 5-6"/>
                            </svg>
                        </span>

                        <span>
                            <strong>Status dapat dipantau</strong>
                            <span>Perkembangan pesanan tampil di dashboard.</span>
                        </span>
                    </div>

                    <div class="home-trust-item">
                        <span class="home-trust-icon">
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
                        </span>

                        <span>
                            <strong>Admin mudah dihubungi</strong>
                            <span>Konfirmasi dapat dilanjutkan melalui WhatsApp.</span>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Advantages --}}
        <section class="home-section">
            <div class="container">
                <div class="home-section-heading center home-reveal">
                    <span class="home-eyebrow">
                        Keunggulan sistem
                    </span>

                    <h2>
                        Pemesanan cetak dibuat lebih sederhana
                    </h2>

                    <p>
                        Seluruh proses utama tersedia dalam satu website.
                        Pelanggan tidak perlu mengirim informasi pesanan
                        berulang kali melalui percakapan yang berbeda.
                    </p>
                </div>

                <div class="home-feature-grid">
                    <article class="home-feature-card home-reveal">
                        <span class="home-feature-icon">
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
                                <path d="M6 9V2h12v7"/>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                <rect width="12" height="8" x="6" y="14"/>
                            </svg>
                        </span>

                        <h3>Pesan Secara Online</h3>

                        <p>
                            Pilih layanan, tentukan jumlah halaman,
                            jumlah copy, ukuran kertas, jadwal, dan
                            lokasi pengambilan dalam satu formulir.
                        </p>

                        <span class="home-feature-number">
                            01
                        </span>
                    </article>

                    <article class="home-feature-card home-reveal">
                        <span class="home-feature-icon">
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
                                <path d="M12 3v12"/>
                                <path d="m7 10 5 5 5-5"/>
                                <path d="M5 21h14"/>
                            </svg>
                        </span>

                        <h3>Upload File Terpusat</h3>

                        <p>
                            File dokumen tersimpan pada pesanan yang
                            sesuai sehingga admin lebih mudah melakukan
                            pemeriksaan sebelum pencetakan.
                        </p>

                        <span class="home-feature-number">
                            02
                        </span>
                    </article>

                    <article class="home-feature-card home-reveal">
                        <span class="home-feature-icon">
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
                                <path d="M4 19V5"/>
                                <path d="M4 19h16"/>
                                <path d="m7 15 4-4 3 3 5-6"/>
                            </svg>
                        </span>

                        <h3>Pantau Status Pesanan</h3>

                        <p>
                            Lihat status pesanan mulai dari menunggu
                            verifikasi, diproses, siap diambil, hingga
                            selesai melalui dashboard pelanggan.
                        </p>

                        <span class="home-feature-number">
                            03
                        </span>
                    </article>
                </div>
            </div>
        </section>

        {{-- Popular Services --}}
        <section class="home-section home-section-white">
            <div class="container">
                <div class="home-section-row">
                    <div class="home-section-heading home-reveal">
                        <span class="home-eyebrow">
                            Layanan populer
                        </span>

                        <h2>
                            Pilih layanan sesuai kebutuhanmu
                        </h2>

                        <p>
                            Temukan layanan cetak untuk tugas, laporan,
                            proposal, presentasi, dan dokumen akademik
                            lainnya.
                        </p>
                    </div>

                    <a
                        href="{{ route('layanan.index') }}"
                        class="home-button home-button-secondary"
                    >
                        Semua Layanan

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
                            <path d="M5 12h14"/>
                            <path d="m13 6 6 6-6 6"/>
                        </svg>
                    </a>
                </div>

                <div class="home-service-grid">
                    @forelse ($layanans->take(6) as $layanan)
                        @php
                            $gambarLayanan = $layanan->gambar
                                ? \Illuminate\Support\Facades\Storage::url(
                                    $layanan->gambar
                                )
                                : asset('images/placeholder.png');

                            $namaKategori = $layanan->kategoriLayanan?->nama_kategori
                                ?? 'Layanan Cetak';
                        @endphp

                        <article class="home-service-card home-reveal">
                            <div class="home-service-image-wrapper">
                                <img
                                    src="{{ $gambarLayanan }}"
                                    alt="{{ $layanan->nama_layanan }}"
                                    class="home-service-image"
                                    loading="lazy"
                                >

                                <span class="home-service-category">
                                    {{ $namaKategori }}
                                </span>
                            </div>

                            <div class="home-service-content">
                                <h3>
                                    {{ $layanan->nama_layanan }}
                                </h3>

                                <p class="home-service-description">
                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $layanan->deskripsi
                                                ?? 'Layanan cetak tersedia untuk kebutuhan dokumen mahasiswa.',
                                            110
                                        )
                                    }}
                                </p>

                                <div class="home-service-footer">
                                    <div class="home-service-price">
                                        <span>Mulai dari</span>

                                        <strong>
                                            Rp {{
                                                number_format(
                                                    (float) $layanan->harga_dasar,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                            / {{ $layanan->satuan }}
                                        </strong>
                                    </div>

                                    <a
                                        href="{{ route('layanan.show', $layanan) }}"
                                        class="home-service-detail"
                                    >
                                        Detail

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
                                            <path d="M5 12h14"/>
                                            <path d="m13 6 6 6-6 6"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="home-empty-state">
                            <span class="home-empty-icon">
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
                                    <path d="M6 9V2h12v7"/>
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                    <rect width="12" height="8" x="6" y="14"/>
                                </svg>
                            </span>

                            <h3>Belum ada layanan aktif</h3>

                            <p>
                                Layanan akan tampil setelah admin menambahkan
                                dan mengaktifkan layanan melalui dashboard.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Ordering Process --}}
        <section class="home-section home-section-soft">
            <div class="container">
                <div class="home-section-heading center home-reveal">
                    <span class="home-eyebrow">
                        Cara pemesanan
                    </span>

                    <h2>
                        Tiga langkah untuk mencetak dokumen
                    </h2>

                    <p>
                        Proses pemesanan dirancang singkat agar pelanggan
                        dapat mengirim file dan mengetahui perkembangan
                        pesanan dengan mudah.
                    </p>
                </div>

                <div class="home-process-wrapper">
                    <div
                        class="home-process-line"
                        aria-hidden="true"
                    ></div>

                    <div class="home-process-grid">
                        <article class="home-process-card home-reveal">
                            <div class="home-process-number-wrapper">
                                <span class="home-process-number">
                                    01
                                </span>
                            </div>

                            <h3>Pilih Layanan</h3>

                            <p>
                                Buka daftar layanan, pilih kebutuhan cetak,
                                lalu periksa harga dan informasi layanan.
                            </p>
                        </article>

                        <article class="home-process-card home-reveal">
                            <div class="home-process-number-wrapper">
                                <span class="home-process-number">
                                    02
                                </span>
                            </div>

                            <h3>Isi Detail dan Upload</h3>

                            <p>
                                Masuk ke akun, isi detail pesanan, unggah
                                file, lalu periksa estimasi biaya.
                            </p>
                        </article>

                        <article class="home-process-card home-reveal">
                            <div class="home-process-number-wrapper">
                                <span class="home-process-number">
                                    03
                                </span>
                            </div>

                            <h3>Pantau Pesanan</h3>

                            <p>
                                Lihat perkembangan pengerjaan melalui
                                dashboard sampai pesanan siap diambil.
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        {{-- Categories --}}
        <section class="home-section home-section-white">
            <div class="container">
                <div class="home-category-layout">
                    <div class="home-category-intro home-reveal">
                        <span class="home-category-intro-badge">
                            Kategori layanan
                        </span>

                        <h2>
                            Temukan kebutuhan cetak lebih cepat
                        </h2>

                        <p>
                            Layanan dikelompokkan berdasarkan kategori
                            agar pelanggan dapat menemukan pilihan yang
                            sesuai tanpa mencari satu per satu.
                        </p>

                        <div class="home-category-summary">
                            <div class="home-category-summary-item">
                                <strong>
                                    {{ $jumlahKategori }}
                                </strong>

                                <span>Kategori aktif</span>
                            </div>

                            <div class="home-category-summary-item">
                                <strong>
                                    {{ $jumlahLayanan }}
                                </strong>

                                <span>Layanan tersedia</span>
                            </div>
                        </div>
                    </div>

                    <div class="home-category-grid">
                        @forelse ($kategoriLayanans as $kategori)
                            <article class="home-category-card home-reveal">
                                <span class="home-category-icon">
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
                                        <path d="M3 7h18"/>
                                        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                        <path d="M8 11h8"/>
                                        <path d="M8 15h5"/>
                                    </svg>
                                </span>

                                <div class="home-category-content">
                                    <h3>
                                        {{ $kategori->nama_kategori }}
                                    </h3>

                                    <p>
                                        {{
                                            \Illuminate\Support\Str::limit(
                                                $kategori->deskripsi
                                                    ?? 'Kategori layanan cetak yang tersedia untuk pelanggan.',
                                                105
                                            )
                                        }}
                                    </p>

                                    <span class="home-category-count">
                                        {{ $kategori->layanans->count() }}
                                        layanan tersedia
                                    </span>
                                </div>
                            </article>
                        @empty
                            <div class="home-empty-state">
                                <span class="home-empty-icon">
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
                                        <path d="M3 7h18"/>
                                        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                    </svg>
                                </span>

                                <h3>Belum ada kategori aktif</h3>

                                <p>
                                    Kategori layanan akan tampil setelah
                                    diaktifkan melalui dashboard admin.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- Final CTA --}}
        <section class="home-section">
            <div class="container">
                <div class="home-final-cta home-reveal">
                    <div class="home-final-cta-grid">
                        <div>
                            <span class="home-final-cta-badge">
                                Pemesanan tersedia
                            </span>

                            <h2>
                                Dokumenmu siap dicetak tanpa proses yang rumit
                            </h2>

                            <p>
                                Buat akun pelanggan, pilih layanan, unggah
                                file, dan pantau pengerjaan pesanan langsung
                                dari website {{ $namaWebsite }}.
                            </p>
                        </div>

                        <div class="home-final-cta-actions">
                            <a
                                href="{{ $tujuanPesanan }}"
                                class="home-button home-button-white"
                            >
                                {{ $labelPesanan }}

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
                                    <path d="M5 12h14"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>
                            </a>

                            <a
                                href="{{ route('kontak.index') }}"
                                class="home-final-secondary-link"
                            >
                                Butuh bantuan? Hubungi admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealElements = document.querySelectorAll(
                '.home-reveal'
            );

            if (
                !('IntersectionObserver' in window)
                || window.matchMedia(
                    '(prefers-reduced-motion: reduce)'
                ).matches
            ) {
                revealElements.forEach((element) => {
                    element.classList.add('is-visible');
                });

                return;
            }

            const observer = new IntersectionObserver(
                (entries, currentObserver) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.classList.add('is-visible');
                        currentObserver.unobserve(entry.target);
                    });
                },
                {
                    threshold: 0.12,
                    rootMargin: '0px 0px -45px 0px',
                }
            );

            revealElements.forEach((element) => {
                observer.observe(element);
            });
        });
    </script>
@endpush