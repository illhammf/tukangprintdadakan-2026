@extends('layouts.public')

@section(
    'title',
    'Layanan - '
    . ($website?->nama_website ?? 'Tukang Print Dadakan')
)

@php
    $namaWebsite = $website?->nama_website
        ?? 'Tukang Print Dadakan';

    $jumlahHasil = method_exists($layanans, 'total')
        ? $layanans->total()
        : $layanans->count();

    $jumlahKategori = $kategoriLayanans->count();

    $sedangMencari = request()->filled('q');

    $sedangMemfilter = request()->filled('kategori');

    $punyaFilter = $sedangMencari || $sedangMemfilter;

    $kategoriAktif = $kategoriLayanans->firstWhere(
        'slug',
        request('kategori')
    );
@endphp

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Service Index Page
        |--------------------------------------------------------------------------
        */

        .service-index-page {
            --service-blue: var(--public-blue, #155eef);
            --service-blue-dark: var(--public-blue-dark, #1046b8);
            --service-blue-soft: var(--public-blue-soft, #edf4ff);

            --service-orange: var(--public-orange, #f97316);
            --service-orange-dark: var(--public-orange-dark, #c2410c);
            --service-orange-soft: var(--public-orange-soft, #fff1e7);

            --service-dark: var(--public-dark, #101828);
            --service-text: var(--public-text, #344054);
            --service-muted: var(--public-muted, #667085);

            --service-white: #ffffff;
            --service-soft: #f7f9fc;
            --service-border: #e4e7ec;

            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .service-index-hero {
            position: relative;
            overflow: hidden;
            padding: 76px 0 68px;
            border-bottom: 1px solid var(--service-border);
            background:
                radial-gradient(
                    circle at 9% 25%,
                    rgba(21, 94, 239, 0.15),
                    transparent 27%
                ),
                radial-gradient(
                    circle at 91% 8%,
                    rgba(249, 115, 22, 0.14),
                    transparent 24%
                ),
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #f7f9fd 100%
                );
        }

        .service-index-hero::before {
            content: "";
            position: absolute;
            top: -90px;
            right: -70px;
            width: 260px;
            height: 260px;
            border: 42px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .service-index-hero::after {
            content: "";
            position: absolute;
            bottom: -100px;
            left: -90px;
            width: 250px;
            height: 250px;
            border: 40px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .service-index-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(320px, 0.62fr);
            gap: 50px;
            align-items: center;
        }

        .service-index-hero-content {
            max-width: 760px;
        }

        .service-index-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--service-orange-dark);
            background: var(--service-orange-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .service-index-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--service-white);
            background: var(--service-orange);
        }

        .service-index-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .service-index-hero h1 {
            margin: 22px 0 17px;
            color: var(--service-dark);
            font-size: clamp(40px, 5.5vw, 64px);
            line-height: 1.05;
            letter-spacing: -2.2px;
        }

        .service-index-hero h1 span {
            position: relative;
            display: inline-block;
            color: var(--service-blue);
        }

        .service-index-hero h1 span::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: -1px;
            left: 0;
            height: 7px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.22);
            transform: rotate(-1deg);
        }

        .service-index-hero-description {
            max-width: 690px;
            margin: 0;
            color: var(--service-muted);
            font-size: 16px;
            line-height: 1.8;
        }

        .service-index-hero-points {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 25px;
        }

        .service-index-point {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--service-text);
            font-size: 12px;
            font-weight: 800;
        }

        .service-index-point-icon {
            width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--service-blue);
            background: var(--service-blue-soft);
        }

        .service-index-point-icon svg {
            width: 14px;
            height: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Summary
        |--------------------------------------------------------------------------
        */

        .service-index-summary-card {
            position: relative;
            padding: 25px;
            border: 1px solid rgba(228, 231, 236, 0.9);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(15px);
            box-shadow:
                0 24px 65px rgba(16, 24, 40, 0.13);
        }

        .service-index-summary-card::before {
            content: "";
            position: absolute;
            top: -11px;
            right: 28px;
            width: 74px;
            height: 23px;
            border-radius: 8px 8px 3px 3px;
            background:
                linear-gradient(
                    90deg,
                    var(--service-blue),
                    var(--service-orange)
                );
            transform: rotate(3deg);
        }

        .service-index-summary-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .service-index-summary-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: var(--service-white);
            background:
                linear-gradient(
                    135deg,
                    var(--service-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 22px rgba(21, 94, 239, 0.21);
        }

        .service-index-summary-icon svg {
            width: 24px;
            height: 24px;
        }

        .service-index-summary-heading strong,
        .service-index-summary-heading span {
            display: block;
        }

        .service-index-summary-heading strong {
            color: var(--service-dark);
            font-size: 17px;
        }

        .service-index-summary-heading span {
            margin-top: 2px;
            color: var(--service-muted);
            font-size: 11px;
        }

        .service-index-summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
        }

        .service-index-summary-item {
            padding: 16px;
            border: 1px solid var(--service-border);
            border-radius: 17px;
            background: var(--service-soft);
        }

        .service-index-summary-item:nth-child(2) {
            background: var(--service-orange-soft);
        }

        .service-index-summary-item strong,
        .service-index-summary-item span {
            display: block;
        }

        .service-index-summary-item strong {
            color: var(--service-blue);
            font-size: 27px;
            line-height: 1;
        }

        .service-index-summary-item:nth-child(2) strong {
            color: var(--service-orange-dark);
        }

        .service-index-summary-item span {
            margin-top: 7px;
            color: var(--service-muted);
            font-size: 10px;
            font-weight: 800;
        }

        .service-index-summary-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 17px;
            padding: 13px;
            border: 1px solid #cfe0ff;
            border-radius: 15px;
            color: var(--service-text);
            background: var(--service-blue-soft);
            font-size: 11px;
            line-height: 1.55;
        }

        .service-index-summary-note svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            color: var(--service-blue);
        }

        /*
        |--------------------------------------------------------------------------
        | Main Section
        |--------------------------------------------------------------------------
        */

        .service-index-main {
            padding: 70px 0 90px;
        }

        /*
        |--------------------------------------------------------------------------
        | Search Panel
        |--------------------------------------------------------------------------
        */

        .service-search-panel {
            position: relative;
            z-index: 4;
            margin-bottom: 27px;
            padding: 22px;
            border: 1px solid var(--service-border);
            border-radius: 24px;
            background: var(--service-white);
            box-shadow:
                0 14px 40px rgba(16, 24, 40, 0.075);
        }

        .service-search-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
        }

        .service-search-input-wrapper {
            position: relative;
        }

        .service-search-input-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            width: 20px;
            height: 20px;
            color: var(--service-muted);
            pointer-events: none;
            transform: translateY(-50%);
        }

        .service-search-input {
            width: 100%;
            min-height: 52px;
            padding: 13px 17px 13px 48px;
            border: 1px solid #d0d5dd;
            border-radius: 15px;
            color: var(--service-dark);
            background: #fcfcfd;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .service-search-input::placeholder {
            color: #98a2b3;
        }

        .service-search-input:hover {
            border-color: #98a2b3;
        }

        .service-search-input:focus {
            border-color: var(--service-blue);
            background: var(--service-white);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.12);
        }

        .service-search-button {
            min-height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 24px;
            border: 0;
            border-radius: 15px;
            color: var(--service-white);
            background:
                linear-gradient(
                    135deg,
                    var(--service-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 22px rgba(21, 94, 239, 0.22);
            font-weight: 900;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .service-search-button:hover {
            background:
                linear-gradient(
                    135deg,
                    var(--service-blue-dark),
                    var(--service-blue)
                );
            box-shadow:
                0 14px 28px rgba(21, 94, 239, 0.28);
            transform: translateY(-1px);
        }

        .service-search-button svg {
            width: 18px;
            height: 18px;
        }

        .service-search-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--service-border);
        }

        .service-search-hint {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--service-muted);
            font-size: 11px;
            font-weight: 700;
        }

        .service-search-hint svg {
            width: 16px;
            height: 16px;
            color: var(--service-orange);
        }

        .service-reset-button {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border: 1px solid #fed7aa;
            border-radius: 11px;
            color: var(--service-orange-dark);
            background: var(--service-orange-soft);
            font-size: 11px;
            font-weight: 900;
            transition:
                border-color 0.2s ease,
                background 0.2s ease;
        }

        .service-reset-button:hover {
            color: var(--service-orange-dark);
            border-color: var(--service-orange);
            background: #ffeadb;
        }

        .service-reset-button svg {
            width: 15px;
            height: 15px;
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        .service-category-section {
            margin-bottom: 37px;
        }

        .service-category-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 13px;
        }

        .service-category-label strong {
            color: var(--service-dark);
            font-size: 14px;
        }

        .service-category-label span {
            color: var(--service-muted);
            font-size: 11px;
        }

        .service-category-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
        }

        .service-category-chip {
            min-height: 41px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border: 1px solid var(--service-border);
            border-radius: 13px;
            color: var(--service-text);
            background: var(--service-white);
            font-size: 12px;
            font-weight: 800;
            transition:
                border-color 0.2s ease,
                color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .service-category-chip:hover {
            color: var(--service-blue);
            border-color: #b9d0ff;
            background: var(--service-blue-soft);
            transform: translateY(-1px);
        }

        .service-category-chip.active {
            color: var(--service-white);
            border-color: var(--service-blue);
            background:
                linear-gradient(
                    135deg,
                    var(--service-blue),
                    #2b70ff
                );
            box-shadow:
                0 7px 17px rgba(21, 94, 239, 0.18);
        }

        .service-category-count {
            min-width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 7px;
            border-radius: 999px;
            color: var(--service-blue);
            background: var(--service-blue-soft);
            font-size: 10px;
            font-weight: 900;
        }

        .service-category-chip.active
        .service-category-count {
            color: var(--service-blue-dark);
            background: var(--service-white);
        }

        /*
        |--------------------------------------------------------------------------
        | Result Heading
        |--------------------------------------------------------------------------
        */

        .service-result-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 22px;
            margin-bottom: 24px;
        }

        .service-result-copy h2 {
            margin: 0 0 6px;
            color: var(--service-dark);
            font-size: 27px;
            line-height: 1.2;
        }

        .service-result-copy p {
            margin: 0;
            color: var(--service-muted);
            font-size: 13px;
        }

        .service-result-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border: 1px solid #cfe0ff;
            border-radius: 12px;
            color: var(--service-blue);
            background: var(--service-blue-soft);
            font-size: 11px;
            font-weight: 900;
            white-space: nowrap;
        }

        .service-result-count svg {
            width: 16px;
            height: 16px;
        }

        /*
        |--------------------------------------------------------------------------
        | Service Grid
        |--------------------------------------------------------------------------
        */

        .service-index-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 23px;
        }

        .service-index-card {
            position: relative;
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--service-border);
            border-radius: 25px;
            background: var(--service-white);
            box-shadow:
                0 8px 28px rgba(16, 24, 40, 0.05);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .service-index-card:hover {
            border-color: #bed2ff;
            box-shadow:
                0 22px 52px rgba(16, 24, 40, 0.12);
            transform: translateY(-7px);
        }

        .service-index-image-wrapper {
            position: relative;
            height: 225px;
            overflow: hidden;
            background:
                linear-gradient(
                    135deg,
                    var(--service-blue-soft),
                    var(--service-orange-soft)
                );
        }

        .service-index-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform 0.45s ease;
        }

        .service-index-card:hover
        .service-index-image {
            transform: scale(1.055);
        }

        .service-index-image-shade {
            position: absolute;
            inset: auto 0 0;
            height: 54%;
            pointer-events: none;
            background:
                linear-gradient(
                    180deg,
                    transparent,
                    rgba(16, 24, 40, 0.34)
                );
        }

        .service-index-category {
            position: absolute;
            top: 15px;
            left: 15px;
            max-width: calc(100% - 105px);
            overflow: hidden;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 999px;
            color: var(--service-dark);
            background: rgba(255, 255, 255, 0.91);
            backdrop-filter: blur(10px);
            font-size: 10px;
            font-weight: 900;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .service-index-available {
            position: absolute;
            top: 15px;
            right: 15px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border: 1px solid rgba(255, 255, 255, 0.70);
            border-radius: 999px;
            color: #166534;
            background: rgba(240, 253, 244, 0.92);
            backdrop-filter: blur(10px);
            font-size: 9px;
            font-weight: 900;
        }

        .service-index-available::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #22c55e;
        }

        .service-index-card-body {
            padding: 23px;
        }

        .service-index-card h3 {
            margin: 0 0 9px;
            color: var(--service-dark);
            font-size: 20px;
            line-height: 1.3;
        }

        .service-index-description {
            min-height: 69px;
            margin: 0;
            color: var(--service-muted);
            font-size: 13px;
            line-height: 1.75;
        }

        .service-index-price-box {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 14px;
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #cfe0ff;
            border-radius: 16px;
            background:
                linear-gradient(
                    135deg,
                    var(--service-blue-soft),
                    #f8fbff
                );
        }

        .service-index-price-label,
        .service-index-price {
            display: block;
        }

        .service-index-price-label {
            color: var(--service-muted);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .service-index-price {
            margin-top: 4px;
            color: var(--service-blue);
            font-size: 19px;
            font-weight: 900;
            line-height: 1.2;
        }

        .service-index-unit {
            color: var(--service-muted);
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .service-index-actions {
            display: grid;
            grid-template-columns: 0.78fr 1.22fr;
            gap: 10px;
            margin-top: 18px;
        }

        .service-index-button {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 13px;
            border: 1px solid transparent;
            border-radius: 13px;
            font-size: 12px;
            font-weight: 900;
            text-align: center;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .service-index-button:hover {
            transform: translateY(-1px);
        }

        .service-index-button svg {
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
        }

        .service-index-button-detail {
            color: var(--service-blue);
            border-color: #b9d0ff;
            background: var(--service-white);
        }

        .service-index-button-detail:hover {
            color: var(--service-blue-dark);
            border-color: var(--service-blue);
            background: var(--service-blue-soft);
        }

        .service-index-button-order {
            color: var(--service-white);
            background:
                linear-gradient(
                    135deg,
                    var(--service-orange),
                    #fb923c
                );
            box-shadow:
                0 8px 18px rgba(249, 115, 22, 0.19);
        }

        .service-index-button-order:hover {
            color: var(--service-white);
            background:
                linear-gradient(
                    135deg,
                    var(--service-orange-dark),
                    var(--service-orange)
                );
            box-shadow:
                0 12px 24px rgba(249, 115, 22, 0.25);
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */

        .service-index-empty {
            grid-column: 1 / -1;
            padding: 64px 28px;
            border: 1px dashed #b9d0ff;
            border-radius: 26px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.09),
                    transparent 28%
                ),
                var(--service-blue-soft);
            text-align: center;
        }

        .service-index-empty-icon {
            width: 70px;
            height: 70px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 19px;
            border-radius: 22px;
            color: var(--service-blue);
            background: var(--service-white);
            box-shadow:
                0 12px 28px rgba(16, 24, 40, 0.09);
        }

        .service-index-empty-icon svg {
            width: 34px;
            height: 34px;
        }

        .service-index-empty h3 {
            margin: 0 0 9px;
            color: var(--service-dark);
            font-size: 24px;
        }

        .service-index-empty p {
            max-width: 560px;
            margin: 0 auto 22px;
            color: var(--service-muted);
            font-size: 14px;
            line-height: 1.7;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        .service-pagination {
            margin-top: 38px;
        }

        .service-pagination nav {
            display: flex;
            justify-content: center;
        }

        .service-pagination nav > div:first-child {
            display: none;
        }

        .service-pagination nav > div:last-child {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .service-pagination nav p {
            margin: 0;
            color: var(--service-muted);
            font-size: 12px;
        }

        .service-pagination nav span,
        .service-pagination nav a {
            border-radius: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Help CTA
        |--------------------------------------------------------------------------
        */

        .service-help-box {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 28px;
            align-items: center;
            margin-top: 60px;
            padding: 35px;
            border-radius: 26px;
            color: var(--service-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.42),
                    transparent 34%
                ),
                linear-gradient(
                    135deg,
                    var(--service-blue-dark),
                    var(--service-blue)
                );
            box-shadow:
                0 24px 62px rgba(21, 94, 239, 0.24);
        }

        .service-help-box::before {
            content: "";
            position: absolute;
            top: -80px;
            right: -65px;
            width: 200px;
            height: 200px;
            border: 32px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .service-help-content,
        .service-help-action {
            position: relative;
            z-index: 2;
        }

        .service-help-label {
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

        .service-help-label::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #fdba74;
        }

        .service-help-box h2 {
            margin: 0 0 9px;
            color: var(--service-white);
            font-size: 29px;
            line-height: 1.15;
        }

        .service-help-box p {
            max-width: 680px;
            margin: 0;
            color: #dbeafe;
            font-size: 13px;
            line-height: 1.7;
        }

        .service-help-button {
            min-height: 49px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 20px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 14px;
            color: var(--service-blue-dark);
            background: var(--service-white);
            font-size: 13px;
            font-weight: 900;
            white-space: nowrap;
            box-shadow:
                0 12px 26px rgba(16, 24, 40, 0.18);
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .service-help-button:hover {
            color: var(--service-blue-dark);
            box-shadow:
                0 16px 34px rgba(16, 24, 40, 0.24);
            transform: translateY(-2px);
        }

        .service-help-button svg {
            width: 18px;
            height: 18px;
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .service-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .service-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .service-index-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(290px, 0.55fr);
                gap: 34px;
            }

            .service-index-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 820px) {
            .service-index-hero {
                padding: 62px 0 58px;
            }

            .service-index-hero-grid {
                grid-template-columns: 1fr;
            }

            .service-index-summary-card {
                max-width: 620px;
            }

            .service-search-bottom,
            .service-result-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .service-help-box {
                grid-template-columns: 1fr;
            }

            .service-help-action,
            .service-help-button {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .service-index-hero {
                padding: 48px 0 48px;
            }

            .service-index-hero::before,
            .service-index-hero::after {
                display: none;
            }

            .service-index-hero h1 {
                margin-top: 18px;
                font-size: 38px;
                letter-spacing: -1.5px;
            }

            .service-index-hero-description {
                font-size: 14px;
            }

            .service-index-hero-points {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .service-index-summary-card {
                padding: 20px;
                border-radius: 22px;
            }

            .service-index-main {
                padding: 48px 0 66px;
            }

            .service-search-panel {
                padding: 16px;
                border-radius: 20px;
            }

            .service-search-form {
                grid-template-columns: 1fr;
            }

            .service-search-button {
                width: 100%;
            }

            .service-search-bottom {
                gap: 12px;
            }

            .service-reset-button {
                width: 100%;
                justify-content: center;
            }

            .service-category-filter {
                margin-right: -12px;
                margin-left: -12px;
                padding: 2px 12px 8px;
                overflow-x: auto;
                flex-wrap: nowrap;
                scrollbar-width: none;
            }

            .service-category-filter::-webkit-scrollbar {
                display: none;
            }

            .service-category-chip {
                flex: 0 0 auto;
                white-space: nowrap;
            }

            .service-index-grid {
                grid-template-columns: 1fr;
            }

            .service-index-image-wrapper {
                height: 215px;
            }

            .service-index-card-body {
                padding: 20px;
            }

            .service-index-description {
                min-height: 0;
            }

            .service-index-actions {
                grid-template-columns: 1fr;
            }

            .service-index-button {
                width: 100%;
            }

            .service-index-empty {
                padding: 45px 20px;
            }

            .service-help-box {
                margin-top: 44px;
                padding: 27px 21px;
                border-radius: 22px;
            }

            .service-help-box h2 {
                font-size: 25px;
            }

            .service-pagination nav > div:last-child {
                align-items: stretch;
                flex-direction: column;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .service-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .service-index-card,
            .service-index-image,
            .service-category-chip,
            .service-index-button {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="service-index-page">
        {{-- Hero --}}
        <section class="service-index-hero">
            <div class="container service-index-hero-grid">
                <div class="service-index-hero-content">
                    <span class="service-index-badge">
                        <span class="service-index-badge-icon">
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

                        Daftar Layanan
                    </span>

                    <h1>
                        Layanan cetak untuk
                        <span>kebutuhan mahasiswa</span>
                    </h1>

                    <p class="service-index-hero-description">
                        Pilih layanan, periksa informasi harga, lalu
                        lanjutkan ke proses pemesanan. File dan detail
                        cetak akan tersimpan secara terstruktur di dalam
                        sistem {{ $namaWebsite }}.
                    </p>

                    <div class="service-index-hero-points">
                        <span class="service-index-point">
                            <span class="service-index-point-icon">
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

                            Harga layanan transparan
                        </span>

                        <span class="service-index-point">
                            <span class="service-index-point-icon">
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

                            Dapat dipesan secara online
                        </span>

                        <span class="service-index-point">
                            <span class="service-index-point-icon">
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

                            Estimasi biaya otomatis
                        </span>
                    </div>
                </div>

                <aside class="service-index-summary-card">
                    <div class="service-index-summary-heading">
                        <span class="service-index-summary-icon">
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

                        <span>
                            <strong>Ringkasan Layanan</strong>
                            <span>Data layanan yang sedang tersedia</span>
                        </span>
                    </div>

                    <div class="service-index-summary-grid">
                        <div class="service-index-summary-item">
                            <strong>{{ $jumlahHasil }}</strong>
                            <span>Layanan ditemukan</span>
                        </div>

                        <div class="service-index-summary-item">
                            <strong>{{ $jumlahKategori }}</strong>
                            <span>Kategori aktif</span>
                        </div>
                    </div>

                    <div class="service-index-summary-note">
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
                            Harga yang ditampilkan merupakan harga dasar.
                            Total akhir mengikuti detail halaman, copy,
                            jilid, laminating, dan pengiriman.
                        </span>
                    </div>
                </aside>
            </div>
        </section>

        {{-- Main Content --}}
        <section class="service-index-main">
            <div class="container">
                {{-- Search --}}
                <div class="service-search-panel">
                    <form
                        action="{{ route('layanan.index') }}"
                        method="GET"
                        class="service-search-form"
                        role="search"
                        aria-label="Cari layanan"
                    >
                        @if (request('kategori'))
                            <input
                                type="hidden"
                                name="kategori"
                                value="{{ request('kategori') }}"
                            >
                        @endif

                        <div class="service-search-input-wrapper">
                            <svg
                                class="service-search-input-icon"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>

                            <input
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                class="service-search-input"
                                placeholder="Cari print, jilid, laminating, atau layanan lainnya"
                                autocomplete="off"
                                aria-label="Kata kunci layanan"
                            >
                        </div>

                        <button
                            type="submit"
                            class="service-search-button"
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
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>

                            Cari Layanan
                        </button>
                    </form>

                    <div class="service-search-bottom">
                        <span class="service-search-hint">
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

                            Gunakan nama layanan atau pilih kategori
                            untuk mempersempit hasil.
                        </span>

                        @if ($punyaFilter)
                            <a
                                href="{{ route('layanan.index') }}"
                                class="service-reset-button"
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
                                    <path d="M3 12a9 9 0 1 0 3-6.7"/>
                                    <path d="M3 3v6h6"/>
                                </svg>

                                Hapus Pencarian dan Filter
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Categories --}}
                <div class="service-category-section">
                    <div class="service-category-label">
                        <strong>Filter berdasarkan kategori</strong>

                        <span>
                            Geser untuk melihat kategori lainnya
                        </span>
                    </div>

                    <div class="service-category-filter">
                        <a
                            href="{{
                                route(
                                    'layanan.index',
                                    request('q')
                                        ? ['q' => request('q')]
                                        : []
                                )
                            }}"
                            class="service-category-chip {{
                                request('kategori') ? '' : 'active'
                            }}"
                        >
                            Semua Layanan

                            <span class="service-category-count">
                                {{ $jumlahHasil }}
                            </span>
                        </a>

                        @foreach ($kategoriLayanans as $kategori)
                            <a
                                href="{{
                                    route(
                                        'layanan.index',
                                        array_filter([
                                            'kategori' => $kategori->slug,
                                            'q' => request('q'),
                                        ])
                                    )
                                }}"
                                class="service-category-chip {{
                                    request('kategori') === $kategori->slug
                                        ? 'active'
                                        : ''
                                }}"
                            >
                                {{ $kategori->nama_kategori }}

                                <span class="service-category-count">
                                    {{ $kategori->layanans_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Result Heading --}}
                <div class="service-result-heading">
                    <div class="service-result-copy">
                        <h2>
                            @if ($sedangMencari)
                                Hasil pencarian
                                “{{ request('q') }}”
                            @elseif ($sedangMemfilter && $kategoriAktif)
                                {{ $kategoriAktif->nama_kategori }}
                            @else
                                Semua layanan aktif
                            @endif
                        </h2>

                        <p>
                            @if ($jumlahHasil > 0)
                                Pilih salah satu layanan untuk melihat
                                informasi lengkap atau membuat pesanan.
                            @else
                                Tidak ada layanan yang sesuai dengan
                                pencarian atau filter saat ini.
                            @endif
                        </p>
                    </div>

                    <span class="service-result-count">
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

                        {{ $jumlahHasil }} layanan
                    </span>
                </div>

                {{-- Service Cards --}}
                <div class="service-index-grid">
                    @forelse ($layanans as $layanan)
                        @php
                            $gambarLayanan = $layanan->gambar
                                ? \Illuminate\Support\Facades\Storage::url(
                                    $layanan->gambar
                                )
                                : asset('images/placeholder.png');

                            $namaKategori = $layanan
                                ->kategoriLayanan
                                ?->nama_kategori
                                ?? 'Layanan Cetak';
                        @endphp

                        <article class="service-index-card service-reveal">
                            <div class="service-index-image-wrapper">
                                <img
                                    src="{{ $gambarLayanan }}"
                                    alt="{{ $layanan->nama_layanan }}"
                                    class="service-index-image"
                                    loading="lazy"
                                >

                                <div
                                    class="service-index-image-shade"
                                    aria-hidden="true"
                                ></div>

                                <span class="service-index-category">
                                    {{ $namaKategori }}
                                </span>

                                <span class="service-index-available">
                                    Tersedia
                                </span>
                            </div>

                            <div class="service-index-card-body">
                                <h3>
                                    {{ $layanan->nama_layanan }}
                                </h3>

                                <p class="service-index-description">
                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $layanan->deskripsi
                                                ?? 'Layanan cetak tersedia untuk kebutuhan dokumen mahasiswa.',
                                            120
                                        )
                                    }}
                                </p>

                                <div class="service-index-price-box">
                                    <span>
                                        <span class="service-index-price-label">
                                            Harga dasar
                                        </span>

                                        <strong class="service-index-price">
                                            Rp {{
                                                number_format(
                                                    (float) $layanan->harga_dasar,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        </strong>
                                    </span>

                                    <span class="service-index-unit">
                                        per {{ $layanan->satuan }}
                                    </span>
                                </div>

                                <div class="service-index-actions">
                                    <a
                                        href="{{ route('layanan.show', $layanan) }}"
                                        class="service-index-button service-index-button-detail"
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
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 11v5"/>
                                            <path d="M12 8h.01"/>
                                        </svg>

                                        Detail
                                    </a>

                                    @auth
                                        <a
                                            href="{{
                                                route(
                                                    'customer.pesanan.create',
                                                    ['layanan' => $layanan->id]
                                                )
                                            }}"
                                            class="service-index-button service-index-button-order"
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

                                            Pesan Sekarang
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('login') }}"
                                            class="service-index-button service-index-button-order"
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

                                            Login untuk Pesan
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="service-index-empty">
                            <span class="service-index-empty-icon">
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
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.3-4.3"/>
                                    <path d="M8 11h6"/>
                                </svg>
                            </span>

                            <h3>Layanan tidak ditemukan</h3>

                            <p>
                                Tidak ada layanan yang sesuai dengan
                                kata kunci atau kategori yang dipilih.
                                Hapus filter untuk melihat seluruh
                                layanan yang tersedia.
                            </p>

                            <a
                                href="{{ route('layanan.index') }}"
                                class="service-search-button"
                            >
                                Lihat Semua Layanan
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($layanans->hasPages())
                    <div class="service-pagination">
                        {{ $layanans->links() }}
                    </div>
                @endif

                {{-- Help CTA --}}
                <div class="service-help-box service-reveal">
                    <div class="service-help-content">
                        <span class="service-help-label">
                            Butuh bantuan
                        </span>

                        <h2>
                            Belum yakin memilih layanan?
                        </h2>

                        <p>
                            Kirim pertanyaan melalui halaman kontak.
                            Admin akan membantu menentukan layanan yang
                            sesuai dengan dokumen dan kebutuhan cetakmu.
                        </p>
                    </div>

                    <div class="service-help-action">
                        <a
                            href="{{ route('kontak.index') }}"
                            class="service-help-button"
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
                '.service-reveal'
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