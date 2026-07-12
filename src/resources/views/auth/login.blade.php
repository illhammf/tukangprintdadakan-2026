@extends('layouts.public')

@section(
    'title',
    'Login - '
    . ($website?->nama_website ?? 'Tukang Print Dadakan')
)

@php
    $namaWebsite = $website?->nama_website
        ?? 'Tukang Print Dadakan';

    $logoUrl = $website?->logo
        ? \Illuminate\Support\Facades\Storage::url($website->logo)
        : asset('images/placeholder.png');
@endphp

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Login Page Variables
        |--------------------------------------------------------------------------
        */

        .login-page {
            --login-blue: var(--public-blue, #155eef);
            --login-blue-dark: var(--public-blue-dark, #1046b8);
            --login-blue-soft: var(--public-blue-soft, #edf4ff);

            --login-orange: var(--public-orange, #f97316);
            --login-orange-dark: var(--public-orange-dark, #c2410c);
            --login-orange-soft: var(--public-orange-soft, #fff1e7);

            --login-green: #16a34a;
            --login-green-soft: #ecfdf3;

            --login-red: #dc2626;
            --login-red-soft: #fff1f2;

            --login-dark: var(--public-dark, #101828);
            --login-text: var(--public-text, #344054);
            --login-muted: var(--public-muted, #667085);

            --login-white: #ffffff;
            --login-soft: #f7f9fc;
            --login-border: #e4e7ec;
            --login-border-dark: #d0d5dd;

            position: relative;
            min-height: calc(100vh - 120px);
            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Page Background
        |--------------------------------------------------------------------------
        */

        .login-section {
            position: relative;
            min-height: 760px;
            display: flex;
            align-items: center;
            padding: 76px 0 90px;
            background:
                radial-gradient(
                    circle at 8% 17%,
                    rgba(21, 94, 239, 0.16),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 92% 8%,
                    rgba(249, 115, 22, 0.15),
                    transparent 25%
                ),
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #f7f9fd 100%
                );
        }

        .login-section::before {
            content: "";
            position: absolute;
            top: 90px;
            left: -115px;
            width: 260px;
            height: 260px;
            border: 42px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .login-section::after {
            content: "";
            position: absolute;
            right: -115px;
            bottom: 60px;
            width: 280px;
            height: 280px;
            border: 45px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .login-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1.05fr)
                minmax(390px, 0.75fr);
            gap: 70px;
            align-items: center;
        }

        /*
        |--------------------------------------------------------------------------
        | Information Side
        |--------------------------------------------------------------------------
        */

        .login-information {
            max-width: 680px;
        }

        .login-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--login-orange-dark);
            background: var(--login-orange-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .login-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--login-white);
            background: var(--login-orange);
        }

        .login-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .login-information h1 {
            margin: 24px 0 18px;
            color: var(--login-dark);
            font-size: clamp(43px, 5.8vw, 69px);
            line-height: 1.04;
            letter-spacing: -2.5px;
        }

        .login-information h1 span {
            position: relative;
            display: inline-block;
            color: var(--login-blue);
        }

        .login-information h1 span::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 8px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.23);
            transform: rotate(-1.3deg);
        }

        .login-description {
            max-width: 640px;
            margin: 0;
            color: var(--login-muted);
            font-size: 16px;
            line-height: 1.85;
        }

        /*
        |--------------------------------------------------------------------------
        | Feature List
        |--------------------------------------------------------------------------
        */

        .login-feature-list {
            display: grid;
            gap: 13px;
            margin-top: 30px;
        }

        .login-feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            max-width: 570px;
            padding: 14px 16px;
            border: 1px solid rgba(228, 231, 236, 0.88);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.74);
            backdrop-filter: blur(10px);
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .login-feature-item:hover {
            border-color: #bdd1ff;
            background: var(--login-white);
            transform: translateX(4px);
        }

        .login-feature-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--login-blue);
            background: var(--login-blue-soft);
        }

        .login-feature-item:nth-child(2)
        .login-feature-icon {
            color: var(--login-orange-dark);
            background: var(--login-orange-soft);
        }

        .login-feature-icon svg {
            width: 20px;
            height: 20px;
        }

        .login-feature-copy strong,
        .login-feature-copy span {
            display: block;
        }

        .login-feature-copy strong {
            color: var(--login-dark);
            font-size: 13px;
        }

        .login-feature-copy span {
            margin-top: 3px;
            color: var(--login-muted);
            font-size: 11px;
            line-height: 1.55;
        }

        /*
        |--------------------------------------------------------------------------
        | Registration Note
        |--------------------------------------------------------------------------
        */

        .login-register-note {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            max-width: 570px;
            margin-top: 25px;
            padding: 17px 18px;
            border: 1px solid #cfe0ff;
            border-radius: 17px;
            background:
                linear-gradient(
                    135deg,
                    var(--login-blue-soft),
                    rgba(255, 255, 255, 0.9)
                );
        }

        .login-register-copy {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .login-register-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--login-white);
            background:
                linear-gradient(
                    135deg,
                    var(--login-blue),
                    #2b70ff
                );
        }

        .login-register-icon svg {
            width: 20px;
            height: 20px;
        }

        .login-register-copy strong,
        .login-register-copy span {
            display: block;
        }

        .login-register-copy strong {
            color: var(--login-dark);
            font-size: 12px;
        }

        .login-register-copy span {
            margin-top: 2px;
            color: var(--login-muted);
            font-size: 10px;
        }

        .login-register-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--login-blue);
            font-size: 11px;
            font-weight: 900;
            white-space: nowrap;
        }

        .login-register-link:hover {
            color: var(--login-blue-dark);
        }

        .login-register-link svg {
            width: 15px;
            height: 15px;
            transition: transform 0.2s ease;
        }

        .login-register-link:hover svg {
            transform: translateX(3px);
        }

        /*
        |--------------------------------------------------------------------------
        | Login Card
        |--------------------------------------------------------------------------
        */

        .login-card-wrapper {
            position: relative;
        }

        .login-card-wrapper::before {
            content: "";
            position: absolute;
            top: -17px;
            right: 33px;
            width: 88px;
            height: 29px;
            border-radius: 10px 10px 4px 4px;
            background:
                linear-gradient(
                    90deg,
                    var(--login-blue),
                    var(--login-orange)
                );
            transform: rotate(3deg);
        }

        .login-card {
            position: relative;
            padding: 31px;
            border: 1px solid rgba(228, 231, 236, 0.94);
            border-radius: 29px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(17px);
            box-shadow:
                0 29px 80px rgba(16, 24, 40, 0.15);
        }

        /*
        |--------------------------------------------------------------------------
        | Login Card Header
        |--------------------------------------------------------------------------
        */

        .login-card-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 25px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--login-border);
        }

        .login-card-logo {
            width: 54px;
            height: 54px;
            flex: 0 0 54px;
            padding: 3px;
            object-fit: contain;
            border: 1px solid var(--login-border);
            border-radius: 17px;
            background: var(--login-white);
            box-shadow:
                0 8px 20px rgba(16, 24, 40, 0.09);
        }

        .login-card-title {
            min-width: 0;
        }

        .login-card-title h2 {
            margin: 0 0 5px;
            color: var(--login-dark);
            font-size: 27px;
            line-height: 1.2;
        }

        .login-card-title p {
            margin: 0;
            color: var(--login-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | Validation Summary
        |--------------------------------------------------------------------------
        */

        .login-error-summary {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: 21px;
            padding: 14px;
            border: 1px solid #fecaca;
            border-radius: 15px;
            color: #991b1b;
            background: var(--login-red-soft);
            font-size: 11px;
            font-weight: 800;
            line-height: 1.55;
        }

        .login-error-summary-icon {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--login-white);
            background: var(--login-red);
            font-size: 13px;
            font-weight: 900;
        }

        /*
        |--------------------------------------------------------------------------
        | Login Form
        |--------------------------------------------------------------------------
        */

        .login-form {
            display: grid;
            gap: 18px;
        }

        .login-form-group {
            display: grid;
            gap: 7px;
        }

        .login-form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .login-form-label {
            color: var(--login-text);
            font-size: 12px;
            font-weight: 900;
        }

        .login-required {
            color: var(--login-orange);
        }

        .login-field-wrapper {
            position: relative;
        }

        .login-field-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            z-index: 2;
            width: 19px;
            height: 19px;
            color: #98a2b3;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .login-input {
            width: 100%;
            min-height: 52px;
            padding: 13px 15px 13px 46px;
            border: 1px solid var(--login-border-dark);
            border-radius: 14px;
            color: var(--login-dark);
            background: #fcfcfd;
            outline: none;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .login-password-input {
            padding-right: 53px;
        }

        .login-input::placeholder {
            color: #98a2b3;
        }

        .login-input:hover {
            border-color: #98a2b3;
        }

        .login-input:focus {
            border-color: var(--login-blue);
            background: var(--login-white);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.12);
        }

        .login-input.is-invalid {
            border-color: var(--login-red);
            background: #fffafa;
        }

        .login-input.is-invalid:focus {
            box-shadow:
                0 0 0 4px rgba(220, 38, 38, 0.10);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Toggle
        |--------------------------------------------------------------------------
        */

        .login-password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            z-index: 3;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 0;
            border-radius: 10px;
            color: var(--login-muted);
            background: transparent;
            cursor: pointer;
            transform: translateY(-50%);
            transition:
                color 0.2s ease,
                background 0.2s ease;
        }

        .login-password-toggle:hover {
            color: var(--login-blue);
            background: var(--login-blue-soft);
        }

        .login-password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .login-password-toggle svg[hidden] {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Error and Help Text
        |--------------------------------------------------------------------------
        */

        .login-field-error {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--login-red);
            font-size: 10px;
            font-weight: 800;
        }

        .login-field-error::before {
            content: "!";
            width: 17px;
            height: 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--login-white);
            background: var(--login-red);
            font-size: 10px;
        }

        .login-field-help {
            color: var(--login-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Form Options
        |--------------------------------------------------------------------------
        */

        .login-form-options {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
            margin-top: -2px;
        }

        .login-forgot-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--login-blue);
            font-size: 11px;
            font-weight: 900;
        }

        .login-forgot-link:hover {
            color: var(--login-blue-dark);
        }

        .login-forgot-link svg {
            width: 16px;
            height: 16px;
        }

        /*
        |--------------------------------------------------------------------------
        | Submit Button
        |--------------------------------------------------------------------------
        */

        .login-submit-button {
            width: 100%;
            min-height: 53px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin-top: 1px;
            padding: 12px 19px;
            border: 0;
            border-radius: 15px;
            color: var(--login-white);
            background:
                linear-gradient(
                    135deg,
                    var(--login-blue),
                    #2b70ff
                );
            box-shadow:
                0 12px 27px rgba(21, 94, 239, 0.24);
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .login-submit-button:hover {
            background:
                linear-gradient(
                    135deg,
                    var(--login-blue-dark),
                    var(--login-blue)
                );
            box-shadow:
                0 16px 34px rgba(21, 94, 239, 0.30);
            transform: translateY(-2px);
        }

        .login-submit-button:disabled {
            cursor: not-allowed;
            opacity: 0.7;
            transform: none;
        }

        .login-submit-button svg {
            width: 18px;
            height: 18px;
        }

        .login-submit-spinner {
            width: 17px;
            height: 17px;
            display: none;
            border: 2px solid rgba(255, 255, 255, 0.42);
            border-top-color: var(--login-white);
            border-radius: 999px;
            animation: login-spin 0.75s linear infinite;
        }

        .login-submit-button.loading
        .login-submit-spinner {
            display: inline-block;
        }

        .login-submit-button.loading
        .login-submit-icon {
            display: none;
        }

        @keyframes login-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Form Security
        |--------------------------------------------------------------------------
        */

        .login-security-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 13px;
            border: 1px solid #bbf7d0;
            border-radius: 14px;
            color: #166534;
            background: var(--login-green-soft);
            font-size: 10px;
            line-height: 1.55;
        }

        .login-security-note svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile Register Link
        |--------------------------------------------------------------------------
        */

        .login-mobile-register {
            display: none;
            margin-top: 21px;
            padding-top: 20px;
            border-top: 1px solid var(--login-border);
            color: var(--login-muted);
            font-size: 11px;
            text-align: center;
        }

        .login-mobile-register a {
            color: var(--login-blue);
            font-weight: 900;
        }

        .login-mobile-register a:hover {
            color: var(--login-blue-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .login-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .login-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .login-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(360px, 0.72fr);
                gap: 42px;
            }

            .login-information h1 {
                font-size: clamp(42px, 5vw, 59px);
            }
        }

        @media (max-width: 860px) {
            .login-section {
                min-height: auto;
                padding: 62px 0 78px;
            }

            .login-grid {
                grid-template-columns: 1fr;
                gap: 45px;
            }

            .login-information {
                max-width: 740px;
            }

            .login-card-wrapper {
                width: 100%;
                max-width: 610px;
                margin: 0 auto;
            }
        }

        @media (max-width: 640px) {
            .login-section {
                padding: 44px 0 61px;
            }

            .login-section::before,
            .login-section::after {
                display: none;
            }

            .login-grid {
                gap: 34px;
            }

            .login-badge {
                font-size: 11px;
            }

            .login-information h1 {
                margin-top: 19px;
                font-size: 39px;
                letter-spacing: -1.5px;
            }

            .login-description {
                font-size: 14px;
            }

            .login-feature-list {
                gap: 10px;
                margin-top: 24px;
            }

            .login-feature-item {
                padding: 12px;
            }

            .login-register-note {
                display: none;
            }

            .login-card-wrapper::before {
                right: 25px;
                width: 72px;
                height: 24px;
            }

            .login-card {
                padding: 22px;
                border-radius: 23px;
            }

            .login-card-header {
                margin-bottom: 21px;
                padding-bottom: 19px;
            }

            .login-card-logo {
                width: 48px;
                height: 48px;
                flex-basis: 48px;
                border-radius: 15px;
            }

            .login-card-title h2 {
                font-size: 24px;
            }

            .login-form {
                gap: 16px;
            }

            .login-form-options {
                align-items: flex-start;
            }

            .login-mobile-register {
                display: block;
            }
        }

        @media (max-width: 390px) {
            .login-card {
                padding: 19px;
            }

            .login-information h1 {
                font-size: 35px;
            }

            .login-card-header {
                gap: 11px;
            }

            .login-card-logo {
                width: 45px;
                height: 45px;
                flex-basis: 45px;
            }

            .login-card-title h2 {
                font-size: 22px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .login-feature-item,
            .login-register-link svg,
            .login-submit-button {
                transition: none;
            }

            .login-submit-spinner {
                animation: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="login-page">
        <section class="login-section">
            <div class="container login-grid">
                {{-- Information --}}
                <div class="login-information login-reveal">
                    <span class="login-badge">
                        <span class="login-badge-icon">
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
                        </span>

                        Login Pelanggan
                    </span>

                    <h1>
                        Masuk dan pantau
                        <span>pesananmu</span>
                    </h1>

                    <p class="login-description">
                        Login digunakan untuk membuat pesanan,
                        mengunggah file, melihat estimasi biaya,
                        melakukan pembayaran, dan memantau status
                        pengerjaan melalui dashboard pelanggan.
                    </p>

                    <div class="login-feature-list">
                        <div class="login-feature-item">
                            <span class="login-feature-icon">
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

                            <span class="login-feature-copy">
                                <strong>
                                    Buat pesanan secara online
                                </strong>

                                <span>
                                    Pilih layanan, lengkapi detail,
                                    dan tentukan kebutuhan cetak dalam
                                    satu formulir.
                                </span>
                            </span>
                        </div>

                        <div class="login-feature-item">
                            <span class="login-feature-icon">
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

                            <span class="login-feature-copy">
                                <strong>
                                    Upload file lebih teratur
                                </strong>

                                <span>
                                    Dokumen tersimpan pada pesanan
                                    yang sesuai dan mudah diperiksa
                                    oleh admin.
                                </span>
                            </span>
                        </div>

                        <div class="login-feature-item">
                            <span class="login-feature-icon">
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

                            <span class="login-feature-copy">
                                <strong>
                                    Pantau perkembangan pesanan
                                </strong>

                                <span>
                                    Lihat status verifikasi, proses
                                    cetak, pembayaran, dan pengambilan
                                    melalui dashboard.
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="login-register-note">
                        <div class="login-register-copy">
                            <span class="login-register-icon">
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
                                    <path d="M19 8v6"/>
                                    <path d="M16 11h6"/>
                                </svg>
                            </span>

                            <span>
                                <strong>Belum memiliki akun?</strong>
                                <span>
                                    Registrasi pelanggan secara gratis.
                                </span>
                            </span>
                        </div>

                        <a
                            href="{{ route('register') }}"
                            class="login-register-link"
                        >
                            Registrasi

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

                {{-- Login Form --}}
                <div class="login-card-wrapper login-reveal">
                    <div class="login-card">
                        <div class="login-card-header">
                            <img
                                src="{{ $logoUrl }}"
                                alt="Logo {{ $namaWebsite }}"
                                class="login-card-logo"
                            >

                            <div class="login-card-title">
                                <h2>Selamat Datang</h2>

                                <p>
                                    Masukkan email dan password akun
                                    pelanggan untuk melanjutkan.
                                </p>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div
                                class="login-error-summary"
                                role="alert"
                            >
                                <span class="login-error-summary-icon">
                                    !
                                </span>

                                <span>
                                    Login belum berhasil. Periksa
                                    kembali email dan password yang
                                    dimasukkan.
                                </span>
                            </div>
                        @endif

                        <form
                            action="{{ route('login.store') }}"
                            method="POST"
                            class="login-form"
                            id="loginForm"
                        >
                            @csrf

                            <div class="login-form-group">
                                <div class="login-form-label-row">
                                    <label
                                        for="email"
                                        class="login-form-label"
                                    >
                                        Email
                                        <span class="login-required">*</span>
                                    </label>
                                </div>

                                <div class="login-field-wrapper">
                                    <svg
                                        class="login-field-icon"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >
                                        <rect
                                            width="18"
                                            height="14"
                                            x="3"
                                            y="5"
                                            rx="2"
                                        />
                                        <path d="m3 7 9 6 9-6"/>
                                    </svg>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        class="login-input {{
                                            $errors->has('email')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Masukkan email"
                                        autocomplete="email"
                                        inputmode="email"
                                        aria-invalid="{{
                                            $errors->has('email')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                        autofocus
                                        required
                                    >
                                </div>

                                @error('email')
                                    <span class="login-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="login-form-group">
                                <div class="login-form-label-row">
                                    <label
                                        for="password"
                                        class="login-form-label"
                                    >
                                        Password
                                        <span class="login-required">*</span>
                                    </label>
                                </div>

                                <div class="login-field-wrapper">
                                    <svg
                                        class="login-field-icon"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >
                                        <rect
                                            width="16"
                                            height="12"
                                            x="4"
                                            y="10"
                                            rx="2"
                                        />
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                    </svg>

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="login-input login-password-input {{
                                            $errors->has('password')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Masukkan password"
                                        autocomplete="current-password"
                                        aria-invalid="{{
                                            $errors->has('password')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="login-password-toggle"
                                        data-password-toggle
                                        data-target="password"
                                        aria-label="Tampilkan password"
                                        aria-pressed="false"
                                    >
                                        <svg
                                            class="login-eye-icon"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            aria-hidden="true"
                                        >
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>

                                        <svg
                                            class="login-eye-off-icon"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            aria-hidden="true"
                                            hidden
                                        >
                                            <path d="m3 3 18 18"/>
                                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                            <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18 18 0 0 1-2.1 3.2"/>
                                            <path d="M6.6 6.6C3.6 8.4 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 4.1-.9"/>
                                        </svg>
                                    </button>
                                </div>

                                @error('password')
                                    <span class="login-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <span class="login-field-help">
                                    Gunakan password yang terdaftar
                                    pada akun pelanggan.
                                </span>
                            </div>

                            <div class="login-form-options">
                                <a
                                    href="{{ route('password.request') }}"
                                    class="login-forgot-link"
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
                                        <path d="M9.5 9a2.5 2.5 0 1 1 4.7 1.2c-.8 1.2-2.2 1.3-2.2 2.8"/>
                                        <path d="M12 17h.01"/>
                                    </svg>

                                    Lupa password?
                                </a>
                            </div>

                            <button
                                type="submit"
                                class="login-submit-button"
                                id="loginSubmitButton"
                            >
                                <span class="login-submit-spinner"></span>

                                <svg
                                    class="login-submit-icon"
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

                                <span data-login-button-text>
                                    Login ke Dashboard
                                </span>
                            </button>

                            <div class="login-security-note">
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

                                <span>
                                    Jangan berikan password akun kepada
                                    pihak lain. Admin tidak pernah
                                    meminta password melalui chat.
                                </span>
                            </div>
                        </form>

                        <div class="login-mobile-register">
                            Belum punya akun?

                            <a href="{{ route('register') }}">
                                Registrasi sekarang
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
            /*
            |--------------------------------------------------------------------------
            | Reveal Animation
            |--------------------------------------------------------------------------
            */

            const revealElements = document.querySelectorAll(
                '.login-reveal'
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
                const revealObserver = new IntersectionObserver(
                    (entries, observer) => {
                        entries.forEach((entry) => {
                            if (!entry.isIntersecting) {
                                return;
                            }

                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        });
                    },
                    {
                        threshold: 0.1,
                    }
                );

                revealElements.forEach((element) => {
                    revealObserver.observe(element);
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Password Visibility
            |--------------------------------------------------------------------------
            */

            const passwordToggleButtons = document.querySelectorAll(
                '[data-password-toggle]'
            );

            passwordToggleButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId = button.dataset.target;
                    const input = document.getElementById(targetId);

                    if (!input) {
                        return;
                    }

                    const passwordIsHidden =
                        input.type === 'password';

                    input.type = passwordIsHidden
                        ? 'text'
                        : 'password';

                    button.setAttribute(
                        'aria-pressed',
                        passwordIsHidden ? 'true' : 'false'
                    );

                    button.setAttribute(
                        'aria-label',
                        passwordIsHidden
                            ? 'Sembunyikan password'
                            : 'Tampilkan password'
                    );

                    const eyeIcon = button.querySelector(
                        '.login-eye-icon'
                    );

                    const eyeOffIcon = button.querySelector(
                        '.login-eye-off-icon'
                    );

                    if (eyeIcon) {
                        eyeIcon.hidden = passwordIsHidden;
                    }

                    if (eyeOffIcon) {
                        eyeOffIcon.hidden = !passwordIsHidden;
                    }

                    input.focus();
                });
            });

            /*
            |--------------------------------------------------------------------------
            | Prevent Double Submission
            |--------------------------------------------------------------------------
            */

            const loginForm = document.getElementById('loginForm');
            const submitButton = document.getElementById(
                'loginSubmitButton'
            );

            loginForm?.addEventListener('submit', () => {
                if (!submitButton) {
                    return;
                }

                submitButton.disabled = true;
                submitButton.classList.add('loading');

                const buttonText = submitButton.querySelector(
                    '[data-login-button-text]'
                );

                if (buttonText) {
                    buttonText.textContent = 'Memproses Login...';
                }
            });
        });
    </script>
@endpush