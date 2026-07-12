@extends('layouts.public')

@section(
    'title',
    'Tentang Kami - '
    . ($website?->nama_website ?? 'Tukang Print Dadakan')
)

@php
    $namaWebsite = $website?->nama_website
        ?? 'Tukang Print Dadakan';

    $gambarTentang = $website?->hero_image
        ? \Illuminate\Support\Facades\Storage::url(
            $website->hero_image
        )
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
        : route('register');

    $labelPesanan = auth()->check()
        ? 'Buat Pesanan'
        : 'Mulai Pesan';
@endphp

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | About Page Variables
        |--------------------------------------------------------------------------
        */

        .about-page {
            --about-blue: var(--public-blue, #155eef);
            --about-blue-dark: var(--public-blue-dark, #1046b8);
            --about-blue-soft: var(--public-blue-soft, #edf4ff);

            --about-orange: var(--public-orange, #f97316);
            --about-orange-dark: var(--public-orange-dark, #c2410c);
            --about-orange-soft: var(--public-orange-soft, #fff1e7);

            --about-green: #16a34a;
            --about-green-soft: #ecfdf3;

            --about-dark: var(--public-dark, #101828);
            --about-text: var(--public-text, #344054);
            --about-muted: var(--public-muted, #667085);

            --about-white: #ffffff;
            --about-soft: #f7f9fc;
            --about-border: #e4e7ec;

            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Shared Elements
        |--------------------------------------------------------------------------
        */

        .about-section {
            position: relative;
            padding: 84px 0;
        }

        .about-section-white {
            background: var(--about-white);
        }

        .about-section-soft {
            background:
                radial-gradient(
                    circle at top left,
                    rgba(21, 94, 239, 0.055),
                    transparent 30%
                ),
                #f8faff;
        }

        .about-section-heading {
            max-width: 740px;
            margin-bottom: 38px;
        }

        .about-section-heading.center {
            margin-right: auto;
            margin-left: auto;
            text-align: center;
        }

        .about-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 14px;
            color: var(--about-blue);
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.09em;
        }

        .about-eyebrow::before {
            content: "";
            width: 27px;
            height: 3px;
            border-radius: 999px;
            background:
                linear-gradient(
                    90deg,
                    var(--about-blue),
                    var(--about-orange)
                );
        }

        .about-section-heading h2 {
            margin: 0 0 14px;
            color: var(--about-dark);
            font-size: clamp(31px, 4vw, 47px);
            line-height: 1.13;
            letter-spacing: -1.1px;
        }

        .about-section-heading p {
            margin: 0;
            color: var(--about-muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .about-button {
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
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .about-button:hover {
            transform: translateY(-2px);
        }

        .about-button svg {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
        }

        .about-button-primary {
            color: var(--about-white);
            background:
                linear-gradient(
                    135deg,
                    var(--about-blue),
                    #2b70ff
                );
            box-shadow:
                0 11px 24px rgba(21, 94, 239, 0.22);
        }

        .about-button-primary:hover {
            color: var(--about-white);
            background:
                linear-gradient(
                    135deg,
                    var(--about-blue-dark),
                    var(--about-blue)
                );
            box-shadow:
                0 15px 31px rgba(21, 94, 239, 0.28);
        }

        .about-button-secondary {
            color: var(--about-orange-dark);
            border-color: #fed7aa;
            background: var(--about-white);
        }

        .about-button-secondary:hover {
            color: var(--about-orange-dark);
            border-color: var(--about-orange);
            background: var(--about-orange-soft);
        }

        /*
        |--------------------------------------------------------------------------
        | Breadcrumb
        |--------------------------------------------------------------------------
        */

        .about-breadcrumb-section {
            padding: 17px 0;
            border-bottom: 1px solid var(--about-border);
            background: var(--about-white);
        }

        .about-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--about-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .about-breadcrumb a {
            color: var(--about-muted);
            transition: color 0.2s ease;
        }

        .about-breadcrumb a:hover {
            color: var(--about-blue);
        }

        .about-breadcrumb svg {
            width: 14px;
            height: 14px;
        }

        .about-breadcrumb strong {
            color: var(--about-blue);
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .about-hero {
            position: relative;
            overflow: hidden;
            padding: 78px 0 88px;
            border-bottom: 1px solid var(--about-border);
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

        .about-hero::before {
            content: "";
            position: absolute;
            top: -105px;
            right: -85px;
            width: 285px;
            height: 285px;
            border: 44px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .about-hero::after {
            content: "";
            position: absolute;
            bottom: -120px;
            left: -100px;
            width: 275px;
            height: 275px;
            border: 42px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .about-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1.02fr)
                minmax(390px, 0.98fr);
            gap: 58px;
            align-items: center;
        }

        .about-hero-content {
            max-width: 720px;
        }

        .about-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--about-orange-dark);
            background: var(--about-orange-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .about-hero-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--about-white);
            background: var(--about-orange);
        }

        .about-hero-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .about-hero h1 {
            margin: 24px 0 18px;
            color: var(--about-dark);
            font-size: clamp(43px, 5.8vw, 69px);
            line-height: 1.04;
            letter-spacing: -2.4px;
        }

        .about-hero h1 span {
            position: relative;
            display: inline-block;
            color: var(--about-blue);
        }

        .about-hero h1 span::after {
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

        .about-hero-description {
            margin: 0;
            color: var(--about-muted);
            font-size: 16px;
            line-height: 1.85;
        }

        .about-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }

        .about-hero-points {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 27px;
        }

        .about-hero-point {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--about-text);
            font-size: 12px;
            font-weight: 800;
        }

        .about-hero-point-icon {
            width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--about-blue);
            background: var(--about-blue-soft);
        }

        .about-hero-point-icon svg {
            width: 14px;
            height: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Visual
        |--------------------------------------------------------------------------
        */

        .about-hero-visual {
            position: relative;
        }

        .about-image-card {
            position: relative;
            padding: 18px;
            border: 1px solid rgba(228, 231, 236, 0.92);
            border-radius: 31px;
            background: rgba(255, 255, 255, 0.91);
            backdrop-filter: blur(16px);
            box-shadow:
                0 28px 78px rgba(16, 24, 40, 0.14);
        }

        .about-image-card::before {
            content: "";
            position: absolute;
            top: -12px;
            right: 31px;
            width: 79px;
            height: 25px;
            border-radius: 9px 9px 3px 3px;
            background:
                linear-gradient(
                    90deg,
                    var(--about-blue),
                    var(--about-orange)
                );
            transform: rotate(3deg);
        }

        .about-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 23px;
            background:
                linear-gradient(
                    135deg,
                    var(--about-blue-soft),
                    var(--about-orange-soft)
                );
        }

        .about-image {
            width: 100%;
            height: 430px;
            display: block;
            object-fit: cover;
        }

        .about-image-overlay {
            position: absolute;
            inset: auto 0 0;
            padding: 58px 21px 19px;
            color: var(--about-white);
            background:
                linear-gradient(
                    180deg,
                    transparent,
                    rgba(16, 24, 40, 0.78)
                );
        }

        .about-image-overlay strong,
        .about-image-overlay span {
            display: block;
        }

        .about-image-overlay strong {
            font-size: 18px;
        }

        .about-image-overlay span {
            margin-top: 4px;
            color: #e2e8f0;
            font-size: 11px;
        }

        .about-floating-card {
            position: absolute;
            right: -25px;
            bottom: 55px;
            max-width: 220px;
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 15px;
            border: 1px solid var(--about-border);
            border-radius: 17px;
            background: var(--about-white);
            box-shadow:
                0 16px 39px rgba(16, 24, 40, 0.15);
        }

        .about-floating-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--about-white);
            background:
                linear-gradient(
                    135deg,
                    var(--about-orange),
                    #fb923c
                );
        }

        .about-floating-icon svg {
            width: 21px;
            height: 21px;
        }

        .about-floating-card strong,
        .about-floating-card span {
            display: block;
        }

        .about-floating-card strong {
            color: var(--about-dark);
            font-size: 12px;
        }

        .about-floating-card span {
            margin-top: 3px;
            color: var(--about-muted);
            font-size: 10px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Introduction
        |--------------------------------------------------------------------------
        */

        .about-introduction-grid {
            display: grid;
            grid-template-columns:
                minmax(0, 0.88fr)
                minmax(0, 1.12fr);
            gap: 45px;
            align-items: start;
        }

        .about-introduction-panel {
            position: sticky;
            top: 120px;
            padding: 32px;
            border-radius: 27px;
            color: var(--about-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.35),
                    transparent 35%
                ),
                linear-gradient(
                    145deg,
                    var(--about-blue-dark),
                    var(--about-blue)
                );
            box-shadow:
                0 22px 58px rgba(21, 94, 239, 0.24);
        }

        .about-introduction-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 999px;
            color: #dbeafe;
            background: rgba(255, 255, 255, 0.10);
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .about-introduction-label::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: #fdba74;
        }

        .about-introduction-panel h2 {
            margin: 18px 0 13px;
            color: var(--about-white);
            font-size: 35px;
            line-height: 1.13;
            letter-spacing: -0.9px;
        }

        .about-introduction-panel p {
            margin: 0;
            color: #dbeafe;
            font-size: 14px;
            line-height: 1.8;
        }

        .about-introduction-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
            margin-top: 26px;
        }

        .about-introduction-stat {
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.09);
        }

        .about-introduction-stat strong,
        .about-introduction-stat span {
            display: block;
        }

        .about-introduction-stat strong {
            color: var(--about-white);
            font-size: 25px;
            line-height: 1;
        }

        .about-introduction-stat span {
            margin-top: 6px;
            color: #dbeafe;
            font-size: 10px;
            font-weight: 700;
        }

        .about-introduction-content {
            display: grid;
            gap: 18px;
        }

        .about-story-card {
            padding: 25px;
            border: 1px solid var(--about-border);
            border-radius: 23px;
            background: var(--about-white);
            box-shadow:
                0 8px 28px rgba(16, 24, 40, 0.05);
        }

        .about-story-card-header {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 14px;
        }

        .about-story-icon {
            width: 49px;
            height: 49px;
            flex: 0 0 49px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: var(--about-blue);
            background: var(--about-blue-soft);
        }

        .about-story-card:nth-child(even)
        .about-story-icon {
            color: var(--about-orange-dark);
            background: var(--about-orange-soft);
        }

        .about-story-icon svg {
            width: 24px;
            height: 24px;
        }

        .about-story-card h3 {
            margin: 0 0 5px;
            color: var(--about-dark);
            font-size: 20px;
        }

        .about-story-card-header span {
            color: var(--about-muted);
            font-size: 11px;
            font-weight: 700;
        }

        .about-story-card p {
            margin: 0;
            color: var(--about-muted);
            font-size: 13px;
            line-height: 1.8;
        }

        /*
        |--------------------------------------------------------------------------
        | Core Values
        |--------------------------------------------------------------------------
        */

        .about-value-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .about-value-card {
            position: relative;
            overflow: hidden;
            padding: 29px;
            border: 1px solid var(--about-border);
            border-radius: 24px;
            background: var(--about-white);
            box-shadow:
                0 8px 27px rgba(16, 24, 40, 0.05);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .about-value-card:hover {
            border-color: #bdd1ff;
            box-shadow:
                0 19px 46px rgba(16, 24, 40, 0.10);
            transform: translateY(-6px);
        }

        .about-value-card::before {
            content: "";
            position: absolute;
            top: -45px;
            right: -45px;
            width: 125px;
            height: 125px;
            border-radius: 999px;
            background: rgba(21, 94, 239, 0.05);
        }

        .about-value-card:nth-child(2)::before {
            background: rgba(249, 115, 22, 0.06);
        }

        .about-value-icon {
            position: relative;
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border-radius: 18px;
            color: var(--about-white);
            background:
                linear-gradient(
                    135deg,
                    var(--about-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 23px rgba(21, 94, 239, 0.20);
        }

        .about-value-card:nth-child(2)
        .about-value-icon {
            background:
                linear-gradient(
                    135deg,
                    var(--about-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 23px rgba(249, 115, 22, 0.20);
        }

        .about-value-icon svg {
            width: 27px;
            height: 27px;
        }

        .about-value-card h3 {
            position: relative;
            margin: 0 0 10px;
            color: var(--about-dark);
            font-size: 21px;
        }

        .about-value-card p {
            position: relative;
            margin: 0;
            color: var(--about-muted);
            font-size: 13px;
            line-height: 1.75;
        }

        .about-value-number {
            position: absolute;
            right: 19px;
            bottom: 12px;
            color: rgba(21, 94, 239, 0.065);
            font-size: 70px;
            font-weight: 900;
            line-height: 1;
        }

        /*
        |--------------------------------------------------------------------------
        | Problems and Solutions
        |--------------------------------------------------------------------------
        */

        .about-solution-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
        }

        .about-solution-card {
            overflow: hidden;
            border: 1px solid var(--about-border);
            border-radius: 25px;
            background: var(--about-white);
            box-shadow:
                0 10px 32px rgba(16, 24, 40, 0.055);
        }

        .about-solution-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 23px;
            border-bottom: 1px solid var(--about-border);
        }

        .about-solution-card.problem
        .about-solution-header {
            background: #fff7ed;
        }

        .about-solution-card.solution
        .about-solution-header {
            background: var(--about-blue-soft);
        }

        .about-solution-header-icon {
            width: 45px;
            height: 45px;
            flex: 0 0 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
        }

        .about-solution-card.problem
        .about-solution-header-icon {
            color: var(--about-orange-dark);
            background: var(--about-orange-soft);
        }

        .about-solution-card.solution
        .about-solution-header-icon {
            color: var(--about-blue);
            background: var(--about-white);
        }

        .about-solution-header-icon svg {
            width: 22px;
            height: 22px;
        }

        .about-solution-header h3 {
            margin: 0;
            color: var(--about-dark);
            font-size: 20px;
        }

        .about-solution-list {
            display: grid;
            gap: 0;
            padding: 7px 23px;
        }

        .about-solution-item {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 16px 0;
            border-bottom: 1px solid var(--about-border);
        }

        .about-solution-item:last-child {
            border-bottom: 0;
        }

        .about-solution-check {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
            border-radius: 999px;
        }

        .about-solution-card.problem
        .about-solution-check {
            color: var(--about-orange-dark);
            background: var(--about-orange-soft);
        }

        .about-solution-card.solution
        .about-solution-check {
            color: var(--about-blue);
            background: var(--about-blue-soft);
        }

        .about-solution-check svg {
            width: 14px;
            height: 14px;
        }

        .about-solution-copy strong,
        .about-solution-copy span {
            display: block;
        }

        .about-solution-copy strong {
            color: var(--about-dark);
            font-size: 13px;
        }

        .about-solution-copy span {
            margin-top: 4px;
            color: var(--about-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | Workflow
        |--------------------------------------------------------------------------
        */

        .about-workflow-wrapper {
            position: relative;
        }

        .about-workflow-line {
            position: absolute;
            top: 44px;
            right: 12.5%;
            left: 12.5%;
            height: 2px;
            background:
                linear-gradient(
                    90deg,
                    var(--about-blue),
                    var(--about-orange),
                    var(--about-blue)
                );
        }

        .about-workflow-grid {
            position: relative;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }

        .about-workflow-card {
            text-align: center;
        }

        .about-workflow-number {
            width: 88px;
            height: 88px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            border: 10px solid var(--about-white);
            border-radius: 999px;
            color: var(--about-white);
            background:
                linear-gradient(
                    135deg,
                    var(--about-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 28px rgba(21, 94, 239, 0.22);
            font-size: 20px;
            font-weight: 900;
        }

        .about-workflow-card:nth-child(even)
        .about-workflow-number {
            background:
                linear-gradient(
                    135deg,
                    var(--about-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 28px rgba(249, 115, 22, 0.22);
        }

        .about-workflow-card h3 {
            margin: 0 0 9px;
            color: var(--about-dark);
            font-size: 17px;
        }

        .about-workflow-card p {
            margin: 0;
            color: var(--about-muted);
            font-size: 12px;
            line-height: 1.7;
        }

        /*
        |--------------------------------------------------------------------------
        | Commitment
        |--------------------------------------------------------------------------
        */

        .about-commitment-grid {
            display: grid;
            grid-template-columns:
                minmax(0, 1.1fr)
                minmax(310px, 0.9fr);
            gap: 32px;
            align-items: stretch;
        }

        .about-commitment-content {
            padding: 34px;
            border: 1px solid var(--about-border);
            border-radius: 27px;
            background: var(--about-white);
            box-shadow:
                0 12px 38px rgba(16, 24, 40, 0.06);
        }

        .about-commitment-content h2 {
            margin: 15px 0 13px;
            color: var(--about-dark);
            font-size: clamp(30px, 4vw, 43px);
            line-height: 1.13;
            letter-spacing: -1px;
        }

        .about-commitment-content > p {
            margin: 0;
            color: var(--about-muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .about-commitment-list {
            display: grid;
            gap: 13px;
            margin-top: 25px;
        }

        .about-commitment-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
            border: 1px solid var(--about-border);
            border-radius: 16px;
            background: #fcfcfd;
        }

        .about-commitment-check {
            width: 29px;
            height: 29px;
            flex: 0 0 29px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--about-blue);
            background: var(--about-blue-soft);
        }

        .about-commitment-item:nth-child(even)
        .about-commitment-check {
            color: var(--about-orange-dark);
            background: var(--about-orange-soft);
        }

        .about-commitment-check svg {
            width: 15px;
            height: 15px;
        }

        .about-commitment-item strong,
        .about-commitment-item span {
            display: block;
        }

        .about-commitment-item strong {
            color: var(--about-dark);
            font-size: 13px;
        }

        .about-commitment-item span {
            margin-top: 4px;
            color: var(--about-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .about-contact-card {
            position: relative;
            overflow: hidden;
            padding: 34px;
            border-radius: 27px;
            color: var(--about-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.38),
                    transparent 35%
                ),
                linear-gradient(
                    145deg,
                    var(--about-blue-dark),
                    var(--about-blue)
                );
            box-shadow:
                0 22px 58px rgba(21, 94, 239, 0.23);
        }

        .about-contact-card::before {
            content: "";
            position: absolute;
            top: -65px;
            right: -60px;
            width: 175px;
            height: 175px;
            border: 28px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .about-contact-content {
            position: relative;
            z-index: 2;
        }

        .about-contact-icon {
            width: 57px;
            height: 57px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 18px;
            color: var(--about-white);
            background: rgba(255, 255, 255, 0.12);
        }

        .about-contact-icon svg {
            width: 28px;
            height: 28px;
        }

        .about-contact-card h3 {
            margin: 0 0 10px;
            color: var(--about-white);
            font-size: 27px;
            line-height: 1.18;
        }

        .about-contact-card p {
            margin: 0;
            color: #dbeafe;
            font-size: 13px;
            line-height: 1.75;
        }

        .about-contact-list {
            display: grid;
            gap: 10px;
            margin-top: 24px;
        }

        .about-contact-item {
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.09);
        }

        .about-contact-item span,
        .about-contact-item strong {
            display: block;
        }

        .about-contact-item span {
            color: #bfdbfe;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .about-contact-item strong {
            margin-top: 4px;
            overflow-wrap: anywhere;
            color: var(--about-white);
            font-size: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | Final CTA
        |--------------------------------------------------------------------------
        */

        .about-final-cta {
            position: relative;
            overflow: hidden;
            padding: 48px;
            border-radius: 30px;
            color: var(--about-white);
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
                    var(--about-blue-dark),
                    var(--about-blue)
                );
            box-shadow:
                0 27px 70px rgba(21, 94, 239, 0.25);
        }

        .about-final-cta::before {
            content: "";
            position: absolute;
            top: -80px;
            right: -70px;
            width: 210px;
            height: 210px;
            border: 35px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .about-final-cta-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 32px;
            align-items: center;
        }

        .about-final-label {
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

        .about-final-label::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #fdba74;
        }

        .about-final-cta h2 {
            margin: 0 0 10px;
            color: var(--about-white);
            font-size: clamp(30px, 4vw, 45px);
            line-height: 1.12;
            letter-spacing: -1.1px;
        }

        .about-final-cta p {
            max-width: 700px;
            margin: 0;
            color: #dbeafe;
            font-size: 14px;
            line-height: 1.75;
        }

        .about-final-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .about-final-button {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 20px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 14px;
            color: var(--about-blue-dark);
            background: var(--about-white);
            font-size: 13px;
            font-weight: 900;
            white-space: nowrap;
            box-shadow:
                0 12px 26px rgba(16, 24, 40, 0.18);
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .about-final-button:hover {
            color: var(--about-blue-dark);
            box-shadow:
                0 16px 34px rgba(16, 24, 40, 0.24);
            transform: translateY(-2px);
        }

        .about-final-button svg {
            width: 18px;
            height: 18px;
        }

        .about-final-link {
            color: #dbeafe;
            font-size: 11px;
            font-weight: 800;
            text-align: center;
        }

        .about-final-link:hover {
            color: var(--about-white);
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .about-reveal {
            opacity: 0;
            transform: translateY(21px);
            transition:
                opacity 0.56s ease,
                transform 0.56s ease;
        }

        .about-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .about-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(350px, 0.86fr);
                gap: 38px;
            }

            .about-workflow-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
                gap: 30px;
            }

            .about-workflow-line {
                display: none;
            }
        }

        @media (max-width: 900px) {
            .about-section {
                padding: 68px 0;
            }

            .about-hero {
                padding: 62px 0 76px;
            }

            .about-hero-grid,
            .about-introduction-grid,
            .about-commitment-grid {
                grid-template-columns: 1fr;
            }

            .about-hero-content {
                max-width: 760px;
            }

            .about-hero-visual {
                max-width: 680px;
                margin: 0 auto;
            }

            .about-floating-card {
                right: -10px;
            }

            .about-introduction-panel {
                position: static;
            }

            .about-value-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .about-value-card:last-child {
                grid-column: 1 / -1;
            }

            .about-final-cta-grid {
                grid-template-columns: 1fr;
            }

            .about-final-actions {
                align-items: flex-start;
            }
        }

        @media (max-width: 700px) {
            .about-value-grid,
            .about-solution-grid,
            .about-workflow-grid {
                grid-template-columns: 1fr;
            }

            .about-value-card:last-child {
                grid-column: auto;
            }

            .about-workflow-card {
                display: grid;
                grid-template-columns: 68px minmax(0, 1fr);
                column-gap: 17px;
                text-align: left;
            }

            .about-workflow-number {
                width: 68px;
                height: 68px;
                grid-row: 1 / span 2;
                margin: 0;
                border-width: 7px;
                font-size: 16px;
            }

            .about-workflow-card h3 {
                align-self: end;
            }
        }

        @media (max-width: 640px) {
            .about-section {
                padding: 55px 0;
            }

            .about-section-heading {
                margin-bottom: 28px;
            }

            .about-section-heading h2 {
                font-size: 31px;
            }

            .about-section-heading p {
                font-size: 13px;
            }

            .about-breadcrumb-section {
                padding: 13px 0;
            }

            .about-hero {
                padding: 47px 0 62px;
            }

            .about-hero::before,
            .about-hero::after {
                display: none;
            }

            .about-hero h1 {
                margin-top: 19px;
                font-size: 39px;
                letter-spacing: -1.5px;
            }

            .about-hero-description {
                font-size: 14px;
            }

            .about-hero-actions {
                flex-direction: column;
            }

            .about-hero-actions .about-button {
                width: 100%;
            }

            .about-hero-points {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .about-image-card {
                padding: 12px;
                border-radius: 23px;
            }

            .about-image-wrapper {
                border-radius: 17px;
            }

            .about-image {
                height: 320px;
            }

            .about-floating-card {
                position: static;
                max-width: none;
                margin-top: 12px;
            }

            .about-introduction-panel,
            .about-commitment-content,
            .about-contact-card {
                padding: 23px;
                border-radius: 22px;
            }

            .about-introduction-panel h2 {
                font-size: 30px;
            }

            .about-story-card,
            .about-value-card {
                padding: 22px;
            }

            .about-solution-header {
                padding: 18px;
            }

            .about-solution-list {
                padding-right: 18px;
                padding-left: 18px;
            }

            .about-contact-card h3 {
                font-size: 24px;
            }

            .about-final-cta {
                padding: 30px 22px;
                border-radius: 23px;
            }

            .about-final-cta h2 {
                font-size: 31px;
            }

            .about-final-actions,
            .about-final-button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .about-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .about-value-card,
            .about-button,
            .about-final-button {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="about-page">
        {{-- Breadcrumb --}}
        <section class="about-breadcrumb-section">
            <div class="container">
                <nav
                    class="about-breadcrumb"
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
                        Tentang Kami
                    </strong>
                </nav>
            </div>
        </section>

        {{-- Hero --}}
        <section class="about-hero">
            <div class="container about-hero-grid">
                <div class="about-hero-content">
                    <span class="about-hero-badge">
                        <span class="about-hero-badge-icon">
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

                        Tentang {{ $namaWebsite }}
                    </span>

                    <h1>
                        Layanan cetak mahasiswa yang
                        <span>lebih terstruktur</span>
                    </h1>

                    <p class="about-hero-description">
                        {{ $namaWebsite }} adalah sistem pemesanan
                        layanan cetak berbasis web yang membantu
                        pelanggan memilih layanan, mengunggah file,
                        melihat estimasi biaya, dan memantau status
                        pesanan secara mandiri.
                    </p>

                    <div class="about-hero-actions">
                        <a
                            href="{{ $tujuanPesanan }}"
                            class="about-button about-button-primary"
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
                            class="about-button about-button-secondary"
                        >
                            Lihat Layanan
                        </a>
                    </div>

                    <div class="about-hero-points">
                        <span class="about-hero-point">
                            <span class="about-hero-point-icon">
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

                            Pemesanan online
                        </span>

                        <span class="about-hero-point">
                            <span class="about-hero-point-icon">
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

                            File terkelola
                        </span>

                        <span class="about-hero-point">
                            <span class="about-hero-point-icon">
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

                            Status transparan
                        </span>
                    </div>
                </div>

                <div class="about-hero-visual">
                    <div class="about-image-card">
                        <div class="about-image-wrapper">
                            <img
                                src="{{ $gambarTentang }}"
                                alt="{{ $namaWebsite }}"
                                class="about-image"
                                fetchpriority="high"
                            >

                            <div class="about-image-overlay">
                                <strong>
                                    {{ $namaWebsite }}
                                </strong>

                                <span>
                                    Solusi cetak praktis untuk kebutuhan
                                    tugas dan dokumen akademik.
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="about-floating-card">
                        <span class="about-floating-icon">
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
                            <strong>
                                Proses mudah dipantau
                            </strong>

                            <span>
                                Pelanggan dapat melihat perkembangan
                                pesanan melalui dashboard.
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Introduction --}}
        <section class="about-section">
            <div class="container">
                <div class="about-introduction-grid">
                    <div class="about-introduction-panel about-reveal">
                        <span class="about-introduction-label">
                            Latar belakang
                        </span>

                        <h2>
                            Berawal dari kebutuhan cetak yang sering mendadak
                        </h2>

                        <p>
                            Mahasiswa sering membutuhkan layanan cetak
                            dalam waktu singkat. Detail pesanan yang
                            dikirim melalui chat dapat tercampur,
                            file mudah terlewat, dan pelanggan harus
                            bertanya berulang kali mengenai status
                            pengerjaan.
                        </p>

                        <div class="about-introduction-stats">
                            <div class="about-introduction-stat">
                                <strong>1</strong>
                                <span>Sistem pemesanan terpusat</span>
                            </div>

                            <div class="about-introduction-stat">
                                <strong>5</strong>
                                <span>Tahapan status pesanan</span>
                            </div>

                            <div class="about-introduction-stat">
                                <strong>50 MB</strong>
                                <span>Total batas upload file</span>
                            </div>

                            <div class="about-introduction-stat">
                                <strong>Online</strong>
                                <span>Dapat diakses melalui web</span>
                            </div>
                        </div>
                    </div>

                    <div class="about-introduction-content">
                        <article class="about-story-card about-reveal">
                            <div class="about-story-card-header">
                                <span class="about-story-icon">
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

                                <span>
                                    <h3>Fokus Layanan</h3>
                                    <span>
                                        Kebutuhan akademik mahasiswa
                                    </span>
                                </span>
                            </div>

                            <p>
                                Kami berfokus pada print dokumen,
                                fotokopi, jilid, laminating, dan
                                layanan pendukung untuk tugas,
                                laporan, proposal, skripsi, serta
                                dokumen akademik lainnya.
                            </p>
                        </article>

                        <article class="about-story-card about-reveal">
                            <div class="about-story-card-header">
                                <span class="about-story-icon">
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

                                <span>
                                    <h3>File Lebih Terorganisir</h3>
                                    <span>
                                        Setiap file terhubung dengan pesanan
                                    </span>
                                </span>
                            </div>

                            <p>
                                File pelanggan disimpan pada pesanan
                                yang sesuai sehingga admin lebih mudah
                                melakukan pemeriksaan dan tidak perlu
                                mencari file di antara percakapan chat.
                            </p>
                        </article>

                        <article class="about-story-card about-reveal">
                            <div class="about-story-card-header">
                                <span class="about-story-icon">
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
                                    <h3>Status Lebih Transparan</h3>
                                    <span>
                                        Pelanggan mengetahui perkembangan
                                    </span>
                                </span>
                            </div>

                            <p>
                                Pelanggan dapat melihat status
                                pesanan mulai dari menunggu verifikasi,
                                diproses, siap diambil, selesai, atau
                                dibatalkan melalui dashboard.
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        {{-- Values --}}
        <section class="about-section about-section-white">
            <div class="container">
                <div class="about-section-heading center about-reveal">
                    <span class="about-eyebrow">
                        Nilai utama
                    </span>

                    <h2>
                        Prinsip yang kami gunakan dalam melayani pelanggan
                    </h2>

                    <p>
                        Sistem dan alur pemesanan dirancang dengan
                        mempertimbangkan kemudahan, kerapian data,
                        dan transparansi proses.
                    </p>
                </div>

                <div class="about-value-grid">
                    <article class="about-value-card about-reveal">
                        <span class="about-value-icon">
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

                        <h3>Cepat dan Praktis</h3>

                        <p>
                            Pelanggan dapat memilih layanan, mengisi
                            detail, dan mengirim file tanpa proses
                            manual yang panjang.
                        </p>

                        <span class="about-value-number">
                            01
                        </span>
                    </article>

                    <article class="about-value-card about-reveal">
                        <span class="about-value-icon">
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

                        <h3>Aman dan Teratur</h3>

                        <p>
                            Data pelanggan, detail pesanan, dan file
                            disimpan secara terpusat sesuai akses
                            pengguna.
                        </p>

                        <span class="about-value-number">
                            02
                        </span>
                    </article>

                    <article class="about-value-card about-reveal">
                        <span class="about-value-icon">
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

                        <h3>Jelas dan Transparan</h3>

                        <p>
                            Informasi harga dasar, estimasi biaya,
                            pembayaran, dan status pesanan dapat
                            dilihat oleh pelanggan.
                        </p>

                        <span class="about-value-number">
                            03
                        </span>
                    </article>
                </div>
            </div>
        </section>

        {{-- Problems and Solutions --}}
        <section class="about-section about-section-soft">
            <div class="container">
                <div class="about-section-heading center about-reveal">
                    <span class="about-eyebrow">
                        Mengapa sistem ini dibuat
                    </span>

                    <h2>
                        Dari proses manual menjadi proses yang lebih tertata
                    </h2>

                    <p>
                        Website ini mengurangi kendala yang sering
                        muncul ketika seluruh proses pemesanan hanya
                        dilakukan melalui percakapan.
                    </p>
                </div>

                <div class="about-solution-grid">
                    <article class="about-solution-card problem about-reveal">
                        <div class="about-solution-header">
                            <span class="about-solution-header-icon">
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
                                    <path d="M12 7v6"/>
                                    <path d="M12 17h.01"/>
                                </svg>
                            </span>

                            <h3>
                                Kendala Proses Manual
                            </h3>
                        </div>

                        <div class="about-solution-list">
                            <div class="about-solution-item">
                                <span class="about-solution-check">
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
                                        <path d="M6 18 18 6"/>
                                        <path d="m6 6 12 12"/>
                                    </svg>
                                </span>

                                <span class="about-solution-copy">
                                    <strong>Detail pesanan tercecer</strong>
                                    <span>
                                        Informasi halaman, copy, dan
                                        jenis cetak dapat terpisah dalam
                                        banyak pesan.
                                    </span>
                                </span>
                            </div>

                            <div class="about-solution-item">
                                <span class="about-solution-check">
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
                                        <path d="M6 18 18 6"/>
                                        <path d="m6 6 12 12"/>
                                    </svg>
                                </span>

                                <span class="about-solution-copy">
                                    <strong>File mudah tertukar</strong>
                                    <span>
                                        File dari beberapa pelanggan
                                        dapat sulit dibedakan apabila
                                        tidak dikelola dengan baik.
                                    </span>
                                </span>
                            </div>

                            <div class="about-solution-item">
                                <span class="about-solution-check">
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
                                        <path d="M6 18 18 6"/>
                                        <path d="m6 6 12 12"/>
                                    </svg>
                                </span>

                                <span class="about-solution-copy">
                                    <strong>Status harus ditanyakan</strong>
                                    <span>
                                        Pelanggan perlu menghubungi
                                        admin untuk mengetahui proses
                                        pengerjaan.
                                    </span>
                                </span>
                            </div>
                        </div>
                    </article>

                    <article class="about-solution-card solution about-reveal">
                        <div class="about-solution-header">
                            <span class="about-solution-header-icon">
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
                            </span>

                            <h3>
                                Solusi Melalui Website
                            </h3>
                        </div>

                        <div class="about-solution-list">
                            <div class="about-solution-item">
                                <span class="about-solution-check">
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

                                <span class="about-solution-copy">
                                    <strong>Form pesanan terstruktur</strong>
                                    <span>
                                        Setiap detail disimpan pada
                                        pesanan yang sama dan mudah
                                        diperiksa kembali.
                                    </span>
                                </span>
                            </div>

                            <div class="about-solution-item">
                                <span class="about-solution-check">
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

                                <span class="about-solution-copy">
                                    <strong>File terhubung dengan pesanan</strong>
                                    <span>
                                        Dokumen pelanggan tersimpan
                                        bersama data layanan dan
                                        informasi pemesanan.
                                    </span>
                                </span>
                            </div>

                            <div class="about-solution-item">
                                <span class="about-solution-check">
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

                                <span class="about-solution-copy">
                                    <strong>Status tersedia di dashboard</strong>
                                    <span>
                                        Pelanggan dapat melihat
                                        perkembangan tanpa harus selalu
                                        bertanya kepada admin.
                                    </span>
                                </span>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        {{-- Workflow --}}
        <section class="about-section about-section-white">
            <div class="container">
                <div class="about-section-heading center about-reveal">
                    <span class="about-eyebrow">
                        Cara kerja sistem
                    </span>

                    <h2>
                        Proses pemesanan dari awal sampai selesai
                    </h2>

                    <p>
                        Pelanggan dapat menyelesaikan seluruh proses
                        utama melalui website dengan alur yang singkat.
                    </p>
                </div>

                <div class="about-workflow-wrapper">
                    <div
                        class="about-workflow-line"
                        aria-hidden="true"
                    ></div>

                    <div class="about-workflow-grid">
                        <article class="about-workflow-card about-reveal">
                            <span class="about-workflow-number">
                                01
                            </span>

                            <h3>Pilih Layanan</h3>

                            <p>
                                Pelanggan melihat daftar layanan,
                                informasi harga dasar, dan ketentuan.
                            </p>
                        </article>

                        <article class="about-workflow-card about-reveal">
                            <span class="about-workflow-number">
                                02
                            </span>

                            <h3>Buat Pesanan</h3>

                            <p>
                                Detail cetak, jumlah halaman, copy,
                                dan kebutuhan tambahan dimasukkan.
                            </p>
                        </article>

                        <article class="about-workflow-card about-reveal">
                            <span class="about-workflow-number">
                                03
                            </span>

                            <h3>Upload File</h3>

                            <p>
                                Dokumen diunggah dan disimpan sesuai
                                pesanan pelanggan.
                            </p>
                        </article>

                        <article class="about-workflow-card about-reveal">
                            <span class="about-workflow-number">
                                04
                            </span>

                            <h3>Pantau Status</h3>

                            <p>
                                Pelanggan melihat perkembangan sampai
                                pesanan siap diambil atau selesai.
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        {{-- Commitment and Contact --}}
        <section class="about-section about-section-soft">
            <div class="container">
                <div class="about-commitment-grid">
                    <div class="about-commitment-content about-reveal">
                        <span class="about-eyebrow">
                            Komitmen layanan
                        </span>

                        <h2>
                            Memberikan pengalaman pemesanan yang jelas
                        </h2>

                        <p>
                            Kami berusaha membuat proses pemesanan
                            cetak lebih mudah dipahami oleh pelanggan
                            dan lebih mudah dikelola oleh admin.
                        </p>

                        <div class="about-commitment-list">
                            <div class="about-commitment-item">
                                <span class="about-commitment-check">
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

                                <span>
                                    <strong>
                                        Informasi layanan yang jelas
                                    </strong>

                                    <span>
                                        Harga dasar dan detail layanan
                                        ditampilkan sebelum pelanggan
                                        membuat pesanan.
                                    </span>
                                </span>
                            </div>

                            <div class="about-commitment-item">
                                <span class="about-commitment-check">
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

                                <span>
                                    <strong>
                                        Data pesanan yang rapi
                                    </strong>

                                    <span>
                                        Detail layanan dan file
                                        pelanggan berada dalam satu
                                        pesanan yang sama.
                                    </span>
                                </span>
                            </div>

                            <div class="about-commitment-item">
                                <span class="about-commitment-check">
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

                                <span>
                                    <strong>
                                        Proses yang dapat dipantau
                                    </strong>

                                    <span>
                                        Status pesanan diperbarui agar
                                        pelanggan mengetahui proses
                                        pengerjaan.
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <aside class="about-contact-card about-reveal">
                        <div class="about-contact-content">
                            <span class="about-contact-icon">
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

                            <h3>
                                Butuh informasi lebih lanjut?
                            </h3>

                            <p>
                                Hubungi kami untuk menanyakan layanan,
                                kebutuhan cetak, waktu pengerjaan,
                                atau informasi pengambilan pesanan.
                            </p>

                            <div class="about-contact-list">
                                <div class="about-contact-item">
                                    <span>WhatsApp</span>

                                    <strong>
                                        {{ $website?->nomor_whatsapp ?? '-' }}
                                    </strong>
                                </div>

                                <div class="about-contact-item">
                                    <span>Email</span>

                                    <strong>
                                        {{ $website?->email ?? '-' }}
                                    </strong>
                                </div>

                                <div class="about-contact-item">
                                    <span>Jam Operasional</span>

                                    <strong>
                                        {{ $website?->jam_operasional ?? '-' }}
                                    </strong>
                                </div>

                                <div class="about-contact-item">
                                    <span>Alamat</span>

                                    <strong>
                                        {{ $website?->alamat ?? '-' }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        {{-- Final CTA --}}
        <section class="about-section">
            <div class="container">
                <div class="about-final-cta about-reveal">
                    <div class="about-final-cta-grid">
                        <div>
                            <span class="about-final-label">
                                Pemesanan tersedia
                            </span>

                            <h2>
                                Siap mencetak dokumenmu?
                            </h2>

                            <p>
                                Pilih layanan yang sesuai, unggah file,
                                periksa estimasi biaya, dan pantau
                                status pesanan melalui
                                {{ $namaWebsite }}.
                            </p>
                        </div>

                        <div class="about-final-actions">
                            <a
                                href="{{ $tujuanPesanan }}"
                                class="about-final-button"
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
                                class="about-final-link"
                            >
                                Hubungi admin untuk bantuan
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
                '.about-reveal'
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