@extends('layouts.customer')

@section('title', 'Pesanan Saya - Tukang Print Dadakan')

@php
    $adaFilter = request()->filled('q')
        || request()->filled('status');

    $labelStatusPesanan = static function (?string $status): string {
        return match ($status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'siap_diambil' => 'Siap Diambil',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => $status
                ? ucwords(str_replace('_', ' ', $status))
                : 'Belum Diketahui',
        };
    };

    $classStatusPesanan = static function (?string $status): string {
        return match ($status) {
            'menunggu_verifikasi' => 'waiting',
            'diproses' => 'processing',
            'siap_diambil' => 'ready',
            'selesai' => 'completed',
            'dibatalkan' => 'cancelled',
            default => 'neutral',
        };
    };

    $labelStatusPembayaran = static function (?string $status): string {
        return match ($status) {
            'belum_bayar' => 'Belum Bayar',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'pending' => 'Menunggu Pembayaran',
            'lunas', 'settlement', 'capture' => 'Lunas',
            'ditolak', 'deny', 'cancel', 'expire' => 'Ditolak',
            default => 'Belum Ada Pembayaran',
        };
    };

    $classStatusPembayaran = static function (?string $status): string {
        return match ($status) {
            'belum_bayar', 'pending' => 'unpaid',
            'menunggu_verifikasi' => 'verification',
            'lunas', 'settlement', 'capture' => 'paid',
            'ditolak', 'deny', 'cancel', 'expire' => 'rejected',
            default => 'neutral',
        };
    };

    $labelMetodePembayaran = static function (?string $metode): string {
        return match ($metode) {
            'cash' => 'Cash',
            'transfer' => 'Online via Midtrans',
            default => $metode
                ? ucwords(str_replace('_', ' ', $metode))
                : 'Belum Ditentukan',
        };
    };

    $totalHasil = $pesanans->total();
    $jumlahDitampilkan = $pesanans->count();
@endphp

