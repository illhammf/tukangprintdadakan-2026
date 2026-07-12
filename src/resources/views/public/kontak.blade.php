@extends('layouts.public')

@section(
    'title',
    'Kontak - '
    . ($website?->nama_website ?? 'Tukang Print Dadakan')
)

@php
    $namaWebsite = $website?->nama_website
        ?? 'Tukang Print Dadakan';

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

    $pesanWhatsapp = urlencode(
        'Halo, saya ingin menanyakan layanan di '
        . $namaWebsite
        . '.'
    );

    $urlWhatsapp = $nomorWhatsappClean
        ? 'https://wa.me/'
            . $nomorWhatsappClean
            . '?text='
            . $pesanWhatsapp
        : null;

    $tujuanPesanan = auth()->check()
        ? route('customer.pesanan.create')
        : route('login');

    $labelPesanan = auth()->check()
        ? 'Buat Pesanan'
        : 'Login untuk Memesan';
@endphp

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Contact Page Variables
        |--------------------------------------------------------------------------
        */

        .contact-page {
            --contact-blue: var(--public-blue, #155eef);
            --contact-blue-dark: var(--public-blue-dark, #1046b8);
            --contact-blue-soft: var(--public-blue-soft, #edf4ff);

            --contact-orange: var(--public-orange, #f97316);
            --contact-orange-dark: var(--public-orange-dark, #c2410c);
            --contact-orange-soft: var(--public-orange-soft, #fff1e7);

            --contact-green: #16a34a;
            --contact-green-dark: #15803d;
            --contact-green-soft: #ecfdf3;

            --contact-red: #dc2626;
            --contact-red-soft: #fff1f2;

            --contact-dark: var(--public-dark, #101828);
            --contact-text: var(--public-text, #344054);
            --contact-muted: var(--public-muted, #667085);

            --contact-white: #ffffff;
            --contact-soft: #f7f9fc;
            --contact-border: #e4e7ec;
            --contact-border-dark: #d0d5dd;

            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Shared Components
        |--------------------------------------------------------------------------
        */

        .contact-section {
            position: relative;
            padding: 82px 0;
        }

        .contact-section-white {
            background: var(--contact-white);
        }

        .contact-section-soft {
            background:
                radial-gradient(
                    circle at top left,
                    rgba(21, 94, 239, 0.055),
                    transparent 30%
                ),
                #f8faff;
        }

        .contact-section-heading {
            max-width: 730px;
            margin-bottom: 36px;
        }

        .contact-section-heading.center {
            margin-right: auto;
            margin-left: auto;
            text-align: center;
        }

        .contact-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 13px;
            color: var(--contact-blue);
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.09em;
        }

        .contact-eyebrow::before {
            content: "";
            width: 27px;
            height: 3px;
            border-radius: 999px;
            background:
                linear-gradient(
                    90deg,
                    var(--contact-blue),
                    var(--contact-orange)
                );
        }

        .contact-section-heading h2 {
            margin: 0 0 13px;
            color: var(--contact-dark);
            font-size: clamp(31px, 4vw, 46px);
            line-height: 1.13;
            letter-spacing: -1px;
        }

        .contact-section-heading p {
            margin: 0;
            color: var(--contact-muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .contact-button {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 20px;
            border: 1px solid transparent;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 900;
            text-align: center;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .contact-button:hover {
            transform: translateY(-2px);
        }

        .contact-button svg {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
        }

        .contact-button-primary {
            color: var(--contact-white);
            background:
                linear-gradient(
                    135deg,
                    var(--contact-blue),
                    #2b70ff
                );
            box-shadow:
                0 11px 24px rgba(21, 94, 239, 0.22);
        }

        .contact-button-primary:hover {
            color: var(--contact-white);
            background:
                linear-gradient(
                    135deg,
                    var(--contact-blue-dark),
                    var(--contact-blue)
                );
            box-shadow:
                0 15px 31px rgba(21, 94, 239, 0.28);
        }

        .contact-button-whatsapp {
            color: var(--contact-white);
            background:
                linear-gradient(
                    135deg,
                    var(--contact-green),
                    #22c55e
                );
            box-shadow:
                0 11px 24px rgba(34, 197, 94, 0.22);
        }

        .contact-button-whatsapp:hover {
            color: var(--contact-white);
            background:
                linear-gradient(
                    135deg,
                    var(--contact-green-dark),
                    var(--contact-green)
                );
            box-shadow:
                0 15px 31px rgba(34, 197, 94, 0.28);
        }

        .contact-button-outline {
            color: var(--contact-blue);
            border-color: #b9d0ff;
            background: var(--contact-white);
        }

        .contact-button-outline:hover {
            color: var(--contact-blue-dark);
            border-color: var(--contact-blue);
            background: var(--contact-blue-soft);
        }

        /*
        |--------------------------------------------------------------------------
        | Breadcrumb
        |--------------------------------------------------------------------------
        */

        .contact-breadcrumb-section {
            padding: 17px 0;
            border-bottom: 1px solid var(--contact-border);
            background: var(--contact-white);
        }

        .contact-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--contact-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .contact-breadcrumb a {
            color: var(--contact-muted);
            transition: color 0.2s ease;
        }

        .contact-breadcrumb a:hover {
            color: var(--contact-blue);
        }

        .contact-breadcrumb svg {
            width: 14px;
            height: 14px;
            flex: 0 0 14px;
        }

        .contact-breadcrumb strong {
            color: var(--contact-blue);
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .contact-hero {
            position: relative;
            overflow: hidden;
            padding: 76px 0 78px;
            border-bottom: 1px solid var(--contact-border);
            background:
                radial-gradient(
                    circle at 9% 22%,
                    rgba(21, 94, 239, 0.15),
                    transparent 27%
                ),
                radial-gradient(
                    circle at 92% 8%,
                    rgba(249, 115, 22, 0.14),
                    transparent 24%
                ),
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #f7f9fd 100%
                );
        }

        .contact-hero::before {
            content: "";
            position: absolute;
            top: -110px;
            right: -90px;
            width: 290px;
            height: 290px;
            border: 44px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .contact-hero::after {
            content: "";
            position: absolute;
            bottom: -115px;
            left: -100px;
            width: 275px;
            height: 275px;
            border: 42px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .contact-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1.05fr)
                minmax(340px, 0.55fr);
            gap: 50px;
            align-items: center;
        }

        .contact-hero-content {
            max-width: 760px;
        }

        .contact-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--contact-orange-dark);
            background: var(--contact-orange-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .contact-hero-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--contact-white);
            background: var(--contact-orange);
        }

        .contact-hero-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .contact-hero h1 {
            margin: 23px 0 17px;
            color: var(--contact-dark);
            font-size: clamp(42px, 5.8vw, 68px);
            line-height: 1.04;
            letter-spacing: -2.3px;
        }

        .contact-hero h1 span {
            position: relative;
            display: inline-block;
            color: var(--contact-blue);
        }

        .contact-hero h1 span::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 8px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.22);
            transform: rotate(-1.2deg);
        }

        .contact-hero-description {
            max-width: 710px;
            margin: 0;
            color: var(--contact-muted);
            font-size: 16px;
            line-height: 1.85;
        }

        .contact-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 29px;
        }

        .contact-hero-points {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 26px;
        }

        .contact-hero-point {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--contact-text);
            font-size: 12px;
            font-weight: 800;
        }

        .contact-hero-point-icon {
            width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--contact-blue);
            background: var(--contact-blue-soft);
        }

        .contact-hero-point-icon svg {
            width: 14px;
            height: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Contact Summary
        |--------------------------------------------------------------------------
        */

        .contact-hero-card {
            position: relative;
            padding: 27px;
            border: 1px solid rgba(228, 231, 236, 0.92);
            border-radius: 29px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            box-shadow:
                0 25px 68px rgba(16, 24, 40, 0.14);
        }

        .contact-hero-card::before {
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
                    var(--contact-blue),
                    var(--contact-orange)
                );
            transform: rotate(3deg);
        }

        .contact-hero-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 19px;
        }

        .contact-hero-card-icon {
            width: 49px;
            height: 49px;
            flex: 0 0 49px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: var(--contact-white);
            background:
                linear-gradient(
                    135deg,
                    var(--contact-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 22px rgba(21, 94, 239, 0.21);
        }

        .contact-hero-card-icon svg {
            width: 24px;
            height: 24px;
        }

        .contact-hero-card-header strong,
        .contact-hero-card-header span {
            display: block;
        }

        .contact-hero-card-header strong {
            color: var(--contact-dark);
            font-size: 17px;
        }

        .contact-hero-card-header span {
            margin-top: 2px;
            color: var(--contact-muted);
            font-size: 11px;
        }

        .contact-hero-card-list {
            display: grid;
            gap: 10px;
        }

        .contact-hero-card-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 13px;
            border: 1px solid var(--contact-border);
            border-radius: 15px;
            background: var(--contact-soft);
        }

        .contact-hero-card-item-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--contact-blue);
            background: var(--contact-blue-soft);
        }

        .contact-hero-card-item:nth-child(even)
        .contact-hero-card-item-icon {
            color: var(--contact-orange-dark);
            background: var(--contact-orange-soft);
        }

        .contact-hero-card-item-icon svg {
            width: 19px;
            height: 19px;
        }

        .contact-hero-card-item-copy {
            min-width: 0;
        }

        .contact-hero-card-item-copy span,
        .contact-hero-card-item-copy strong {
            display: block;
        }

        .contact-hero-card-item-copy span {
            color: var(--contact-muted);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .contact-hero-card-item-copy strong {
            margin-top: 3px;
            overflow: hidden;
            color: var(--contact-dark);
            font-size: 12px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Contact Layout
        |--------------------------------------------------------------------------
        */

        .contact-main-section {
            padding: 72px 0 88px;
        }

        .contact-main-grid {
            display: grid;
            grid-template-columns:
                minmax(300px, 0.72fr)
                minmax(0, 1.28fr);
            gap: 29px;
            align-items: start;
        }

        /*
        |--------------------------------------------------------------------------
        | Information Column
        |--------------------------------------------------------------------------
        */

        .contact-information-column {
            display: grid;
            gap: 19px;
        }

        .contact-information-card {
            overflow: hidden;
            border: 1px solid var(--contact-border);
            border-radius: 25px;
            background: var(--contact-white);
            box-shadow:
                0 11px 34px rgba(16, 24, 40, 0.06);
        }

        .contact-information-header {
            padding: 24px 24px 20px;
            border-bottom: 1px solid var(--contact-border);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.08),
                    transparent 35%
                ),
                #fcfcfd;
        }

        .contact-information-header-icon {
            width: 51px;
            height: 51px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border-radius: 17px;
            color: var(--contact-white);
            background:
                linear-gradient(
                    135deg,
                    var(--contact-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 22px rgba(21, 94, 239, 0.20);
        }

        .contact-information-header-icon svg {
            width: 25px;
            height: 25px;
        }

        .contact-information-header h2 {
            margin: 0 0 7px;
            color: var(--contact-dark);
            font-size: 23px;
        }

        .contact-information-header p {
            margin: 0;
            color: var(--contact-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .contact-information-list {
            display: grid;
            gap: 0;
            padding: 7px 22px;
        }

        .contact-information-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 0;
            border-bottom: 1px solid var(--contact-border);
        }

        .contact-information-item:last-child {
            border-bottom: 0;
        }

        .contact-information-item-icon {
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--contact-blue);
            background: var(--contact-blue-soft);
        }

        .contact-information-item:nth-child(even)
        .contact-information-item-icon {
            color: var(--contact-orange-dark);
            background: var(--contact-orange-soft);
        }

        .contact-information-item-icon svg {
            width: 19px;
            height: 19px;
        }

        .contact-information-item-copy {
            min-width: 0;
        }

        .contact-information-item-copy span,
        .contact-information-item-copy strong {
            display: block;
        }

        .contact-information-item-copy span {
            color: var(--contact-muted);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .contact-information-item-copy strong {
            margin-top: 4px;
            overflow-wrap: anywhere;
            color: var(--contact-dark);
            font-size: 12px;
            line-height: 1.55;
        }

        .contact-information-actions {
            display: grid;
            gap: 10px;
            padding: 20px 22px 23px;
            border-top: 1px solid var(--contact-border);
            background: #fcfcfd;
        }

        .contact-information-actions
        .contact-button {
            width: 100%;
        }

        /*
        |--------------------------------------------------------------------------
        | Ordering Note Card
        |--------------------------------------------------------------------------
        */

        .contact-order-note {
            position: relative;
            overflow: hidden;
            padding: 25px;
            border-radius: 24px;
            color: var(--contact-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.38),
                    transparent 36%
                ),
                linear-gradient(
                    145deg,
                    var(--contact-blue-dark),
                    var(--contact-blue)
                );
            box-shadow:
                0 20px 52px rgba(21, 94, 239, 0.22);
        }

        .contact-order-note::before {
            content: "";
            position: absolute;
            top: -58px;
            right: -54px;
            width: 155px;
            height: 155px;
            border: 25px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .contact-order-note-content {
            position: relative;
            z-index: 2;
        }

        .contact-order-note-icon {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 16px;
            color: var(--contact-white);
            background: rgba(255, 255, 255, 0.12);
        }

        .contact-order-note-icon svg {
            width: 24px;
            height: 24px;
        }

        .contact-order-note h3 {
            margin: 0 0 9px;
            color: var(--contact-white);
            font-size: 21px;
        }

        .contact-order-note p {
            margin: 0;
            color: #dbeafe;
            font-size: 12px;
            line-height: 1.75;
        }

        .contact-order-note-list {
            display: grid;
            gap: 9px;
            margin-top: 19px;
        }

        .contact-order-note-item {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            color: #eaf2ff;
            font-size: 11px;
            line-height: 1.55;
        }

        .contact-order-note-check {
            width: 22px;
            height: 22px;
            flex: 0 0 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--contact-white);
            background: rgba(255, 255, 255, 0.14);
        }

        .contact-order-note-check svg {
            width: 12px;
            height: 12px;
        }

        .contact-order-note .contact-button {
            width: 100%;
            margin-top: 21px;
            color: var(--contact-blue-dark);
            border-color: rgba(255, 255, 255, 0.75);
            background: var(--contact-white);
            box-shadow:
                0 11px 24px rgba(16, 24, 40, 0.18);
        }

        .contact-order-note .contact-button:hover {
            color: var(--contact-blue-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | Contact Form
        |--------------------------------------------------------------------------
        */

        .contact-form-card {
            padding: 31px;
            border: 1px solid var(--contact-border);
            border-radius: 27px;
            background: var(--contact-white);
            box-shadow:
                0 15px 45px rgba(16, 24, 40, 0.075);
        }

        .contact-form-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 27px;
            padding-bottom: 23px;
            border-bottom: 1px solid var(--contact-border);
        }

        .contact-form-title {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .contact-form-title-icon {
            width: 51px;
            height: 51px;
            flex: 0 0 51px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 17px;
            color: var(--contact-white);
            background:
                linear-gradient(
                    135deg,
                    var(--contact-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 22px rgba(249, 115, 22, 0.20);
        }

        .contact-form-title-icon svg {
            width: 25px;
            height: 25px;
        }

        .contact-form-title h2 {
            margin: 0 0 5px;
            color: var(--contact-dark);
            font-size: 25px;
        }

        .contact-form-title p {
            margin: 0;
            color: var(--contact-muted);
            font-size: 12px;
            line-height: 1.65;
        }

        .contact-form-security {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border: 1px solid #bbf7d0;
            border-radius: 11px;
            color: #166534;
            background: var(--contact-green-soft);
            font-size: 9px;
            font-weight: 900;
            white-space: nowrap;
        }

        .contact-form-security svg {
            width: 15px;
            height: 15px;
        }

        /*
        |--------------------------------------------------------------------------
        | Validation Summary
        |--------------------------------------------------------------------------
        */

        .contact-error-summary {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: 22px;
            padding: 14px;
            border: 1px solid #fecaca;
            border-radius: 15px;
            color: #991b1b;
            background: var(--contact-red-soft);
            font-size: 12px;
            font-weight: 750;
        }

        .contact-error-summary-icon {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--contact-white);
            background: var(--contact-red);
            font-size: 13px;
            font-weight: 900;
        }

        /*
        |--------------------------------------------------------------------------
        | Form Inputs
        |--------------------------------------------------------------------------
        */

        .contact-form {
            display: grid;
            gap: 18px;
        }

        .contact-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .contact-form-group {
            display: grid;
            gap: 7px;
        }

        .contact-form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--contact-text);
            font-size: 12px;
            font-weight: 900;
        }

        .contact-required {
            color: var(--contact-orange);
            font-size: 11px;
        }

        .contact-optional {
            color: var(--contact-muted);
            font-size: 9px;
            font-weight: 700;
        }

        .contact-input-wrapper {
            position: relative;
        }

        .contact-input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            width: 19px;
            height: 19px;
            color: #98a2b3;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .contact-textarea-wrapper
        .contact-input-icon {
            top: 16px;
            transform: none;
        }

        .contact-form-input,
        .contact-form-textarea {
            width: 100%;
            border: 1px solid var(--contact-border-dark);
            border-radius: 14px;
            color: var(--contact-dark);
            background: #fcfcfd;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .contact-form-input {
            min-height: 50px;
            padding: 12px 15px 12px 46px;
        }

        .contact-form-textarea {
            min-height: 160px;
            padding: 14px 15px 14px 46px;
            resize: vertical;
            line-height: 1.65;
        }

        .contact-form-input::placeholder,
        .contact-form-textarea::placeholder {
            color: #98a2b3;
        }

        .contact-form-input:hover,
        .contact-form-textarea:hover {
            border-color: #98a2b3;
        }

        .contact-form-input:focus,
        .contact-form-textarea:focus {
            border-color: var(--contact-blue);
            background: var(--contact-white);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.12);
        }

        .contact-form-input.is-invalid,
        .contact-form-textarea.is-invalid {
            border-color: var(--contact-red);
            background: #fffafa;
        }

        .contact-form-input.is-invalid:focus,
        .contact-form-textarea.is-invalid:focus {
            box-shadow:
                0 0 0 4px rgba(220, 38, 38, 0.10);
        }

        .contact-field-error {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--contact-red);
            font-size: 10px;
            font-weight: 800;
        }

        .contact-field-error::before {
            content: "!";
            width: 17px;
            height: 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--contact-white);
            background: var(--contact-red);
            font-size: 10px;
        }

        .contact-field-help {
            color: var(--contact-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Form Footer
        |--------------------------------------------------------------------------
        */

        .contact-form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-top: 4px;
            padding-top: 20px;
            border-top: 1px solid var(--contact-border);
        }

        .contact-form-footer-note {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            max-width: 440px;
            color: var(--contact-muted);
            font-size: 10px;
            line-height: 1.55;
        }

        .contact-form-footer-note svg {
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
            color: var(--contact-blue);
        }

        .contact-submit-button {
            min-width: 180px;
            border: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Contact Reasons
        |--------------------------------------------------------------------------
        */

        .contact-reason-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .contact-reason-card {
            position: relative;
            overflow: hidden;
            padding: 28px;
            border: 1px solid var(--contact-border);
            border-radius: 23px;
            background: var(--contact-white);
            box-shadow:
                0 8px 27px rgba(16, 24, 40, 0.05);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .contact-reason-card:hover {
            border-color: #bdd1ff;
            box-shadow:
                0 19px 46px rgba(16, 24, 40, 0.10);
            transform: translateY(-6px);
        }

        .contact-reason-card::before {
            content: "";
            position: absolute;
            top: -45px;
            right: -45px;
            width: 125px;
            height: 125px;
            border-radius: 999px;
            background: rgba(21, 94, 239, 0.05);
        }

        .contact-reason-card:nth-child(2)::before {
            background: rgba(249, 115, 22, 0.06);
        }

        .contact-reason-icon {
            position: relative;
            width: 54px;
            height: 54px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 19px;
            border-radius: 18px;
            color: var(--contact-white);
            background:
                linear-gradient(
                    135deg,
                    var(--contact-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 23px rgba(21, 94, 239, 0.20);
        }

        .contact-reason-card:nth-child(2)
        .contact-reason-icon {
            background:
                linear-gradient(
                    135deg,
                    var(--contact-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 23px rgba(249, 115, 22, 0.20);
        }

        .contact-reason-icon svg {
            width: 26px;
            height: 26px;
        }

        .contact-reason-card h3 {
            position: relative;
            margin: 0 0 10px;
            color: var(--contact-dark);
            font-size: 20px;
        }

        .contact-reason-card p {
            position: relative;
            margin: 0;
            color: var(--contact-muted);
            font-size: 13px;
            line-height: 1.75;
        }

        .contact-reason-number {
            position: absolute;
            right: 19px;
            bottom: 12px;
            color: rgba(21, 94, 239, 0.065);
            font-size: 68px;
            font-weight: 900;
            line-height: 1;
        }

        /*
        |--------------------------------------------------------------------------
        | FAQ
        |--------------------------------------------------------------------------
        */

        .contact-faq-layout {
            display: grid;
            grid-template-columns:
                minmax(300px, 0.72fr)
                minmax(0, 1.28fr);
            gap: 39px;
            align-items: start;
        }

        .contact-faq-intro {
            position: sticky;
            top: 120px;
            padding: 31px;
            border-radius: 26px;
            color: var(--contact-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.36),
                    transparent 35%
                ),
                linear-gradient(
                    145deg,
                    var(--contact-blue-dark),
                    var(--contact-blue)
                );
            box-shadow:
                0 21px 56px rgba(21, 94, 239, 0.23);
        }

        .contact-faq-intro-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.23);
            border-radius: 999px;
            color: #dbeafe;
            background: rgba(255, 255, 255, 0.10);
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .contact-faq-intro-label::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: #fdba74;
        }

        .contact-faq-intro h2 {
            margin: 18px 0 12px;
            color: var(--contact-white);
            font-size: 34px;
            line-height: 1.13;
            letter-spacing: -0.9px;
        }

        .contact-faq-intro p {
            margin: 0;
            color: #dbeafe;
            font-size: 13px;
            line-height: 1.75;
        }

        .contact-faq-intro .contact-button {
            width: 100%;
            margin-top: 23px;
            color: var(--contact-blue-dark);
            border-color: rgba(255, 255, 255, 0.75);
            background: var(--contact-white);
            box-shadow:
                0 11px 24px rgba(16, 24, 40, 0.18);
        }

        .contact-faq-intro .contact-button:hover {
            color: var(--contact-blue-dark);
        }

        .contact-faq-list {
            display: grid;
            gap: 13px;
        }

        .contact-faq-item {
            overflow: hidden;
            border: 1px solid var(--contact-border);
            border-radius: 18px;
            background: var(--contact-white);
            box-shadow:
                0 6px 22px rgba(16, 24, 40, 0.045);
        }

        .contact-faq-question {
            width: 100%;
            min-height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 17px;
            padding: 16px 18px;
            border: 0;
            color: var(--contact-dark);
            background: var(--contact-white);
            font-size: 13px;
            font-weight: 900;
            text-align: left;
            cursor: pointer;
        }

        .contact-faq-question:hover {
            background: #fcfcfd;
        }

        .contact-faq-question-icon {
            width: 31px;
            height: 31px;
            flex: 0 0 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: var(--contact-blue);
            background: var(--contact-blue-soft);
            transition:
                color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .contact-faq-question-icon svg {
            width: 16px;
            height: 16px;
        }

        .contact-faq-item.open
        .contact-faq-question-icon {
            color: var(--contact-white);
            background: var(--contact-orange);
            transform: rotate(45deg);
        }

        .contact-faq-answer {
            display: none;
            padding: 0 18px 18px;
            color: var(--contact-muted);
            font-size: 12px;
            line-height: 1.75;
        }

        .contact-faq-answer p {
            margin: 0;
            padding-top: 15px;
            border-top: 1px solid var(--contact-border);
        }

        .contact-faq-item.open
        .contact-faq-answer {
            display: block;
        }

        /*
        |--------------------------------------------------------------------------
        | Final CTA
        |--------------------------------------------------------------------------
        */

        .contact-final-cta {
            position: relative;
            overflow: hidden;
            padding: 47px;
            border-radius: 29px;
            color: var(--contact-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.44),
                    transparent 33%
                ),
                radial-gradient(
                    circle at bottom left,
                    rgba(255, 255, 255, 0.11),
                    transparent 36%
                ),
                linear-gradient(
                    135deg,
                    var(--contact-blue-dark),
                    var(--contact-blue)
                );
            box-shadow:
                0 27px 70px rgba(21, 94, 239, 0.25);
        }

        .contact-final-cta::before {
            content: "";
            position: absolute;
            top: -80px;
            right: -70px;
            width: 210px;
            height: 210px;
            border: 35px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .contact-final-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 31px;
            align-items: center;
        }

        .contact-final-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: #dbeafe;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .contact-final-label::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #fdba74;
        }

        .contact-final-cta h2 {
            margin: 0 0 10px;
            color: var(--contact-white);
            font-size: clamp(30px, 4vw, 44px);
            line-height: 1.12;
            letter-spacing: -1.1px;
        }

        .contact-final-cta p {
            max-width: 700px;
            margin: 0;
            color: #dbeafe;
            font-size: 14px;
            line-height: 1.75;
        }

        .contact-final-actions {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .contact-final-button {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 20px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 14px;
            color: var(--contact-blue-dark);
            background: var(--contact-white);
            font-size: 13px;
            font-weight: 900;
            white-space: nowrap;
            box-shadow:
                0 12px 26px rgba(16, 24, 40, 0.18);
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .contact-final-button:hover {
            color: var(--contact-blue-dark);
            box-shadow:
                0 16px 34px rgba(16, 24, 40, 0.24);
            transform: translateY(-2px);
        }

        .contact-final-button svg {
            width: 18px;
            height: 18px;
        }

        .contact-final-link {
            color: #dbeafe;
            font-size: 11px;
            font-weight: 800;
            text-align: center;
        }

        .contact-final-link:hover {
            color: var(--contact-white);
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .contact-reveal {
            opacity: 0;
            transform: translateY(21px);
            transition:
                opacity 0.56s ease,
                transform 0.56s ease;
        }

        .contact-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .contact-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(310px, 0.5fr);
                gap: 35px;
            }

            .contact-main-grid {
                grid-template-columns:
                    minmax(280px, 0.68fr)
                    minmax(0, 1.32fr);
            }
        }

        @media (max-width: 900px) {
            .contact-section {
                padding: 68px 0;
            }

            .contact-hero {
                padding: 62px 0 70px;
            }

            .contact-hero-grid,
            .contact-main-grid,
            .contact-faq-layout {
                grid-template-columns: 1fr;
            }

            .contact-hero-card {
                max-width: 650px;
            }

            .contact-information-column {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .contact-information-card,
            .contact-order-note {
                height: 100%;
            }

            .contact-faq-intro {
                position: static;
            }

            .contact-final-grid {
                grid-template-columns: 1fr;
            }

            .contact-final-actions {
                align-items: flex-start;
            }
        }

        @media (max-width: 760px) {
            .contact-information-column,
            .contact-reason-grid {
                grid-template-columns: 1fr;
            }

            .contact-form-header,
            .contact-form-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .contact-form-security {
                white-space: normal;
            }

            .contact-submit-button {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .contact-section {
                padding: 54px 0;
            }

            .contact-section-heading {
                margin-bottom: 28px;
            }

            .contact-section-heading h2 {
                font-size: 31px;
            }

            .contact-section-heading p {
                font-size: 13px;
            }

            .contact-breadcrumb-section {
                padding: 13px 0;
            }

            .contact-hero {
                padding: 47px 0 56px;
            }

            .contact-hero::before,
            .contact-hero::after {
                display: none;
            }

            .contact-hero h1 {
                margin-top: 18px;
                font-size: 39px;
                letter-spacing: -1.5px;
            }

            .contact-hero-description {
                font-size: 14px;
            }

            .contact-hero-actions {
                flex-direction: column;
            }

            .contact-hero-actions
            .contact-button {
                width: 100%;
            }

            .contact-hero-points {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .contact-hero-card {
                padding: 21px;
                border-radius: 23px;
            }

            .contact-main-section {
                padding: 48px 0 62px;
            }

            .contact-information-card,
            .contact-order-note,
            .contact-form-card {
                border-radius: 22px;
            }

            .contact-information-header {
                padding: 21px 20px 18px;
            }

            .contact-information-list {
                padding-right: 19px;
                padding-left: 19px;
            }

            .contact-information-actions {
                padding-right: 19px;
                padding-left: 19px;
            }

            .contact-order-note {
                padding: 22px;
            }

            .contact-form-card {
                padding: 21px;
            }

            .contact-form-title-icon {
                width: 45px;
                height: 45px;
                flex-basis: 45px;
                border-radius: 14px;
            }

            .contact-form-title h2 {
                font-size: 22px;
            }

            .contact-form-grid {
                grid-template-columns: 1fr;
            }

            .contact-reason-card {
                padding: 22px;
            }

            .contact-faq-intro {
                padding: 23px;
                border-radius: 22px;
            }

            .contact-faq-intro h2 {
                font-size: 29px;
            }

            .contact-final-cta {
                padding: 30px 22px;
                border-radius: 23px;
            }

            .contact-final-cta h2 {
                font-size: 31px;
            }

            .contact-final-actions,
            .contact-final-button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .contact-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .contact-reason-card,
            .contact-button,
            .contact-final-button {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="contact-page">
        {{-- Breadcrumb --}}
        <section class="contact-breadcrumb-section">
            <div class="container">
                <nav
                    class="contact-breadcrumb"
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

                    <strong>
                        Kontak
                    </strong>
                </nav>
            </div>
        </section>

        {{-- Hero --}}
        <section class="contact-hero">
            <div class="container contact-hero-grid">
                <div class="contact-hero-content">
                    <span class="contact-hero-badge">
                        <span class="contact-hero-badge-icon">
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

                        Hubungi Kami
                    </span>

                    <h1>
                        Ada pertanyaan?
                        <span>Kami siap membantu</span>
                    </h1>

                    <p class="contact-hero-description">
                        Kirim pertanyaan mengenai layanan, harga,
                        kebutuhan khusus, waktu pengerjaan, atau
                        pengambilan pesanan. Pesanmu akan diterima
                        oleh admin melalui dashboard
                        {{ $namaWebsite }}.
                    </p>

                    <div class="contact-hero-actions">
                        @if ($urlWhatsapp)
                            <a
                                href="{{ $urlWhatsapp }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="contact-button contact-button-whatsapp"
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
                                    <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                                    <path d="M8 9h8"/>
                                    <path d="M8 13h5"/>
                                </svg>

                                Chat WhatsApp
                            </a>
                        @endif

                        <a
                            href="#form-kontak"
                            class="contact-button contact-button-outline"
                        >
                            Kirim Melalui Form
                        </a>
                    </div>

                    <div class="contact-hero-points">
                        <span class="contact-hero-point">
                            <span class="contact-hero-point-icon">
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

                            Pesan diterima admin
                        </span>

                        <span class="contact-hero-point">
                            <span class="contact-hero-point-icon">
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

                            Data tersimpan di sistem
                        </span>

                        <span class="contact-hero-point">
                            <span class="contact-hero-point-icon">
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

                            Mudah digunakan
                        </span>
                    </div>
                </div>

                <aside class="contact-hero-card">
                    <div class="contact-hero-card-header">
                        <span class="contact-hero-card-icon">
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
                            <strong>Informasi Singkat</strong>
                            <span>Kontak dan operasional layanan</span>
                        </span>
                    </div>

                    <div class="contact-hero-card-list">
                        <div class="contact-hero-card-item">
                            <span class="contact-hero-card-item-icon">
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
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.69 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.28-1.28a2 2 0 0 1 2.11-.45c.9.33 1.84.56 2.8.69A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>

                            <span class="contact-hero-card-item-copy">
                                <span>WhatsApp</span>
                                <strong>
                                    {{ $website?->nomor_whatsapp ?? '-' }}
                                </strong>
                            </span>
                        </div>

                        <div class="contact-hero-card-item">
                            <span class="contact-hero-card-item-icon">
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
                                    <rect width="18" height="14" x="3" y="5" rx="2"/>
                                    <path d="m3 7 9 6 9-6"/>
                                </svg>
                            </span>

                            <span class="contact-hero-card-item-copy">
                                <span>Email</span>
                                <strong>
                                    {{ $website?->email ?? '-' }}
                                </strong>
                            </span>
                        </div>

                        <div class="contact-hero-card-item">
                            <span class="contact-hero-card-item-icon">
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

                            <span class="contact-hero-card-item-copy">
                                <span>Jam Operasional</span>
                                <strong>
                                    {{ $website?->jam_operasional ?? '-' }}
                                </strong>
                            </span>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        {{-- Main Contact --}}
        <section
            class="contact-main-section"
            id="form-kontak"
        >
            <div class="container">
                <div class="contact-main-grid">
                    {{-- Contact Information --}}
                    <div class="contact-information-column">
                        <article class="contact-information-card contact-reveal">
                            <div class="contact-information-header">
                                <span class="contact-information-header-icon">
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
                                </span>

                                <h2>Informasi Kontak</h2>

                                <p>
                                    Gunakan salah satu kontak berikut
                                    untuk berkomunikasi dengan admin.
                                </p>
                            </div>

                            <div class="contact-information-list">
                                <div class="contact-information-item">
                                    <span class="contact-information-item-icon">
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
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.69 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.28-1.28a2 2 0 0 1 2.11-.45c.9.33 1.84.56 2.8.69A2 2 0 0 1 22 16.92z"/>
                                        </svg>
                                    </span>

                                    <span class="contact-information-item-copy">
                                        <span>WhatsApp</span>

                                        <strong>
                                            {{ $website?->nomor_whatsapp ?? '-' }}
                                        </strong>
                                    </span>
                                </div>

                                <div class="contact-information-item">
                                    <span class="contact-information-item-icon">
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
                                            <rect width="18" height="14" x="3" y="5" rx="2"/>
                                            <path d="m3 7 9 6 9-6"/>
                                        </svg>
                                    </span>

                                    <span class="contact-information-item-copy">
                                        <span>Email</span>

                                        <strong>
                                            {{ $website?->email ?? '-' }}
                                        </strong>
                                    </span>
                                </div>

                                <div class="contact-information-item">
                                    <span class="contact-information-item-icon">
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

                                    <span class="contact-information-item-copy">
                                        <span>Jam Operasional</span>

                                        <strong>
                                            {{ $website?->jam_operasional ?? '-' }}
                                        </strong>
                                    </span>
                                </div>

                                <div class="contact-information-item">
                                    <span class="contact-information-item-icon">
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
                                            <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                    </span>

                                    <span class="contact-information-item-copy">
                                        <span>Lokasi Pengambilan</span>

                                        <strong>
                                            {{ $website?->alamat ?? '-' }}
                                        </strong>
                                    </span>
                                </div>
                            </div>

                            <div class="contact-information-actions">
                                @if ($urlWhatsapp)
                                    <a
                                        href="{{ $urlWhatsapp }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="contact-button contact-button-whatsapp"
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
                                            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                                        </svg>

                                        Chat WhatsApp
                                    </a>
                                @endif
                            </div>
                        </article>

                        <aside class="contact-order-note contact-reveal">
                            <div class="contact-order-note-content">
                                <span class="contact-order-note-icon">
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

                                <h3>Ingin membuat pesanan?</h3>

                                <p>
                                    Form kontak digunakan untuk
                                    pertanyaan. Untuk pemesanan resmi,
                                    gunakan halaman pelanggan agar file,
                                    biaya, dan status pesanan tercatat.
                                </p>

                                <div class="contact-order-note-list">
                                    <span class="contact-order-note-item">
                                        <span class="contact-order-note-check">
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

                                        File terhubung dengan pesanan
                                    </span>

                                    <span class="contact-order-note-item">
                                        <span class="contact-order-note-check">
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

                                        Estimasi biaya dapat dilihat
                                    </span>

                                    <span class="contact-order-note-item">
                                        <span class="contact-order-note-check">
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

                                        Status pengerjaan dapat dipantau
                                    </span>
                                </div>

                                <a
                                    href="{{ $tujuanPesanan }}"
                                    class="contact-button"
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
                            </div>
                        </aside>
                    </div>

                    {{-- Contact Form --}}
                    <div class="contact-form-card contact-reveal">
                        <div class="contact-form-header">
                            <div class="contact-form-title">
                                <span class="contact-form-title-icon">
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
                                        <path d="M8 9h8"/>
                                        <path d="M8 13h5"/>
                                    </svg>
                                </span>

                                <span>
                                    <h2>Form Pertanyaan</h2>

                                    <p>
                                        Lengkapi data berikut agar admin
                                        dapat memahami pertanyaanmu.
                                    </p>
                                </span>
                            </div>

                            <span class="contact-form-security">
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

                                Data dikirim ke admin
                            </span>
                        </div>

                        @if ($errors->any())
                            <div class="contact-error-summary">
                                <span class="contact-error-summary-icon">
                                    !
                                </span>

                                <span>
                                    Periksa kembali kolom yang masih
                                    salah atau belum diisi.
                                </span>
                            </div>
                        @endif

                        <form
                            action="{{ route('kontak.store') }}"
                            method="POST"
                            class="contact-form"
                        >
                            @csrf

                            <div class="contact-form-group">
                                <label
                                    for="nama"
                                    class="contact-form-label"
                                >
                                    <span>
                                        Nama Lengkap
                                        <span class="contact-required">*</span>
                                    </span>
                                </label>

                                <div class="contact-input-wrapper">
                                    <svg
                                        class="contact-input-icon"
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

                                    <input
                                        type="text"
                                        id="nama"
                                        name="nama"
                                        value="{{
                                            old(
                                                'nama',
                                                auth()->user()?->name
                                            )
                                        }}"
                                        class="contact-form-input {{
                                            $errors->has('nama')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Masukkan nama lengkap"
                                        autocomplete="name"
                                        aria-invalid="{{
                                            $errors->has('nama')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                        required
                                    >
                                </div>

                                @error('nama')
                                    <span class="contact-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="contact-form-grid">
                                <div class="contact-form-group">
                                    <label
                                        for="email"
                                        class="contact-form-label"
                                    >
                                        <span>
                                            Email
                                            <span class="contact-required">*</span>
                                        </span>
                                    </label>

                                    <div class="contact-input-wrapper">
                                        <svg
                                            class="contact-input-icon"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            aria-hidden="true"
                                        >
                                            <rect width="18" height="14" x="3" y="5" rx="2"/>
                                            <path d="m3 7 9 6 9-6"/>
                                        </svg>

                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="{{
                                                old(
                                                    'email',
                                                    auth()->user()?->email
                                                )
                                            }}"
                                            class="contact-form-input {{
                                                $errors->has('email')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="contoh@email.com"
                                            autocomplete="email"
                                            aria-invalid="{{
                                                $errors->has('email')
                                                    ? 'true'
                                                    : 'false'
                                            }}"
                                            required
                                        >
                                    </div>

                                    @error('email')
                                        <span class="contact-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="contact-form-group">
                                    <label
                                        for="nomor_whatsapp"
                                        class="contact-form-label"
                                    >
                                        <span>
                                            Nomor WhatsApp
                                            <span class="contact-required">*</span>
                                        </span>
                                    </label>

                                    <div class="contact-input-wrapper">
                                        <svg
                                            class="contact-input-icon"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            aria-hidden="true"
                                        >
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.69 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.28-1.28a2 2 0 0 1 2.11-.45c.9.33 1.84.56 2.8.69A2 2 0 0 1 22 16.92z"/>
                                        </svg>

                                        <input
                                            type="tel"
                                            id="nomor_whatsapp"
                                            name="nomor_whatsapp"
                                            value="{{
                                                old(
                                                    'nomor_whatsapp',
                                                    auth()->user()
                                                        ?->nomor_whatsapp
                                                )
                                            }}"
                                            class="contact-form-input {{
                                                $errors->has(
                                                    'nomor_whatsapp'
                                                )
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="08xxxxxxxxxx"
                                            inputmode="tel"
                                            autocomplete="tel"
                                            aria-invalid="{{
                                                $errors->has(
                                                    'nomor_whatsapp'
                                                )
                                                    ? 'true'
                                                    : 'false'
                                            }}"
                                            required
                                        >
                                    </div>

                                    @error('nomor_whatsapp')
                                        <span class="contact-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="contact-form-group">
                                <label
                                    for="subjek"
                                    class="contact-form-label"
                                >
                                    <span>
                                        Subjek
                                        <span class="contact-required">*</span>
                                    </span>
                                </label>

                                <div class="contact-input-wrapper">
                                    <svg
                                        class="contact-input-icon"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >
                                        <path d="M4 6h16"/>
                                        <path d="M4 12h16"/>
                                        <path d="M4 18h10"/>
                                    </svg>

                                    <input
                                        type="text"
                                        id="subjek"
                                        name="subjek"
                                        value="{{ old('subjek') }}"
                                        class="contact-form-input {{
                                            $errors->has('subjek')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Contoh: Tanya estimasi print laporan"
                                        aria-invalid="{{
                                            $errors->has('subjek')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                        required
                                    >
                                </div>

                                @error('subjek')
                                    <span class="contact-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="contact-form-group">
                                <label
                                    for="pesan"
                                    class="contact-form-label"
                                >
                                    <span>
                                        Pesan
                                        <span class="contact-required">*</span>
                                    </span>

                                    <span class="contact-optional">
                                        Jelaskan kebutuhan dengan jelas
                                    </span>
                                </label>

                                <div class="contact-input-wrapper contact-textarea-wrapper">
                                    <svg
                                        class="contact-input-icon"
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

                                    <textarea
                                        id="pesan"
                                        name="pesan"
                                        rows="7"
                                        class="contact-form-textarea {{
                                            $errors->has('pesan')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Tuliskan pertanyaan, jenis dokumen, jumlah halaman, waktu yang dibutuhkan, atau informasi lainnya"
                                        aria-invalid="{{
                                            $errors->has('pesan')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                        required
                                    >{{ old('pesan') }}</textarea>
                                </div>

                                @error('pesan')
                                    <span class="contact-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <span class="contact-field-help">
                                    Jangan mengirim password, kode OTP,
                                    atau informasi pembayaran rahasia
                                    melalui form ini.
                                </span>
                            </div>

                            <div class="contact-form-footer">
                                <span class="contact-form-footer-note">
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

                                    Pesan ini akan tersimpan pada
                                    dashboard admin agar dapat ditinjau
                                    dan ditindaklanjuti.
                                </span>

                                <button
                                    type="submit"
                                    class="contact-button contact-button-primary contact-submit-button"
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
                                        <path d="m22 2-7 20-4-9-9-4Z"/>
                                        <path d="M22 2 11 13"/>
                                    </svg>

                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        {{-- Contact Reasons --}}
        <section class="contact-section contact-section-white">
            <div class="container">
                <div class="contact-section-heading center contact-reveal">
                    <span class="contact-eyebrow">
                        Hal yang dapat ditanyakan
                    </span>

                    <h2>
                        Gunakan form kontak sesuai kebutuhanmu
                    </h2>

                    <p>
                        Form kontak dapat digunakan untuk pertanyaan
                        sebelum melakukan pemesanan atau untuk
                        menyampaikan kebutuhan khusus kepada admin.
                    </p>
                </div>

                <div class="contact-reason-grid">
                    <article class="contact-reason-card contact-reveal">
                        <span class="contact-reason-icon">
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

                        <h3>Pertanyaan Layanan</h3>

                        <p>
                            Tanyakan jenis print, ukuran kertas,
                            jilid, laminating, atau pilihan layanan
                            yang sesuai dengan dokumenmu.
                        </p>

                        <span class="contact-reason-number">
                            01
                        </span>
                    </article>

                    <article class="contact-reason-card contact-reveal">
                        <span class="contact-reason-icon">
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

                        <h3>Estimasi Pengerjaan</h3>

                        <p>
                            Konfirmasikan perkiraan waktu pengerjaan
                            apabila dokumen dibutuhkan dalam waktu
                            tertentu.
                        </p>

                        <span class="contact-reason-number">
                            02
                        </span>
                    </article>

                    <article class="contact-reason-card contact-reveal">
                        <span class="contact-reason-icon">
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
                                <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </span>

                        <h3>Pengambilan Pesanan</h3>

                        <p>
                            Tanyakan lokasi pengambilan, jam
                            operasional, atau kebutuhan pengiriman
                            pesanan.
                        </p>

                        <span class="contact-reason-number">
                            03
                        </span>
                    </article>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="contact-section contact-section-soft">
            <div class="container">
                <div class="contact-faq-layout">
                    <aside class="contact-faq-intro contact-reveal">
                        <span class="contact-faq-intro-label">
                            Pertanyaan umum
                        </span>

                        <h2>
                            Jawaban cepat sebelum menghubungi admin
                        </h2>

                        <p>
                            Beberapa pertanyaan umum mengenai
                            pemesanan, upload file, dan status pesanan
                            dapat dilihat di bagian ini.
                        </p>

                        <a
                            href="{{ route('layanan.index') }}"
                            class="contact-button"
                        >
                            Lihat Daftar Layanan
                        </a>
                    </aside>

                    <div class="contact-faq-list">
                        <article class="contact-faq-item contact-reveal open">
                            <button
                                type="button"
                                class="contact-faq-question"
                                aria-expanded="true"
                            >
                                <span>
                                    Apakah pesanan dapat dibuat melalui WhatsApp?
                                </span>

                                <span class="contact-faq-question-icon">
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
                            </button>

                            <div class="contact-faq-answer">
                                <p>
                                    WhatsApp dapat digunakan untuk
                                    pertanyaan atau konfirmasi. Pesanan
                                    resmi sebaiknya dibuat melalui
                                    halaman pelanggan agar file,
                                    estimasi biaya, pembayaran, dan
                                    status tercatat di sistem.
                                </p>
                            </div>
                        </article>

                        <article class="contact-faq-item contact-reveal">
                            <button
                                type="button"
                                class="contact-faq-question"
                                aria-expanded="false"
                            >
                                <span>
                                    Bagaimana cara mengirim file dokumen?
                                </span>

                                <span class="contact-faq-question-icon">
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
                            </button>

                            <div class="contact-faq-answer">
                                <p>
                                    Login ke akun pelanggan, buka
                                    halaman Buat Pesanan, pilih layanan,
                                    isi detail cetak, lalu unggah file
                                    pada bagian yang tersedia.
                                </p>
                            </div>
                        </article>

                        <article class="contact-faq-item contact-reveal">
                            <button
                                type="button"
                                class="contact-faq-question"
                                aria-expanded="false"
                            >
                                <span>
                                    Apakah harga pada layanan merupakan harga akhir?
                                </span>

                                <span class="contact-faq-question-icon">
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
                            </button>

                            <div class="contact-faq-answer">
                                <p>
                                    Harga yang ditampilkan merupakan
                                    harga dasar. Total dapat berubah
                                    berdasarkan jumlah halaman, copy,
                                    jilid, laminating, dan pengiriman.
                                </p>
                            </div>
                        </article>

                        <article class="contact-faq-item contact-reveal">
                            <button
                                type="button"
                                class="contact-faq-question"
                                aria-expanded="false"
                            >
                                <span>
                                    Bagaimana cara melihat status pesanan?
                                </span>

                                <span class="contact-faq-question-icon">
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
                            </button>

                            <div class="contact-faq-answer">
                                <p>
                                    Masuk ke dashboard pelanggan,
                                    kemudian buka Pesanan Saya. Status
                                    pesanan dan pembayaran akan tampil
                                    pada daftar serta halaman detail
                                    pesanan.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        {{-- Final CTA --}}
        <section class="contact-section">
            <div class="container">
                <div class="contact-final-cta contact-reveal">
                    <div class="contact-final-grid">
                        <div>
                            <span class="contact-final-label">
                                Siap membuat pesanan
                            </span>

                            <h2>
                                Tidak perlu menunggu untuk mulai mencetak
                            </h2>

                            <p>
                                Pilih layanan, unggah dokumen, lihat
                                estimasi biaya, dan pantau pengerjaan
                                pesanan melalui
                                {{ $namaWebsite }}.
                            </p>
                        </div>

                        <div class="contact-final-actions">
                            <a
                                href="{{ $tujuanPesanan }}"
                                class="contact-final-button"
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
                                class="contact-final-link"
                            >
                                Lihat layanan yang tersedia
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
                '.contact-reveal'
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
            } else {
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
            }

            const faqItems = document.querySelectorAll(
                '.contact-faq-item'
            );

            faqItems.forEach((item) => {
                const button = item.querySelector(
                    '.contact-faq-question'
                );

                if (!button) {
                    return;
                }

                button.addEventListener('click', () => {
                    const isOpen = item.classList.contains('open');

                    faqItems.forEach((faqItem) => {
                        faqItem.classList.remove('open');

                        faqItem
                            .querySelector('.contact-faq-question')
                            ?.setAttribute(
                                'aria-expanded',
                                'false'
                            );
                    });

                    if (!isOpen) {
                        item.classList.add('open');

                        button.setAttribute(
                            'aria-expanded',
                            'true'
                        );
                    }
                });
            });
        });
    </script>
@endpush
