@extends('layouts.customer')

@section('title', 'Profil Saya - Tukang Print Dadakan')

@php
    $namaPelanggan = $user->name ?? 'Pelanggan';

    $inisialPelanggan = strtoupper(
        mb_substr($namaPelanggan, 0, 1)
    );

    $daftarRole = $user->roles
        ->pluck('name')
        ->filter()
        ->map(fn ($role) => ucwords(str_replace('_', ' ', $role)))
        ->implode(', ');

    $daftarRole = $daftarRole ?: 'Pelanggan';

    $tanggalBergabung = $user->created_at?->format('d M Y')
        ?? '-';

    $profilMemilikiError = $errors->hasAny([
        'name',
        'email',
        'nomor_whatsapp',
    ]);

    $passwordMemilikiError = $errors->hasAny([
        'current_password',
        'password',
        'password_confirmation',
    ]);
@endphp

@push('styles')
    <style>
        .profile-page {
            --profile-blue: var(--customer-blue, #155eef);
            --profile-blue-dark: var(--customer-blue-dark, #1046b8);
            --profile-blue-soft: var(--customer-blue-soft, #edf4ff);

            --profile-orange: var(--customer-orange, #f97316);
            --profile-orange-dark: var(--customer-orange-dark, #c2410c);
            --profile-orange-soft: var(--customer-orange-soft, #fff1e7);

            --profile-green: #16a34a;
            --profile-green-dark: #15803d;
            --profile-green-soft: #ecfdf3;

            --profile-yellow: #d97706;
            --profile-yellow-soft: #fffbeb;

            --profile-red: #dc2626;
            --profile-red-soft: #fff1f2;

            --profile-purple: #7c3aed;
            --profile-purple-soft: #f3e8ff;

            --profile-dark: #101828;
            --profile-text: #344054;
            --profile-muted: #667085;

            --profile-white: #ffffff;
            --profile-soft: #f7f9fc;
            --profile-border: #e4e7ec;
            --profile-border-dark: #d0d5dd;

            min-height: 100vh;
            overflow: hidden;
            background: #f8faff;
        }

        /*
        |--------------------------------------------------------------------------
        | Shared Button
        |--------------------------------------------------------------------------
        */

        .profile-button {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 17px;
            border: 1px solid transparent;
            border-radius: 14px;
            font-size: 11px;
            font-weight: 900;
            text-align: center;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease;
        }

        .profile-button:hover {
            transform: translateY(-2px);
        }

        .profile-button svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
        }

        .profile-button:disabled {
            cursor: not-allowed;
            opacity: 0.68;
            transform: none;
        }

        .profile-button-primary {
            color: var(--profile-white);
            background:
                linear-gradient(
                    135deg,
                    var(--profile-blue),
                    #2b70ff
                );
            box-shadow:
                0 11px 25px rgba(21, 94, 239, 0.23);
        }

        .profile-button-primary:hover {
            color: var(--profile-white);
            background:
                linear-gradient(
                    135deg,
                    var(--profile-blue-dark),
                    var(--profile-blue)
                );
            box-shadow:
                0 15px 32px rgba(21, 94, 239, 0.29);
        }

        .profile-button-orange {
            color: var(--profile-white);
            background:
                linear-gradient(
                    135deg,
                    var(--profile-orange),
                    #fb923c
                );
            box-shadow:
                0 11px 25px rgba(249, 115, 22, 0.23);
        }

        .profile-button-orange:hover {
            color: var(--profile-white);
            background:
                linear-gradient(
                    135deg,
                    var(--profile-orange-dark),
                    var(--profile-orange)
                );
            box-shadow:
                0 15px 32px rgba(249, 115, 22, 0.29);
        }

        .profile-button-outline {
            color: var(--profile-blue);
            border-color: #b9d0ff;
            background: var(--profile-white);
        }

        .profile-button-outline:hover {
            color: var(--profile-blue-dark);
            border-color: var(--profile-blue);
            background: var(--profile-blue-soft);
        }

        .profile-button-spinner {
            width: 17px;
            height: 17px;
            display: none;
            border: 2px solid rgba(255, 255, 255, 0.42);
            border-top-color: var(--profile-white);
            border-radius: 999px;
            animation: profile-spin 0.75s linear infinite;
        }

        .profile-button.loading .profile-button-spinner {
            display: inline-block;
        }

        .profile-button.loading .profile-button-icon {
            display: none;
        }

        @keyframes profile-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .profile-hero {
            position: relative;
            overflow: hidden;
            padding: 53px 0 77px;
            border-bottom: 1px solid var(--profile-border);
            background:
                radial-gradient(
                    circle at 8% 18%,
                    rgba(21, 94, 239, 0.17),
                    transparent 27%
                ),
                radial-gradient(
                    circle at 92% 8%,
                    rgba(249, 115, 22, 0.15),
                    transparent 25%
                ),
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #f5f8ff 100%
                );
        }

        .profile-hero::before {
            content: "";
            position: absolute;
            top: -110px;
            right: -85px;
            width: 290px;
            height: 290px;
            border: 44px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .profile-hero::after {
            content: "";
            position: absolute;
            bottom: -125px;
            left: -100px;
            width: 280px;
            height: 280px;
            border: 43px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .profile-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(330px, 0.48fr);
            gap: 46px;
            align-items: center;
        }

        .profile-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 19px;
            color: var(--profile-muted);
            font-size: 10px;
            font-weight: 800;
        }

        .profile-breadcrumb a {
            color: var(--profile-muted);
        }

        .profile-breadcrumb a:hover {
            color: var(--profile-blue);
        }

        .profile-breadcrumb svg {
            width: 13px;
            height: 13px;
        }

        .profile-breadcrumb strong {
            color: var(--profile-blue);
        }

        .profile-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 13px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--profile-orange-dark);
            background: var(--profile-orange-soft);
            font-size: 11px;
            font-weight: 900;
        }

        .profile-hero-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--profile-white);
            background: var(--profile-orange);
        }

        .profile-hero-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .profile-hero h1 {
            margin: 20px 0 15px;
            color: var(--profile-dark);
            font-size: clamp(38px, 5vw, 60px);
            line-height: 1.06;
            letter-spacing: -2px;
        }

        .profile-hero h1 span {
            position: relative;
            display: inline-block;
            color: var(--profile-blue);
        }

        .profile-hero h1 span::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 7px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.22);
            transform: rotate(-1deg);
        }

        .profile-hero-description {
            max-width: 700px;
            margin: 0;
            color: var(--profile-muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .profile-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 26px;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Account Card
        |--------------------------------------------------------------------------
        */

        .profile-account-card {
            position: relative;
            padding: 24px;
            border: 1px solid rgba(228, 231, 236, 0.94);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            box-shadow:
                0 23px 62px rgba(16, 24, 40, 0.13);
        }

        .profile-account-card::before {
            content: "";
            position: absolute;
            top: -10px;
            right: 27px;
            width: 73px;
            height: 22px;
            border-radius: 8px 8px 3px 3px;
            background:
                linear-gradient(
                    90deg,
                    var(--profile-blue),
                    var(--profile-orange)
                );
            transform: rotate(3deg);
        }

        .profile-account-main {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .profile-account-avatar {
            width: 66px;
            height: 66px;
            flex: 0 0 66px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 5px solid var(--profile-blue-soft);
            border-radius: 21px;
            color: var(--profile-white);
            background:
                linear-gradient(
                    135deg,
                    var(--profile-blue),
                    #2b70ff
                );
            box-shadow:
                0 11px 25px rgba(21, 94, 239, 0.23);
            font-size: 25px;
            font-weight: 900;
        }

        .profile-account-copy {
            min-width: 0;
        }

        .profile-account-copy strong,
        .profile-account-copy span {
            display: block;
        }

        .profile-account-copy strong {
            overflow: hidden;
            color: var(--profile-dark);
            font-size: 18px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .profile-account-copy span {
            margin-top: 4px;
            overflow: hidden;
            color: var(--profile-muted);
            font-size: 10px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .profile-account-divider {
            height: 1px;
            margin: 19px 0;
            background: var(--profile-border);
        }

        .profile-account-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
        }

        .profile-account-info {
            padding: 12px;
            border: 1px solid var(--profile-border);
            border-radius: 13px;
            background: var(--profile-soft);
        }

        .profile-account-info span,
        .profile-account-info strong {
            display: block;
        }

        .profile-account-info span {
            color: var(--profile-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .profile-account-info strong {
            margin-top: 5px;
            overflow-wrap: anywhere;
            color: var(--profile-dark);
            font-size: 10px;
            line-height: 1.45;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Layout
        |--------------------------------------------------------------------------
        */

        .profile-main {
            position: relative;
            z-index: 3;
            margin-top: -31px;
            padding-bottom: 84px;
        }

        .profile-summary-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid var(--profile-border);
            border-radius: 21px;
            background: var(--profile-white);
            box-shadow:
                0 16px 47px rgba(16, 24, 40, 0.09);
        }

        .profile-summary-item {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
            padding: 18px;
            border-right: 1px solid var(--profile-border);
        }

        .profile-summary-item:last-child {
            border-right: 0;
        }

        .profile-summary-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--profile-blue);
            background: var(--profile-blue-soft);
        }

        .profile-summary-item:nth-child(2)
        .profile-summary-icon {
            color: var(--profile-orange-dark);
            background: var(--profile-orange-soft);
        }

        .profile-summary-item:nth-child(3)
        .profile-summary-icon {
            color: var(--profile-green-dark);
            background: var(--profile-green-soft);
        }

        .profile-summary-icon svg {
            width: 20px;
            height: 20px;
        }

        .profile-summary-copy {
            min-width: 0;
        }

        .profile-summary-copy span,
        .profile-summary-copy strong {
            display: block;
        }

        .profile-summary-copy span {
            color: var(--profile-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .profile-summary-copy strong {
            margin-top: 4px;
            overflow-wrap: anywhere;
            color: var(--profile-dark);
            font-size: 11px;
            line-height: 1.4;
        }

        .profile-layout {
            display: grid;
            grid-template-columns:
                minmax(280px, 0.38fr)
                minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .profile-sidebar {
            position: sticky;
            top: 105px;
            display: grid;
            gap: 18px;
        }

        .profile-forms {
            display: grid;
            gap: 22px;
        }

        /*
        |--------------------------------------------------------------------------
        | Profile Summary Card
        |--------------------------------------------------------------------------
        */

        .profile-summary-card {
            overflow: hidden;
            border: 1px solid var(--profile-border);
            border-radius: 22px;
            background: var(--profile-white);
            box-shadow:
                0 10px 34px rgba(16, 24, 40, 0.065);
        }

        .profile-summary-cover {
            position: relative;
            height: 112px;
            overflow: hidden;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.42),
                    transparent 38%
                ),
                linear-gradient(
                    145deg,
                    var(--profile-blue-dark),
                    var(--profile-blue)
                );
        }

        .profile-summary-cover::before {
            content: "";
            position: absolute;
            top: -62px;
            right: -55px;
            width: 170px;
            height: 170px;
            border: 28px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .profile-summary-body {
            position: relative;
            padding: 0 21px 21px;
            text-align: center;
        }

        .profile-summary-avatar {
            width: 86px;
            height: 86px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: -43px;
            border: 7px solid var(--profile-white);
            border-radius: 27px;
            color: var(--profile-white);
            background:
                linear-gradient(
                    135deg,
                    var(--profile-orange),
                    #fb923c
                );
            box-shadow:
                0 13px 30px rgba(16, 24, 40, 0.17);
            font-size: 31px;
            font-weight: 900;
        }

        .profile-summary-body h2 {
            margin: 14px 0 5px;
            color: var(--profile-dark);
            font-size: 20px;
            overflow-wrap: anywhere;
        }

        .profile-summary-email,
        .profile-summary-phone {
            display: block;
            margin-top: 4px;
            color: var(--profile-muted);
            font-size: 9px;
            overflow-wrap: anywhere;
        }

        .profile-active-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 13px;
            padding: 7px 10px;
            border-radius: 999px;
            color: #166534;
            background: var(--profile-green-soft);
            font-size: 8px;
            font-weight: 900;
        }

        .profile-active-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--profile-green);
        }

        .profile-summary-meta {
            display: grid;
            gap: 9px;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--profile-border);
            text-align: left;
        }

        .profile-summary-meta-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 11px;
            border: 1px solid var(--profile-border);
            border-radius: 13px;
            background: var(--profile-soft);
        }

        .profile-summary-meta-item span {
            color: var(--profile-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .profile-summary-meta-item strong {
            max-width: 62%;
            color: var(--profile-dark);
            font-size: 9px;
            line-height: 1.45;
            text-align: right;
            overflow-wrap: anywhere;
        }

        /*
        |--------------------------------------------------------------------------
        | Security Card
        |--------------------------------------------------------------------------
        */

        .profile-security-card {
            padding: 19px;
            border: 1px solid #fed7aa;
            border-radius: 20px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.09),
                    transparent 38%
                ),
                var(--profile-orange-soft);
        }

        .profile-security-icon {
            width: 43px;
            height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 13px;
            border-radius: 14px;
            color: var(--profile-white);
            background:
                linear-gradient(
                    135deg,
                    var(--profile-orange),
                    #fb923c
                );
        }

        .profile-security-icon svg {
            width: 20px;
            height: 20px;
        }

        .profile-security-card h3 {
            margin: 0 0 6px;
            color: var(--profile-dark);
            font-size: 16px;
        }

        .profile-security-card p {
            margin: 0;
            color: var(--profile-muted);
            font-size: 9px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | Form Card
        |--------------------------------------------------------------------------
        */

        .profile-form-card {
            overflow: hidden;
            border: 1px solid var(--profile-border);
            border-radius: 22px;
            background: var(--profile-white);
            box-shadow:
                0 9px 31px rgba(16, 24, 40, 0.055);
        }

        .profile-form-header {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 22px 24px;
            border-bottom: 1px solid var(--profile-border);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.07),
                    transparent 35%
                ),
                #fcfcfd;
        }

        .profile-form-icon {
            width: 45px;
            height: 45px;
            flex: 0 0 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: var(--profile-white);
            background:
                linear-gradient(
                    135deg,
                    var(--profile-blue),
                    #2b70ff
                );
            box-shadow:
                0 8px 20px rgba(21, 94, 239, 0.19);
        }

        .profile-form-card.password-card
        .profile-form-icon {
            background:
                linear-gradient(
                    135deg,
                    var(--profile-orange),
                    #fb923c
                );
            box-shadow:
                0 8px 20px rgba(249, 115, 22, 0.19);
        }

        .profile-form-icon svg {
            width: 21px;
            height: 21px;
        }

        .profile-form-heading h2 {
            margin: 0 0 5px;
            color: var(--profile-dark);
            font-size: 20px;
        }

        .profile-form-heading p {
            margin: 0;
            color: var(--profile-muted);
            font-size: 10px;
            line-height: 1.55;
        }

        .profile-form-body {
            padding: 24px;
        }

        .profile-error-summary {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 19px;
            padding: 13px;
            border: 1px solid #fecaca;
            border-radius: 14px;
            color: #991b1b;
            background: var(--profile-red-soft);
            font-size: 10px;
            font-weight: 800;
            line-height: 1.55;
        }

        .profile-error-summary-icon {
            width: 23px;
            height: 23px;
            flex: 0 0 23px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--profile-white);
            background: var(--profile-red);
            font-size: 11px;
            font-weight: 900;
        }

        /*
        |--------------------------------------------------------------------------
        | Form Controls
        |--------------------------------------------------------------------------
        */

        .profile-form {
            display: grid;
            gap: 18px;
        }

        .profile-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .profile-form-group {
            display: grid;
            gap: 7px;
        }

        .profile-form-group.full {
            grid-column: 1 / -1;
        }

        .profile-form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .profile-form-label {
            color: var(--profile-text);
            font-size: 11px;
            font-weight: 900;
        }

        .profile-required {
            color: var(--profile-orange);
        }

        .profile-field-hint {
            color: var(--profile-muted);
            font-size: 8px;
            font-weight: 750;
        }

        .profile-field-wrapper {
            position: relative;
        }

        .profile-field-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            z-index: 2;
            width: 18px;
            height: 18px;
            color: #98a2b3;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .profile-input {
            width: 100%;
            min-height: 50px;
            padding: 11px 14px 11px 43px;
            border: 1px solid var(--profile-border-dark);
            border-radius: 14px;
            color: var(--profile-dark);
            background: #fcfcfd;
            outline: none;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .profile-input.password-input {
            padding-right: 52px;
        }

        .profile-input::placeholder {
            color: #98a2b3;
        }

        .profile-input:hover {
            border-color: #98a2b3;
        }

        .profile-input:focus {
            border-color: var(--profile-blue);
            background: var(--profile-white);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.11);
        }

        .password-card .profile-input:focus {
            border-color: var(--profile-orange);
            box-shadow:
                0 0 0 4px rgba(249, 115, 22, 0.11);
        }

        .profile-input.is-invalid {
            border-color: var(--profile-red);
            background: #fffafa;
        }

        .profile-field-error {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--profile-red);
            font-size: 9px;
            font-weight: 850;
        }

        .profile-field-error::before {
            content: "!";
            width: 16px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--profile-white);
            background: var(--profile-red);
            font-size: 9px;
        }

        .profile-field-help {
            color: var(--profile-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Password Toggle
        |--------------------------------------------------------------------------
        */

        .profile-password-toggle {
            position: absolute;
            top: 50%;
            right: 11px;
            z-index: 3;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 0;
            border-radius: 10px;
            color: var(--profile-muted);
            background: transparent;
            cursor: pointer;
            transform: translateY(-50%);
            transition:
                color 0.2s ease,
                background 0.2s ease;
        }

        .profile-password-toggle:hover {
            color: var(--profile-orange-dark);
            background: var(--profile-orange-soft);
        }

        .profile-password-toggle svg {
            width: 19px;
            height: 19px;
        }

        .profile-password-toggle svg[hidden] {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Password Strength
        |--------------------------------------------------------------------------
        */

        .profile-password-strength {
            display: grid;
            gap: 8px;
        }

        .profile-strength-bars {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 5px;
        }

        .profile-strength-bar {
            height: 5px;
            border-radius: 999px;
            background: #eaecf0;
            transition: background 0.2s ease;
        }

        .profile-password-strength[data-level="1"]
        .profile-strength-bar:nth-child(1) {
            background: var(--profile-red);
        }

        .profile-password-strength[data-level="2"]
        .profile-strength-bar:nth-child(-n + 2) {
            background: var(--profile-yellow);
        }

        .profile-password-strength[data-level="3"]
        .profile-strength-bar:nth-child(-n + 3) {
            background: var(--profile-blue);
        }

        .profile-password-strength[data-level="4"]
        .profile-strength-bar {
            background: var(--profile-green);
        }

        .profile-strength-copy {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--profile-muted);
            font-size: 8px;
        }

        .profile-strength-label {
            font-weight: 900;
        }

        .profile-password-strength[data-level="1"]
        .profile-strength-label {
            color: var(--profile-red);
        }

        .profile-password-strength[data-level="2"]
        .profile-strength-label {
            color: var(--profile-yellow);
        }

        .profile-password-strength[data-level="3"]
        .profile-strength-label {
            color: var(--profile-blue);
        }

        .profile-password-strength[data-level="4"]
        .profile-strength-label {
            color: var(--profile-green);
        }

        .profile-password-requirements {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 7px;
            padding: 12px;
            border: 1px solid var(--profile-border);
            border-radius: 13px;
            background: #fcfcfd;
        }

        .profile-password-requirement {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--profile-muted);
            font-size: 8px;
            font-weight: 800;
        }

        .profile-requirement-icon {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: #98a2b3;
            background: #eaecf0;
            font-size: 9px;
            font-weight: 900;
        }

        .profile-password-requirement.valid {
            color: #166534;
        }

        .profile-password-requirement.valid
        .profile-requirement-icon {
            color: var(--profile-white);
            background: var(--profile-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Match
        |--------------------------------------------------------------------------
        */

        .profile-password-match {
            display: none;
            align-items: center;
            gap: 6px;
            font-size: 9px;
            font-weight: 850;
        }

        .profile-password-match.visible {
            display: flex;
        }

        .profile-password-match.match {
            color: var(--profile-green-dark);
        }

        .profile-password-match.not-match {
            color: var(--profile-red);
        }

        .profile-password-match-icon {
            width: 17px;
            height: 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--profile-white);
            font-size: 9px;
            font-weight: 900;
        }

        .profile-password-match.match
        .profile-password-match-icon {
            background: var(--profile-green);
        }

        .profile-password-match.not-match
        .profile-password-match-icon {
            background: var(--profile-red);
        }

        /*
        |--------------------------------------------------------------------------
        | Notices and Footer
        |--------------------------------------------------------------------------
        */

        .profile-form-notice {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 13px;
            border: 1px solid #cfe0ff;
            border-radius: 14px;
            color: var(--profile-text);
            background: var(--profile-blue-soft);
            font-size: 9px;
            line-height: 1.55;
        }

        .password-card .profile-form-notice {
            border-color: #fed7aa;
            background: var(--profile-orange-soft);
        }

        .profile-form-notice svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            color: var(--profile-blue);
        }

        .password-card .profile-form-notice svg {
            color: var(--profile-orange);
        }

        .profile-form-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 4px;
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .profile-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .profile-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .profile-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(300px, 0.46fr);
                gap: 34px;
            }

            .profile-layout {
                grid-template-columns:
                    minmax(250px, 0.36fr)
                    minmax(0, 1fr);
            }
        }

        @media (max-width: 900px) {
            .profile-hero-grid,
            .profile-layout {
                grid-template-columns: 1fr;
            }

            .profile-account-card {
                max-width: 650px;
            }

            .profile-sidebar {
                position: static;
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(250px, 0.55fr);
                align-items: start;
            }
        }

        @media (max-width: 720px) {
            .profile-summary-strip {
                grid-template-columns: 1fr;
            }

            .profile-summary-item {
                border-right: 0;
                border-bottom: 1px solid var(--profile-border);
            }

            .profile-summary-item:last-child {
                border-bottom: 0;
            }

            .profile-sidebar {
                grid-template-columns: 1fr;
            }

            .profile-form-grid {
                grid-template-columns: 1fr;
            }

            .profile-form-group.full {
                grid-column: auto;
            }
        }

        @media (max-width: 640px) {
            .profile-hero {
                padding: 39px 0 65px;
            }

            .profile-hero::before,
            .profile-hero::after {
                display: none;
            }

            .profile-hero h1 {
                margin-top: 17px;
                font-size: 36px;
                letter-spacing: -1.4px;
            }

            .profile-hero-description {
                font-size: 13px;
            }

            .profile-hero-actions {
                flex-direction: column;
            }

            .profile-hero-actions .profile-button {
                width: 100%;
            }

            .profile-account-card {
                padding: 20px;
                border-radius: 21px;
            }

            .profile-account-avatar {
                width: 58px;
                height: 58px;
                flex-basis: 58px;
                border-radius: 18px;
                font-size: 22px;
            }

            .profile-main {
                margin-top: -27px;
                padding-bottom: 63px;
            }

            .profile-summary-strip {
                border-radius: 18px;
            }

            .profile-form-card,
            .profile-summary-card {
                border-radius: 19px;
            }

            .profile-form-header {
                padding: 19px;
            }

            .profile-form-icon {
                width: 41px;
                height: 41px;
                flex-basis: 41px;
                border-radius: 13px;
            }

            .profile-form-heading h2 {
                font-size: 18px;
            }

            .profile-form-body {
                padding: 19px;
            }

            .profile-password-requirements {
                grid-template-columns: 1fr;
            }

            .profile-strength-copy {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

            .profile-form-footer {
                align-items: stretch;
                flex-direction: column;
            }

            .profile-form-footer .profile-button {
                width: 100%;
            }
        }

        @media (max-width: 390px) {
            .profile-hero h1 {
                font-size: 33px;
            }

            .profile-account-main {
                align-items: flex-start;
            }

            .profile-account-grid {
                grid-template-columns: 1fr;
            }

            .profile-summary-avatar {
                width: 78px;
                height: 78px;
                margin-top: -39px;
                border-radius: 24px;
                font-size: 28px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .profile-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .profile-button,
            .profile-input,
            .profile-password-toggle {
                transition: none;
            }

            .profile-button-spinner {
                animation: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="profile-page">
        {{-- Hero --}}
        <section class="profile-hero">
            <div class="container profile-hero-grid">
                <div class="profile-reveal">
                    <nav
                        class="profile-breadcrumb"
                        aria-label="Breadcrumb"
                    >
                        <a href="{{ route('customer.dashboard') }}">
                            Dashboard
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

                        <strong>Profil Saya</strong>
                    </nav>

                    <span class="profile-hero-badge">
                        <span class="profile-hero-badge-icon">
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
                        </span>

                        Profil Pelanggan
                    </span>

                    <h1>
                        Kelola informasi dan
                        <span>keamanan akun</span>
                    </h1>

                    <p class="profile-hero-description">
                        Perbarui nama, email, nomor WhatsApp, dan
                        password yang digunakan untuk login serta
                        informasi pemesanan.
                    </p>

                    <div class="profile-hero-actions">
                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="profile-button profile-button-outline"
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

                            Kembali ke Dashboard
                        </a>

                        <a
                            href="{{ route('customer.pesanan.index') }}"
                            class="profile-button profile-button-primary"
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
                                <path d="M3 7h18"/>
                                <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                <path d="M8 11h8"/>
                                <path d="M8 15h5"/>
                            </svg>

                            Pesanan Saya
                        </a>
                    </div>
                </div>

                <aside class="profile-account-card profile-reveal">
                    <div class="profile-account-main">
                        <span
                            class="profile-account-avatar"
                            id="profileHeroAvatar"
                        >
                            {{ $inisialPelanggan }}
                        </span>

                        <div class="profile-account-copy">
                            <strong id="profileHeroName">
                                {{ $namaPelanggan }}
                            </strong>

                            <span id="profileHeroEmail">
                                {{ $user->email }}
                            </span>

                            <span id="profileHeroPhone">
                                {{
                                    $user->nomor_whatsapp
                                    ?? 'Nomor WhatsApp belum diisi'
                                }}
                            </span>
                        </div>
                    </div>

                    <div class="profile-account-divider"></div>

                    <div class="profile-account-grid">
                        <div class="profile-account-info">
                            <span>Role</span>
                            <strong>{{ $daftarRole }}</strong>
                        </div>

                        <div class="profile-account-info">
                            <span>Bergabung</span>
                            <strong>{{ $tanggalBergabung }}</strong>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        {{-- Main --}}
        <main class="profile-main">
            <div class="container">
                {{-- Summary --}}
                <section class="profile-summary-strip profile-reveal">
                    <div class="profile-summary-item">
                        <span class="profile-summary-icon">
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
                        </span>

                        <span class="profile-summary-copy">
                            <span>Nama Akun</span>
                            <strong>{{ $namaPelanggan }}</strong>
                        </span>
                    </div>

                    <div class="profile-summary-item">
                        <span class="profile-summary-icon">
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
                                <rect
                                    width="18"
                                    height="14"
                                    x="3"
                                    y="5"
                                    rx="2"
                                />
                                <path d="m3 7 9 6 9-6"/>
                            </svg>
                        </span>

                        <span class="profile-summary-copy">
                            <span>Email Login</span>
                            <strong>{{ $user->email }}</strong>
                        </span>
                    </div>

                    <div class="profile-summary-item">
                        <span class="profile-summary-icon">
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

                        <span class="profile-summary-copy">
                            <span>Status Akun</span>
                            <strong>Aktif dan Terdaftar</strong>
                        </span>
                    </div>
                </section>

                <div class="profile-layout">
                    {{-- Sidebar --}}
                    <aside class="profile-sidebar">
                        <section class="profile-summary-card profile-reveal">
                            <div class="profile-summary-cover"></div>

                            <div class="profile-summary-body">
                                <span
                                    class="profile-summary-avatar"
                                    id="profileSidebarAvatar"
                                >
                                    {{ $inisialPelanggan }}
                                </span>

                                <h2 id="profileSidebarName">
                                    {{ $namaPelanggan }}
                                </h2>

                                <span
                                    class="profile-summary-email"
                                    id="profileSidebarEmail"
                                >
                                    {{ $user->email }}
                                </span>

                                <span
                                    class="profile-summary-phone"
                                    id="profileSidebarPhone"
                                >
                                    {{
                                        $user->nomor_whatsapp
                                        ?? 'Nomor WhatsApp belum diisi'
                                    }}
                                </span>

                                <span class="profile-active-badge">
                                    Akun Aktif
                                </span>

                                <div class="profile-summary-meta">
                                    <div class="profile-summary-meta-item">
                                        <span>Role</span>
                                        <strong>{{ $daftarRole }}</strong>
                                    </div>

                                    <div class="profile-summary-meta-item">
                                        <span>Bergabung</span>

                                        <strong>
                                            {{ $tanggalBergabung }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="profile-security-card profile-reveal">
                            <span class="profile-security-icon">
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

                            <h3>Keamanan Akun</h3>

                            <p>
                                Gunakan password unik dan jangan
                                membagikannya kepada admin atau pihak
                                lain. Perbarui password apabila merasa
                                akun pernah diakses tanpa izin.
                            </p>
                        </section>
                    </aside>

                    {{-- Forms --}}
                    <div class="profile-forms">
                        {{-- Profile Information --}}
                        <section class="profile-form-card profile-reveal">
                            <div class="profile-form-header">
                                <span class="profile-form-icon">
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
                                        <path d="M12 20h9"/>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                    </svg>
                                </span>

                                <div class="profile-form-heading">
                                    <h2>Informasi Profil</h2>

                                    <p>
                                        Data ini digunakan pada akun
                                        pelanggan dan pesanan baru.
                                    </p>
                                </div>
                            </div>

                            <div class="profile-form-body">
                                @if ($profilMemilikiError)
                                    <div
                                        class="profile-error-summary"
                                        role="alert"
                                    >
                                        <span class="profile-error-summary-icon">
                                            !
                                        </span>

                                        <span>
                                            Informasi profil belum
                                            berhasil disimpan. Periksa
                                            kembali kolom yang ditandai.
                                        </span>
                                    </div>
                                @endif

                                <form
                                    action="{{ route('customer.profil.update') }}"
                                    method="POST"
                                    class="profile-form"
                                    id="profileInformationForm"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="profile-form-grid">
                                        <div class="profile-form-group full">
                                            <div class="profile-form-label-row">
                                                <label
                                                    for="name"
                                                    class="profile-form-label"
                                                >
                                                    Nama Lengkap
                                                    <span class="profile-required">*</span>
                                                </label>

                                                <span class="profile-field-hint">
                                                    Maksimal 150 karakter
                                                </span>
                                            </div>

                                            <div class="profile-field-wrapper">
                                                <svg
                                                    class="profile-field-icon"
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
                                                    value="{{ old('name', $user->name) }}"
                                                    class="profile-input {{
                                                        $errors->has('name')
                                                            ? 'is-invalid'
                                                            : ''
                                                    }}"
                                                    maxlength="150"
                                                    placeholder="Masukkan nama lengkap"
                                                    autocomplete="name"
                                                    aria-invalid="{{
                                                        $errors->has('name')
                                                            ? 'true'
                                                            : 'false'
                                                    }}"
                                                    required
                                                >
                                            </div>

                                            @error('name')
                                                <span class="profile-field-error">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="profile-form-group">
                                            <div class="profile-form-label-row">
                                                <label
                                                    for="email"
                                                    class="profile-form-label"
                                                >
                                                    Email
                                                    <span class="profile-required">*</span>
                                                </label>
                                            </div>

                                            <div class="profile-field-wrapper">
                                                <svg
                                                    class="profile-field-icon"
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
                                                    value="{{ old('email', $user->email) }}"
                                                    class="profile-input {{
                                                        $errors->has('email')
                                                            ? 'is-invalid'
                                                            : ''
                                                    }}"
                                                    maxlength="150"
                                                    placeholder="contoh@email.com"
                                                    autocomplete="email"
                                                    inputmode="email"
                                                    autocapitalize="none"
                                                    aria-invalid="{{
                                                        $errors->has('email')
                                                            ? 'true'
                                                            : 'false'
                                                    }}"
                                                    required
                                                >
                                            </div>

                                            @error('email')
                                                <span class="profile-field-error">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="profile-form-group">
                                            <div class="profile-form-label-row">
                                                <label
                                                    for="nomor_whatsapp"
                                                    class="profile-form-label"
                                                >
                                                    Nomor WhatsApp
                                                    <span class="profile-required">*</span>
                                                </label>
                                            </div>

                                            <div class="profile-field-wrapper">
                                                <svg
                                                    class="profile-field-icon"
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
                                                    value="{{ old('nomor_whatsapp', $user->nomor_whatsapp) }}"
                                                    class="profile-input {{
                                                        $errors->has('nomor_whatsapp')
                                                            ? 'is-invalid'
                                                            : ''
                                                    }}"
                                                    maxlength="30"
                                                    placeholder="Contoh: 081234567890"
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
                                                <span class="profile-field-error">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="profile-form-notice">
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
                                            Gunakan email dan nomor
                                            WhatsApp aktif. Informasi
                                            tersebut digunakan untuk
                                            login, pemulihan password,
                                            dan komunikasi terkait
                                            pesanan.
                                        </span>
                                    </div>

                                    <div class="profile-form-footer">
                                        <button
                                            type="submit"
                                            class="profile-button profile-button-primary"
                                            id="profileInformationSubmit"
                                        >
                                            <span class="profile-button-spinner"></span>

                                            <svg
                                                class="profile-button-icon"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                aria-hidden="true"
                                            >
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                                                <path d="M17 21v-8H7v8"/>
                                                <path d="M7 3v5h8"/>
                                            </svg>

                                            <span data-profile-submit-text>
                                                Simpan Informasi Profil
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </section>

                        {{-- Password --}}
                        <section class="profile-form-card password-card profile-reveal">
                            <div class="profile-form-header">
                                <span class="profile-form-icon">
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
                                        <rect
                                            width="16"
                                            height="12"
                                            x="4"
                                            y="10"
                                            rx="2"
                                        />
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                    </svg>
                                </span>

                                <div class="profile-form-heading">
                                    <h2>Ganti Password</h2>

                                    <p>
                                        Verifikasi password saat ini
                                        sebelum membuat password baru.
                                    </p>
                                </div>
                            </div>

                            <div class="profile-form-body">
                                @if ($passwordMemilikiError)
                                    <div
                                        class="profile-error-summary"
                                        role="alert"
                                    >
                                        <span class="profile-error-summary-icon">
                                            !
                                        </span>

                                        <span>
                                            Password belum berhasil
                                            diperbarui. Periksa password
                                            saat ini dan konfirmasi
                                            password baru.
                                        </span>
                                    </div>
                                @endif

                                <form
                                    action="{{ route('customer.profil.password.update') }}"
                                    method="POST"
                                    class="profile-form"
                                    id="profilePasswordForm"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="profile-form-group">
                                        <div class="profile-form-label-row">
                                            <label
                                                for="current_password"
                                                class="profile-form-label"
                                            >
                                                Password Saat Ini
                                                <span class="profile-required">*</span>
                                            </label>
                                        </div>

                                        <div class="profile-field-wrapper">
                                            <svg
                                                class="profile-field-icon"
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
                                                id="current_password"
                                                name="current_password"
                                                class="profile-input password-input {{
                                                    $errors->has('current_password')
                                                        ? 'is-invalid'
                                                        : ''
                                                }}"
                                                placeholder="Masukkan password saat ini"
                                                autocomplete="current-password"
                                                aria-invalid="{{
                                                    $errors->has('current_password')
                                                        ? 'true'
                                                        : 'false'
                                                }}"
                                                required
                                            >

                                            <button
                                                type="button"
                                                class="profile-password-toggle"
                                                data-profile-password-toggle
                                                data-target="current_password"
                                                aria-label="Tampilkan password saat ini"
                                                aria-pressed="false"
                                            >
                                                <svg
                                                    class="profile-eye-icon"
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
                                                    class="profile-eye-off-icon"
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

                                        @error('current_password')
                                            <span class="profile-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="profile-form-grid">
                                        <div class="profile-form-group">
                                            <div class="profile-form-label-row">
                                                <label
                                                    for="password"
                                                    class="profile-form-label"
                                                >
                                                    Password Baru
                                                    <span class="profile-required">*</span>
                                                </label>
                                            </div>

                                            <div class="profile-field-wrapper">
                                                <svg
                                                    class="profile-field-icon"
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
                                                </svg>

                                                <input
                                                    type="password"
                                                    id="password"
                                                    name="password"
                                                    class="profile-input password-input {{
                                                        $errors->has('password')
                                                            ? 'is-invalid'
                                                            : ''
                                                    }}"
                                                    minlength="8"
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
                                                    class="profile-password-toggle"
                                                    data-profile-password-toggle
                                                    data-target="password"
                                                    aria-label="Tampilkan password baru"
                                                    aria-pressed="false"
                                                >
                                                    <svg
                                                        class="profile-eye-icon"
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
                                                        class="profile-eye-off-icon"
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
                                                <span class="profile-field-error">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="profile-form-group">
                                            <div class="profile-form-label-row">
                                                <label
                                                    for="password_confirmation"
                                                    class="profile-form-label"
                                                >
                                                    Konfirmasi Password
                                                    <span class="profile-required">*</span>
                                                </label>
                                            </div>

                                            <div class="profile-field-wrapper">
                                                <svg
                                                    class="profile-field-icon"
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
                                                    class="profile-input password-input {{
                                                        $errors->has('password_confirmation')
                                                            ? 'is-invalid'
                                                            : ''
                                                    }}"
                                                    minlength="8"
                                                    placeholder="Ulangi password baru"
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
                                                    class="profile-password-toggle"
                                                    data-profile-password-toggle
                                                    data-target="password_confirmation"
                                                    aria-label="Tampilkan konfirmasi password"
                                                    aria-pressed="false"
                                                >
                                                    <svg
                                                        class="profile-eye-icon"
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
                                                        class="profile-eye-off-icon"
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
                                                <span class="profile-field-error">
                                                    {{ $message }}
                                                </span>
                                            @enderror

                                            <div
                                                class="profile-password-match"
                                                id="profilePasswordMatch"
                                            >
                                                <span class="profile-password-match-icon">
                                                    ×
                                                </span>

                                                <span data-profile-match-text>
                                                    Konfirmasi password belum sesuai
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="profile-password-strength"
                                        id="profilePasswordStrength"
                                        data-level="0"
                                    >
                                        <div class="profile-strength-bars">
                                            <span class="profile-strength-bar"></span>
                                            <span class="profile-strength-bar"></span>
                                            <span class="profile-strength-bar"></span>
                                            <span class="profile-strength-bar"></span>
                                        </div>

                                        <div class="profile-strength-copy">
                                            <span>
                                                Kekuatan password:

                                                <strong class="profile-strength-label">
                                                    Belum diisi
                                                </strong>
                                            </span>

                                            <span>
                                                Gunakan password yang sulit ditebak
                                            </span>
                                        </div>
                                    </div>

                                    <div class="profile-password-requirements">
                                        <span
                                            class="profile-password-requirement"
                                            data-profile-requirement="length"
                                        >
                                            <span class="profile-requirement-icon">
                                                ×
                                            </span>

                                            Minimal 8 karakter
                                        </span>

                                        <span
                                            class="profile-password-requirement"
                                            data-profile-requirement="letter"
                                        >
                                            <span class="profile-requirement-icon">
                                                ×
                                            </span>

                                            Memiliki huruf
                                        </span>

                                        <span
                                            class="profile-password-requirement"
                                            data-profile-requirement="number"
                                        >
                                            <span class="profile-requirement-icon">
                                                ×
                                            </span>

                                            Memiliki angka
                                        </span>

                                        <span
                                            class="profile-password-requirement"
                                            data-profile-requirement="space"
                                        >
                                            <span class="profile-requirement-icon">
                                                ×
                                            </span>

                                            Tanpa spasi
                                        </span>
                                    </div>

                                    <div class="profile-form-notice">
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
                                            Setelah password diperbarui,
                                            gunakan password baru untuk
                                            proses login berikutnya.
                                            Jangan gunakan password yang
                                            sama dengan akun lain.
                                        </span>
                                    </div>

                                    <div class="profile-form-footer">
                                        <button
                                            type="submit"
                                            class="profile-button profile-button-orange"
                                            id="profilePasswordSubmit"
                                        >
                                            <span class="profile-button-spinner"></span>

                                            <svg
                                                class="profile-button-icon"
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

                                            <span data-password-submit-text>
                                                Simpan Password Baru
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /*
            |--------------------------------------------------------------------------
            | Profile Live Preview
            |--------------------------------------------------------------------------
            */

            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const whatsappInput = document.getElementById(
                'nomor_whatsapp'
            );

            const previewTargets = {
                name: [
                    document.getElementById('profileHeroName'),
                    document.getElementById('profileSidebarName'),
                ],
                email: [
                    document.getElementById('profileHeroEmail'),
                    document.getElementById('profileSidebarEmail'),
                ],
                phone: [
                    document.getElementById('profileHeroPhone'),
                    document.getElementById('profileSidebarPhone'),
                ],
                avatar: [
                    document.getElementById('profileHeroAvatar'),
                    document.getElementById('profileSidebarAvatar'),
                ],
            };

            const updateTextTargets = (targets, value, fallback) => {
                targets.forEach((target) => {
                    if (!target) {
                        return;
                    }

                    target.textContent = value.trim() || fallback;
                });
            };

            const updateProfilePreview = () => {
                const nameValue = nameInput?.value || '';
                const emailValue = emailInput?.value || '';
                const phoneValue = whatsappInput?.value || '';

                updateTextTargets(
                    previewTargets.name,
                    nameValue,
                    'Pelanggan'
                );

                updateTextTargets(
                    previewTargets.email,
                    emailValue,
                    'Email belum diisi'
                );

                updateTextTargets(
                    previewTargets.phone,
                    phoneValue,
                    'Nomor WhatsApp belum diisi'
                );

                const initial = (
                    nameValue.trim().charAt(0) || 'P'
                ).toUpperCase();

                previewTargets.avatar.forEach((target) => {
                    if (target) {
                        target.textContent = initial;
                    }
                });
            };

            nameInput?.addEventListener(
                'input',
                updateProfilePreview
            );

            emailInput?.addEventListener(
                'input',
                updateProfilePreview
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

                updateProfilePreview();
            });

            /*
            |--------------------------------------------------------------------------
            | Password Visibility
            |--------------------------------------------------------------------------
            */

            const passwordToggleButtons = document.querySelectorAll(
                '[data-profile-password-toggle]'
            );

            passwordToggleButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const input = document.getElementById(
                        button.dataset.target
                    );

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
                        '.profile-eye-icon'
                    );

                    const eyeOffIcon = button.querySelector(
                        '.profile-eye-off-icon'
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
                'profilePasswordStrength'
            );

            const strengthLabel = strengthContainer?.querySelector(
                '.profile-strength-label'
            );

            const requirementElements = {
                length: document.querySelector(
                    '[data-profile-requirement="length"]'
                ),
                letter: document.querySelector(
                    '[data-profile-requirement="letter"]'
                ),
                number: document.querySelector(
                    '[data-profile-requirement="number"]'
                ),
                space: document.querySelector(
                    '[data-profile-requirement="space"]'
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
                    '.profile-requirement-icon'
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

            const passwordMatch = document.getElementById(
                'profilePasswordMatch'
            );

            const passwordMatchText = passwordMatch?.querySelector(
                '[data-profile-match-text]'
            );

            const passwordMatchIcon = passwordMatch?.querySelector(
                '.profile-password-match-icon'
            );

            const updatePasswordMatch = () => {
                if (
                    !passwordInput
                    || !confirmationInput
                    || !passwordMatch
                ) {
                    return;
                }

                if (!confirmationInput.value) {
                    passwordMatch.classList.remove(
                        'visible',
                        'match',
                        'not-match'
                    );

                    confirmationInput.setCustomValidity('');

                    return;
                }

                const passwordsMatch =
                    passwordInput.value === confirmationInput.value;

                passwordMatch.classList.add('visible');

                passwordMatch.classList.toggle(
                    'match',
                    passwordsMatch
                );

                passwordMatch.classList.toggle(
                    'not-match',
                    !passwordsMatch
                );

                if (passwordMatchText) {
                    passwordMatchText.textContent =
                        passwordsMatch
                            ? 'Konfirmasi password sudah sesuai'
                            : 'Konfirmasi password belum sesuai';
                }

                if (passwordMatchIcon) {
                    passwordMatchIcon.textContent =
                        passwordsMatch ? '✓' : '×';
                }

                confirmationInput.setCustomValidity(
                    passwordsMatch
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
            | Prevent Double Submission
            |--------------------------------------------------------------------------
            */

            const informationForm = document.getElementById(
                'profileInformationForm'
            );

            const informationSubmit = document.getElementById(
                'profileInformationSubmit'
            );

            informationForm?.addEventListener(
                'submit',
                (event) => {
                    if (!informationForm.checkValidity()) {
                        event.preventDefault();
                        informationForm.reportValidity();
                        return;
                    }

                    if (!informationSubmit) {
                        return;
                    }

                    informationSubmit.disabled = true;
                    informationSubmit.classList.add('loading');

                    const submitText =
                        informationSubmit.querySelector(
                            '[data-profile-submit-text]'
                        );

                    if (submitText) {
                        submitText.textContent =
                            'Menyimpan Profil...';
                    }
                }
            );

            const passwordForm = document.getElementById(
                'profilePasswordForm'
            );

            const passwordSubmit = document.getElementById(
                'profilePasswordSubmit'
            );

            passwordForm?.addEventListener(
                'submit',
                (event) => {
                    updatePasswordMatch();

                    if (!passwordForm.checkValidity()) {
                        event.preventDefault();
                        passwordForm.reportValidity();
                        return;
                    }

                    if (!passwordSubmit) {
                        return;
                    }

                    passwordSubmit.disabled = true;
                    passwordSubmit.classList.add('loading');

                    const submitText =
                        passwordSubmit.querySelector(
                            '[data-password-submit-text]'
                        );

                    if (submitText) {
                        submitText.textContent =
                            'Memperbarui Password...';
                    }
                }
            );

            /*
            |--------------------------------------------------------------------------
            | Reveal Animation
            |--------------------------------------------------------------------------
            */

            const revealElements = document.querySelectorAll(
                '.profile-reveal'
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

                            entry.target.classList.add(
                                'is-visible'
                            );

                            currentObserver.unobserve(
                                entry.target
                            );
                        });
                    },
                    {
                        threshold: 0.08,
                        rootMargin: '0px 0px -35px 0px',
                    }
                );

                revealElements.forEach((element) => {
                    observer.observe(element);
                });
            }

            updateProfilePreview();
            updatePasswordStrength();
            updatePasswordMatch();
        });
    </script>
@endpush