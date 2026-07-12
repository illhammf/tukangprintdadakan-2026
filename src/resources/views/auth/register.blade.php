@extends('layouts.public')

@section(
    'title',
    'Registrasi - '
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
        | Register Page Variables
        |--------------------------------------------------------------------------
        */

        .register-page {
            --register-blue: var(--public-blue, #155eef);
            --register-blue-dark: var(--public-blue-dark, #1046b8);
            --register-blue-soft: var(--public-blue-soft, #edf4ff);

            --register-orange: var(--public-orange, #f97316);
            --register-orange-dark: var(--public-orange-dark, #c2410c);
            --register-orange-soft: var(--public-orange-soft, #fff1e7);

            --register-green: #16a34a;
            --register-green-dark: #15803d;
            --register-green-soft: #ecfdf3;

            --register-yellow: #d97706;
            --register-yellow-soft: #fffbeb;

            --register-red: #dc2626;
            --register-red-soft: #fff1f2;

            --register-dark: var(--public-dark, #101828);
            --register-text: var(--public-text, #344054);
            --register-muted: var(--public-muted, #667085);

            --register-white: #ffffff;
            --register-soft: #f7f9fc;
            --register-border: #e4e7ec;
            --register-border-dark: #d0d5dd;

            position: relative;
            min-height: calc(100vh - 120px);
            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Page Background
        |--------------------------------------------------------------------------
        */

        .register-section {
            position: relative;
            min-height: 890px;
            display: flex;
            align-items: center;
            padding: 76px 0 94px;
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

        .register-section::before {
            content: "";
            position: absolute;
            top: 110px;
            left: -120px;
            width: 275px;
            height: 275px;
            border: 44px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .register-section::after {
            content: "";
            position: absolute;
            right: -125px;
            bottom: 80px;
            width: 300px;
            height: 300px;
            border: 48px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .register-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 0.92fr)
                minmax(430px, 0.88fr);
            gap: 64px;
            align-items: center;
        }

        /*
        |--------------------------------------------------------------------------
        | Information Column
        |--------------------------------------------------------------------------
        */

        .register-information {
            max-width: 670px;
        }

        .register-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--register-orange-dark);
            background: var(--register-orange-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .register-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--register-white);
            background: var(--register-orange);
        }

        .register-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .register-information h1 {
            margin: 24px 0 18px;
            color: var(--register-dark);
            font-size: clamp(43px, 5.5vw, 67px);
            line-height: 1.04;
            letter-spacing: -2.4px;
        }

        .register-information h1 span {
            position: relative;
            display: inline-block;
            color: var(--register-blue);
        }

        .register-information h1 span::after {
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

        .register-description {
            max-width: 630px;
            margin: 0;
            color: var(--register-muted);
            font-size: 16px;
            line-height: 1.85;
        }

        /*
        |--------------------------------------------------------------------------
        | Registration Benefits
        |--------------------------------------------------------------------------
        */

        .register-benefit-list {
            display: grid;
            gap: 13px;
            margin-top: 30px;
        }

        .register-benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            max-width: 580px;
            padding: 14px 16px;
            border: 1px solid rgba(228, 231, 236, 0.88);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.76);
            backdrop-filter: blur(10px);
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .register-benefit-item:hover {
            border-color: #bdd1ff;
            background: var(--register-white);
            transform: translateX(4px);
        }

        .register-benefit-icon {
            width: 41px;
            height: 41px;
            flex: 0 0 41px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--register-blue);
            background: var(--register-blue-soft);
        }

        .register-benefit-item:nth-child(2)
        .register-benefit-icon {
            color: var(--register-orange-dark);
            background: var(--register-orange-soft);
        }

        .register-benefit-item:nth-child(3)
        .register-benefit-icon {
            color: var(--register-green-dark);
            background: var(--register-green-soft);
        }

        .register-benefit-icon svg {
            width: 20px;
            height: 20px;
        }

        .register-benefit-copy strong,
        .register-benefit-copy span {
            display: block;
        }

        .register-benefit-copy strong {
            color: var(--register-dark);
            font-size: 13px;
        }

        .register-benefit-copy span {
            margin-top: 3px;
            color: var(--register-muted);
            font-size: 11px;
            line-height: 1.55;
        }

        /*
        |--------------------------------------------------------------------------
        | Login Note
        |--------------------------------------------------------------------------
        */

        .register-login-note {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            max-width: 580px;
            margin-top: 25px;
            padding: 17px 18px;
            border: 1px solid #cfe0ff;
            border-radius: 17px;
            background:
                linear-gradient(
                    135deg,
                    var(--register-blue-soft),
                    rgba(255, 255, 255, 0.91)
                );
        }

        .register-login-copy {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .register-login-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--register-white);
            background:
                linear-gradient(
                    135deg,
                    var(--register-blue),
                    #2b70ff
                );
        }

        .register-login-icon svg {
            width: 20px;
            height: 20px;
        }

        .register-login-copy strong,
        .register-login-copy span {
            display: block;
        }

        .register-login-copy strong {
            color: var(--register-dark);
            font-size: 12px;
        }

        .register-login-copy span {
            margin-top: 2px;
            color: var(--register-muted);
            font-size: 10px;
        }

        .register-login-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--register-blue);
            font-size: 11px;
            font-weight: 900;
            white-space: nowrap;
        }

        .register-login-link:hover {
            color: var(--register-blue-dark);
        }

        .register-login-link svg {
            width: 15px;
            height: 15px;
            transition: transform 0.2s ease;
        }

        .register-login-link:hover svg {
            transform: translateX(3px);
        }

        /*
        |--------------------------------------------------------------------------
        | Register Card
        |--------------------------------------------------------------------------
        */

        .register-card-wrapper {
            position: relative;
        }

        .register-card-wrapper::before {
            content: "";
            position: absolute;
            top: -17px;
            right: 34px;
            width: 89px;
            height: 29px;
            border-radius: 10px 10px 4px 4px;
            background:
                linear-gradient(
                    90deg,
                    var(--register-blue),
                    var(--register-orange)
                );
            transform: rotate(3deg);
        }

        .register-card {
            position: relative;
            padding: 31px;
            border: 1px solid rgba(228, 231, 236, 0.94);
            border-radius: 29px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(17px);
            box-shadow:
                0 29px 80px rgba(16, 24, 40, 0.15);
        }

        /*
        |--------------------------------------------------------------------------
        | Card Header
        |--------------------------------------------------------------------------
        */

        .register-card-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 24px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--register-border);
        }

        .register-card-logo {
            width: 54px;
            height: 54px;
            flex: 0 0 54px;
            padding: 3px;
            object-fit: contain;
            border: 1px solid var(--register-border);
            border-radius: 17px;
            background: var(--register-white);
            box-shadow:
                0 8px 20px rgba(16, 24, 40, 0.09);
        }

        .register-card-title {
            min-width: 0;
        }

        .register-card-title h2 {
            margin: 0 0 5px;
            color: var(--register-dark);
            font-size: 27px;
            line-height: 1.2;
        }

        .register-card-title p {
            margin: 0;
            color: var(--register-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | Form Progress
        |--------------------------------------------------------------------------
        */

        .register-progress {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 7px;
            margin-bottom: 22px;
        }

        .register-progress-item {
            height: 5px;
            overflow: hidden;
            border-radius: 999px;
            background: #eaecf0;
        }

        .register-progress-item.active {
            background:
                linear-gradient(
                    90deg,
                    var(--register-blue),
                    var(--register-orange)
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation Summary
        |--------------------------------------------------------------------------
        */

        .register-error-summary {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: 21px;
            padding: 14px;
            border: 1px solid #fecaca;
            border-radius: 15px;
            color: #991b1b;
            background: var(--register-red-soft);
            font-size: 11px;
            font-weight: 800;
            line-height: 1.55;
        }

        .register-error-summary-icon {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--register-white);
            background: var(--register-red);
            font-size: 13px;
            font-weight: 900;
        }

        /*
        |--------------------------------------------------------------------------
        | Form
        |--------------------------------------------------------------------------
        */

        .register-form {
            display: grid;
            gap: 17px;
        }

        .register-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .register-form-group {
            display: grid;
            gap: 7px;
        }

        .register-form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .register-form-label {
            color: var(--register-text);
            font-size: 12px;
            font-weight: 900;
        }

        .register-required {
            color: var(--register-orange);
        }

        .register-field-wrapper {
            position: relative;
        }

        .register-field-icon {
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

        .register-input {
            width: 100%;
            min-height: 51px;
            padding: 13px 15px 13px 46px;
            border: 1px solid var(--register-border-dark);
            border-radius: 14px;
            color: var(--register-dark);
            background: #fcfcfd;
            outline: none;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .register-password-input {
            padding-right: 53px;
        }

        .register-input::placeholder {
            color: #98a2b3;
        }

        .register-input:hover {
            border-color: #98a2b3;
        }

        .register-input:focus {
            border-color: var(--register-blue);
            background: var(--register-white);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.12);
        }

        .register-input.is-invalid {
            border-color: var(--register-red);
            background: #fffafa;
        }

        .register-input.is-invalid:focus {
            box-shadow:
                0 0 0 4px rgba(220, 38, 38, 0.10);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Toggle
        |--------------------------------------------------------------------------
        */

        .register-password-toggle {
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
            color: var(--register-muted);
            background: transparent;
            cursor: pointer;
            transform: translateY(-50%);
            transition:
                color 0.2s ease,
                background 0.2s ease;
        }

        .register-password-toggle:hover {
            color: var(--register-blue);
            background: var(--register-blue-soft);
        }

        .register-password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .register-password-toggle svg[hidden] {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Field Error and Help
        |--------------------------------------------------------------------------
        */

        .register-field-error {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--register-red);
            font-size: 10px;
            font-weight: 800;
        }

        .register-field-error::before {
            content: "!";
            width: 17px;
            height: 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--register-white);
            background: var(--register-red);
            font-size: 10px;
        }

        .register-field-help {
            color: var(--register-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Password Strength
        |--------------------------------------------------------------------------
        */

        .register-password-strength {
            display: grid;
            gap: 8px;
            margin-top: 1px;
        }

        .register-password-strength-bars {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 5px;
        }

        .register-password-strength-bar {
            height: 5px;
            border-radius: 999px;
            background: #eaecf0;
            transition: background 0.2s ease;
        }

        .register-password-strength[data-level="1"]
        .register-password-strength-bar:nth-child(1) {
            background: var(--register-red);
        }

        .register-password-strength[data-level="2"]
        .register-password-strength-bar:nth-child(-n + 2) {
            background: var(--register-yellow);
        }

        .register-password-strength[data-level="3"]
        .register-password-strength-bar:nth-child(-n + 3) {
            background: var(--register-blue);
        }

        .register-password-strength[data-level="4"]
        .register-password-strength-bar {
            background: var(--register-green);
        }

        .register-password-strength-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--register-muted);
            font-size: 9px;
        }

        .register-password-strength-label {
            font-weight: 900;
        }

        .register-password-strength[data-level="1"]
        .register-password-strength-label {
            color: var(--register-red);
        }

        .register-password-strength[data-level="2"]
        .register-password-strength-label {
            color: var(--register-yellow);
        }

        .register-password-strength[data-level="3"]
        .register-password-strength-label {
            color: var(--register-blue);
        }

        .register-password-strength[data-level="4"]
        .register-password-strength-label {
            color: var(--register-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Requirements
        |--------------------------------------------------------------------------
        */

        .register-password-requirements {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 7px;
            padding: 12px;
            border: 1px solid var(--register-border);
            border-radius: 13px;
            background: #fcfcfd;
        }

        .register-password-requirement {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--register-muted);
            font-size: 9px;
            font-weight: 750;
        }

        .register-password-requirement-icon {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: #98a2b3;
            background: #eaecf0;
            font-size: 10px;
            font-weight: 900;
            transition:
                color 0.2s ease,
                background 0.2s ease;
        }

        .register-password-requirement.valid {
            color: #166534;
        }

        .register-password-requirement.valid
        .register-password-requirement-icon {
            color: var(--register-white);
            background: var(--register-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Match
        |--------------------------------------------------------------------------
        */

        .register-password-match {
            display: none;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 800;
        }

        .register-password-match.visible {
            display: flex;
        }

        .register-password-match.match {
            color: var(--register-green);
        }

        .register-password-match.not-match {
            color: var(--register-red);
        }

        .register-password-match-icon {
            width: 17px;
            height: 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--register-white);
            font-size: 10px;
            font-weight: 900;
        }

        .register-password-match.match
        .register-password-match-icon {
            background: var(--register-green);
        }

        .register-password-match.not-match
        .register-password-match-icon {
            background: var(--register-red);
        }

        /*
        |--------------------------------------------------------------------------
        | Privacy Note
        |--------------------------------------------------------------------------
        */

        .register-privacy-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 13px;
            border: 1px solid #cfe0ff;
            border-radius: 14px;
            color: var(--register-text);
            background: var(--register-blue-soft);
            font-size: 10px;
            line-height: 1.6;
        }

        .register-privacy-note svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            color: var(--register-blue);
        }

        /*
        |--------------------------------------------------------------------------
        | Submit Button
        |--------------------------------------------------------------------------
        */

        .register-submit-button {
            width: 100%;
            min-height: 54px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 19px;
            border: 0;
            border-radius: 15px;
            color: var(--register-white);
            background:
                linear-gradient(
                    135deg,
                    var(--register-blue),
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

        .register-submit-button:hover {
            background:
                linear-gradient(
                    135deg,
                    var(--register-blue-dark),
                    var(--register-blue)
                );
            box-shadow:
                0 16px 34px rgba(21, 94, 239, 0.30);
            transform: translateY(-2px);
        }

        .register-submit-button:disabled {
            cursor: not-allowed;
            opacity: 0.7;
            transform: none;
        }

        .register-submit-button svg {
            width: 18px;
            height: 18px;
        }

        .register-submit-spinner {
            width: 17px;
            height: 17px;
            display: none;
            border: 2px solid rgba(255, 255, 255, 0.42);
            border-top-color: var(--register-white);
            border-radius: 999px;
            animation: register-spin 0.75s linear infinite;
        }

        .register-submit-button.loading
        .register-submit-spinner {
            display: inline-block;
        }

        .register-submit-button.loading
        .register-submit-icon {
            display: none;
        }

        @keyframes register-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile Login
        |--------------------------------------------------------------------------
        */

        .register-mobile-login {
            display: none;
            margin-top: 21px;
            padding-top: 20px;
            border-top: 1px solid var(--register-border);
            color: var(--register-muted);
            font-size: 11px;
            text-align: center;
        }

        .register-mobile-login a {
            color: var(--register-blue);
            font-weight: 900;
        }

        .register-mobile-login a:hover {
            color: var(--register-blue-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .register-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .register-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1100px) {
            .register-grid {
                grid-template-columns:
                    minmax(0, 0.82fr)
                    minmax(410px, 0.88fr);
                gap: 42px;
            }

            .register-information h1 {
                font-size: clamp(42px, 5vw, 58px);
            }
        }

        @media (max-width: 900px) {
            .register-section {
                min-height: auto;
                padding: 62px 0 80px;
            }

            .register-grid {
                grid-template-columns: 1fr;
                gap: 45px;
            }

            .register-information {
                max-width: 760px;
            }

            .register-card-wrapper {
                width: 100%;
                max-width: 670px;
                margin: 0 auto;
            }
        }

        @media (max-width: 640px) {
            .register-section {
                padding: 44px 0 62px;
            }

            .register-section::before,
            .register-section::after {
                display: none;
            }

            .register-grid {
                gap: 34px;
            }

            .register-badge {
                font-size: 11px;
            }

            .register-information h1 {
                margin-top: 19px;
                font-size: 39px;
                letter-spacing: -1.5px;
            }

            .register-description {
                font-size: 14px;
            }

            .register-benefit-list {
                gap: 10px;
                margin-top: 24px;
            }

            .register-benefit-item {
                padding: 12px;
            }

            .register-login-note {
                display: none;
            }

            .register-card-wrapper::before {
                right: 25px;
                width: 73px;
                height: 24px;
            }

            .register-card {
                padding: 22px;
                border-radius: 23px;
            }

            .register-card-header {
                margin-bottom: 20px;
                padding-bottom: 19px;
            }

            .register-card-logo {
                width: 48px;
                height: 48px;
                flex-basis: 48px;
                border-radius: 15px;
            }

            .register-card-title h2 {
                font-size: 24px;
            }

            .register-form {
                gap: 16px;
            }

            .register-form-grid {
                grid-template-columns: 1fr;
            }

            .register-password-requirements {
                grid-template-columns: 1fr;
            }

            .register-mobile-login {
                display: block;
            }
        }

        @media (max-width: 390px) {
            .register-card {
                padding: 19px;
            }

            .register-information h1 {
                font-size: 35px;
            }

            .register-card-header {
                gap: 11px;
            }

            .register-card-logo {
                width: 45px;
                height: 45px;
                flex-basis: 45px;
            }

            .register-card-title h2 {
                font-size: 22px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .register-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .register-benefit-item,
            .register-login-link svg,
            .register-submit-button {
                transition: none;
            }

            .register-submit-spinner {
                animation: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="register-page">
        <section class="register-section">
            <div class="container register-grid">
                {{-- Information --}}
                <div class="register-information register-reveal">
                    <span class="register-badge">
                        <span class="register-badge-icon">
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

                        Registrasi Pelanggan
                    </span>

                    <h1>
                        Buat akun dan mulai
                        <span>mencetak online</span>
                    </h1>

                    <p class="register-description">
                        Setelah registrasi, pelanggan dapat memilih
                        layanan, membuat pesanan, mengunggah file,
                        melihat estimasi biaya, melakukan pembayaran,
                        dan memantau status pengerjaan secara mandiri.
                    </p>

                    <div class="register-benefit-list">
                        <div class="register-benefit-item">
                            <span class="register-benefit-icon">
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

                            <span class="register-benefit-copy">
                                <strong>
                                    Buat pesanan lebih mudah
                                </strong>

                                <span>
                                    Pilih layanan dan lengkapi seluruh
                                    detail kebutuhan cetak melalui satu
                                    formulir.
                                </span>
                            </span>
                        </div>

                        <div class="register-benefit-item">
                            <span class="register-benefit-icon">
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

                            <span class="register-benefit-copy">
                                <strong>
                                    Upload file secara terpusat
                                </strong>

                                <span>
                                    File dokumen tersimpan bersama
                                    detail pesanan dan tidak mudah
                                    tertukar.
                                </span>
                            </span>
                        </div>

                        <div class="register-benefit-item">
                            <span class="register-benefit-icon">
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

                            <span class="register-benefit-copy">
                                <strong>
                                    Pantau pesanan secara mandiri
                                </strong>

                                <span>
                                    Lihat perkembangan pengerjaan,
                                    pembayaran, dan status pengambilan
                                    melalui dashboard.
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="register-login-note">
                        <div class="register-login-copy">
                            <span class="register-login-icon">
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

                            <span>
                                <strong>Sudah memiliki akun?</strong>

                                <span>
                                    Masuk menggunakan email dan password.
                                </span>
                            </span>
                        </div>

                        <a
                            href="{{ route('login') }}"
                            class="register-login-link"
                        >
                            Login

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

                {{-- Register Form --}}
                <div class="register-card-wrapper register-reveal">
                    <div class="register-card">
                        <div class="register-card-header">
                            <img
                                src="{{ $logoUrl }}"
                                alt="Logo {{ $namaWebsite }}"
                                class="register-card-logo"
                            >

                            <div class="register-card-title">
                                <h2>Buat Akun Pelanggan</h2>

                                <p>
                                    Lengkapi informasi berikut untuk
                                    mendapatkan akses ke dashboard
                                    pelanggan.
                                </p>
                            </div>
                        </div>

                        <div
                            class="register-progress"
                            aria-label="Proses registrasi"
                        >
                            <span class="register-progress-item active"></span>
                            <span class="register-progress-item active"></span>
                            <span class="register-progress-item active"></span>
                        </div>

                        @if ($errors->any())
                            <div
                                class="register-error-summary"
                                role="alert"
                            >
                                <span class="register-error-summary-icon">
                                    !
                                </span>

                                <span>
                                    Registrasi belum berhasil. Periksa
                                    kembali kolom yang masih salah atau
                                    belum diisi.
                                </span>
                            </div>
                        @endif

                        <form
                            action="{{ route('register.store') }}"
                            method="POST"
                            class="register-form"
                            id="registerForm"
                        >
                            @csrf

                            {{-- Nama --}}
                            <div class="register-form-group">
                                <div class="register-form-label-row">
                                    <label
                                        for="name"
                                        class="register-form-label"
                                    >
                                        Nama Lengkap
                                        <span class="register-required">*</span>
                                    </label>
                                </div>

                                <div class="register-field-wrapper">
                                    <svg
                                        class="register-field-icon"
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
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        class="register-input {{
                                            $errors->has('name')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Masukkan nama lengkap"
                                        autocomplete="name"
                                        aria-invalid="{{
                                            $errors->has('name')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                        autofocus
                                        required
                                    >
                                </div>

                                @error('name')
                                    <span class="register-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            {{-- Email dan WhatsApp --}}
                            <div class="register-form-grid">
                                <div class="register-form-group">
                                    <div class="register-form-label-row">
                                        <label
                                            for="email"
                                            class="register-form-label"
                                        >
                                            Email
                                            <span class="register-required">*</span>
                                        </label>
                                    </div>

                                    <div class="register-field-wrapper">
                                        <svg
                                            class="register-field-icon"
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
                                            class="register-input {{
                                                $errors->has('email')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="contoh@email.com"
                                            autocomplete="email"
                                            inputmode="email"
                                            aria-invalid="{{
                                                $errors->has('email')
                                                    ? 'true'
                                                    : 'false'
                                            }}"
                                            required
                                        >
                                    </div>

                                    @error('email')
                                        <span class="register-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="register-form-group">
                                    <div class="register-form-label-row">
                                        <label
                                            for="nomor_whatsapp"
                                            class="register-form-label"
                                        >
                                            Nomor WhatsApp
                                            <span class="register-required">*</span>
                                        </label>
                                    </div>

                                    <div class="register-field-wrapper">
                                        <svg
                                            class="register-field-icon"
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
                                            value="{{ old('nomor_whatsapp') }}"
                                            class="register-input {{
                                                $errors->has('nomor_whatsapp')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="08xxxxxxxxxx"
                                            autocomplete="tel"
                                            inputmode="tel"
                                            aria-invalid="{{
                                                $errors->has('nomor_whatsapp')
                                                    ? 'true'
                                                    : 'false'
                                            }}"
                                            required
                                        >
                                    </div>

                                    @error('nomor_whatsapp')
                                        <span class="register-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="register-form-group">
                                <div class="register-form-label-row">
                                    <label
                                        for="password"
                                        class="register-form-label"
                                    >
                                        Password
                                        <span class="register-required">*</span>
                                    </label>
                                </div>

                                <div class="register-field-wrapper">
                                    <svg
                                        class="register-field-icon"
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
                                        class="register-input register-password-input {{
                                            $errors->has('password')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Minimal 8 karakter"
                                        autocomplete="new-password"
                                        aria-invalid="{{
                                            $errors->has('password')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="register-password-toggle"
                                        data-register-password-toggle
                                        data-target="password"
                                        aria-label="Tampilkan password"
                                        aria-pressed="false"
                                    >
                                        <svg
                                            class="register-eye-icon"
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
                                            class="register-eye-off-icon"
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
                                    <span class="register-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <div
                                    class="register-password-strength"
                                    id="registerPasswordStrength"
                                    data-level="0"
                                >
                                    <div class="register-password-strength-bars">
                                        <span class="register-password-strength-bar"></span>
                                        <span class="register-password-strength-bar"></span>
                                        <span class="register-password-strength-bar"></span>
                                        <span class="register-password-strength-bar"></span>
                                    </div>

                                    <div class="register-password-strength-text">
                                        <span>
                                            Kekuatan password:
                                            <strong class="register-password-strength-label">
                                                Belum diisi
                                            </strong>
                                        </span>

                                        <span>
                                            Gunakan password yang sulit ditebak
                                        </span>
                                    </div>
                                </div>

                                <div class="register-password-requirements">
                                    <span
                                        class="register-password-requirement"
                                        data-password-requirement="length"
                                    >
                                        <span class="register-password-requirement-icon">
                                            ×
                                        </span>

                                        Minimal 8 karakter
                                    </span>

                                    <span
                                        class="register-password-requirement"
                                        data-password-requirement="letter"
                                    >
                                        <span class="register-password-requirement-icon">
                                            ×
                                        </span>

                                        Memiliki huruf
                                    </span>

                                    <span
                                        class="register-password-requirement"
                                        data-password-requirement="number"
                                    >
                                        <span class="register-password-requirement-icon">
                                            ×
                                        </span>

                                        Memiliki angka
                                    </span>

                                    <span
                                        class="register-password-requirement"
                                        data-password-requirement="space"
                                    >
                                        <span class="register-password-requirement-icon">
                                            ×
                                        </span>

                                        Tanpa spasi
                                    </span>
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="register-form-group">
                                <div class="register-form-label-row">
                                    <label
                                        for="password_confirmation"
                                        class="register-form-label"
                                    >
                                        Konfirmasi Password
                                        <span class="register-required">*</span>
                                    </label>
                                </div>

                                <div class="register-field-wrapper">
                                    <svg
                                        class="register-field-icon"
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

                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="register-input register-password-input"
                                        placeholder="Ulangi password baru"
                                        autocomplete="new-password"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="register-password-toggle"
                                        data-register-password-toggle
                                        data-target="password_confirmation"
                                        aria-label="Tampilkan konfirmasi password"
                                        aria-pressed="false"
                                    >
                                        <svg
                                            class="register-eye-icon"
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
                                            class="register-eye-off-icon"
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

                                <div
                                    class="register-password-match"
                                    id="registerPasswordMatch"
                                >
                                    <span class="register-password-match-icon">
                                        ×
                                    </span>

                                    <span data-password-match-text>
                                        Password belum sama
                                    </span>
                                </div>
                            </div>

                            {{-- Privacy --}}
                            <div class="register-privacy-note">
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
                                    Gunakan email dan nomor WhatsApp yang
                                    aktif. Data tersebut digunakan untuk
                                    akun pelanggan, informasi pesanan,
                                    dan proses pemulihan password.
                                </span>
                            </div>

                            {{-- Submit --}}
                            <button
                                type="submit"
                                class="register-submit-button"
                                id="registerSubmitButton"
                            >
                                <span class="register-submit-spinner"></span>

                                <svg
                                    class="register-submit-icon"
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

                                <span data-register-button-text>
                                    Daftar Akun Pelanggan
                                </span>
                            </button>
                        </form>

                        <div class="register-mobile-login">
                            Sudah memiliki akun?

                            <a href="{{ route('login') }}">
                                Login sekarang
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
                '.register-reveal'
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
                '[data-register-password-toggle]'
            );

            passwordToggleButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId = button.dataset.target;
                    const input = document.getElementById(targetId);

                    if (!input) {
                        return;
                    }

                    const isHidden = input.type === 'password';

                    input.type = isHidden
                        ? 'text'
                        : 'password';

                    button.setAttribute(
                        'aria-pressed',
                        isHidden ? 'true' : 'false'
                    );

                    button.setAttribute(
                        'aria-label',
                        isHidden
                            ? 'Sembunyikan password'
                            : 'Tampilkan password'
                    );

                    const eyeIcon = button.querySelector(
                        '.register-eye-icon'
                    );

                    const eyeOffIcon = button.querySelector(
                        '.register-eye-off-icon'
                    );

                    if (eyeIcon) {
                        eyeIcon.hidden = isHidden;
                    }

                    if (eyeOffIcon) {
                        eyeOffIcon.hidden = !isHidden;
                    }

                    input.focus();
                });
            });

            /*
            |--------------------------------------------------------------------------
            | Password Strength
            |--------------------------------------------------------------------------
            */

            const passwordInput = document.getElementById(
                'password'
            );

            const passwordConfirmationInput = document.getElementById(
                'password_confirmation'
            );

            const strengthContainer = document.getElementById(
                'registerPasswordStrength'
            );

            const strengthLabel = strengthContainer?.querySelector(
                '.register-password-strength-label'
            );

            const requirementElements = {
                length: document.querySelector(
                    '[data-password-requirement="length"]'
                ),
                letter: document.querySelector(
                    '[data-password-requirement="letter"]'
                ),
                number: document.querySelector(
                    '[data-password-requirement="number"]'
                ),
                space: document.querySelector(
                    '[data-password-requirement="space"]'
                ),
            };

            const setRequirementState = (
                element,
                isValid
            ) => {
                if (!element) {
                    return;
                }

                element.classList.toggle(
                    'valid',
                    isValid
                );

                const icon = element.querySelector(
                    '.register-password-requirement-icon'
                );

                if (icon) {
                    icon.textContent = isValid ? '✓' : '×';
                }
            };

            const calculatePasswordStrength = () => {
                if (!passwordInput || !strengthContainer) {
                    return;
                }

                const password = passwordInput.value;

                const requirements = {
                    length: password.length >= 8,
                    letter: /[a-zA-Z]/.test(password),
                    number: /[0-9]/.test(password),
                    space: password.length > 0
                        && !/\s/.test(password),
                };

                setRequirementState(
                    requirementElements.length,
                    requirements.length
                );

                setRequirementState(
                    requirementElements.letter,
                    requirements.letter
                );

                setRequirementState(
                    requirementElements.number,
                    requirements.number
                );

                setRequirementState(
                    requirementElements.space,
                    requirements.space
                );

                if (password.length === 0) {
                    strengthContainer.dataset.level = '0';

                    if (strengthLabel) {
                        strengthLabel.textContent = 'Belum diisi';
                    }

                    return;
                }

                let score = 0;

                if (requirements.length) {
                    score++;
                }

                if (requirements.letter) {
                    score++;
                }

                if (requirements.number) {
                    score++;
                }

                if (
                    password.length >= 10
                    && /[^a-zA-Z0-9\s]/.test(password)
                ) {
                    score++;
                }

                score = Math.max(1, Math.min(score, 4));

                strengthContainer.dataset.level =
                    String(score);

                const labels = {
                    1: 'Lemah',
                    2: 'Cukup',
                    3: 'Baik',
                    4: 'Kuat',
                };

                if (strengthLabel) {
                    strengthLabel.textContent =
                        labels[score];
                }
            };

            /*
            |--------------------------------------------------------------------------
            | Password Confirmation
            |--------------------------------------------------------------------------
            */

            const passwordMatchElement = document.getElementById(
                'registerPasswordMatch'
            );

            const passwordMatchText = passwordMatchElement?.querySelector(
                '[data-password-match-text]'
            );

            const passwordMatchIcon = passwordMatchElement?.querySelector(
                '.register-password-match-icon'
            );

            const checkPasswordMatch = () => {
                if (
                    !passwordInput
                    || !passwordConfirmationInput
                    || !passwordMatchElement
                ) {
                    return;
                }

                const confirmationValue =
                    passwordConfirmationInput.value;

                if (confirmationValue.length === 0) {
                    passwordMatchElement.classList.remove(
                        'visible',
                        'match',
                        'not-match'
                    );

                    passwordConfirmationInput.setCustomValidity('');

                    return;
                }

                const isMatch =
                    passwordInput.value === confirmationValue;

                passwordMatchElement.classList.add('visible');

                passwordMatchElement.classList.toggle(
                    'match',
                    isMatch
                );

                passwordMatchElement.classList.toggle(
                    'not-match',
                    !isMatch
                );

                if (passwordMatchText) {
                    passwordMatchText.textContent = isMatch
                        ? 'Konfirmasi password sudah sesuai'
                        : 'Konfirmasi password belum sesuai';
                }

                if (passwordMatchIcon) {
                    passwordMatchIcon.textContent =
                        isMatch ? '✓' : '×';
                }

                passwordConfirmationInput.setCustomValidity(
                    isMatch
                        ? ''
                        : 'Konfirmasi password belum sesuai.'
                );
            };

            passwordInput?.addEventListener('input', () => {
                calculatePasswordStrength();
                checkPasswordMatch();
            });

            passwordConfirmationInput?.addEventListener(
                'input',
                checkPasswordMatch
            );

            /*
            |--------------------------------------------------------------------------
            | Normalize WhatsApp Field
            |--------------------------------------------------------------------------
            */

            const whatsappInput = document.getElementById(
                'nomor_whatsapp'
            );

            whatsappInput?.addEventListener('input', () => {
                whatsappInput.value = whatsappInput.value.replace(
                    /[^0-9+]/g,
                    ''
                );
            });

            /*
            |--------------------------------------------------------------------------
            | Prevent Double Submission
            |--------------------------------------------------------------------------
            */

            const registerForm = document.getElementById(
                'registerForm'
            );

            const submitButton = document.getElementById(
                'registerSubmitButton'
            );

            registerForm?.addEventListener('submit', (event) => {
                checkPasswordMatch();

                if (!registerForm.checkValidity()) {
                    event.preventDefault();
                    registerForm.reportValidity();
                    return;
                }

                if (!submitButton) {
                    return;
                }

                submitButton.disabled = true;
                submitButton.classList.add('loading');

                const buttonText = submitButton.querySelector(
                    '[data-register-button-text]'
                );

                if (buttonText) {
                    buttonText.textContent =
                        'Membuat Akun...';
                }
            });

            calculatePasswordStrength();
            checkPasswordMatch();
        });
    </script>
@endpush