@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan - Tukang Print Dadakan')

@php
    $namaPelanggan = $user->name ?? 'Pelanggan';

    $inisialPelanggan = strtoupper(
        mb_substr($namaPelanggan, 0, 1)
    );

    $jumlahPesananTerbaru = $pesananTerbaru->count();

    $persentaseSelesai = $totalPesanan > 0
        ? min(
            100,
            (int) round(
                ($pesananSelesai / $totalPesanan) * 100
            )
        )
        : 0;

    $pesananTerakhir = $pesananTerbaru->first();

    $statusPesananTerakhir = $pesananTerakhir
        ? match ($pesananTerakhir->status_pesanan) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'siap_diambil' => 'Siap Diambil',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucwords(
                str_replace(
                    '_',
                    ' ',
                    $pesananTerakhir->status_pesanan
                )
            ),
        }
        : null;
@endphp

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Dashboard Variables
        |--------------------------------------------------------------------------
        */

        .customer-dashboard-page {
            --dashboard-blue: var(--customer-blue, #155eef);
            --dashboard-blue-dark: var(--customer-blue-dark, #1046b8);
            --dashboard-blue-soft: var(--customer-blue-soft, #edf4ff);

            --dashboard-orange: var(--customer-orange, #f97316);
            --dashboard-orange-dark: var(--customer-orange-dark, #c2410c);
            --dashboard-orange-soft: var(--customer-orange-soft, #fff1e7);

            --dashboard-green: #16a34a;
            --dashboard-green-dark: #15803d;
            --dashboard-green-soft: #ecfdf3;

            --dashboard-yellow: #d97706;
            --dashboard-yellow-soft: #fffbeb;

            --dashboard-red: #dc2626;
            --dashboard-red-soft: #fff1f2;

            --dashboard-purple: #7c3aed;
            --dashboard-purple-soft: #f3e8ff;

            --dashboard-dark: #101828;
            --dashboard-text: #344054;
            --dashboard-muted: #667085;

            --dashboard-white: #ffffff;
            --dashboard-soft: #f7f9fc;
            --dashboard-border: #e4e7ec;

            min-height: 100vh;
            overflow: hidden;
            background: #f8faff;
        }

        /*
        |--------------------------------------------------------------------------
        | Shared Components
        |--------------------------------------------------------------------------
        */

        .dashboard-section {
            position: relative;
            padding: 34px 0 82px;
        }

        .dashboard-button {
            min-height: 47px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 10px 17px;
            border: 1px solid transparent;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 900;
            text-align: center;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease;
        }

        .dashboard-button:hover {
            transform: translateY(-2px);
        }

        .dashboard-button svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
        }

        .dashboard-button-primary {
            color: var(--dashboard-white);
            background:
                linear-gradient(
                    135deg,
                    var(--dashboard-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 23px rgba(21, 94, 239, 0.22);
        }

        .dashboard-button-primary:hover {
            color: var(--dashboard-white);
            background:
                linear-gradient(
                    135deg,
                    var(--dashboard-blue-dark),
                    var(--dashboard-blue)
                );
            box-shadow:
                0 14px 30px rgba(21, 94, 239, 0.28);
        }

        .dashboard-button-orange {
            color: var(--dashboard-white);
            background:
                linear-gradient(
                    135deg,
                    var(--dashboard-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 23px rgba(249, 115, 22, 0.22);
        }

        .dashboard-button-orange:hover {
            color: var(--dashboard-white);
            background:
                linear-gradient(
                    135deg,
                    var(--dashboard-orange-dark),
                    var(--dashboard-orange)
                );
            box-shadow:
                0 14px 30px rgba(249, 115, 22, 0.28);
        }

        .dashboard-button-outline {
            color: var(--dashboard-blue);
            border-color: #b9d0ff;
            background: var(--dashboard-white);
        }

        .dashboard-button-outline:hover {
            color: var(--dashboard-blue-dark);
            border-color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .dashboard-hero {
            position: relative;
            overflow: hidden;
            padding: 56px 0 82px;
            border-bottom: 1px solid var(--dashboard-border);
            background:
                radial-gradient(
                    circle at 8% 18%,
                    rgba(21, 94, 239, 0.17),
                    transparent 27%
                ),
                radial-gradient(
                    circle at 91% 7%,
                    rgba(249, 115, 22, 0.15),
                    transparent 25%
                ),
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #f5f8ff 100%
                );
        }

        .dashboard-hero::before {
            content: "";
            position: absolute;
            top: -105px;
            right: -80px;
            width: 285px;
            height: 285px;
            border: 44px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .dashboard-hero::after {
            content: "";
            position: absolute;
            bottom: -125px;
            left: -100px;
            width: 280px;
            height: 280px;
            border: 43px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .dashboard-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1.08fr)
                minmax(330px, 0.52fr);
            gap: 48px;
            align-items: center;
        }

        .dashboard-hero-content {
            max-width: 760px;
        }

        .dashboard-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 13px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--dashboard-orange-dark);
            background: var(--dashboard-orange-soft);
            font-size: 11px;
            font-weight: 900;
        }

        .dashboard-hero-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--dashboard-white);
            background: var(--dashboard-orange);
        }

        .dashboard-hero-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .dashboard-hero h1 {
            margin: 21px 0 15px;
            color: var(--dashboard-dark);
            font-size: clamp(38px, 5vw, 61px);
            line-height: 1.06;
            letter-spacing: -2px;
        }

        .dashboard-hero h1 span {
            position: relative;
            display: inline-block;
            color: var(--dashboard-blue);
        }

        .dashboard-hero h1 span::after {
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

        .dashboard-hero-description {
            max-width: 700px;
            margin: 0;
            color: var(--dashboard-muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .dashboard-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 11px;
            margin-top: 27px;
        }

        /*
        |--------------------------------------------------------------------------
        | Profile Card
        |--------------------------------------------------------------------------
        */

        .dashboard-profile-card {
            position: relative;
            padding: 24px;
            border: 1px solid rgba(228, 231, 236, 0.93);
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            box-shadow:
                0 23px 62px rgba(16, 24, 40, 0.13);
        }

        .dashboard-profile-card::before {
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
                    var(--dashboard-blue),
                    var(--dashboard-orange)
                );
            transform: rotate(3deg);
        }

        .dashboard-profile-main {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .dashboard-profile-avatar {
            width: 62px;
            height: 62px;
            flex: 0 0 62px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 5px solid var(--dashboard-blue-soft);
            border-radius: 20px;
            color: var(--dashboard-white);
            background:
                linear-gradient(
                    135deg,
                    var(--dashboard-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 24px rgba(21, 94, 239, 0.22);
            font-size: 24px;
            font-weight: 900;
        }

        .dashboard-profile-copy {
            min-width: 0;
        }

        .dashboard-profile-copy strong,
        .dashboard-profile-copy span {
            display: block;
        }

        .dashboard-profile-copy strong {
            overflow: hidden;
            color: var(--dashboard-dark);
            font-size: 17px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .dashboard-profile-copy span {
            margin-top: 3px;
            overflow: hidden;
            color: var(--dashboard-muted);
            font-size: 10px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .dashboard-profile-divider {
            height: 1px;
            margin: 19px 0;
            background: var(--dashboard-border);
        }

        .dashboard-profile-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .dashboard-profile-status-copy span,
        .dashboard-profile-status-copy strong {
            display: block;
        }

        .dashboard-profile-status-copy span {
            color: var(--dashboard-muted);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .dashboard-profile-status-copy strong {
            margin-top: 3px;
            color: var(--dashboard-dark);
            font-size: 11px;
        }

        .dashboard-account-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border-radius: 999px;
            color: #166534;
            background: var(--dashboard-green-soft);
            font-size: 9px;
            font-weight: 900;
        }

        .dashboard-account-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--dashboard-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Overview Strip
        |--------------------------------------------------------------------------
        */

        .dashboard-overview-strip {
            position: relative;
            z-index: 3;
            margin-top: -40px;
        }

        .dashboard-overview-card {
            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr))
                minmax(250px, 0.9fr);
            overflow: hidden;
            border: 1px solid var(--dashboard-border);
            border-radius: 24px;
            background: var(--dashboard-white);
            box-shadow:
                0 18px 50px rgba(16, 24, 40, 0.09);
        }

        .dashboard-overview-item {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 21px;
            border-right: 1px solid var(--dashboard-border);
        }

        .dashboard-overview-icon {
            width: 45px;
            height: 45px;
            flex: 0 0 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
        }

        .dashboard-overview-item:nth-child(2)
        .dashboard-overview-icon {
            color: var(--dashboard-orange-dark);
            background: var(--dashboard-orange-soft);
        }

        .dashboard-overview-item:nth-child(3)
        .dashboard-overview-icon {
            color: var(--dashboard-green-dark);
            background: var(--dashboard-green-soft);
        }

        .dashboard-overview-icon svg {
            width: 22px;
            height: 22px;
        }

        .dashboard-overview-copy strong,
        .dashboard-overview-copy span {
            display: block;
        }

        .dashboard-overview-copy strong {
            color: var(--dashboard-dark);
            font-size: 20px;
            line-height: 1;
        }

        .dashboard-overview-copy span {
            margin-top: 5px;
            color: var(--dashboard-muted);
            font-size: 10px;
            font-weight: 750;
        }

        .dashboard-overview-progress {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 17px 20px;
            background:
                linear-gradient(
                    135deg,
                    var(--dashboard-blue-soft),
                    #f8fbff
                );
        }

        .dashboard-progress-ring {
            --ring-value: 0deg;

            width: 62px;
            height: 62px;
            flex: 0 0 62px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background:
                conic-gradient(
                    var(--dashboard-blue) var(--ring-value),
                    #dbe7ff 0
                );
        }

        .dashboard-progress-ring::before {
            content: "";
            width: 48px;
            height: 48px;
            border-radius: 999px;
            background: var(--dashboard-white);
        }

        .dashboard-progress-ring strong {
            position: absolute;
            color: var(--dashboard-blue);
            font-size: 12px;
            font-weight: 900;
        }

        .dashboard-progress-copy strong,
        .dashboard-progress-copy span {
            display: block;
        }

        .dashboard-progress-copy strong {
            color: var(--dashboard-dark);
            font-size: 12px;
        }

        .dashboard-progress-copy span {
            margin-top: 4px;
            color: var(--dashboard-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Dashboard Layout
        |--------------------------------------------------------------------------
        */

        .dashboard-main-grid {
            display: grid;
            grid-template-columns:
                minmax(0, 1.3fr)
                minmax(300px, 0.7fr);
            gap: 25px;
            align-items: start;
        }

        .dashboard-main-column,
        .dashboard-sidebar-column {
            display: grid;
            gap: 24px;
        }

        /*
        |--------------------------------------------------------------------------
        | Section Header
        |--------------------------------------------------------------------------
        */

        .dashboard-section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .dashboard-section-heading h2 {
            margin: 0 0 5px;
            color: var(--dashboard-dark);
            font-size: 24px;
            line-height: 1.2;
        }

        .dashboard-section-heading p {
            margin: 0;
            color: var(--dashboard-muted);
            font-size: 11px;
        }

        /*
        |--------------------------------------------------------------------------
        | Statistic Cards
        |--------------------------------------------------------------------------
        */

        .dashboard-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .dashboard-stat-card {
            position: relative;
            overflow: hidden;
            padding: 22px;
            border: 1px solid var(--dashboard-border);
            border-radius: 21px;
            background: var(--dashboard-white);
            box-shadow:
                0 8px 27px rgba(16, 24, 40, 0.05);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .dashboard-stat-card:hover {
            border-color: #bdd1ff;
            box-shadow:
                0 17px 42px rgba(16, 24, 40, 0.09);
            transform: translateY(-5px);
        }

        .dashboard-stat-card::before {
            content: "";
            position: absolute;
            top: -42px;
            right: -42px;
            width: 115px;
            height: 115px;
            border-radius: 999px;
            background: rgba(21, 94, 239, 0.055);
        }

        .dashboard-stat-card:nth-child(2)::before {
            background: rgba(249, 115, 22, 0.065);
        }

        .dashboard-stat-card:nth-child(3)::before {
            background: rgba(22, 163, 74, 0.06);
        }

        .dashboard-stat-header {
            position: relative;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .dashboard-stat-icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
        }

        .dashboard-stat-card:nth-child(2)
        .dashboard-stat-icon {
            color: var(--dashboard-orange-dark);
            background: var(--dashboard-orange-soft);
        }

        .dashboard-stat-card:nth-child(3)
        .dashboard-stat-icon {
            color: var(--dashboard-green-dark);
            background: var(--dashboard-green-soft);
        }

        .dashboard-stat-icon svg {
            width: 23px;
            height: 23px;
        }

        .dashboard-stat-label {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            border-radius: 999px;
            color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dashboard-stat-card:nth-child(2)
        .dashboard-stat-label {
            color: var(--dashboard-orange-dark);
            background: var(--dashboard-orange-soft);
        }

        .dashboard-stat-card:nth-child(3)
        .dashboard-stat-label {
            color: var(--dashboard-green-dark);
            background: var(--dashboard-green-soft);
        }

        .dashboard-stat-number {
            position: relative;
            display: block;
            margin-top: 18px;
            color: var(--dashboard-dark);
            font-size: 37px;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -1px;
        }

        .dashboard-stat-card p {
            position: relative;
            min-height: 49px;
            margin: 11px 0 0;
            color: var(--dashboard-muted);
            font-size: 10px;
            line-height: 1.6;
        }

        .dashboard-stat-footer {
            position: relative;
            display: flex;
            align-items: center;
            gap: 7px;
            margin-top: 15px;
            padding-top: 14px;
            border-top: 1px solid var(--dashboard-border);
            color: var(--dashboard-muted);
            font-size: 9px;
            font-weight: 750;
        }

        .dashboard-stat-footer svg {
            width: 14px;
            height: 14px;
            color: var(--dashboard-blue);
        }

        .dashboard-stat-card:nth-child(2)
        .dashboard-stat-footer svg {
            color: var(--dashboard-orange);
        }

        .dashboard-stat-card:nth-child(3)
        .dashboard-stat-footer svg {
            color: var(--dashboard-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Quick Actions
        |--------------------------------------------------------------------------
        */

        .dashboard-action-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .dashboard-action-card {
            position: relative;
            min-width: 0;
            overflow: hidden;
            padding: 22px;
            border: 1px solid var(--dashboard-border);
            border-radius: 21px;
            color: var(--dashboard-text);
            background: var(--dashboard-white);
            box-shadow:
                0 8px 27px rgba(16, 24, 40, 0.05);
            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .dashboard-action-card:hover {
            color: var(--dashboard-text);
            border-color: #b8ceff;
            box-shadow:
                0 18px 44px rgba(16, 24, 40, 0.10);
            transform: translateY(-6px);
        }

        .dashboard-action-card.primary {
            color: var(--dashboard-white);
            border-color: transparent;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.36),
                    transparent 39%
                ),
                linear-gradient(
                    145deg,
                    var(--dashboard-blue-dark),
                    var(--dashboard-blue)
                );
            box-shadow:
                0 16px 40px rgba(21, 94, 239, 0.22);
        }

        .dashboard-action-card.primary:hover {
            color: var(--dashboard-white);
            box-shadow:
                0 22px 52px rgba(21, 94, 239, 0.28);
        }

        .dashboard-action-card::before {
            content: "";
            position: absolute;
            top: -43px;
            right: -43px;
            width: 120px;
            height: 120px;
            border-radius: 999px;
            background: rgba(21, 94, 239, 0.055);
        }

        .dashboard-action-card.primary::before {
            border: 21px solid rgba(255, 255, 255, 0.08);
            background: transparent;
        }

        .dashboard-action-card:nth-child(3)::before {
            background: rgba(249, 115, 22, 0.06);
        }

        .dashboard-action-icon {
            position: relative;
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 17px;
            border-radius: 16px;
            color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
        }

        .dashboard-action-card.primary
        .dashboard-action-icon {
            color: var(--dashboard-white);
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.13);
        }

        .dashboard-action-card:nth-child(3)
        .dashboard-action-icon {
            color: var(--dashboard-orange-dark);
            background: var(--dashboard-orange-soft);
        }

        .dashboard-action-icon svg {
            width: 24px;
            height: 24px;
        }

        .dashboard-action-card h3 {
            position: relative;
            margin: 0 0 8px;
            color: var(--dashboard-dark);
            font-size: 18px;
        }

        .dashboard-action-card.primary h3 {
            color: var(--dashboard-white);
        }

        .dashboard-action-card p {
            position: relative;
            min-height: 58px;
            margin: 0;
            color: var(--dashboard-muted);
            font-size: 11px;
            line-height: 1.65;
        }

        .dashboard-action-card.primary p {
            color: #dbeafe;
        }

        .dashboard-action-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 18px;
            color: var(--dashboard-blue);
            font-size: 10px;
            font-weight: 900;
        }

        .dashboard-action-card.primary
        .dashboard-action-link {
            color: var(--dashboard-white);
        }

        .dashboard-action-card:nth-child(3)
        .dashboard-action-link {
            color: var(--dashboard-orange-dark);
        }

        .dashboard-action-link svg {
            width: 15px;
            height: 15px;
            transition: transform 0.2s ease;
        }

        .dashboard-action-card:hover
        .dashboard-action-link svg {
            transform: translateX(3px);
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Panel
        |--------------------------------------------------------------------------
        */

        .dashboard-panel {
            overflow: hidden;
            border: 1px solid var(--dashboard-border);
            border-radius: 23px;
            background: var(--dashboard-white);
            box-shadow:
                0 10px 34px rgba(16, 24, 40, 0.06);
        }

        .dashboard-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 23px 24px;
            border-bottom: 1px solid var(--dashboard-border);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.07),
                    transparent 35%
                ),
                #fcfcfd;
        }

        .dashboard-panel-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dashboard-panel-title-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--dashboard-white);
            background:
                linear-gradient(
                    135deg,
                    var(--dashboard-blue),
                    #2b70ff
                );
            box-shadow:
                0 8px 20px rgba(21, 94, 239, 0.19);
        }

        .dashboard-panel-title-icon svg {
            width: 21px;
            height: 21px;
        }

        .dashboard-panel-title h2 {
            margin: 0 0 4px;
            color: var(--dashboard-dark);
            font-size: 20px;
        }

        .dashboard-panel-title p {
            margin: 0;
            color: var(--dashboard-muted);
            font-size: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Order Table
        |--------------------------------------------------------------------------
        */

        .dashboard-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .dashboard-order-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dashboard-order-table th {
            padding: 13px 17px;
            color: var(--dashboard-muted);
            background: #f9fafb;
            font-size: 9px;
            font-weight: 900;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.055em;
            white-space: nowrap;
        }

        .dashboard-order-table td {
            padding: 17px;
            border-top: 1px solid var(--dashboard-border);
            color: var(--dashboard-text);
            font-size: 11px;
            vertical-align: middle;
        }

        .dashboard-order-table tbody tr {
            transition: background 0.2s ease;
        }

        .dashboard-order-table tbody tr:hover {
            background: #f9fbff;
        }

        .dashboard-order-code {
            display: inline-flex;
            align-items: center;
            gap: 9px;
        }

        .dashboard-order-code-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
        }

        .dashboard-order-code-icon svg {
            width: 17px;
            height: 17px;
        }

        .dashboard-order-code strong,
        .dashboard-order-code span {
            display: block;
        }

        .dashboard-order-code strong {
            color: var(--dashboard-dark);
            font-size: 11px;
        }

        .dashboard-order-code span {
            margin-top: 2px;
            color: var(--dashboard-muted);
            font-size: 8px;
        }

        .dashboard-order-date strong,
        .dashboard-order-date span {
            display: block;
        }

        .dashboard-order-date strong {
            color: var(--dashboard-dark);
            font-size: 10px;
        }

        .dashboard-order-date span {
            margin-top: 3px;
            color: var(--dashboard-muted);
            font-size: 8px;
        }

        .dashboard-order-price {
            color: var(--dashboard-dark);
            font-size: 11px;
            font-weight: 900;
            white-space: nowrap;
        }

        /*
        |--------------------------------------------------------------------------
        | Status Pill
        |--------------------------------------------------------------------------
        */

        .dashboard-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 27px;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 900;
            white-space: nowrap;
        }

        .dashboard-status-pill::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
        }

        .dashboard-status-menunggu_verifikasi {
            color: #92400e;
            background: var(--dashboard-yellow-soft);
        }

        .dashboard-status-menunggu_verifikasi::before {
            background: var(--dashboard-yellow);
        }

        .dashboard-status-diproses {
            color: var(--dashboard-blue-dark);
            background: var(--dashboard-blue-soft);
        }

        .dashboard-status-diproses::before {
            background: var(--dashboard-blue);
        }

        .dashboard-status-siap_diambil {
            color: #6b21a8;
            background: var(--dashboard-purple-soft);
        }

        .dashboard-status-siap_diambil::before {
            background: var(--dashboard-purple);
        }

        .dashboard-status-selesai {
            color: #166534;
            background: var(--dashboard-green-soft);
        }

        .dashboard-status-selesai::before {
            background: var(--dashboard-green);
        }

        .dashboard-status-dibatalkan {
            color: #991b1b;
            background: var(--dashboard-red-soft);
        }

        .dashboard-status-dibatalkan::before {
            background: var(--dashboard-red);
        }

        .dashboard-order-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #b9d0ff;
            border-radius: 11px;
            color: var(--dashboard-blue);
            background: var(--dashboard-white);
            transition:
                color 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease;
        }

        .dashboard-order-action:hover {
            color: var(--dashboard-blue-dark);
            border-color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
        }

        .dashboard-order-action svg {
            width: 16px;
            height: 16px;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile Order Cards
        |--------------------------------------------------------------------------
        */

        .dashboard-mobile-orders {
            display: none;
            padding: 15px;
        }

        .dashboard-mobile-order-card {
            padding: 16px;
            border: 1px solid var(--dashboard-border);
            border-radius: 17px;
            background: var(--dashboard-white);
        }

        .dashboard-mobile-order-card + .dashboard-mobile-order-card {
            margin-top: 12px;
        }

        .dashboard-mobile-order-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .dashboard-mobile-order-code strong,
        .dashboard-mobile-order-code span {
            display: block;
        }

        .dashboard-mobile-order-code strong {
            color: var(--dashboard-dark);
            font-size: 12px;
        }

        .dashboard-mobile-order-code span {
            margin-top: 3px;
            color: var(--dashboard-muted);
            font-size: 9px;
        }

        .dashboard-mobile-order-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
        }

        .dashboard-mobile-order-info {
            padding: 11px;
            border-radius: 13px;
            background: var(--dashboard-soft);
        }

        .dashboard-mobile-order-info span,
        .dashboard-mobile-order-info strong {
            display: block;
        }

        .dashboard-mobile-order-info span {
            color: var(--dashboard-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dashboard-mobile-order-info strong {
            margin-top: 4px;
            color: var(--dashboard-dark);
            font-size: 10px;
            line-height: 1.45;
        }

        .dashboard-mobile-order-action {
            width: 100%;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 12px;
            border: 1px solid #b9d0ff;
            border-radius: 12px;
            color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
            font-size: 10px;
            font-weight: 900;
        }

        .dashboard-mobile-order-action:hover {
            color: var(--dashboard-blue-dark);
            border-color: var(--dashboard-blue);
        }

        .dashboard-mobile-order-action svg {
            width: 15px;
            height: 15px;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */

        .dashboard-empty-state {
            padding: 52px 24px;
            text-align: center;
        }

        .dashboard-empty-icon {
            width: 67px;
            height: 67px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 17px;
            border-radius: 21px;
            color: var(--dashboard-blue);
            background: var(--dashboard-blue-soft);
        }

        .dashboard-empty-icon svg {
            width: 32px;
            height: 32px;
        }

        .dashboard-empty-state h3 {
            margin: 0 0 8px;
            color: var(--dashboard-dark);
            font-size: 20px;
        }

        .dashboard-empty-state p {
            max-width: 490px;
            margin: 0 auto 20px;
            color: var(--dashboard-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        /*
        |--------------------------------------------------------------------------
        | Latest Order Sidebar
        |--------------------------------------------------------------------------
        */

        .dashboard-latest-card {
            position: relative;
            overflow: hidden;
            padding: 24px;
            border-radius: 23px;
            color: var(--dashboard-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.4),
                    transparent 37%
                ),
                linear-gradient(
                    145deg,
                    var(--dashboard-blue-dark),
                    var(--dashboard-blue)
                );
            box-shadow:
                0 20px 54px rgba(21, 94, 239, 0.23);
        }

        .dashboard-latest-card::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -55px;
            width: 165px;
            height: 165px;
            border: 27px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .dashboard-latest-content {
            position: relative;
            z-index: 2;
        }

        .dashboard-latest-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            border: 1px solid rgba(255, 255, 255, 0.19);
            border-radius: 999px;
            color: #dbeafe;
            background: rgba(255, 255, 255, 0.10);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .dashboard-latest-label::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #fdba74;
        }

        .dashboard-latest-card h3 {
            margin: 17px 0 7px;
            color: var(--dashboard-white);
            font-size: 21px;
        }

        .dashboard-latest-card > .dashboard-latest-content > p {
            margin: 0;
            color: #dbeafe;
            font-size: 10px;
            line-height: 1.6;
        }

        .dashboard-latest-order {
            margin-top: 19px;
            padding: 17px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 17px;
            background: rgba(255, 255, 255, 0.10);
        }

        .dashboard-latest-order-code {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 13px;
        }

        .dashboard-latest-order-code strong {
            color: var(--dashboard-white);
            font-size: 13px;
        }

        .dashboard-latest-order-code span {
            color: #bfdbfe;
            font-size: 8px;
        }

        .dashboard-latest-order-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
        }

        .dashboard-latest-order-info {
            padding: 10px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.09);
        }

        .dashboard-latest-order-info span,
        .dashboard-latest-order-info strong {
            display: block;
        }

        .dashboard-latest-order-info span {
            color: #bfdbfe;
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dashboard-latest-order-info strong {
            margin-top: 4px;
            color: var(--dashboard-white);
            font-size: 10px;
            line-height: 1.4;
        }

        .dashboard-latest-button {
            width: 100%;
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 14px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 12px;
            color: var(--dashboard-blue-dark);
            background: var(--dashboard-white);
            font-size: 10px;
            font-weight: 900;
        }

        .dashboard-latest-button:hover {
            color: var(--dashboard-blue-dark);
        }

        .dashboard-latest-button svg {
            width: 15px;
            height: 15px;
        }

        /*
        |--------------------------------------------------------------------------
        | Order Flow
        |--------------------------------------------------------------------------
        */

        .dashboard-flow-card {
            padding: 23px;
            border: 1px solid var(--dashboard-border);
            border-radius: 22px;
            background: var(--dashboard-white);
            box-shadow:
                0 9px 30px rgba(16, 24, 40, 0.055);
        }

        .dashboard-flow-header {
            margin-bottom: 18px;
        }

        .dashboard-flow-header h3 {
            margin: 0 0 5px;
            color: var(--dashboard-dark);
            font-size: 18px;
        }

        .dashboard-flow-header p {
            margin: 0;
            color: var(--dashboard-muted);
            font-size: 10px;
            line-height: 1.55;
        }

        .dashboard-flow-list {
            position: relative;
            display: grid;
            gap: 5px;
        }

        .dashboard-flow-list::before {
            content: "";
            position: absolute;
            top: 22px;
            bottom: 22px;
            left: 18px;
            width: 2px;
            background:
                linear-gradient(
                    180deg,
                    var(--dashboard-blue),
                    var(--dashboard-orange),
                    var(--dashboard-green)
                );
        }

        .dashboard-flow-item {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
        }

        .dashboard-flow-number {
            position: relative;
            z-index: 2;
            width: 37px;
            height: 37px;
            flex: 0 0 37px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 5px solid var(--dashboard-white);
            border-radius: 999px;
            color: var(--dashboard-white);
            background: var(--dashboard-blue);
            font-size: 9px;
            font-weight: 900;
            box-shadow:
                0 5px 14px rgba(21, 94, 239, 0.18);
        }

        .dashboard-flow-item:nth-child(2)
        .dashboard-flow-number {
            background: var(--dashboard-orange);
        }

        .dashboard-flow-item:nth-child(3)
        .dashboard-flow-number {
            background: var(--dashboard-purple);
        }

        .dashboard-flow-item:nth-child(4)
        .dashboard-flow-number {
            background: var(--dashboard-green);
        }

        .dashboard-flow-copy strong,
        .dashboard-flow-copy span {
            display: block;
        }

        .dashboard-flow-copy strong {
            color: var(--dashboard-dark);
            font-size: 11px;
        }

        .dashboard-flow-copy span {
            margin-top: 3px;
            color: var(--dashboard-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | Help Card
        |--------------------------------------------------------------------------
        */

        .dashboard-help-card {
            padding: 23px;
            border: 1px solid #fed7aa;
            border-radius: 22px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.09),
                    transparent 36%
                ),
                var(--dashboard-orange-soft);
        }

        .dashboard-help-icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border-radius: 15px;
            color: var(--dashboard-white);
            background:
                linear-gradient(
                    135deg,
                    var(--dashboard-orange),
                    #fb923c
                );
        }

        .dashboard-help-icon svg {
            width: 22px;
            height: 22px;
        }

        .dashboard-help-card h3 {
            margin: 0 0 7px;
            color: var(--dashboard-dark);
            font-size: 18px;
        }

        .dashboard-help-card p {
            margin: 0;
            color: var(--dashboard-muted);
            font-size: 10px;
            line-height: 1.65;
        }

        .dashboard-help-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 16px;
            color: var(--dashboard-orange-dark);
            font-size: 10px;
            font-weight: 900;
        }

        .dashboard-help-link:hover {
            color: var(--dashboard-orange-dark);
        }

        .dashboard-help-link svg {
            width: 15px;
            height: 15px;
            transition: transform 0.2s ease;
        }

        .dashboard-help-link:hover svg {
            transform: translateX(3px);
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .dashboard-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .dashboard-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1100px) {
            .dashboard-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(300px, 0.48fr);
                gap: 34px;
            }

            .dashboard-overview-card {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

            .dashboard-overview-progress {
                grid-column: 1 / -1;
                border-top: 1px solid var(--dashboard-border);
            }

            .dashboard-overview-item:nth-child(3) {
                border-right: 0;
            }

            .dashboard-main-grid {
                grid-template-columns:
                    minmax(0, 1.18fr)
                    minmax(280px, 0.62fr);
            }

            .dashboard-stat-grid,
            .dashboard-action-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .dashboard-stat-card:last-child,
            .dashboard-action-card:last-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 900px) {
            .dashboard-hero {
                padding: 48px 0 75px;
            }

            .dashboard-hero-grid,
            .dashboard-main-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-profile-card {
                max-width: 620px;
            }

            .dashboard-main-column,
            .dashboard-sidebar-column {
                gap: 21px;
            }

            .dashboard-sidebar-column {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .dashboard-latest-card {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 720px) {
            .dashboard-overview-card {
                grid-template-columns: 1fr;
            }

            .dashboard-overview-item {
                border-right: 0;
                border-bottom: 1px solid var(--dashboard-border);
            }

            .dashboard-overview-item:nth-child(3) {
                border-bottom: 1px solid var(--dashboard-border);
            }

            .dashboard-overview-progress {
                grid-column: auto;
                border-top: 0;
            }

            .dashboard-stat-grid,
            .dashboard-action-grid,
            .dashboard-sidebar-column {
                grid-template-columns: 1fr;
            }

            .dashboard-stat-card:last-child,
            .dashboard-action-card:last-child,
            .dashboard-latest-card {
                grid-column: auto;
            }

            .dashboard-table-wrapper {
                display: none;
            }

            .dashboard-mobile-orders {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .dashboard-hero {
                padding: 42px 0 68px;
            }

            .dashboard-hero::before,
            .dashboard-hero::after {
                display: none;
            }

            .dashboard-hero h1 {
                margin-top: 18px;
                font-size: 37px;
                letter-spacing: -1.4px;
            }

            .dashboard-hero-description {
                font-size: 13px;
            }

            .dashboard-hero-actions {
                flex-direction: column;
            }

            .dashboard-hero-actions
            .dashboard-button {
                width: 100%;
            }

            .dashboard-profile-card {
                padding: 20px;
                border-radius: 22px;
            }

            .dashboard-profile-avatar {
                width: 55px;
                height: 55px;
                flex-basis: 55px;
                border-radius: 17px;
                font-size: 21px;
            }

            .dashboard-overview-strip {
                margin-top: -31px;
            }

            .dashboard-overview-card {
                border-radius: 20px;
            }

            .dashboard-overview-item {
                padding: 17px;
            }

            .dashboard-overview-progress {
                padding: 17px;
            }

            .dashboard-section {
                padding: 29px 0 61px;
            }

            .dashboard-section-header,
            .dashboard-panel-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .dashboard-section-header
            .dashboard-button,
            .dashboard-panel-header
            .dashboard-button {
                width: 100%;
            }

            .dashboard-stat-card,
            .dashboard-action-card {
                padding: 19px;
                border-radius: 18px;
            }

            .dashboard-stat-card p,
            .dashboard-action-card p {
                min-height: 0;
            }

            .dashboard-panel {
                border-radius: 20px;
            }

            .dashboard-panel-header {
                padding: 20px;
            }

            .dashboard-mobile-order-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-latest-card,
            .dashboard-flow-card,
            .dashboard-help-card {
                padding: 20px;
                border-radius: 19px;
            }
        }

        @media (max-width: 390px) {
            .dashboard-hero h1 {
                font-size: 34px;
            }

            .dashboard-profile-main {
                align-items: flex-start;
            }

            .dashboard-profile-status {
                align-items: flex-start;
                flex-direction: column;
            }

            .dashboard-progress-ring {
                width: 56px;
                height: 56px;
                flex-basis: 56px;
            }

            .dashboard-progress-ring::before {
                width: 43px;
                height: 43px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .dashboard-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .dashboard-stat-card,
            .dashboard-action-card,
            .dashboard-button,
            .dashboard-action-link svg {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="customer-dashboard-page">
        {{-- Hero --}}
        <section class="dashboard-hero">
            <div class="container dashboard-hero-grid">
                <div class="dashboard-hero-content dashboard-reveal">
                    <span class="dashboard-hero-badge">
                        <span class="dashboard-hero-badge-icon">
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
                                <path d="M7 9h10"/>
                                <path d="M7 13h6"/>
                            </svg>
                        </span>

                        Dashboard Pelanggan
                    </span>

                    <h1>
                        {{ $greeting }},
                        <span>{{ $namaPelanggan }}</span>
                    </h1>

                    <p class="dashboard-hero-description">
                        Kelola seluruh aktivitas cetak dalam satu
                        dashboard. Buat pesanan baru, unggah file,
                        periksa pembayaran, dan pantau perkembangan
                        pengerjaan pesananmu.
                    </p>

                    <div class="dashboard-hero-actions">
                        <a
                            href="{{ route('customer.pesanan.create') }}"
                            class="dashboard-button dashboard-button-orange"
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
                            href="{{ route('customer.pesanan.index') }}"
                            class="dashboard-button dashboard-button-outline"
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

                            Lihat Pesanan Saya
                        </a>
                    </div>
                </div>

                <aside class="dashboard-profile-card dashboard-reveal">
                    <div class="dashboard-profile-main">
                        <span class="dashboard-profile-avatar">
                            {{ $inisialPelanggan }}
                        </span>

                        <div class="dashboard-profile-copy">
                            <strong>
                                {{ $namaPelanggan }}
                            </strong>

                            <span>
                                {{ $user->email }}
                            </span>

                            <span>
                                {{
                                    $user->nomor_whatsapp
                                        ?? 'Nomor WhatsApp belum diisi'
                                }}
                            </span>
                        </div>
                    </div>

                    <div class="dashboard-profile-divider"></div>

                    <div class="dashboard-profile-status">
                        <div class="dashboard-profile-status-copy">
                            <span>Status Akun</span>
                            <strong>Pelanggan Terdaftar</strong>
                        </div>

                        <span class="dashboard-account-badge">
                            Aktif
                        </span>
                    </div>
                </aside>
            </div>
        </section>

        {{-- Overview Strip --}}
        <section class="dashboard-overview-strip">
            <div class="container">
                <div class="dashboard-overview-card dashboard-reveal">
                    <div class="dashboard-overview-item">
                        <span class="dashboard-overview-icon">
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

                        <span class="dashboard-overview-copy">
                            <strong>{{ $totalPesanan }}</strong>
                            <span>Total Pesanan</span>
                        </span>
                    </div>

                    <div class="dashboard-overview-item">
                        <span class="dashboard-overview-icon">
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

                        <span class="dashboard-overview-copy">
                            <strong>{{ $pesananAktif }}</strong>
                            <span>Pesanan Aktif</span>
                        </span>
                    </div>

                    <div class="dashboard-overview-item">
                        <span class="dashboard-overview-icon">
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

                        <span class="dashboard-overview-copy">
                            <strong>{{ $pesananSelesai }}</strong>
                            <span>Pesanan Selesai</span>
                        </span>
                    </div>

                    <div class="dashboard-overview-progress">
                        <span
                            class="dashboard-progress-ring"
                            style="--ring-value: {{
                                $persentaseSelesai * 3.6
                            }}deg;"
                        >
                            <strong>
                                {{ $persentaseSelesai }}%
                            </strong>
                        </span>

                        <span class="dashboard-progress-copy">
                            <strong>Tingkat Penyelesaian</strong>

                            <span>
                                Perbandingan pesanan selesai dengan
                                seluruh pesanan yang pernah dibuat.
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Dashboard Main --}}
        <section class="dashboard-section">
            <div class="container">
                <div class="dashboard-main-grid">
                    {{-- Main Column --}}
                    <div class="dashboard-main-column">
                        {{-- Statistics --}}
                        <section>
                            <div class="dashboard-section-header">
                                <div class="dashboard-section-heading">
                                    <h2>Ringkasan Pesanan</h2>

                                    <p>
                                        Lihat kondisi seluruh pesanan
                                        yang terhubung dengan akunmu.
                                    </p>
                                </div>
                            </div>

                            <div class="dashboard-stat-grid">
                                <article class="dashboard-stat-card dashboard-reveal">
                                    <div class="dashboard-stat-header">
                                        <span class="dashboard-stat-icon">
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

                                        <span class="dashboard-stat-label">
                                            Semua
                                        </span>
                                    </div>

                                    <strong class="dashboard-stat-number">
                                        {{ $totalPesanan }}
                                    </strong>

                                    <p>
                                        Seluruh pesanan yang pernah
                                        dibuat menggunakan akun
                                        pelanggan ini.
                                    </p>

                                    <div class="dashboard-stat-footer">
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

                                        Riwayat pesanan tersimpan
                                    </div>
                                </article>

                                <article class="dashboard-stat-card dashboard-reveal">
                                    <div class="dashboard-stat-header">
                                        <span class="dashboard-stat-icon">
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

                                        <span class="dashboard-stat-label">
                                            Berjalan
                                        </span>
                                    </div>

                                    <strong class="dashboard-stat-number">
                                        {{ $pesananAktif }}
                                    </strong>

                                    <p>
                                        Pesanan yang masih menunggu
                                        verifikasi, sedang diproses,
                                        atau siap diambil.
                                    </p>

                                    <div class="dashboard-stat-footer">
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

                                        Perlu dipantau secara berkala
                                    </div>
                                </article>

                                <article class="dashboard-stat-card dashboard-reveal">
                                    <div class="dashboard-stat-header">
                                        <span class="dashboard-stat-icon">
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

                                        <span class="dashboard-stat-label">
                                            Selesai
                                        </span>
                                    </div>

                                    <strong class="dashboard-stat-number">
                                        {{ $pesananSelesai }}
                                    </strong>

                                    <p>
                                        Pesanan yang telah selesai
                                        dikerjakan dan ditutup oleh
                                        admin.
                                    </p>

                                    <div class="dashboard-stat-footer">
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

                                        {{ $persentaseSelesai }}% dari total pesanan
                                    </div>
                                </article>
                            </div>
                        </section>

                        {{-- Quick Actions --}}
                        <section>
                            <div class="dashboard-section-header">
                                <div class="dashboard-section-heading">
                                    <h2>Akses Cepat</h2>

                                    <p>
                                        Pilih tindakan yang ingin
                                        dilakukan pada akun pelanggan.
                                    </p>
                                </div>
                            </div>

                            <div class="dashboard-action-grid">
                                <a
                                    href="{{ route('customer.pesanan.create') }}"
                                    class="dashboard-action-card primary dashboard-reveal"
                                >
                                    <span class="dashboard-action-icon">
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

                                    <h3>Buat Pesanan</h3>

                                    <p>
                                        Mulai pesanan cetak baru,
                                        pilih layanan, dan unggah file
                                        dokumenmu.
                                    </p>

                                    <span class="dashboard-action-link">
                                        Buat sekarang

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
                                    </span>
                                </a>

                                <a
                                    href="{{ route('customer.pesanan.index') }}"
                                    class="dashboard-action-card dashboard-reveal"
                                >
                                    <span class="dashboard-action-icon">
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

                                    <h3>Pesanan Saya</h3>

                                    <p>
                                        Lihat daftar, status,
                                        pembayaran, dan detail seluruh
                                        pesanan.
                                    </p>

                                    <span class="dashboard-action-link">
                                        Lihat pesanan

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
                                    </span>
                                </a>

                                <a
                                    href="{{ route('customer.profil.edit') }}"
                                    class="dashboard-action-card dashboard-reveal"
                                >
                                    <span class="dashboard-action-icon">
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

                                    <h3>Kelola Profil</h3>

                                    <p>
                                        Perbarui nama, email, nomor
                                        WhatsApp, dan keamanan akun.
                                    </p>

                                    <span class="dashboard-action-link">
                                        Edit profil

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
                                    </span>
                                </a>
                            </div>
                        </section>

                        {{-- Latest Orders --}}
                        <section class="dashboard-panel dashboard-reveal">
                            <div class="dashboard-panel-header">
                                <div class="dashboard-panel-title">
                                    <span class="dashboard-panel-title-icon">
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

                                    <div>
                                        <h2>Pesanan Terbaru</h2>

                                        <p>
                                            {{ $jumlahPesananTerbaru }}
                                            pesanan terakhir yang
                                            ditampilkan.
                                        </p>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('customer.pesanan.index') }}"
                                    class="dashboard-button dashboard-button-outline"
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

                            @if ($pesananTerbaru->isNotEmpty())
                                {{-- Desktop Table --}}
                                <div class="dashboard-table-wrapper">
                                    <table class="dashboard-order-table">
                                        <thead>
                                            <tr>
                                                <th>Pesanan</th>
                                                <th>Tanggal Pesan</th>
                                                <th>Pengambilan</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($pesananTerbaru as $pesanan)
                                                @php
                                                    $labelStatus = match (
                                                        $pesanan->status_pesanan
                                                    ) {
                                                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                                        'diproses' => 'Diproses',
                                                        'siap_diambil' => 'Siap Diambil',
                                                        'selesai' => 'Selesai',
                                                        'dibatalkan' => 'Dibatalkan',
                                                        default => ucwords(
                                                            str_replace(
                                                                '_',
                                                                ' ',
                                                                $pesanan->status_pesanan
                                                            )
                                                        ),
                                                    };
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <span class="dashboard-order-code">
                                                            <span class="dashboard-order-code-icon">
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
                                                                <strong>
                                                                    {{ $pesanan->kode_pesanan }}
                                                                </strong>

                                                                <span>
                                                                    Pesanan cetak
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="dashboard-order-date">
                                                            <strong>
                                                                {{
                                                                    $pesanan
                                                                        ->tanggal_pesan
                                                                        ?->format('d M Y')
                                                                    ?? '-'
                                                                }}
                                                            </strong>

                                                            <span>
                                                                Tanggal dibuat
                                                            </span>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="dashboard-order-date">
                                                            <strong>
                                                                {{
                                                                    $pesanan
                                                                        ->tanggal_pengambilan
                                                                        ?->format('d M Y')
                                                                    ?? '-'
                                                                }}
                                                            </strong>

                                                            <span>
                                                                Jadwal pengambilan
                                                            </span>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="dashboard-order-price">
                                                            Rp {{
                                                                number_format(
                                                                    (float) $pesanan->total_harga,
                                                                    0,
                                                                    ',',
                                                                    '.'
                                                                )
                                                            }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="dashboard-status-pill dashboard-status-{{
                                                            $pesanan->status_pesanan
                                                        }}">
                                                            {{ $labelStatus }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <a
                                                            href="{{
                                                                route(
                                                                    'customer.pesanan.show',
                                                                    $pesanan
                                                                )
                                                            }}"
                                                            class="dashboard-order-action"
                                                            aria-label="Lihat detail pesanan {{ $pesanan->kode_pesanan }}"
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
                                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                                                <circle cx="12" cy="12" r="3"/>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Mobile Cards --}}
                                <div class="dashboard-mobile-orders">
                                    @foreach ($pesananTerbaru as $pesanan)
                                        @php
                                            $labelStatusMobile = match (
                                                $pesanan->status_pesanan
                                            ) {
                                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                                'diproses' => 'Diproses',
                                                'siap_diambil' => 'Siap Diambil',
                                                'selesai' => 'Selesai',
                                                'dibatalkan' => 'Dibatalkan',
                                                default => ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $pesanan->status_pesanan
                                                    )
                                                ),
                                            };
                                        @endphp

                                        <article class="dashboard-mobile-order-card">
                                            <div class="dashboard-mobile-order-header">
                                                <span class="dashboard-mobile-order-code">
                                                    <strong>
                                                        {{ $pesanan->kode_pesanan }}
                                                    </strong>

                                                    <span>
                                                        {{
                                                            $pesanan
                                                                ->tanggal_pesan
                                                                ?->format('d M Y')
                                                            ?? '-'
                                                        }}
                                                    </span>
                                                </span>

                                                <span class="dashboard-status-pill dashboard-status-{{
                                                    $pesanan->status_pesanan
                                                }}">
                                                    {{ $labelStatusMobile }}
                                                </span>
                                            </div>

                                            <div class="dashboard-mobile-order-grid">
                                                <div class="dashboard-mobile-order-info">
                                                    <span>Pengambilan</span>

                                                    <strong>
                                                        {{
                                                            $pesanan
                                                                ->tanggal_pengambilan
                                                                ?->format('d M Y')
                                                            ?? '-'
                                                        }}
                                                    </strong>
                                                </div>

                                                <div class="dashboard-mobile-order-info">
                                                    <span>Total</span>

                                                    <strong>
                                                        Rp {{
                                                            number_format(
                                                                (float) $pesanan->total_harga,
                                                                0,
                                                                ',',
                                                                '.'
                                                            )
                                                        }}
                                                    </strong>
                                                </div>
                                            </div>

                                            <a
                                                href="{{
                                                    route(
                                                        'customer.pesanan.show',
                                                        $pesanan
                                                    )
                                                }}"
                                                class="dashboard-mobile-order-action"
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
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="dashboard-empty-state">
                                    <span class="dashboard-empty-icon">
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

                                    <h3>Belum ada pesanan</h3>

                                    <p>
                                        Kamu belum membuat pesanan.
                                        Pilih layanan, lengkapi detail
                                        cetak, dan unggah file untuk
                                        membuat pesanan pertama.
                                    </p>

                                    <a
                                        href="{{ route('customer.pesanan.create') }}"
                                        class="dashboard-button dashboard-button-primary"
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
                        </section>
                    </div>

                    {{-- Sidebar --}}
                    <aside class="dashboard-sidebar-column">
                        {{-- Latest Order --}}
                        <section class="dashboard-latest-card dashboard-reveal">
                            <div class="dashboard-latest-content">
                                <span class="dashboard-latest-label">
                                    Pesanan terakhir
                                </span>

                                @if ($pesananTerakhir)
                                    <h3>
                                        {{ $pesananTerakhir->kode_pesanan }}
                                    </h3>

                                    <p>
                                        Pantau informasi utama dari
                                        pesanan yang terakhir dibuat.
                                    </p>

                                    <div class="dashboard-latest-order">
                                        <div class="dashboard-latest-order-code">
                                            <strong>
                                                {{ $statusPesananTerakhir }}
                                            </strong>

                                            <span>
                                                {{
                                                    $pesananTerakhir
                                                        ->tanggal_pesan
                                                        ?->format('d M Y')
                                                    ?? '-'
                                                }}
                                            </span>
                                        </div>

                                        <div class="dashboard-latest-order-grid">
                                            <div class="dashboard-latest-order-info">
                                                <span>Total</span>

                                                <strong>
                                                    Rp {{
                                                        number_format(
                                                            (float) $pesananTerakhir->total_harga,
                                                            0,
                                                            ',',
                                                            '.'
                                                        )
                                                    }}
                                                </strong>
                                            </div>

                                            <div class="dashboard-latest-order-info">
                                                <span>Pengambilan</span>

                                                <strong>
                                                    {{
                                                        $pesananTerakhir
                                                            ->tanggal_pengambilan
                                                            ?->format('d M Y')
                                                        ?? '-'
                                                    }}
                                                </strong>
                                            </div>
                                        </div>

                                        <a
                                            href="{{
                                                route(
                                                    'customer.pesanan.show',
                                                    $pesananTerakhir
                                                )
                                            }}"
                                            class="dashboard-latest-button"
                                        >
                                            Buka Detail Pesanan

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
                                @else
                                    <h3>Belum Ada Pesanan</h3>

                                    <p>
                                        Pesanan terakhir akan muncul
                                        setelah kamu membuat pesanan
                                        pertama.
                                    </p>

                                    <a
                                        href="{{ route('customer.pesanan.create') }}"
                                        class="dashboard-latest-button"
                                    >
                                        Buat Pesanan

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
                                @endif
                            </div>
                        </section>

                        {{-- Order Flow --}}
                        <section class="dashboard-flow-card dashboard-reveal">
                            <div class="dashboard-flow-header">
                                <h3>Alur Pesanan</h3>

                                <p>
                                    Tahapan umum yang akan dilalui
                                    setelah pesanan dikirim.
                                </p>
                            </div>

                            <div class="dashboard-flow-list">
                                <div class="dashboard-flow-item">
                                    <span class="dashboard-flow-number">
                                        01
                                    </span>

                                    <span class="dashboard-flow-copy">
                                        <strong>Verifikasi</strong>

                                        <span>
                                            Admin memeriksa detail
                                            pesanan dan file.
                                        </span>
                                    </span>
                                </div>

                                <div class="dashboard-flow-item">
                                    <span class="dashboard-flow-number">
                                        02
                                    </span>

                                    <span class="dashboard-flow-copy">
                                        <strong>Diproses</strong>

                                        <span>
                                            Pesanan mulai dikerjakan
                                            sesuai detail.
                                        </span>
                                    </span>
                                </div>

                                <div class="dashboard-flow-item">
                                    <span class="dashboard-flow-number">
                                        03
                                    </span>

                                    <span class="dashboard-flow-copy">
                                        <strong>Siap Diambil</strong>

                                        <span>
                                            Pesanan sudah dapat diambil
                                            pelanggan.
                                        </span>
                                    </span>
                                </div>

                                <div class="dashboard-flow-item">
                                    <span class="dashboard-flow-number">
                                        04
                                    </span>

                                    <span class="dashboard-flow-copy">
                                        <strong>Selesai</strong>

                                        <span>
                                            Pesanan ditutup setelah
                                            diterima pelanggan.
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </section>

                        {{-- Help --}}
                        <section class="dashboard-help-card dashboard-reveal">
                            <span class="dashboard-help-icon">
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

                            <h3>Butuh Bantuan?</h3>

                            <p>
                                Hubungi admin apabila ada pertanyaan
                                mengenai layanan, pembayaran, file,
                                atau status pesanan.
                            </p>

                            <a
                                href="{{ route('kontak.index') }}"
                                class="dashboard-help-link"
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
                        </section>
                    </aside>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealElements = document.querySelectorAll(
                '.dashboard-reveal'
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