@push('styles')
    <style>
        .orders-index-page {
            --orders-blue: var(--customer-blue, #155eef);
            --orders-blue-dark: var(--customer-blue-dark, #1046b8);
            --orders-blue-soft: var(--customer-blue-soft, #edf4ff);

            --orders-orange: var(--customer-orange, #f97316);
            --orders-orange-dark: var(--customer-orange-dark, #c2410c);
            --orders-orange-soft: var(--customer-orange-soft, #fff1e7);

            --orders-green: #16a34a;
            --orders-green-dark: #15803d;
            --orders-green-soft: #ecfdf3;

            --orders-yellow: #d97706;
            --orders-yellow-soft: #fffbeb;

            --orders-red: #dc2626;
            --orders-red-soft: #fff1f2;

            --orders-purple: #7c3aed;
            --orders-purple-soft: #f3e8ff;

            --orders-dark: #101828;
            --orders-text: #344054;
            --orders-muted: #667085;
            --orders-white: #ffffff;
            --orders-soft: #f7f9fc;
            --orders-border: #e4e7ec;
            --orders-border-dark: #d0d5dd;

            min-height: 100vh;
            overflow: hidden;
            background: #f8faff;
        }

        /*
        |--------------------------------------------------------------------------
        | Shared
        |--------------------------------------------------------------------------
        */

        .orders-button {
            min-height: 47px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 17px;
            border: 1px solid transparent;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 900;
            text-align: center;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease;
        }

        .orders-button:hover {
            transform: translateY(-2px);
        }

        .orders-button svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
        }

        .orders-button-primary {
            color: var(--orders-white);
            background:
                linear-gradient(
                    135deg,
                    var(--orders-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 24px rgba(21, 94, 239, 0.23);
        }

        .orders-button-primary:hover {
            color: var(--orders-white);
            background:
                linear-gradient(
                    135deg,
                    var(--orders-blue-dark),
                    var(--orders-blue)
                );
            box-shadow:
                0 14px 31px rgba(21, 94, 239, 0.29);
        }

        .orders-button-orange {
            color: var(--orders-white);
            background:
                linear-gradient(
                    135deg,
                    var(--orders-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 24px rgba(249, 115, 22, 0.23);
        }

        .orders-button-orange:hover {
            color: var(--orders-white);
            background:
                linear-gradient(
                    135deg,
                    var(--orders-orange-dark),
                    var(--orders-orange)
                );
            box-shadow:
                0 14px 31px rgba(249, 115, 22, 0.29);
        }

        .orders-button-outline {
            color: var(--orders-blue);
            border-color: #b9d0ff;
            background: var(--orders-white);
        }

        .orders-button-outline:hover {
            color: var(--orders-blue-dark);
            border-color: var(--orders-blue);
            background: var(--orders-blue-soft);
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .orders-hero {
            position: relative;
            overflow: hidden;
            padding: 53px 0 75px;
            border-bottom: 1px solid var(--orders-border);
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

        .orders-hero::before {
            content: "";
            position: absolute;
            top: -110px;
            right: -85px;
            width: 290px;
            height: 290px;
            border: 44px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .orders-hero::after {
            content: "";
            position: absolute;
            bottom: -125px;
            left: -100px;
            width: 280px;
            height: 280px;
            border: 43px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .orders-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1.08fr)
                minmax(320px, 0.5fr);
            gap: 46px;
            align-items: center;
        }

        .orders-hero-content {
            max-width: 770px;
        }

        .orders-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 19px;
            color: var(--orders-muted);
            font-size: 10px;
            font-weight: 800;
        }

        .orders-breadcrumb a {
            color: var(--orders-muted);
        }

        .orders-breadcrumb a:hover {
            color: var(--orders-blue);
        }

        .orders-breadcrumb svg {
            width: 13px;
            height: 13px;
        }

        .orders-breadcrumb strong {
            color: var(--orders-blue);
        }

        .orders-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 13px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--orders-orange-dark);
            background: var(--orders-orange-soft);
            font-size: 11px;
            font-weight: 900;
        }

        .orders-hero-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--orders-white);
            background: var(--orders-orange);
        }

        .orders-hero-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .orders-hero h1 {
            margin: 20px 0 15px;
            color: var(--orders-dark);
            font-size: clamp(38px, 5vw, 60px);
            line-height: 1.06;
            letter-spacing: -2px;
        }

        .orders-hero h1 span {
            position: relative;
            display: inline-block;
            color: var(--orders-blue);
        }

        .orders-hero h1 span::after {
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

        .orders-hero-description {
            max-width: 690px;
            margin: 0;
            color: var(--orders-muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .orders-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 11px;
            margin-top: 26px;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Summary
        |--------------------------------------------------------------------------
        */

        .orders-hero-summary {
            position: relative;
            padding: 24px;
            border: 1px solid rgba(228, 231, 236, 0.93);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.93);
            backdrop-filter: blur(16px);
            box-shadow:
                0 23px 62px rgba(16, 24, 40, 0.13);
        }

        .orders-hero-summary::before {
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
                    var(--orders-blue),
                    var(--orders-orange)
                );
            transform: rotate(3deg);
        }

        .orders-hero-summary-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .orders-hero-summary-icon {
            width: 47px;
            height: 47px;
            flex: 0 0 47px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: var(--orders-white);
            background:
                linear-gradient(
                    135deg,
                    var(--orders-blue),
                    #2b70ff
                );
            box-shadow:
                0 9px 21px rgba(21, 94, 239, 0.2);
        }

        .orders-hero-summary-icon svg {
            width: 23px;
            height: 23px;
        }

        .orders-hero-summary-header strong,
        .orders-hero-summary-header span {
            display: block;
        }

        .orders-hero-summary-header strong {
            color: var(--orders-dark);
            font-size: 16px;
        }

        .orders-hero-summary-header span {
            margin-top: 3px;
            color: var(--orders-muted);
            font-size: 10px;
        }

        .orders-hero-summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .orders-hero-summary-item {
            padding: 14px;
            border: 1px solid var(--orders-border);
            border-radius: 14px;
            background: var(--orders-soft);
        }

        .orders-hero-summary-item span,
        .orders-hero-summary-item strong {
            display: block;
        }

        .orders-hero-summary-item span {
            color: var(--orders-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.055em;
        }

        .orders-hero-summary-item strong {
            margin-top: 6px;
            color: var(--orders-dark);
            font-size: 22px;
            line-height: 1;
        }

        .orders-hero-summary-item:last-child strong {
            color: var(--orders-orange-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | Main Section
        |--------------------------------------------------------------------------
        */

        .orders-main-section {
            position: relative;
            z-index: 3;
            margin-top: -31px;
            padding-bottom: 85px;
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Panel
        |--------------------------------------------------------------------------
        */

        .orders-filter-panel {
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid var(--orders-border);
            border-radius: 23px;
            background: var(--orders-white);
            box-shadow:
                0 16px 47px rgba(16, 24, 40, 0.09);
        }

        .orders-filter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 20px 23px;
            border-bottom: 1px solid var(--orders-border);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.07),
                    transparent 35%
                ),
                #fcfcfd;
        }

        .orders-filter-title {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .orders-filter-title-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--orders-blue);
            background: var(--orders-blue-soft);
        }

        .orders-filter-title-icon svg {
            width: 20px;
            height: 20px;
        }

        .orders-filter-title h2 {
            margin: 0 0 4px;
            color: var(--orders-dark);
            font-size: 18px;
        }

        .orders-filter-title p {
            margin: 0;
            color: var(--orders-muted);
            font-size: 9px;
        }

        .orders-filter-count {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            border-radius: 999px;
            color: var(--orders-blue-dark);
            background: var(--orders-blue-soft);
            font-size: 9px;
            font-weight: 900;
        }

        .orders-filter-count::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--orders-blue);
        }

        .orders-filter-form {
            display: grid;
            grid-template-columns:
                minmax(0, 1.25fr)
                minmax(220px, 0.6fr)
                auto;
            gap: 14px;
            align-items: end;
            padding: 22px 23px;
        }

        .orders-form-group {
            display: grid;
            gap: 7px;
        }

        .orders-form-label {
            color: var(--orders-text);
            font-size: 10px;
            font-weight: 900;
        }

        .orders-field-wrapper {
            position: relative;
        }

        .orders-field-icon {
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

        .orders-input,
        .orders-select {
            width: 100%;
            min-height: 48px;
            border: 1px solid var(--orders-border-dark);
            border-radius: 14px;
            color: var(--orders-dark);
            background: #fcfcfd;
            outline: none;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .orders-input {
            padding: 11px 14px 11px 43px;
        }

        .orders-select {
            padding: 11px 14px;
        }

        .orders-input::placeholder {
            color: #98a2b3;
        }

        .orders-input:hover,
        .orders-select:hover {
            border-color: #98a2b3;
        }

        .orders-input:focus,
        .orders-select:focus {
            border-color: var(--orders-blue);
            background: var(--orders-white);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.11);
        }

        .orders-filter-actions {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        /*
        |--------------------------------------------------------------------------
        | Active Filter
        |--------------------------------------------------------------------------
        */

        .orders-active-filters {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 0 23px 21px;
        }

        .orders-active-filter-label {
            color: var(--orders-muted);
            font-size: 9px;
            font-weight: 850;
        }

        .orders-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 30px;
            padding: 6px 10px;
            border: 1px solid #cfe0ff;
            border-radius: 999px;
            color: var(--orders-blue-dark);
            background: var(--orders-blue-soft);
            font-size: 8px;
            font-weight: 900;
        }

        .orders-filter-chip.orange {
            color: var(--orders-orange-dark);
            border-color: #fed7aa;
            background: var(--orders-orange-soft);
        }

        .orders-filter-chip svg {
            width: 13px;
            height: 13px;
        }

        /*
        |--------------------------------------------------------------------------
        | Results Header
        |--------------------------------------------------------------------------
        */

        .orders-results-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        .orders-results-heading h2 {
            margin: 0 0 5px;
            color: var(--orders-dark);
            font-size: 24px;
        }

        .orders-results-heading p {
            margin: 0;
            color: var(--orders-muted);
            font-size: 10px;
        }

        .orders-results-page {
            color: var(--orders-muted);
            font-size: 9px;
            font-weight: 800;
        }

        /*
        |--------------------------------------------------------------------------
        | Order List
        |--------------------------------------------------------------------------
        */

        .orders-list {
            display: grid;
            gap: 17px;
        }

        .orders-card {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--orders-border);
            border-radius: 22px;
            background: var(--orders-white);
            box-shadow:
                0 9px 30px rgba(16, 24, 40, 0.055);
            transition:
                transform 0.24s ease,
                border-color 0.24s ease,
                box-shadow 0.24s ease;
        }

        .orders-card:hover {
            border-color: #bdd1ff;
            box-shadow:
                0 18px 45px rgba(16, 24, 40, 0.095);
            transform: translateY(-4px);
        }

        .orders-card::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 5px;
            background: var(--orders-yellow);
        }

        .orders-card.status-processing::before {
            background: var(--orders-blue);
        }

        .orders-card.status-ready::before {
            background: var(--orders-purple);
        }

        .orders-card.status-completed::before {
            background: var(--orders-green);
        }

        .orders-card.status-cancelled::before {
            background: var(--orders-red);
        }

        .orders-card.status-neutral::before {
            background: #98a2b3;
        }

        .orders-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
            padding: 21px 23px 18px 27px;
            border-bottom: 1px solid var(--orders-border);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.055),
                    transparent 34%
                ),
                #fcfcfd;
        }

        .orders-card-identity {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            min-width: 0;
        }

        .orders-card-icon {
            width: 47px;
            height: 47px;
            flex: 0 0 47px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: var(--orders-blue);
            background: var(--orders-blue-soft);
        }

        .orders-card-icon svg {
            width: 22px;
            height: 22px;
        }

        .orders-card-copy {
            min-width: 0;
        }

        .orders-card-code {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--orders-blue);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.055em;
        }

        .orders-card-code::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--orders-orange);
        }

        .orders-card-copy h3 {
            margin: 6px 0 5px;
            color: var(--orders-dark);
            font-size: 18px;
            line-height: 1.3;
        }

        .orders-card-copy p {
            margin: 0;
            color: var(--orders-muted);
            font-size: 9px;
            line-height: 1.55;
        }

        .orders-card-statuses {
            display: flex;
            align-items: flex-end;
            flex-direction: column;
            gap: 7px;
            flex: 0 0 auto;
        }

        /*
        |--------------------------------------------------------------------------
        | Status Badges
        |--------------------------------------------------------------------------
        */

        .orders-status-badge,
        .orders-payment-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 29px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 900;
            white-space: nowrap;
        }

        .orders-status-badge::before,
        .orders-payment-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
        }

        .orders-status-badge.waiting {
            color: #92400e;
            background: var(--orders-yellow-soft);
        }

        .orders-status-badge.waiting::before {
            background: var(--orders-yellow);
        }

        .orders-status-badge.processing {
            color: var(--orders-blue-dark);
            background: var(--orders-blue-soft);
        }

        .orders-status-badge.processing::before {
            background: var(--orders-blue);
        }

        .orders-status-badge.ready {
            color: #6b21a8;
            background: var(--orders-purple-soft);
        }

        .orders-status-badge.ready::before {
            background: var(--orders-purple);
        }

        .orders-status-badge.completed {
            color: #166534;
            background: var(--orders-green-soft);
        }

        .orders-status-badge.completed::before {
            background: var(--orders-green);
        }

        .orders-status-badge.cancelled {
            color: #991b1b;
            background: var(--orders-red-soft);
        }

        .orders-status-badge.cancelled::before {
            background: var(--orders-red);
        }

        .orders-status-badge.neutral,
        .orders-payment-badge.neutral {
            color: var(--orders-text);
            background: #f2f4f7;
        }

        .orders-status-badge.neutral::before,
        .orders-payment-badge.neutral::before {
            background: #98a2b3;
        }

        .orders-payment-badge.unpaid {
            color: #92400e;
            background: var(--orders-yellow-soft);
        }

        .orders-payment-badge.unpaid::before {
            background: var(--orders-yellow);
        }

        .orders-payment-badge.verification {
            color: var(--orders-blue-dark);
            background: var(--orders-blue-soft);
        }

        .orders-payment-badge.verification::before {
            background: var(--orders-blue);
        }

        .orders-payment-badge.paid {
            color: #166534;
            background: var(--orders-green-soft);
        }

        .orders-payment-badge.paid::before {
            background: var(--orders-green);
        }

        .orders-payment-badge.rejected {
            color: #991b1b;
            background: var(--orders-red-soft);
        }

        .orders-payment-badge.rejected::before {
            background: var(--orders-red);
        }

        /*
        |--------------------------------------------------------------------------
        | Card Body
        |--------------------------------------------------------------------------
        */

        .orders-card-body {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            padding: 4px 27px;
        }

        .orders-info-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            min-width: 0;
            padding: 17px 16px;
            border-right: 1px solid var(--orders-border);
        }

        .orders-info-item:first-child {
            padding-left: 0;
        }

        .orders-info-item:last-child {
            padding-right: 0;
            border-right: 0;
        }

        .orders-info-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--orders-blue);
            background: var(--orders-blue-soft);
        }

        .orders-info-item:nth-child(2)
        .orders-info-icon {
            color: var(--orders-orange-dark);
            background: var(--orders-orange-soft);
        }

        .orders-info-item:nth-child(3)
        .orders-info-icon {
            color: var(--orders-purple);
            background: var(--orders-purple-soft);
        }

        .orders-info-item:nth-child(4)
        .orders-info-icon {
            color: var(--orders-green-dark);
            background: var(--orders-green-soft);
        }

        .orders-info-icon svg {
            width: 17px;
            height: 17px;
        }

        .orders-info-copy {
            min-width: 0;
        }

        .orders-info-copy span,
        .orders-info-copy strong,
        .orders-info-copy small {
            display: block;
        }

        .orders-info-copy span {
            color: var(--orders-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .orders-info-copy strong {
            margin-top: 4px;
            overflow-wrap: anywhere;
            color: var(--orders-dark);
            font-size: 10px;
            line-height: 1.45;
        }

        .orders-info-copy small {
            margin-top: 3px;
            color: var(--orders-muted);
            font-size: 8px;
            line-height: 1.4;
        }

        /*
        |--------------------------------------------------------------------------
        | Card Footer
        |--------------------------------------------------------------------------
        */

        .orders-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 17px 23px 17px 27px;
            border-top: 1px solid var(--orders-border);
            background: #fcfcfd;
        }

        .orders-total {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .orders-total-icon {
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--orders-white);
            background:
                linear-gradient(
                    135deg,
                    var(--orders-blue),
                    #2b70ff
                );
        }

        .orders-total-icon svg {
            width: 19px;
            height: 19px;
        }

        .orders-total-copy span,
        .orders-total-copy strong {
            display: block;
        }

        .orders-total-copy span {
            color: var(--orders-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .orders-total-copy strong {
            margin-top: 3px;
            color: var(--orders-blue);
            font-size: 17px;
        }

        .orders-detail-button {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 14px;
            border: 1px solid #b9d0ff;
            border-radius: 12px;
            color: var(--orders-blue);
            background: var(--orders-white);
            font-size: 10px;
            font-weight: 900;
            transition:
                color 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .orders-detail-button:hover {
            color: var(--orders-blue-dark);
            border-color: var(--orders-blue);
            background: var(--orders-blue-soft);
            transform: translateX(2px);
        }

        .orders-detail-button svg {
            width: 15px;
            height: 15px;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */

        .orders-empty-state {
            padding: 58px 25px;
            border: 1px solid var(--orders-border);
            border-radius: 23px;
            background: var(--orders-white);
            box-shadow:
                0 10px 34px rgba(16, 24, 40, 0.055);
            text-align: center;
        }

        .orders-empty-icon {
            width: 72px;
            height: 72px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            border-radius: 22px;
            color: var(--orders-blue);
            background: var(--orders-blue-soft);
        }

        .orders-empty-icon svg {
            width: 34px;
            height: 34px;
        }

        .orders-empty-state h3 {
            margin: 0 0 8px;
            color: var(--orders-dark);
            font-size: 21px;
        }

        .orders-empty-state p {
            max-width: 520px;
            margin: 0 auto 21px;
            color: var(--orders-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .orders-empty-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        .orders-pagination {
            margin-top: 27px;
        }

        .orders-pagination nav {
            display: flex;
            justify-content: center;
        }

        .orders-pagination svg {
            width: 18px;
            height: 18px;
        }

        /*
        |--------------------------------------------------------------------------
        | Animation
        |--------------------------------------------------------------------------
        */

        .orders-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .orders-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .orders-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(290px, 0.48fr);
                gap: 35px;
            }

            .orders-card-body {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .orders-info-item:nth-child(2) {
                border-right: 0;
            }

            .orders-info-item:nth-child(-n + 2) {
                border-bottom: 1px solid var(--orders-border);
            }

            .orders-info-item:nth-child(3) {
                padding-left: 0;
            }
        }

        @media (max-width: 900px) {
            .orders-hero {
                padding: 47px 0 70px;
            }

            .orders-hero-grid {
                grid-template-columns: 1fr;
            }

            .orders-hero-summary {
                max-width: 620px;
            }

            .orders-filter-form {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(220px, 0.65fr);
            }

            .orders-filter-actions {
                grid-column: 1 / -1;
                justify-content: flex-end;
            }
        }

        @media (max-width: 700px) {
            .orders-filter-form {
                grid-template-columns: 1fr;
            }

            .orders-filter-actions {
                grid-column: auto;
                justify-content: stretch;
            }

            .orders-filter-actions .orders-button {
                flex: 1;
            }

            .orders-card-header,
            .orders-card-footer,
            .orders-results-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .orders-card-statuses {
                align-items: flex-start;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .orders-card-footer {
                gap: 16px;
            }

            .orders-detail-button {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .orders-hero {
                padding: 39px 0 63px;
            }

            .orders-hero::before,
            .orders-hero::after {
                display: none;
            }

            .orders-hero h1 {
                margin-top: 17px;
                font-size: 36px;
                letter-spacing: -1.4px;
            }

            .orders-hero-description {
                font-size: 13px;
            }

            .orders-hero-actions {
                flex-direction: column;
            }

            .orders-hero-actions .orders-button {
                width: 100%;
            }

            .orders-hero-summary {
                padding: 20px;
                border-radius: 21px;
            }

            .orders-main-section {
                margin-top: -27px;
                padding-bottom: 63px;
            }

            .orders-filter-panel {
                border-radius: 20px;
            }

            .orders-filter-header {
                align-items: flex-start;
                flex-direction: column;
                padding: 19px;
            }

            .orders-filter-form {
                padding: 19px;
            }

            .orders-active-filters {
                padding-right: 19px;
                padding-left: 19px;
            }

            .orders-card {
                border-radius: 19px;
            }

            .orders-card-header {
                padding: 19px 19px 17px 23px;
            }

            .orders-card-icon {
                width: 42px;
                height: 42px;
                flex-basis: 42px;
                border-radius: 13px;
            }

            .orders-card-copy h3 {
                font-size: 16px;
            }

            .orders-card-body {
                grid-template-columns: 1fr;
                padding: 2px 23px;
            }

            .orders-info-item,
            .orders-info-item:first-child,
            .orders-info-item:nth-child(3),
            .orders-info-item:last-child {
                padding: 14px 0;
                border-right: 0;
                border-bottom: 1px solid var(--orders-border);
            }

            .orders-info-item:last-child {
                border-bottom: 0;
            }

            .orders-card-footer {
                padding: 17px 19px 17px 23px;
            }

            .orders-total-copy strong {
                font-size: 16px;
            }

            .orders-empty-state {
                padding: 45px 20px;
                border-radius: 20px;
            }

            .orders-empty-actions {
                flex-direction: column;
            }

            .orders-empty-actions .orders-button {
                width: 100%;
            }
        }

        @media (max-width: 390px) {
            .orders-hero h1 {
                font-size: 33px;
            }

            .orders-hero-summary-grid {
                grid-template-columns: 1fr;
            }

            .orders-card-identity {
                gap: 10px;
            }

            .orders-card-icon {
                width: 39px;
                height: 39px;
                flex-basis: 39px;
            }

            .orders-card-statuses {
                flex-direction: column;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .orders-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .orders-card,
            .orders-button,
            .orders-detail-button {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="orders-index-page">
        {{-- Hero --}}
        <section class="orders-hero">
            <div class="container orders-hero-grid">
                <div class="orders-hero-content orders-reveal">
                    <nav
                        class="orders-breadcrumb"
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

                        <strong>Pesanan Saya</strong>
                    </nav>

                    <span class="orders-hero-badge">
                        <span class="orders-hero-badge-icon">
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

                        Riwayat Pesanan Pelanggan
                    </span>

                    <h1>
                        Pantau seluruh
                        <span>pesananmu</span>
                    </h1>

                    <p class="orders-hero-description">
                        Periksa status pengerjaan, jadwal pengambilan,
                        total biaya, dan pembayaran setiap pesanan
                        melalui satu halaman.
                    </p>

                    <div class="orders-hero-actions">
                        <a
                            href="{{ route('customer.pesanan.create') }}"
                            class="orders-button orders-button-orange"
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

                            Buat Pesanan Baru
                        </a>

                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="orders-button orders-button-outline"
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
                    </div>
                </div>

                <aside class="orders-hero-summary orders-reveal">
                    <div class="orders-hero-summary-header">
                        <span class="orders-hero-summary-icon">
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
                            <strong>Ringkasan Hasil</strong>
                            <span>
                                Berdasarkan pencarian dan filter aktif.
                            </span>
                        </span>
                    </div>

                    <div class="orders-hero-summary-grid">
                        <div class="orders-hero-summary-item">
                            <span>Total Ditemukan</span>
                            <strong>{{ $totalHasil }}</strong>
                        </div>

                        <div class="orders-hero-summary-item">
                            <span>Ditampilkan</span>
                            <strong>{{ $jumlahDitampilkan }}</strong>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        {{-- Main --}}
        <section class="orders-main-section">
            <div class="container">
                {{-- Filter --}}
                <div class="orders-filter-panel orders-reveal">
                    <div class="orders-filter-header">
                        <div class="orders-filter-title">
                            <span class="orders-filter-title-icon">
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
                                    <path d="M4 6h16"/>
                                    <path d="M7 12h10"/>
                                    <path d="M10 18h4"/>
                                </svg>
                            </span>

                            <div>
                                <h2>Cari dan Filter</h2>

                                <p>
                                    Temukan pesanan berdasarkan kode
                                    atau status pengerjaan.
                                </p>
                            </div>
                        </div>

                        <span class="orders-filter-count">
                            {{ $totalHasil }} pesanan
                        </span>
                    </div>

                    <form
                        action="{{ route('customer.pesanan.index') }}"
                        method="GET"
                        class="orders-filter-form"
                    >
                        <div class="orders-form-group">
                            <label
                                for="q"
                                class="orders-form-label"
                            >
                                Cari Pesanan
                            </label>

                            <div class="orders-field-wrapper">
                                <svg
                                    class="orders-field-icon"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <circle cx="11" cy="11" r="7"/>
                                    <path d="m20 20-3.5-3.5"/>
                                </svg>

                                <input
                                    type="search"
                                    id="q"
                                    name="q"
                                    value="{{ request('q') }}"
                                    class="orders-input"
                                    placeholder="Masukkan kode pesanan"
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <div class="orders-form-group">
                            <label
                                for="status"
                                class="orders-form-label"
                            >
                                Status Pesanan
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="orders-select"
                            >
                                <option value="">
                                    Semua Status
                                </option>

                                <option
                                    value="menunggu_verifikasi"
                                    {{
                                        request('status')
                                        === 'menunggu_verifikasi'
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    Menunggu Verifikasi
                                </option>

                                <option
                                    value="diproses"
                                    {{
                                        request('status')
                                        === 'diproses'
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    Diproses
                                </option>

                                <option
                                    value="siap_diambil"
                                    {{
                                        request('status')
                                        === 'siap_diambil'
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    Siap Diambil
                                </option>

                                <option
                                    value="selesai"
                                    {{
                                        request('status')
                                        === 'selesai'
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    Selesai
                                </option>

                                <option
                                    value="dibatalkan"
                                    {{
                                        request('status')
                                        === 'dibatalkan'
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    Dibatalkan
                                </option>
                            </select>
                        </div>

                        <div class="orders-filter-actions">
                            <button
                                type="submit"
                                class="orders-button orders-button-primary"
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
                                    <path d="M4 6h16"/>
                                    <path d="M7 12h10"/>
                                    <path d="M10 18h4"/>
                                </svg>

                                Terapkan Filter
                            </button>

                            @if ($adaFilter)
                                <a
                                    href="{{ route('customer.pesanan.index') }}"
                                    class="orders-button orders-button-outline"
                                >
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    @if ($adaFilter)
                        <div class="orders-active-filters">
                            <span class="orders-active-filter-label">
                                Filter aktif:
                            </span>

                            @if (request()->filled('q'))
                                <span class="orders-filter-chip">
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
                                        <circle cx="11" cy="11" r="7"/>
                                        <path d="m20 20-3.5-3.5"/>
                                    </svg>

                                    {{ request('q') }}
                                </span>
                            @endif

                            @if (request()->filled('status'))
                                <span class="orders-filter-chip orange">
                                    {{
                                        $labelStatusPesanan(
                                            request('status')
                                        )
                                    }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Results Header --}}
                <div class="orders-results-header">
                    <div class="orders-results-heading">
                        <h2>Daftar Pesanan</h2>

                        <p>
                            @if ($totalHasil > 0)
                                Menampilkan
                                {{ $pesanans->firstItem() }}
                                sampai
                                {{ $pesanans->lastItem() }}
                                dari
                                {{ $totalHasil }}
                                pesanan.
                            @else
                                Tidak ada pesanan yang dapat ditampilkan.
                            @endif
                        </p>
                    </div>

                    @if ($pesanans->lastPage() > 1)
                        <span class="orders-results-page">
                            Halaman
                            {{ $pesanans->currentPage() }}
                            dari
                            {{ $pesanans->lastPage() }}
                        </span>
                    @endif
                </div>

                {{-- Orders --}}
                <div class="orders-list">
                    @forelse ($pesanans as $pesanan)
                        @php
                            $statusPesanan =
                                $pesanan->status_pesanan;

                            $classPesanan =
                                $classStatusPesanan(
                                    $statusPesanan
                                );

                            $statusPembayaran =
                                $pesanan->pembayaran
                                    ?->status_pembayaran;

                            $classPembayaran =
                                $classStatusPembayaran(
                                    $statusPembayaran
                                );

                            $tanggalPesan =
                                $pesanan
                                    ->tanggal_pesan
                                    ?->format('d M Y')
                                ?? $pesanan
                                    ->created_at
                                    ?->format('d M Y')
                                ?? '-';

                            $tanggalPengambilan =
                                $pesanan
                                    ->tanggal_pengambilan
                                    ?->format('d M Y')
                                ?? '-';

                            $jamPengambilan =
                                $pesanan
                                    ->jam_pengambilan
                                    ?->format('H:i')
                                ?? '-';
                        @endphp

                        <article
                            class="orders-card orders-reveal status-{{
                                $classPesanan
                            }}"
                        >
                            <div class="orders-card-header">
                                <div class="orders-card-identity">
                                    <span class="orders-card-icon">
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
                                            <rect
                                                width="12"
                                                height="8"
                                                x="6"
                                                y="14"
                                            />
                                        </svg>
                                    </span>

                                    <div class="orders-card-copy">
                                        <span class="orders-card-code">
                                            {{ $pesanan->kode_pesanan }}
                                        </span>

                                        <h3>
                                            Pesanan {{ $tanggalPesan }}
                                        </h3>

                                        <p>
                                            Dibuat pada
                                            {{ $tanggalPesan }} dan dapat
                                            dipantau melalui halaman detail.
                                        </p>
                                    </div>
                                </div>

                                <div class="orders-card-statuses">
                                    <span
                                        class="orders-status-badge {{
                                            $classPesanan
                                        }}"
                                    >
                                        {{
                                            $labelStatusPesanan(
                                                $statusPesanan
                                            )
                                        }}
                                    </span>

                                    <span
                                        class="orders-payment-badge {{
                                            $classPembayaran
                                        }}"
                                    >
                                        {{
                                            $labelStatusPembayaran(
                                                $statusPembayaran
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>

                            <div class="orders-card-body">
                                <div class="orders-info-item">
                                    <span class="orders-info-icon">
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
                                                height="18"
                                                x="3"
                                                y="4"
                                                rx="2"
                                            />
                                            <path d="M16 2v4"/>
                                            <path d="M8 2v4"/>
                                            <path d="M3 10h18"/>
                                        </svg>
                                    </span>

                                    <span class="orders-info-copy">
                                        <span>Pengambilan</span>

                                        <strong>
                                            {{ $tanggalPengambilan }}
                                        </strong>

                                        <small>
                                            Pukul {{ $jamPengambilan }}
                                        </small>
                                    </span>
                                </div>

                                <div class="orders-info-item">
                                    <span class="orders-info-icon">
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

                                    <span class="orders-info-copy">
                                        <span>Lokasi</span>

                                        <strong>
                                            {{
                                                $pesanan
                                                    ->lokasi_pengambilan
                                                ?? '-'
                                            }}
                                        </strong>

                                        <small>
                                            Metode penyerahan pesanan
                                        </small>
                                    </span>
                                </div>

                                <div class="orders-info-item">
                                    <span class="orders-info-icon">
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
                                            <path d="M3 10h18"/>
                                        </svg>
                                    </span>

                                    <span class="orders-info-copy">
                                        <span>Pembayaran</span>

                                        <strong>
                                            {{
                                                $labelMetodePembayaran(
                                                    $pesanan
                                                        ->metode_pembayaran
                                                )
                                            }}
                                        </strong>

                                        <small>
                                            {{
                                                $labelStatusPembayaran(
                                                    $statusPembayaran
                                                )
                                            }}
                                        </small>
                                    </span>
                                </div>

                                <div class="orders-info-item">
                                    <span class="orders-info-icon">
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
                                            <path d="M8 12h8"/>
                                            <path d="M12 8v8"/>
                                        </svg>
                                    </span>

                                    <span class="orders-info-copy">
                                        <span>Status</span>

                                        <strong>
                                            {{
                                                $labelStatusPesanan(
                                                    $statusPesanan
                                                )
                                            }}
                                        </strong>

                                        <small>
                                            Status pengerjaan saat ini
                                        </small>
                                    </span>
                                </div>
                            </div>

                            <div class="orders-card-footer">
                                <div class="orders-total">
                                    <span class="orders-total-icon">
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
                                            <path d="M12 2v20"/>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/>
                                        </svg>
                                    </span>

                                    <span class="orders-total-copy">
                                        <span>Total Pesanan</span>

                                        <strong>
                                            Rp {{
                                                number_format(
                                                    (float) $pesanan
                                                        ->total_harga,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        </strong>
                                    </span>
                                </div>

                                <a
                                    href="{{
                                        route(
                                            'customer.pesanan.show',
                                            $pesanan
                                        )
                                    }}"
                                    class="orders-detail-button"
                                >
                                    Lihat Detail Pesanan

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
                        </article>
                    @empty
                        <div class="orders-empty-state orders-reveal">
                            <span class="orders-empty-icon">
                                @if ($adaFilter)
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
                                        <circle cx="11" cy="11" r="7"/>
                                        <path d="m20 20-3.5-3.5"/>
                                        <path d="m9 9 4 4"/>
                                        <path d="m13 9-4 4"/>
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
                                        <path d="M6 9V2h12v7"/>
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                        <rect
                                            width="12"
                                            height="8"
                                            x="6"
                                            y="14"
                                        />
                                    </svg>
                                @endif
                            </span>

                            @if ($adaFilter)
                                <h3>Pesanan tidak ditemukan</h3>

                                <p>
                                    Tidak ada pesanan yang sesuai dengan
                                    kata kunci atau status yang dipilih.
                                    Hapus filter untuk melihat seluruh
                                    pesanan.
                                </p>

                                <div class="orders-empty-actions">
                                    <a
                                        href="{{ route('customer.pesanan.index') }}"
                                        class="orders-button orders-button-outline"
                                    >
                                        Reset Filter
                                    </a>

                                    <a
                                        href="{{ route('customer.pesanan.create') }}"
                                        class="orders-button orders-button-primary"
                                    >
                                        Buat Pesanan Baru
                                    </a>
                                </div>
                            @else
                                <h3>Belum ada pesanan</h3>

                                <p>
                                    Kamu belum membuat pesanan. Mulai
                                    dengan memilih layanan, mengunggah
                                    dokumen, dan menentukan jadwal
                                    pengambilan.
                                </p>

                                <div class="orders-empty-actions">
                                    <a
                                        href="{{ route('customer.pesanan.create') }}"
                                        class="orders-button orders-button-primary"
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

                                        Buat Pesanan Pertama
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if ($pesanans->hasPages())
                    <div class="orders-pagination">
                        {{ $pesanans->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealElements = document.querySelectorAll(
                '.orders-reveal'
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
                    threshold: 0.08,
                    rootMargin: '0px 0px -35px 0px',
                }
            );

            revealElements.forEach((element) => {
                observer.observe(element);
            });
        });
    </script>
@endpush