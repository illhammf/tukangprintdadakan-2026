@extends('layouts.public')

@section(
    'title',
    $layanan->nama_layanan
    . ' - '
    . ($website?->nama_website ?? 'Tukang Print Dadakan')
)

@php
    $namaWebsite = $website?->nama_website
        ?? 'Tukang Print Dadakan';

    $gambarLayanan = $layanan->gambar
        ? \Illuminate\Support\Facades\Storage::url($layanan->gambar)
        : asset('images/placeholder.png');

    $namaKategori = $layanan->kategoriLayanan?->nama_kategori
        ?? 'Layanan Cetak';

    $deskripsiLayanan = $layanan->deskripsi
        ?? 'Layanan cetak tersedia untuk kebutuhan dokumen mahasiswa.';

    $layananDapatDipesan = (bool) $layanan->status
        && (bool) $layanan->bisa_online;

    $tujuanPesanan = auth()->check()
        ? route(
            'customer.pesanan.create',
            ['layanan' => $layanan->id]
        )
        : route('login');

    $labelPesanan = auth()->check()
        ? 'Buat Pesanan'
        : 'Login untuk Memesan';
@endphp

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Service Detail Page
        |--------------------------------------------------------------------------
        */

        .service-detail-page {
            --detail-blue: var(--public-blue, #155eef);
            --detail-blue-dark: var(--public-blue-dark, #1046b8);
            --detail-blue-soft: var(--public-blue-soft, #edf4ff);

            --detail-orange: var(--public-orange, #f97316);
            --detail-orange-dark: var(--public-orange-dark, #c2410c);
            --detail-orange-soft: var(--public-orange-soft, #fff1e7);

            --detail-green: #16a34a;
            --detail-green-soft: #ecfdf3;

            --detail-red: #dc2626;
            --detail-red-soft: #fff1f2;

            --detail-dark: var(--public-dark, #101828);
            --detail-text: var(--public-text, #344054);
            --detail-muted: var(--public-muted, #667085);

            --detail-white: #ffffff;
            --detail-soft: #f7f9fc;
            --detail-border: #e4e7ec;

            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Breadcrumb
        |--------------------------------------------------------------------------
        */

        .service-detail-breadcrumb-section {
            padding: 17px 0;
            border-bottom: 1px solid var(--detail-border);
            background: var(--detail-white);
        }

        .service-detail-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--detail-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .service-detail-breadcrumb a {
            color: var(--detail-muted);
            transition: color 0.2s ease;
        }

        .service-detail-breadcrumb a:hover {
            color: var(--detail-blue);
        }

        .service-detail-breadcrumb svg {
            width: 14px;
            height: 14px;
            flex: 0 0 14px;
        }

        .service-detail-breadcrumb strong {
            max-width: 420px;
            overflow: hidden;
            color: var(--detail-blue);
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .service-detail-hero {
            position: relative;
            overflow: hidden;
            padding: 72px 0 66px;
            border-bottom: 1px solid var(--detail-border);
            background:
                radial-gradient(
                    circle at 9% 22%,
                    rgba(21, 94, 239, 0.15),
                    transparent 27%
                ),
                radial-gradient(
                    circle at 92% 9%,
                    rgba(249, 115, 22, 0.15),
                    transparent 25%
                ),
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #f7f9fd 100%
                );
        }

        .service-detail-hero::before {
            content: "";
            position: absolute;
            top: -110px;
            right: -90px;
            width: 290px;
            height: 290px;
            border: 44px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .service-detail-hero::after {
            content: "";
            position: absolute;
            bottom: -100px;
            left: -90px;
            width: 250px;
            height: 250px;
            border: 40px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .service-detail-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1.05fr)
                minmax(330px, 0.55fr);
            gap: 48px;
            align-items: center;
        }

        .service-detail-hero-content {
            max-width: 780px;
        }

        .service-detail-category-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--detail-orange-dark);
            background: var(--detail-orange-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .service-detail-category-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--detail-white);
            background: var(--detail-orange);
        }

        .service-detail-category-icon svg {
            width: 15px;
            height: 15px;
        }

        .service-detail-hero h1 {
            margin: 23px 0 17px;
            color: var(--detail-dark);
            font-size: clamp(40px, 5.6vw, 66px);
            line-height: 1.05;
            letter-spacing: -2.2px;
        }

        .service-detail-hero-description {
            max-width: 720px;
            margin: 0;
            color: var(--detail-muted);
            font-size: 16px;
            line-height: 1.8;
        }

        .service-detail-hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 25px;
        }

        .service-detail-hero-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 39px;
            padding: 8px 12px;
            border: 1px solid var(--detail-border);
            border-radius: 12px;
            color: var(--detail-text);
            background: rgba(255, 255, 255, 0.82);
            font-size: 11px;
            font-weight: 800;
        }

        .service-detail-hero-meta-item svg {
            width: 17px;
            height: 17px;
            color: var(--detail-blue);
        }

        .service-detail-hero-meta-item.orange svg {
            color: var(--detail-orange);
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Price Card
        |--------------------------------------------------------------------------
        */

        .service-detail-price-card {
            position: relative;
            padding: 27px;
            border: 1px solid rgba(228, 231, 236, 0.94);
            border-radius: 29px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            box-shadow:
                0 25px 68px rgba(16, 24, 40, 0.14);
        }

        .service-detail-price-card::before {
            content: "";
            position: absolute;
            top: -11px;
            right: 29px;
            width: 77px;
            height: 24px;
            border-radius: 8px 8px 3px 3px;
            background:
                linear-gradient(
                    90deg,
                    var(--detail-blue),
                    var(--detail-orange)
                );
            transform: rotate(3deg);
        }

        .service-detail-price-card-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--detail-muted);
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .service-detail-price-card-label::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--detail-orange);
        }

        .service-detail-main-price {
            margin: 13px 0 6px;
            color: var(--detail-blue);
            font-size: clamp(34px, 4vw, 48px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -1.4px;
        }

        .service-detail-main-price-unit {
            color: var(--detail-muted);
            font-size: 12px;
            font-weight: 800;
        }

        .service-detail-price-divider {
            height: 1px;
            margin: 21px 0;
            background: var(--detail-border);
        }

        .service-detail-price-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 0;
            color: var(--detail-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .service-detail-price-note svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            color: var(--detail-orange);
        }

        /*
        |--------------------------------------------------------------------------
        | Main Detail Section
        |--------------------------------------------------------------------------
        */

        .service-detail-main-section {
            padding: 72px 0 88px;
        }

        .service-detail-main-grid {
            display: grid;
            grid-template-columns:
                minmax(0, 0.92fr)
                minmax(0, 1.08fr);
            gap: 30px;
            align-items: start;
        }

        /*
        |--------------------------------------------------------------------------
        | Image Panel
        |--------------------------------------------------------------------------
        */

        .service-detail-media-column {
            display: grid;
            gap: 20px;
        }

        .service-detail-image-panel {
            position: relative;
            overflow: hidden;
            padding: 17px;
            border: 1px solid var(--detail-border);
            border-radius: 27px;
            background: var(--detail-white);
            box-shadow:
                0 14px 42px rgba(16, 24, 40, 0.08);
        }

        .service-detail-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 21px;
            background:
                linear-gradient(
                    135deg,
                    var(--detail-blue-soft),
                    var(--detail-orange-soft)
                );
        }

        .service-detail-image {
            width: 100%;
            height: 475px;
            display: block;
            object-fit: cover;
            transition: transform 0.45s ease;
        }

        .service-detail-image-panel:hover
        .service-detail-image {
            transform: scale(1.035);
        }

        .service-detail-image-shade {
            position: absolute;
            inset: auto 0 0;
            height: 48%;
            pointer-events: none;
            background:
                linear-gradient(
                    180deg,
                    transparent,
                    rgba(16, 24, 40, 0.65)
                );
        }

        .service-detail-image-caption {
            position: absolute;
            right: 20px;
            bottom: 20px;
            left: 20px;
            color: var(--detail-white);
        }

        .service-detail-image-caption strong,
        .service-detail-image-caption span {
            display: block;
        }

        .service-detail-image-caption strong {
            font-size: 17px;
        }

        .service-detail-image-caption span {
            margin-top: 3px;
            color: #e2e8f0;
            font-size: 11px;
        }

        .service-detail-image-status {
            position: absolute;
            top: 31px;
            left: 31px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 999px;
            color: #166534;
            background: rgba(240, 253, 244, 0.92);
            backdrop-filter: blur(10px);
            font-size: 10px;
            font-weight: 900;
        }

        .service-detail-image-status::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: #22c55e;
        }

        .service-detail-image-status.inactive {
            color: #991b1b;
            background: rgba(255, 241, 242, 0.92);
        }

        .service-detail-image-status.inactive::before {
            background: var(--detail-red);
        }

        /*
        |--------------------------------------------------------------------------
        | Quick Information
        |--------------------------------------------------------------------------
        */

        .service-detail-quick-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .service-detail-quick-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border: 1px solid var(--detail-border);
            border-radius: 17px;
            background: var(--detail-white);
            box-shadow:
                0 6px 20px rgba(16, 24, 40, 0.045);
        }

        .service-detail-quick-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--detail-blue);
            background: var(--detail-blue-soft);
        }

        .service-detail-quick-item:nth-child(even)
        .service-detail-quick-icon {
            color: var(--detail-orange-dark);
            background: var(--detail-orange-soft);
        }

        .service-detail-quick-icon svg {
            width: 20px;
            height: 20px;
        }

        .service-detail-quick-copy {
            min-width: 0;
        }

        .service-detail-quick-copy span,
        .service-detail-quick-copy strong {
            display: block;
        }

        .service-detail-quick-copy span {
            color: var(--detail-muted);
            font-size: 10px;
            font-weight: 800;
        }

        .service-detail-quick-copy strong {
            margin-top: 3px;
            overflow: hidden;
            color: var(--detail-dark);
            font-size: 12px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /*
        |--------------------------------------------------------------------------
        | Detail Panel
        |--------------------------------------------------------------------------
        */

        .service-detail-content-panel {
            padding: 31px;
            border: 1px solid var(--detail-border);
            border-radius: 27px;
            background: var(--detail-white);
            box-shadow:
                0 14px 42px rgba(16, 24, 40, 0.07);
        }

        .service-detail-content-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 11px;
            border: 1px solid #cfe0ff;
            border-radius: 999px;
            color: var(--detail-blue);
            background: var(--detail-blue-soft);
            font-size: 10px;
            font-weight: 900;
        }

        .service-detail-content-badge svg {
            width: 15px;
            height: 15px;
        }

        .service-detail-content-panel h2 {
            margin: 17px 0 12px;
            color: var(--detail-dark);
            font-size: clamp(29px, 4vw, 42px);
            line-height: 1.12;
            letter-spacing: -1px;
        }

        .service-detail-description {
            margin: 0;
            color: var(--detail-muted);
            font-size: 14px;
            line-height: 1.85;
            white-space: pre-line;
        }

        /*
        |--------------------------------------------------------------------------
        | Price Box
        |--------------------------------------------------------------------------
        */

        .service-detail-inline-price {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 26px 0;
            padding: 21px;
            border: 1px solid #cfe0ff;
            border-radius: 20px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.10),
                    transparent 35%
                ),
                linear-gradient(
                    135deg,
                    var(--detail-blue-soft),
                    #f9fbff
                );
        }

        .service-detail-inline-price::before {
            content: "";
            position: absolute;
            top: -38px;
            right: -38px;
            width: 100px;
            height: 100px;
            border: 20px solid rgba(21, 94, 239, 0.055);
            border-radius: 999px;
        }

        .service-detail-inline-price-copy,
        .service-detail-inline-price-value {
            position: relative;
            z-index: 2;
        }

        .service-detail-inline-price-copy span,
        .service-detail-inline-price-copy strong {
            display: block;
        }

        .service-detail-inline-price-copy span {
            color: var(--detail-muted);
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .service-detail-inline-price-copy strong {
            margin-top: 5px;
            color: var(--detail-dark);
            font-size: 13px;
        }

        .service-detail-inline-price-value {
            color: var(--detail-blue);
            font-size: 25px;
            font-weight: 900;
            text-align: right;
        }

        .service-detail-inline-price-value span {
            display: block;
            margin-top: 4px;
            color: var(--detail-muted);
            font-size: 10px;
            font-weight: 800;
        }

        /*
        |--------------------------------------------------------------------------
        | Specification List
        |--------------------------------------------------------------------------
        */

        .service-detail-specification {
            margin-top: 25px;
        }

        .service-detail-specification-heading {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 12px;
            color: var(--detail-dark);
            font-size: 14px;
            font-weight: 900;
        }

        .service-detail-specification-heading svg {
            width: 19px;
            height: 19px;
            color: var(--detail-orange);
        }

        .service-detail-specification-list {
            display: grid;
            gap: 0;
            overflow: hidden;
            border: 1px solid var(--detail-border);
            border-radius: 18px;
        }

        .service-detail-specification-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 15px 17px;
            border-bottom: 1px solid var(--detail-border);
            background: var(--detail-white);
        }

        .service-detail-specification-item:last-child {
            border-bottom: 0;
        }

        .service-detail-specification-item:nth-child(even) {
            background: #fcfcfd;
        }

        .service-detail-specification-label {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--detail-muted);
            font-size: 12px;
            font-weight: 750;
        }

        .service-detail-specification-label svg {
            width: 17px;
            height: 17px;
            color: var(--detail-blue);
        }

        .service-detail-specification-value {
            color: var(--detail-dark);
            font-size: 12px;
            font-weight: 900;
            text-align: right;
        }

        .service-detail-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 999px;
            color: #166534;
            background: var(--detail-green-soft);
            font-size: 9px;
            font-weight: 900;
        }

        .service-detail-status-pill::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--detail-green);
        }

        .service-detail-status-pill.inactive {
            color: #991b1b;
            background: var(--detail-red-soft);
        }

        .service-detail-status-pill.inactive::before {
            background: var(--detail-red);
        }

        /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        */

        .service-detail-actions {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 11px;
            margin-top: 27px;
        }

        .service-detail-button {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 16px;
            border: 1px solid transparent;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 900;
            text-align: center;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .service-detail-button:hover {
            transform: translateY(-2px);
        }

        .service-detail-button svg {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
        }

        .service-detail-button-primary {
            color: var(--detail-white);
            background:
                linear-gradient(
                    135deg,
                    var(--detail-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 24px rgba(249, 115, 22, 0.21);
        }

        .service-detail-button-primary:hover {
            color: var(--detail-white);
            background:
                linear-gradient(
                    135deg,
                    var(--detail-orange-dark),
                    var(--detail-orange)
                );
            box-shadow:
                0 14px 30px rgba(249, 115, 22, 0.27);
        }

        .service-detail-button-outline {
            color: var(--detail-blue);
            border-color: #b9d0ff;
            background: var(--detail-white);
        }

        .service-detail-button-outline:hover {
            color: var(--detail-blue-dark);
            border-color: var(--detail-blue);
            background: var(--detail-blue-soft);
        }

        .service-detail-button-disabled {
            color: #667085;
            border-color: #d0d5dd;
            background: #f2f4f7;
            pointer-events: none;
            box-shadow: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

        .service-detail-note {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 19px;
            padding: 14px;
            border: 1px solid #fed7aa;
            border-radius: 15px;
            color: var(--detail-text);
            background: var(--detail-orange-soft);
            font-size: 11px;
            line-height: 1.65;
        }

        .service-detail-note svg {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            color: var(--detail-orange);
        }

        /*
        |--------------------------------------------------------------------------
        | Process Information
        |--------------------------------------------------------------------------
        */

        .service-detail-process {
            margin-top: 30px;
            padding: 25px;
            border: 1px solid var(--detail-border);
            border-radius: 24px;
            background: var(--detail-white);
            box-shadow:
                0 10px 32px rgba(16, 24, 40, 0.055);
        }

        .service-detail-process-heading {
            margin-bottom: 19px;
        }

        .service-detail-process-heading h3 {
            margin: 0 0 5px;
            color: var(--detail-dark);
            font-size: 20px;
        }

        .service-detail-process-heading p {
            margin: 0;
            color: var(--detail-muted);
            font-size: 12px;
        }

        .service-detail-process-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 13px;
        }

        .service-detail-process-item {
            position: relative;
            padding: 17px;
            border: 1px solid var(--detail-border);
            border-radius: 17px;
            background: #fcfcfd;
        }

        .service-detail-process-number {
            width: 31px;
            height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            border-radius: 10px;
            color: var(--detail-white);
            background:
                linear-gradient(
                    135deg,
                    var(--detail-blue),
                    #2b70ff
                );
            font-size: 11px;
            font-weight: 900;
        }

        .service-detail-process-item:nth-child(2)
        .service-detail-process-number {
            background:
                linear-gradient(
                    135deg,
                    var(--detail-orange),
                    #fb923c
                );
        }

        .service-detail-process-item strong,
        .service-detail-process-item span {
            display: block;
        }

        .service-detail-process-item strong {
            color: var(--detail-dark);
            font-size: 12px;
        }

        .service-detail-process-item span {
            margin-top: 5px;
            color: var(--detail-muted);
            font-size: 10px;
            line-height: 1.55;
        }

        /*
        |--------------------------------------------------------------------------
        | Related Services
        |--------------------------------------------------------------------------
        */

        .service-related-section {
            padding: 82px 0;
            background:
                radial-gradient(
                    circle at top left,
                    rgba(21, 94, 239, 0.055),
                    transparent 30%
                ),
                #f8faff;
        }

        .service-related-heading-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 25px;
            margin-bottom: 34px;
        }

        .service-related-heading {
            max-width: 700px;
        }

        .service-related-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 13px;
            color: var(--detail-blue);
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.09em;
        }

        .service-related-eyebrow::before {
            content: "";
            width: 25px;
            height: 3px;
            border-radius: 999px;
            background:
                linear-gradient(
                    90deg,
                    var(--detail-blue),
                    var(--detail-orange)
                );
        }

        .service-related-heading h2 {
            margin: 0 0 10px;
            color: var(--detail-dark);
            font-size: clamp(31px, 4vw, 44px);
            line-height: 1.13;
            letter-spacing: -1px;
        }

        .service-related-heading p {
            margin: 0;
            color: var(--detail-muted);
            font-size: 14px;
        }

        .service-related-all-button {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 15px;
            border: 1px solid #b9d0ff;
            border-radius: 13px;
            color: var(--detail-blue);
            background: var(--detail-white);
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .service-related-all-button:hover {
            color: var(--detail-blue-dark);
            border-color: var(--detail-blue);
            background: var(--detail-blue-soft);
            transform: translateY(-1px);
        }

        .service-related-all-button svg {
            width: 17px;
            height: 17px;
        }

        .service-related-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .service-related-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--detail-border);
            border-radius: 23px;
            background: var(--detail-white);
            box-shadow:
                0 8px 28px rgba(16, 24, 40, 0.05);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .service-related-card:hover {
            border-color: #bed2ff;
            box-shadow:
                0 19px 46px rgba(16, 24, 40, 0.11);
            transform: translateY(-6px);
        }

        .service-related-image-wrapper {
            position: relative;
            height: 190px;
            overflow: hidden;
            background:
                linear-gradient(
                    135deg,
                    var(--detail-blue-soft),
                    var(--detail-orange-soft)
                );
        }

        .service-related-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform 0.45s ease;
        }

        .service-related-card:hover
        .service-related-image {
            transform: scale(1.055);
        }

        .service-related-category {
            position: absolute;
            top: 13px;
            left: 13px;
            max-width: calc(100% - 26px);
            overflow: hidden;
            padding: 6px 10px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 999px;
            color: var(--detail-dark);
            background: rgba(255, 255, 255, 0.91);
            backdrop-filter: blur(10px);
            font-size: 9px;
            font-weight: 900;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .service-related-content {
            padding: 20px;
        }

        .service-related-content h3 {
            margin: 0 0 8px;
            color: var(--detail-dark);
            font-size: 18px;
            line-height: 1.3;
        }

        .service-related-description {
            min-height: 60px;
            margin: 0;
            color: var(--detail-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .service-related-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 14px;
            margin-top: 17px;
            padding-top: 16px;
            border-top: 1px solid var(--detail-border);
        }

        .service-related-price span,
        .service-related-price strong {
            display: block;
        }

        .service-related-price span {
            color: var(--detail-muted);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .service-related-price strong {
            margin-top: 3px;
            color: var(--detail-blue);
            font-size: 16px;
        }

        .service-related-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--detail-orange-dark);
            font-size: 11px;
            font-weight: 900;
        }

        .service-related-link:hover {
            color: var(--detail-orange-dark);
        }

        .service-related-link svg {
            width: 15px;
            height: 15px;
            transition: transform 0.2s ease;
        }

        .service-related-link:hover svg {
            transform: translateX(3px);
        }

        /*
        |--------------------------------------------------------------------------
        | Final Help CTA
        |--------------------------------------------------------------------------
        */

        .service-detail-help-section {
            padding: 0 0 82px;
            background: #f8faff;
        }

        .service-detail-help {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 30px;
            align-items: center;
            padding: 37px;
            border-radius: 27px;
            color: var(--detail-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.42),
                    transparent 34%
                ),
                linear-gradient(
                    135deg,
                    var(--detail-blue-dark),
                    var(--detail-blue)
                );
            box-shadow:
                0 24px 62px rgba(21, 94, 239, 0.24);
        }

        .service-detail-help::before {
            content: "";
            position: absolute;
            top: -75px;
            right: -60px;
            width: 190px;
            height: 190px;
            border: 31px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .service-detail-help-content,
        .service-detail-help-actions {
            position: relative;
            z-index: 2;
        }

        .service-detail-help-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 10px;
            color: #dbeafe;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .service-detail-help-label::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #fdba74;
        }

        .service-detail-help h2 {
            margin: 0 0 9px;
            color: var(--detail-white);
            font-size: 30px;
            line-height: 1.15;
        }

        .service-detail-help p {
            max-width: 680px;
            margin: 0;
            color: #dbeafe;
            font-size: 13px;
            line-height: 1.7;
        }

        .service-detail-help-actions {
            display: flex;
            gap: 10px;
        }

        .service-detail-help-button {
            min-height: 49px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 19px;
            border: 1px solid transparent;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .service-detail-help-button:hover {
            transform: translateY(-2px);
        }

        .service-detail-help-button.primary {
            color: var(--detail-blue-dark);
            border-color: rgba(255, 255, 255, 0.75);
            background: var(--detail-white);
            box-shadow:
                0 12px 26px rgba(16, 24, 40, 0.18);
        }

        .service-detail-help-button.primary:hover {
            color: var(--detail-blue-dark);
        }

        .service-detail-help-button.secondary {
            color: var(--detail-white);
            border-color: rgba(255, 255, 255, 0.31);
            background: rgba(255, 255, 255, 0.10);
        }

        .service-detail-help-button.secondary:hover {
            color: var(--detail-white);
            background: rgba(255, 255, 255, 0.16);
        }

        .service-detail-help-button svg {
            width: 17px;
            height: 17px;
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .service-detail-reveal {
            opacity: 0;
            transform: translateY(21px);
            transition:
                opacity 0.56s ease,
                transform 0.56s ease;
        }

        .service-detail-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .service-detail-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(290px, 0.5fr);
                gap: 34px;
            }

            .service-related-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .service-related-card:last-child:nth-child(odd) {
                grid-column: 1 / -1;
                max-width: calc(50% - 11px);
            }
        }

        @media (max-width: 900px) {
            .service-detail-hero-grid,
            .service-detail-main-grid {
                grid-template-columns: 1fr;
            }

            .service-detail-price-card {
                max-width: 620px;
            }

            .service-detail-image {
                height: 430px;
            }

            .service-detail-help {
                grid-template-columns: 1fr;
            }

            .service-detail-help-actions {
                align-items: flex-start;
            }
        }

        @media (max-width: 700px) {
            .service-detail-process-grid {
                grid-template-columns: 1fr;
            }

            .service-related-heading-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .service-related-all-button {
                width: 100%;
            }

            .service-related-grid {
                grid-template-columns: 1fr;
            }

            .service-related-card:last-child:nth-child(odd) {
                grid-column: auto;
                max-width: none;
            }
        }

        @media (max-width: 640px) {
            .service-detail-breadcrumb-section {
                padding: 13px 0;
            }

            .service-detail-breadcrumb strong {
                max-width: 190px;
            }

            .service-detail-hero {
                padding: 47px 0 47px;
            }

            .service-detail-hero::before,
            .service-detail-hero::after {
                display: none;
            }

            .service-detail-hero h1 {
                margin-top: 18px;
                font-size: 38px;
                letter-spacing: -1.5px;
            }

            .service-detail-hero-description {
                font-size: 14px;
            }

            .service-detail-hero-meta {
                display: grid;
                grid-template-columns: 1fr;
                gap: 9px;
            }

            .service-detail-price-card {
                padding: 21px;
                border-radius: 23px;
            }

            .service-detail-main-section {
                padding: 48px 0 62px;
            }

            .service-detail-image-panel {
                padding: 12px;
                border-radius: 22px;
            }

            .service-detail-image-wrapper {
                border-radius: 17px;
            }

            .service-detail-image {
                height: 320px;
            }

            .service-detail-image-status {
                top: 23px;
                left: 23px;
            }

            .service-detail-quick-grid {
                grid-template-columns: 1fr;
            }

            .service-detail-content-panel {
                padding: 22px;
                border-radius: 22px;
            }

            .service-detail-inline-price {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .service-detail-inline-price-value {
                text-align: left;
            }

            .service-detail-specification-item {
                align-items: flex-start;
                flex-direction: column;
                gap: 7px;
            }

            .service-detail-specification-value {
                text-align: left;
            }

            .service-detail-actions {
                grid-template-columns: 1fr;
            }

            .service-detail-button {
                width: 100%;
            }

            .service-detail-process {
                padding: 20px;
                border-radius: 20px;
            }

            .service-related-section {
                padding: 57px 0;
            }

            .service-related-image-wrapper {
                height: 200px;
            }

            .service-related-description {
                min-height: 0;
            }

            .service-detail-help-section {
                padding-bottom: 60px;
            }

            .service-detail-help {
                padding: 27px 21px;
                border-radius: 22px;
            }

            .service-detail-help h2 {
                font-size: 25px;
            }

            .service-detail-help-actions {
                width: 100%;
                flex-direction: column;
            }

            .service-detail-help-button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .service-detail-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .service-detail-image,
            .service-related-image,
            .service-related-card,
            .service-detail-button {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="service-detail-page">
        {{-- Breadcrumb --}}
        <section class="service-detail-breadcrumb-section">
            <div class="container">
                <nav
                    class="service-detail-breadcrumb"
                    aria-label="Breadcrumb"
                >
                    <a href="{{ route('home') }}">
                        Beranda
                    </a>

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
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                    <a href="{{ route('layanan.index') }}">
                        Layanan
                    </a>

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
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                    <strong>
                        {{ $layanan->nama_layanan }}
                    </strong>
                </nav>
            </div>
        </section>

        {{-- Hero --}}
        <section class="service-detail-hero">
            <div class="container service-detail-hero-grid">
                <div class="service-detail-hero-content">
                    <span class="service-detail-category-badge">
                        <span class="service-detail-category-icon">
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

                        {{ $namaKategori }}
                    </span>

                    <h1>
                        {{ $layanan->nama_layanan }}
                    </h1>

                    <p class="service-detail-hero-description">
                        Lihat informasi layanan, harga dasar, ketentuan
                        file, dan ketersediaan pemesanan sebelum
                        membuat pesanan.
                    </p>

                    <div class="service-detail-hero-meta">
                        <span class="service-detail-hero-meta-item">
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

                            {{
                                $layanan->butuh_upload_file
                                    ? 'Upload file diperlukan'
                                    : 'Tanpa upload file'
                            }}
                        </span>

                        <span class="service-detail-hero-meta-item orange">
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

                            Estimasi biaya langsung
                        </span>

                        <span class="service-detail-hero-meta-item">
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

                            Status dapat dipantau
                        </span>
                    </div>
                </div>

                <aside class="service-detail-price-card">
                    <span class="service-detail-price-card-label">
                        Harga dasar layanan
                    </span>

                    <div class="service-detail-main-price">
                        Rp {{
                            number_format(
                                (float) $layanan->harga_dasar,
                                0,
                                ',',
                                '.'
                            )
                        }}
                    </div>

                    <span class="service-detail-main-price-unit">
                        Per {{ $layanan->satuan }}
                    </span>

                    <div class="service-detail-price-divider"></div>

                    <p class="service-detail-price-note">
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
                            <path d="M12 11v5"/>
                            <path d="M12 8h.01"/>
                        </svg>

                        Harga akhir menyesuaikan jumlah halaman,
                        jumlah copy, jilid, laminating, dan biaya
                        pengiriman jika digunakan.
                    </p>
                </aside>
            </div>
        </section>

        {{-- Main Details --}}
        <section class="service-detail-main-section">
            <div class="container">
                <div class="service-detail-main-grid">
                    {{-- Media --}}
                    <div class="service-detail-media-column">
                        <div class="service-detail-image-panel service-detail-reveal">
                            <div class="service-detail-image-wrapper">
                                <img
                                    src="{{ $gambarLayanan }}"
                                    alt="{{ $layanan->nama_layanan }}"
                                    class="service-detail-image"
                                    fetchpriority="high"
                                >

                                <div
                                    class="service-detail-image-shade"
                                    aria-hidden="true"
                                ></div>

                                <div class="service-detail-image-caption">
                                    <strong>
                                        {{ $layanan->nama_layanan }}
                                    </strong>

                                    <span>
                                        {{ $namaKategori }}
                                    </span>
                                </div>
                            </div>

                            <span class="service-detail-image-status {{
                                $layanan->status ? '' : 'inactive'
                            }}">
                                {{
                                    $layanan->status
                                        ? 'Layanan aktif'
                                        : 'Layanan tidak aktif'
                                }}
                            </span>
                        </div>

                        <div class="service-detail-quick-grid">
                            <div class="service-detail-quick-item service-detail-reveal">
                                <span class="service-detail-quick-icon">
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

                                <span class="service-detail-quick-copy">
                                    <span>Kategori</span>
                                    <strong>{{ $namaKategori }}</strong>
                                </span>
                            </div>

                            <div class="service-detail-quick-item service-detail-reveal">
                                <span class="service-detail-quick-icon">
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

                                <span class="service-detail-quick-copy">
                                    <span>Upload File</span>
                                    <strong>
                                        {{
                                            $layanan->butuh_upload_file
                                                ? 'Diperlukan'
                                                : 'Tidak diperlukan'
                                        }}
                                    </strong>
                                </span>
                            </div>

                            <div class="service-detail-quick-item service-detail-reveal">
                                <span class="service-detail-quick-icon">
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
                                </span>

                                <span class="service-detail-quick-copy">
                                    <span>Pemesanan Online</span>
                                    <strong>
                                        {{
                                            $layanan->bisa_online
                                                ? 'Tersedia'
                                                : 'Tidak tersedia'
                                        }}
                                    </strong>
                                </span>
                            </div>

                            <div class="service-detail-quick-item service-detail-reveal">
                                <span class="service-detail-quick-icon">
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

                                <span class="service-detail-quick-copy">
                                    <span>Status Pesanan</span>
                                    <strong>Dapat dipantau</strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="service-detail-content-panel service-detail-reveal">
                        <span class="service-detail-content-badge">
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
                                <path d="M12 11v5"/>
                                <path d="M12 8h.01"/>
                            </svg>

                            Informasi layanan
                        </span>

                        <h2>
                            {{ $layanan->nama_layanan }}
                        </h2>

                        <p class="service-detail-description">
                            {{ $deskripsiLayanan }}
                        </p>

                        <div class="service-detail-inline-price">
                            <div class="service-detail-inline-price-copy">
                                <span>Harga dasar</span>

                                <strong>
                                    Harga awal sebelum layanan tambahan
                                </strong>
                            </div>

                            <div class="service-detail-inline-price-value">
                                Rp {{
                                    number_format(
                                        (float) $layanan->harga_dasar,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }}

                                <span>
                                    per {{ $layanan->satuan }}
                                </span>
                            </div>
                        </div>

                        <div class="service-detail-specification">
                            <div class="service-detail-specification-heading">
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
                                    <path d="M9 11l3 3L22 4"/>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                </svg>

                                Spesifikasi Layanan
                            </div>

                            <div class="service-detail-specification-list">
                                <div class="service-detail-specification-item">
                                    <span class="service-detail-specification-label">
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

                                        Kategori
                                    </span>

                                    <strong class="service-detail-specification-value">
                                        {{ $namaKategori }}
                                    </strong>
                                </div>

                                <div class="service-detail-specification-item">
                                    <span class="service-detail-specification-label">
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

                                        Upload File
                                    </span>

                                    <strong class="service-detail-specification-value">
                                        {{
                                            $layanan->butuh_upload_file
                                                ? 'Diperlukan'
                                                : 'Tidak diperlukan'
                                        }}
                                    </strong>
                                </div>

                                <div class="service-detail-specification-item">
                                    <span class="service-detail-specification-label">
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

                                        Pemesanan Online
                                    </span>

                                    <span class="service-detail-status-pill {{
                                        $layanan->bisa_online
                                            ? ''
                                            : 'inactive'
                                    }}">
                                        {{
                                            $layanan->bisa_online
                                                ? 'Tersedia'
                                                : 'Tidak tersedia'
                                        }}
                                    </span>
                                </div>

                                <div class="service-detail-specification-item">
                                    <span class="service-detail-specification-label">
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

                                        Status Layanan
                                    </span>

                                    <span class="service-detail-status-pill {{
                                        $layanan->status
                                            ? ''
                                            : 'inactive'
                                    }}">
                                        {{
                                            $layanan->status
                                                ? 'Aktif'
                                                : 'Tidak aktif'
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="service-detail-actions">
                            @if ($layananDapatDipesan)
                                <a
                                    href="{{ $tujuanPesanan }}"
                                    class="service-detail-button service-detail-button-primary"
                                >
                                    @auth
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
                                    @else
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
                                    @endauth

                                    {{ $labelPesanan }}
                                </a>
                            @else
                                <span
                                    class="service-detail-button service-detail-button-disabled"
                                >
                                    Pemesanan Tidak Tersedia
                                </span>
                            @endif

                            <a
                                href="{{ route('layanan.index') }}"
                                class="service-detail-button service-detail-button-outline"
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
                                    <path d="m15 18-6-6 6-6"/>
                                </svg>

                                Kembali
                            </a>
                        </div>

                        <div class="service-detail-note">
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
                                <path d="M12 11v5"/>
                                <path d="M12 8h.01"/>
                            </svg>

                            <span>
                                Estimasi akhir dapat berubah berdasarkan
                                jumlah halaman, jumlah copy, jilid,
                                laminating, pengiriman, dan hasil
                                pemeriksaan admin.
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Process --}}
                <div class="service-detail-process service-detail-reveal">
                    <div class="service-detail-process-heading">
                        <h3>Bagaimana proses pemesanannya?</h3>

                        <p>
                            Pesanan dapat dibuat dengan tiga langkah sederhana.
                        </p>
                    </div>

                    <div class="service-detail-process-grid">
                        <div class="service-detail-process-item">
                            <span class="service-detail-process-number">
                                01
                            </span>

                            <strong>Lengkapi Detail</strong>

                            <span>
                                Tentukan jenis print, ukuran, halaman,
                                copy, dan layanan tambahan.
                            </span>
                        </div>

                        <div class="service-detail-process-item">
                            <span class="service-detail-process-number">
                                02
                            </span>

                            <strong>Upload File</strong>

                            <span>
                                Unggah dokumen sesuai format dan batas
                                ukuran yang tersedia.
                            </span>
                        </div>

                        <div class="service-detail-process-item">
                            <span class="service-detail-process-number">
                                03
                            </span>

                            <strong>Pantau Pesanan</strong>

                            <span>
                                Lihat status pengerjaan melalui dashboard
                                hingga pesanan selesai.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Related Services --}}
        @if ($layananTerkait->isNotEmpty())
            <section class="service-related-section">
                <div class="container">
                    <div class="service-related-heading-row">
                        <div class="service-related-heading service-detail-reveal">
                            <span class="service-related-eyebrow">
                                Rekomendasi lainnya
                            </span>

                            <h2>
                                Layanan terkait
                            </h2>

                            <p>
                                Pilihan layanan lain dari kategori
                                {{ $namaKategori }}.
                            </p>
                        </div>

                        <a
                            href="{{
                                route(
                                    'layanan.index',
                                    $layanan->kategoriLayanan?->slug
                                        ? [
                                            'kategori' =>
                                                $layanan
                                                    ->kategoriLayanan
                                                    ->slug
                                        ]
                                        : []
                                )
                            }}"
                            class="service-related-all-button"
                        >
                            Lihat Semua

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

                    <div class="service-related-grid">
                        @foreach ($layananTerkait->take(3) as $item)
                            @php
                                $gambarItem = $item->gambar
                                    ? \Illuminate\Support\Facades\Storage::url(
                                        $item->gambar
                                    )
                                    : asset('images/placeholder.png');

                                $kategoriItem = $item
                                    ->kategoriLayanan
                                    ?->nama_kategori
                                    ?? $namaKategori;
                            @endphp

                            <article class="service-related-card service-detail-reveal">
                                <div class="service-related-image-wrapper">
                                    <img
                                        src="{{ $gambarItem }}"
                                        alt="{{ $item->nama_layanan }}"
                                        class="service-related-image"
                                        loading="lazy"
                                    >

                                    <span class="service-related-category">
                                        {{ $kategoriItem }}
                                    </span>
                                </div>

                                <div class="service-related-content">
                                    <h3>
                                        {{ $item->nama_layanan }}
                                    </h3>

                                    <p class="service-related-description">
                                        {{
                                            \Illuminate\Support\Str::limit(
                                                $item->deskripsi
                                                    ?? 'Layanan cetak tersedia untuk kebutuhan dokumen mahasiswa.',
                                                100
                                            )
                                        }}
                                    </p>

                                    <div class="service-related-footer">
                                        <div class="service-related-price">
                                            <span>Harga dasar</span>

                                            <strong>
                                                Rp {{
                                                    number_format(
                                                        (float) $item->harga_dasar,
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                }}
                                                / {{ $item->satuan }}
                                            </strong>
                                        </div>

                                        <a
                                            href="{{ route('layanan.show', $item) }}"
                                            class="service-related-link"
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
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- Help CTA --}}
        <section class="service-detail-help-section">
            <div class="container">
                <div class="service-detail-help service-detail-reveal">
                    <div class="service-detail-help-content">
                        <span class="service-detail-help-label">
                            Butuh bantuan
                        </span>

                        <h2>
                            Masih bingung dengan layanan ini?
                        </h2>

                        <p>
                            Hubungi admin untuk memastikan jenis print,
                            ukuran kertas, jumlah halaman, atau layanan
                            tambahan yang sesuai dengan dokumenmu.
                        </p>
                    </div>

                    <div class="service-detail-help-actions">
                        <a
                            href="{{ route('kontak.index') }}"
                            class="service-detail-help-button primary"
                        >
                            Hubungi Admin

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
                            class="service-detail-help-button secondary"
                        >
                            Pilih Layanan Lain
                        </a>
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
                '.service-detail-reveal'
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
                    threshold: 0.1,
                    rootMargin: '0px 0px -40px 0px',
                }
            );

            revealElements.forEach((element) => {
                observer.observe(element);
            });
        });
    </script>
@endpush