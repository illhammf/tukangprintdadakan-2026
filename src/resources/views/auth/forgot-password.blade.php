@extends('layouts.public')

@section(
    'title',
    'Lupa Password - '
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
        | Forgot Password Variables
        |--------------------------------------------------------------------------
        */

        .forgot-page {
            --forgot-blue: var(--public-blue, #155eef);
            --forgot-blue-dark: var(--public-blue-dark, #1046b8);
            --forgot-blue-soft: var(--public-blue-soft, #edf4ff);

            --forgot-orange: var(--public-orange, #f97316);
            --forgot-orange-dark: var(--public-orange-dark, #c2410c);
            --forgot-orange-soft: var(--public-orange-soft, #fff1e7);

            --forgot-green: #16a34a;
            --forgot-green-dark: #15803d;
            --forgot-green-soft: #ecfdf3;

            --forgot-yellow: #d97706;
            --forgot-yellow-soft: #fffbeb;

            --forgot-red: #dc2626;
            --forgot-red-soft: #fff1f2;

            --forgot-dark: var(--public-dark, #101828);
            --forgot-text: var(--public-text, #344054);
            --forgot-muted: var(--public-muted, #667085);

            --forgot-white: #ffffff;
            --forgot-soft: #f7f9fc;
            --forgot-border: #e4e7ec;
            --forgot-border-dark: #d0d5dd;

            position: relative;
            min-height: calc(100vh - 120px);
            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Page Background
        |--------------------------------------------------------------------------
        */

        .forgot-section {
            position: relative;
            min-height: 900px;
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

        .forgot-section::before {
            content: "";
            position: absolute;
            top: 110px;
            left: -125px;
            width: 280px;
            height: 280px;
            border: 45px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .forgot-section::after {
            content: "";
            position: absolute;
            right: -130px;
            bottom: 75px;
            width: 305px;
            height: 305px;
            border: 49px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .forgot-grid {
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

        .forgot-information {
            max-width: 675px;
        }

        .forgot-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--forgot-orange-dark);
            background: var(--forgot-orange-soft);
            font-size: 12px;
            font-weight: 900;
        }

        .forgot-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--forgot-white);
            background: var(--forgot-orange);
        }

        .forgot-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .forgot-information h1 {
            margin: 24px 0 18px;
            color: var(--forgot-dark);
            font-size: clamp(43px, 5.5vw, 67px);
            line-height: 1.04;
            letter-spacing: -2.4px;
        }

        .forgot-information h1 span {
            position: relative;
            display: inline-block;
            color: var(--forgot-blue);
        }

        .forgot-information h1 span::after {
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

        .forgot-description {
            max-width: 630px;
            margin: 0;
            color: var(--forgot-muted);
            font-size: 16px;
            line-height: 1.85;
        }

        /*
        |--------------------------------------------------------------------------
        | Reset Steps
        |--------------------------------------------------------------------------
        */

        .forgot-step-list {
            display: grid;
            gap: 13px;
            margin-top: 30px;
        }

        .forgot-step-item {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            max-width: 580px;
            padding: 14px 16px;
            border: 1px solid rgba(228, 231, 236, 0.88);
            border-radius: 17px;
            background: rgba(255, 255, 255, 0.76);
            backdrop-filter: blur(10px);
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .forgot-step-item:hover {
            border-color: #bdd1ff;
            background: var(--forgot-white);
            transform: translateX(4px);
        }

        .forgot-step-number {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--forgot-white);
            background:
                linear-gradient(
                    135deg,
                    var(--forgot-blue),
                    #2b70ff
                );
            box-shadow:
                0 8px 18px rgba(21, 94, 239, 0.18);
            font-size: 12px;
            font-weight: 900;
        }

        .forgot-step-item:nth-child(2)
        .forgot-step-number {
            background:
                linear-gradient(
                    135deg,
                    var(--forgot-orange),
                    #fb923c
                );
            box-shadow:
                0 8px 18px rgba(249, 115, 22, 0.18);
        }

        .forgot-step-item:nth-child(3)
        .forgot-step-number {
            background:
                linear-gradient(
                    135deg,
                    var(--forgot-green),
                    #22c55e
                );
            box-shadow:
                0 8px 18px rgba(34, 197, 94, 0.18);
        }

        .forgot-step-copy strong,
        .forgot-step-copy span {
            display: block;
        }

        .forgot-step-copy strong {
            color: var(--forgot-dark);
            font-size: 13px;
        }

        .forgot-step-copy span {
            margin-top: 4px;
            color: var(--forgot-muted);
            font-size: 11px;
            line-height: 1.55;
        }

        /*
        |--------------------------------------------------------------------------
        | Back to Login Card
        |--------------------------------------------------------------------------
        */

        .forgot-login-note {
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
                    var(--forgot-blue-soft),
                    rgba(255, 255, 255, 0.91)
                );
        }

        .forgot-login-copy {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .forgot-login-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--forgot-white);
            background:
                linear-gradient(
                    135deg,
                    var(--forgot-blue),
                    #2b70ff
                );
        }

        .forgot-login-icon svg {
            width: 20px;
            height: 20px;
        }

        .forgot-login-copy strong,
        .forgot-login-copy span {
            display: block;
        }

        .forgot-login-copy strong {
            color: var(--forgot-dark);
            font-size: 12px;
        }

        .forgot-login-copy span {
            margin-top: 2px;
            color: var(--forgot-muted);
            font-size: 10px;
        }

        .forgot-login-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--forgot-blue);
            font-size: 11px;
            font-weight: 900;
            white-space: nowrap;
        }

        .forgot-login-link:hover {
            color: var(--forgot-blue-dark);
        }

        .forgot-login-link svg {
            width: 15px;
            height: 15px;
            transition: transform 0.2s ease;
        }

        .forgot-login-link:hover svg {
            transform: translateX(-3px);
        }

        /*
        |--------------------------------------------------------------------------
        | Reset Card
        |--------------------------------------------------------------------------
        */

        .forgot-card-wrapper {
            position: relative;
        }

        .forgot-card-wrapper::before {
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
                    var(--forgot-blue),
                    var(--forgot-orange)
                );
            transform: rotate(3deg);
        }

        .forgot-card {
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

        .forgot-card-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 24px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--forgot-border);
        }

        .forgot-card-logo {
            width: 54px;
            height: 54px;
            flex: 0 0 54px;
            padding: 3px;
            object-fit: contain;
            border: 1px solid var(--forgot-border);
            border-radius: 17px;
            background: var(--forgot-white);
            box-shadow:
                0 8px 20px rgba(16, 24, 40, 0.09);
        }

        .forgot-card-title {
            min-width: 0;
        }

        .forgot-card-title h2 {
            margin: 0 0 5px;
            color: var(--forgot-dark);
            font-size: 27px;
            line-height: 1.2;
        }

        .forgot-card-title p {
            margin: 0;
            color: var(--forgot-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | Verification Indicator
        |--------------------------------------------------------------------------
        */

        .forgot-verification-indicator {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 22px;
            padding: 13px 14px;
            border: 1px solid #cfe0ff;
            border-radius: 15px;
            color: var(--forgot-text);
            background: var(--forgot-blue-soft);
        }

        .forgot-verification-icon {
            width: 37px;
            height: 37px;
            flex: 0 0 37px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--forgot-blue);
            background: var(--forgot-white);
        }

        .forgot-verification-icon svg {
            width: 19px;
            height: 19px;
        }

        .forgot-verification-copy strong,
        .forgot-verification-copy span {
            display: block;
        }

        .forgot-verification-copy strong {
            color: var(--forgot-dark);
            font-size: 11px;
        }

        .forgot-verification-copy span {
            margin-top: 3px;
            color: var(--forgot-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Error Summary
        |--------------------------------------------------------------------------
        */

        .forgot-error-summary {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: 21px;
            padding: 14px;
            border: 1px solid #fecaca;
            border-radius: 15px;
            color: #991b1b;
            background: var(--forgot-red-soft);
            font-size: 11px;
            font-weight: 800;
            line-height: 1.55;
        }

        .forgot-error-summary-icon {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--forgot-white);
            background: var(--forgot-red);
            font-size: 13px;
            font-weight: 900;
        }

        /*
        |--------------------------------------------------------------------------
        | Form
        |--------------------------------------------------------------------------
        */

        .forgot-form {
            display: grid;
            gap: 17px;
        }

        .forgot-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .forgot-form-group {
            display: grid;
            gap: 7px;
        }

        .forgot-form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .forgot-form-label {
            color: var(--forgot-text);
            font-size: 12px;
            font-weight: 900;
        }

        .forgot-required {
            color: var(--forgot-orange);
        }

        .forgot-field-wrapper {
            position: relative;
        }

        .forgot-field-icon {
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

        .forgot-input {
            width: 100%;
            min-height: 51px;
            padding: 13px 15px 13px 46px;
            border: 1px solid var(--forgot-border-dark);
            border-radius: 14px;
            color: var(--forgot-dark);
            background: #fcfcfd;
            outline: none;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .forgot-password-input {
            padding-right: 53px;
        }

        .forgot-input::placeholder {
            color: #98a2b3;
        }

        .forgot-input:hover {
            border-color: #98a2b3;
        }

        .forgot-input:focus {
            border-color: var(--forgot-blue);
            background: var(--forgot-white);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.12);
        }

        .forgot-input.is-invalid {
            border-color: var(--forgot-red);
            background: #fffafa;
        }

        .forgot-input.is-invalid:focus {
            box-shadow:
                0 0 0 4px rgba(220, 38, 38, 0.10);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Toggle
        |--------------------------------------------------------------------------
        */

        .forgot-password-toggle {
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
            color: var(--forgot-muted);
            background: transparent;
            cursor: pointer;
            transform: translateY(-50%);
            transition:
                color 0.2s ease,
                background 0.2s ease;
        }

        .forgot-password-toggle:hover {
            color: var(--forgot-blue);
            background: var(--forgot-blue-soft);
        }

        .forgot-password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .forgot-password-toggle svg[hidden] {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Field Error and Help
        |--------------------------------------------------------------------------
        */

        .forgot-field-error {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--forgot-red);
            font-size: 10px;
            font-weight: 800;
        }

        .forgot-field-error::before {
            content: "!";
            width: 17px;
            height: 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--forgot-white);
            background: var(--forgot-red);
            font-size: 10px;
        }

        .forgot-field-help {
            color: var(--forgot-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Password Strength
        |--------------------------------------------------------------------------
        */

        .forgot-password-strength {
            display: grid;
            gap: 8px;
            margin-top: 1px;
        }

        .forgot-password-strength-bars {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 5px;
        }

        .forgot-password-strength-bar {
            height: 5px;
            border-radius: 999px;
            background: #eaecf0;
            transition: background 0.2s ease;
        }

        .forgot-password-strength[data-level="1"]
        .forgot-password-strength-bar:nth-child(1) {
            background: var(--forgot-red);
        }

        .forgot-password-strength[data-level="2"]
        .forgot-password-strength-bar:nth-child(-n + 2) {
            background: var(--forgot-yellow);
        }

        .forgot-password-strength[data-level="3"]
        .forgot-password-strength-bar:nth-child(-n + 3) {
            background: var(--forgot-blue);
        }

        .forgot-password-strength[data-level="4"]
        .forgot-password-strength-bar {
            background: var(--forgot-green);
        }

        .forgot-password-strength-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--forgot-muted);
            font-size: 9px;
        }

        .forgot-password-strength-label {
            font-weight: 900;
        }

        .forgot-password-strength[data-level="1"]
        .forgot-password-strength-label {
            color: var(--forgot-red);
        }

        .forgot-password-strength[data-level="2"]
        .forgot-password-strength-label {
            color: var(--forgot-yellow);
        }

        .forgot-password-strength[data-level="3"]
        .forgot-password-strength-label {
            color: var(--forgot-blue);
        }

        .forgot-password-strength[data-level="4"]
        .forgot-password-strength-label {
            color: var(--forgot-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Requirements
        |--------------------------------------------------------------------------
        */

        .forgot-password-requirements {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 7px;
            padding: 12px;
            border: 1px solid var(--forgot-border);
            border-radius: 13px;
            background: #fcfcfd;
        }

        .forgot-password-requirement {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--forgot-muted);
            font-size: 9px;
            font-weight: 750;
        }

        .forgot-password-requirement-icon {
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

        .forgot-password-requirement.valid {
            color: #166534;
        }

        .forgot-password-requirement.valid
        .forgot-password-requirement-icon {
            color: var(--forgot-white);
            background: var(--forgot-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Confirmation
        |--------------------------------------------------------------------------
        */

        .forgot-password-match {
            display: none;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 800;
        }

        .forgot-password-match.visible {
            display: flex;
        }

        .forgot-password-match.match {
            color: var(--forgot-green);
        }

        .forgot-password-match.not-match {
            color: var(--forgot-red);
        }

        .forgot-password-match-icon {
            width: 17px;
            height: 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--forgot-white);
            font-size: 10px;
            font-weight: 900;
        }

        .forgot-password-match.match
        .forgot-password-match-icon {
            background: var(--forgot-green);
        }

        .forgot-password-match.not-match
        .forgot-password-match-icon {
            background: var(--forgot-red);
        }

        /*
        |--------------------------------------------------------------------------
        | Security Notice
        |--------------------------------------------------------------------------
        */

        .forgot-security-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 13px;
            border: 1px solid #fed7aa;
            border-radius: 14px;
            color: var(--forgot-text);
            background: var(--forgot-orange-soft);
            font-size: 10px;
            line-height: 1.6;
        }

        .forgot-security-note svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            color: var(--forgot-orange);
        }

        /*
        |--------------------------------------------------------------------------
        | Submit Button
        |--------------------------------------------------------------------------
        */

        .forgot-submit-button {
            width: 100%;
            min-height: 54px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 19px;
            border: 0;
            border-radius: 15px;
            color: var(--forgot-white);
            background:
                linear-gradient(
                    135deg,
                    var(--forgot-blue),
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

        .forgot-submit-button:hover {
            background:
                linear-gradient(
                    135deg,
                    var(--forgot-blue-dark),
                    var(--forgot-blue)
                );
            box-shadow:
                0 16px 34px rgba(21, 94, 239, 0.30);
            transform: translateY(-2px);
        }

        .forgot-submit-button:disabled {
            cursor: not-allowed;
            opacity: 0.7;
            transform: none;
        }

        .forgot-submit-button svg {
            width: 18px;
            height: 18px;
        }

        .forgot-submit-spinner {
            width: 17px;
            height: 17px;
            display: none;
            border: 2px solid rgba(255, 255, 255, 0.42);
            border-top-color: var(--forgot-white);
            border-radius: 999px;
            animation: forgot-spin 0.75s linear infinite;
        }

        .forgot-submit-button.loading
        .forgot-submit-spinner {
            display: inline-block;
        }

        .forgot-submit-button.loading
        .forgot-submit-icon {
            display: none;
        }

        @keyframes forgot-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile Login Link
        |--------------------------------------------------------------------------
        */

        .forgot-mobile-login {
            display: none;
            margin-top: 21px;
            padding-top: 20px;
            border-top: 1px solid var(--forgot-border);
            color: var(--forgot-muted);
            font-size: 11px;
            text-align: center;
        }

        .forgot-mobile-login a {
            color: var(--forgot-blue);
            font-weight: 900;
        }

        .forgot-mobile-login a:hover {
            color: var(--forgot-blue-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .forgot-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .forgot-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1100px) {
            .forgot-grid {
                grid-template-columns:
                    minmax(0, 0.82fr)
                    minmax(410px, 0.88fr);
                gap: 42px;
            }

            .forgot-information h1 {
                font-size: clamp(42px, 5vw, 58px);
            }
        }

        @media (max-width: 900px) {
            .forgot-section {
                min-height: auto;
                padding: 62px 0 80px;
            }

            .forgot-grid {
                grid-template-columns: 1fr;
                gap: 45px;
            }

            .forgot-information {
                max-width: 760px;
            }

            .forgot-card-wrapper {
                width: 100%;
                max-width: 680px;
                margin: 0 auto;
            }
        }

        @media (max-width: 640px) {
            .forgot-section {
                padding: 44px 0 62px;
            }

            .forgot-section::before,
            .forgot-section::after {
                display: none;
            }

            .forgot-grid {
                gap: 34px;
            }

            .forgot-badge {
                font-size: 11px;
            }

            .forgot-information h1 {
                margin-top: 19px;
                font-size: 39px;
                letter-spacing: -1.5px;
            }

            .forgot-description {
                font-size: 14px;
            }

            .forgot-step-list {
                gap: 10px;
                margin-top: 24px;
            }

            .forgot-step-item {
                padding: 12px;
            }

            .forgot-login-note {
                display: none;
            }

            .forgot-card-wrapper::before {
                right: 25px;
                width: 73px;
                height: 24px;
            }

            .forgot-card {
                padding: 22px;
                border-radius: 23px;
            }

            .forgot-card-header {
                margin-bottom: 20px;
                padding-bottom: 19px;
            }

            .forgot-card-logo {
                width: 48px;
                height: 48px;
                flex-basis: 48px;
                border-radius: 15px;
            }

            .forgot-card-title h2 {
                font-size: 24px;
            }

            .forgot-form {
                gap: 16px;
            }

            .forgot-form-grid {
                grid-template-columns: 1fr;
            }

            .forgot-password-requirements {
                grid-template-columns: 1fr;
            }

            .forgot-password-strength-text {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

            .forgot-mobile-login {
                display: block;
            }
        }

        @media (max-width: 390px) {
            .forgot-card {
                padding: 19px;
            }

            .forgot-information h1 {
                font-size: 35px;
            }

            .forgot-card-header {
                gap: 11px;
            }

            .forgot-card-logo {
                width: 45px;
                height: 45px;
                flex-basis: 45px;
            }

            .forgot-card-title h2 {
                font-size: 22px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .forgot-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .forgot-step-item,
            .forgot-login-link svg,
            .forgot-submit-button {
                transition: none;
            }

            .forgot-submit-spinner {
                animation: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="forgot-page">
        <section class="forgot-section">
            <div class="container forgot-grid">
                {{-- Information --}}
                <div class="forgot-information forgot-reveal">
                    <span class="forgot-badge">
                        <span class="forgot-badge-icon">
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
                                <path d="M12 17v.01"/>
                                <path d="M8 8a4 4 0 1 1 7.2 2.4c-1.2 1.6-3.2 1.8-3.2 4.1"/>
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                        </span>

                        Pemulihan Akun
                    </span>

                    <h1>
                        Buat password baru untuk
                        <span>akunmu</span>
                    </h1>

                    <p class="forgot-description">
                        Masukkan email dan nomor WhatsApp yang
                        terdaftar pada akun pelanggan. Setelah data
                        sesuai, tentukan password baru yang akan
                        digunakan untuk login.
                    </p>

                    <div class="forgot-step-list">
                        <div class="forgot-step-item">
                            <span class="forgot-step-number">
                                01
                            </span>

                            <span class="forgot-step-copy">
                                <strong>
                                    Masukkan email terdaftar
                                </strong>

                                <span>
                                    Gunakan alamat email yang sama
                                    dengan email pada akun pelanggan.
                                </span>
                            </span>
                        </div>

                        <div class="forgot-step-item">
                            <span class="forgot-step-number">
                                02
                            </span>

                            <span class="forgot-step-copy">
                                <strong>
                                    Verifikasi nomor WhatsApp
                                </strong>

                                <span>
                                    Nomor WhatsApp harus sesuai dengan
                                    data yang disimpan saat registrasi.
                                </span>
                            </span>
                        </div>

                        <div class="forgot-step-item">
                            <span class="forgot-step-number">
                                03
                            </span>

                            <span class="forgot-step-copy">
                                <strong>
                                    Tentukan password baru
                                </strong>

                                <span>
                                    Gunakan password yang berbeda,
                                    kuat, dan tidak mudah ditebak.
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="forgot-login-note">
                        <div class="forgot-login-copy">
                            <span class="forgot-login-icon">
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
                                <strong>
                                    Sudah ingat password?
                                </strong>

                                <span>
                                    Kembali dan login ke akun pelanggan.
                                </span>
                            </span>
                        </div>

                        <a
                            href="{{ route('login') }}"
                            class="forgot-login-link"
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

                            Login
                        </a>
                    </div>
                </div>

                {{-- Reset Password Form --}}
                <div class="forgot-card-wrapper forgot-reveal">
                    <div class="forgot-card">
                        <div class="forgot-card-header">
                            <img
                                src="{{ $logoUrl }}"
                                alt="Logo {{ $namaWebsite }}"
                                class="forgot-card-logo"
                            >

                            <div class="forgot-card-title">
                                <h2>Reset Password</h2>

                                <p>
                                    Verifikasi data akun dan masukkan
                                    password baru untuk melanjutkan.
                                </p>
                            </div>
                        </div>

                        <div class="forgot-verification-indicator">
                            <span class="forgot-verification-icon">
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

                            <span class="forgot-verification-copy">
                                <strong>
                                    Verifikasi identitas akun
                                </strong>

                                <span>
                                    Email dan nomor WhatsApp harus
                                    cocok dengan akun yang tersimpan.
                                </span>
                            </span>
                        </div>

                        @if ($errors->any())
                            <div
                                class="forgot-error-summary"
                                role="alert"
                            >
                                <span class="forgot-error-summary-icon">
                                    !
                                </span>

                                <span>
                                    Password belum berhasil diubah.
                                    Periksa kembali data akun dan
                                    password yang dimasukkan.
                                </span>
                            </div>
                        @endif

                        <form
                            action="{{ route('password.direct-update') }}"
                            method="POST"
                            class="forgot-form"
                            id="forgotPasswordForm"
                        >
                            @csrf

                            {{-- Email dan WhatsApp --}}
                            <div class="forgot-form-grid">
                                <div class="forgot-form-group">
                                    <div class="forgot-form-label-row">
                                        <label
                                            for="email"
                                            class="forgot-form-label"
                                        >
                                            Email
                                            <span class="forgot-required">*</span>
                                        </label>
                                    </div>

                                    <div class="forgot-field-wrapper">
                                        <svg
                                            class="forgot-field-icon"
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
                                            class="forgot-input {{
                                                $errors->has('email')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="contoh@email.com"
                                            autocomplete="email"
                                            inputmode="email"
                                            autocapitalize="none"
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
                                        <span class="forgot-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="forgot-form-group">
                                    <div class="forgot-form-label-row">
                                        <label
                                            for="nomor_whatsapp"
                                            class="forgot-form-label"
                                        >
                                            Nomor WhatsApp
                                            <span class="forgot-required">*</span>
                                        </label>
                                    </div>

                                    <div class="forgot-field-wrapper">
                                        <svg
                                            class="forgot-field-icon"
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
                                            class="forgot-input {{
                                                $errors->has('nomor_whatsapp')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="08xxxxxxxxxx"
                                            maxlength="30"
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
                                        <span class="forgot-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password Baru --}}
                            <div class="forgot-form-group">
                                <div class="forgot-form-label-row">
                                    <label
                                        for="password"
                                        class="forgot-form-label"
                                    >
                                        Password Baru
                                        <span class="forgot-required">*</span>
                                    </label>
                                </div>

                                <div class="forgot-field-wrapper">
                                    <svg
                                        class="forgot-field-icon"
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
                                        class="forgot-input forgot-password-input {{
                                            $errors->has('password')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Minimal 8 karakter"
                                        minlength="8"
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
                                        class="forgot-password-toggle"
                                        data-forgot-password-toggle
                                        data-target="password"
                                        aria-label="Tampilkan password baru"
                                        aria-pressed="false"
                                    >
                                        <svg
                                            class="forgot-eye-icon"
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
                                            class="forgot-eye-off-icon"
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
                                    <span class="forgot-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <div
                                    class="forgot-password-strength"
                                    id="forgotPasswordStrength"
                                    data-level="0"
                                >
                                    <div class="forgot-password-strength-bars">
                                        <span class="forgot-password-strength-bar"></span>
                                        <span class="forgot-password-strength-bar"></span>
                                        <span class="forgot-password-strength-bar"></span>
                                        <span class="forgot-password-strength-bar"></span>
                                    </div>

                                    <div class="forgot-password-strength-text">
                                        <span>
                                            Kekuatan password:

                                            <strong class="forgot-password-strength-label">
                                                Belum diisi
                                            </strong>
                                        </span>

                                        <span>
                                            Hindari password yang mudah ditebak
                                        </span>
                                    </div>
                                </div>

                                <div class="forgot-password-requirements">
                                    <span
                                        class="forgot-password-requirement"
                                        data-forgot-requirement="length"
                                    >
                                        <span class="forgot-password-requirement-icon">
                                            ×
                                        </span>

                                        Minimal 8 karakter
                                    </span>

                                    <span
                                        class="forgot-password-requirement"
                                        data-forgot-requirement="letter"
                                    >
                                        <span class="forgot-password-requirement-icon">
                                            ×
                                        </span>

                                        Memiliki huruf
                                    </span>

                                    <span
                                        class="forgot-password-requirement"
                                        data-forgot-requirement="number"
                                    >
                                        <span class="forgot-password-requirement-icon">
                                            ×
                                        </span>

                                        Memiliki angka
                                    </span>

                                    <span
                                        class="forgot-password-requirement"
                                        data-forgot-requirement="space"
                                    >
                                        <span class="forgot-password-requirement-icon">
                                            ×
                                        </span>

                                        Tanpa spasi
                                    </span>
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="forgot-form-group">
                                <div class="forgot-form-label-row">
                                    <label
                                        for="password_confirmation"
                                        class="forgot-form-label"
                                    >
                                        Konfirmasi Password Baru
                                        <span class="forgot-required">*</span>
                                    </label>
                                </div>

                                <div class="forgot-field-wrapper">
                                    <svg
                                        class="forgot-field-icon"
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
                                        class="forgot-input forgot-password-input {{
                                            $errors->has('password_confirmation')
                                                ? 'is-invalid'
                                                : ''
                                        }}"
                                        placeholder="Ulangi password baru"
                                        minlength="8"
                                        autocomplete="new-password"
                                        aria-invalid="{{
                                            $errors->has('password_confirmation')
                                                ? 'true'
                                                : 'false'
                                        }}"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="forgot-password-toggle"
                                        data-forgot-password-toggle
                                        data-target="password_confirmation"
                                        aria-label="Tampilkan konfirmasi password"
                                        aria-pressed="false"
                                    >
                                        <svg
                                            class="forgot-eye-icon"
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
                                            class="forgot-eye-off-icon"
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

                                @error('password_confirmation')
                                    <span class="forgot-field-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <div
                                    class="forgot-password-match"
                                    id="forgotPasswordMatch"
                                >
                                    <span class="forgot-password-match-icon">
                                        ×
                                    </span>

                                    <span data-forgot-password-match-text>
                                        Konfirmasi password belum sesuai
                                    </span>
                                </div>
                            </div>

                            {{-- Security Note --}}
                            <div class="forgot-security-note">
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
                                    Setelah password berhasil diubah,
                                    gunakan password baru untuk login.
                                    Jangan berikan password kepada admin
                                    atau pihak lain.
                                </span>
                            </div>

                            {{-- Submit --}}
                            <button
                                type="submit"
                                class="forgot-submit-button"
                                id="forgotSubmitButton"
                            >
                                <span class="forgot-submit-spinner"></span>

                                <svg
                                    class="forgot-submit-icon"
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

                                <span data-forgot-button-text>
                                    Ganti Password
                                </span>
                            </button>
                        </form>

                        <div class="forgot-mobile-login">
                            Sudah ingat password?

                            <a href="{{ route('login') }}">
                                Kembali ke login
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
                '.forgot-reveal'
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
                '[data-forgot-password-toggle]'
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
                        '.forgot-eye-icon'
                    );

                    const eyeOffIcon = button.querySelector(
                        '.forgot-eye-off-icon'
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

            const confirmationInput = document.getElementById(
                'password_confirmation'
            );

            const strengthContainer = document.getElementById(
                'forgotPasswordStrength'
            );

            const strengthLabel = strengthContainer?.querySelector(
                '.forgot-password-strength-label'
            );

            const requirementElements = {
                length: document.querySelector(
                    '[data-forgot-requirement="length"]'
                ),

                letter: document.querySelector(
                    '[data-forgot-requirement="letter"]'
                ),

                number: document.querySelector(
                    '[data-forgot-requirement="number"]'
                ),

                space: document.querySelector(
                    '[data-forgot-requirement="space"]'
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
                    '.forgot-password-requirement-icon'
                );

                if (icon) {
                    icon.textContent = isValid ? '✓' : '×';
                }
            };

            const updatePasswordStrength = () => {
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

                score = Math.max(
                    1,
                    Math.min(score, 4)
                );

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
                'forgotPasswordMatch'
            );

            const passwordMatchText =
                passwordMatchElement?.querySelector(
                    '[data-forgot-password-match-text]'
                );

            const passwordMatchIcon =
                passwordMatchElement?.querySelector(
                    '.forgot-password-match-icon'
                );

            const updatePasswordMatch = () => {
                if (
                    !passwordInput
                    || !confirmationInput
                    || !passwordMatchElement
                ) {
                    return;
                }

                const confirmationValue =
                    confirmationInput.value;

                if (confirmationValue.length === 0) {
                    passwordMatchElement.classList.remove(
                        'visible',
                        'match',
                        'not-match'
                    );

                    confirmationInput.setCustomValidity('');

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

                confirmationInput.setCustomValidity(
                    isMatch
                        ? ''
                        : 'Konfirmasi password belum sesuai.'
                );
            };

            passwordInput?.addEventListener('input', () => {
                updatePasswordStrength();
                updatePasswordMatch();
            });

            confirmationInput?.addEventListener(
                'input',
                updatePasswordMatch
            );

            /*
            |--------------------------------------------------------------------------
            | WhatsApp Input
            |--------------------------------------------------------------------------
            */

            const whatsappInput = document.getElementById(
                'nomor_whatsapp'
            );

            whatsappInput?.addEventListener('input', () => {
                let value = whatsappInput.value.replace(
                    /[^0-9+]/g,
                    ''
                );

                value = value.replace(
                    /(?!^)\+/g,
                    ''
                );

                whatsappInput.value = value;
            });

            /*
            |--------------------------------------------------------------------------
            | Prevent Double Submission
            |--------------------------------------------------------------------------
            */

            const forgotPasswordForm = document.getElementById(
                'forgotPasswordForm'
            );

            const submitButton = document.getElementById(
                'forgotSubmitButton'
            );

            forgotPasswordForm?.addEventListener(
                'submit',
                (event) => {
                    updatePasswordMatch();

                    if (!forgotPasswordForm.checkValidity()) {
                        event.preventDefault();
                        forgotPasswordForm.reportValidity();
                        return;
                    }

                    if (!submitButton) {
                        return;
                    }

                    submitButton.disabled = true;
                    submitButton.classList.add('loading');

                    const buttonText = submitButton.querySelector(
                        '[data-forgot-button-text]'
                    );

                    if (buttonText) {
                        buttonText.textContent =
                            'Mengganti Password...';
                    }
                }
            );

            updatePasswordStrength();
            updatePasswordMatch();
        });
    </script>
@endpush