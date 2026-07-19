@extends('layouts.customer')

@section('title', 'Detail Pesanan - Tukang Print Dadakan')

@php
    $pembayaran = $pesanan->pembayaran;
    $pengiriman = $pesanan->pengiriman;

    $statusPesanan = $pesanan->status_pesanan;
    $statusPembayaran = $pembayaran?->status_pembayaran;
    $metodePembayaran = $pembayaran?->metode_pembayaran;

    $statusPembayaranLunas = in_array(
        $statusPembayaran,
        ['lunas', 'settlement', 'capture'],
        true
    );

    $pesananDibatalkan = $statusPesanan === 'dibatalkan';

    $bisaBayarMidtrans =
        $metodePembayaran === 'transfer'
        && ! $statusPembayaranLunas
        && ! $pesananDibatalkan;

    $bisaCekMidtrans =
        $bisaBayarMidtrans
        && filled($pembayaran?->midtrans_order_id);

    $bisaDibatalkan =
        $statusPesanan === 'menunggu_verifikasi'
        && ! $statusPembayaranLunas;

    $labelStatusPesanan = match ($statusPesanan) {
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'diproses' => 'Sedang Diproses',
        'siap_diambil' => 'Siap Diambil',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        default => $statusPesanan
            ? ucwords(str_replace('_', ' ', $statusPesanan))
            : 'Belum Diketahui',
    };

    $classStatusPesanan = match ($statusPesanan) {
        'menunggu_verifikasi' => 'waiting',
        'diproses' => 'processing',
        'siap_diambil' => 'ready',
        'selesai' => 'completed',
        'dibatalkan' => 'cancelled',
        default => 'neutral',
    };

    $labelStatusPembayaran = match ($statusPembayaran) {
        'belum_bayar' => 'Belum Bayar',
        'pending' => 'Menunggu Pembayaran',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'lunas', 'settlement', 'capture' => 'Lunas',
        'ditolak', 'deny' => 'Ditolak',
        'cancel' => 'Dibatalkan',
        'expire' => 'Kedaluwarsa',
        'refund', 'partial_refund' => 'Dikembalikan',
        default => 'Belum Ada Pembayaran',
    };

    $classStatusPembayaran = match ($statusPembayaran) {
        'belum_bayar', 'pending' => 'unpaid',
        'menunggu_verifikasi' => 'verification',
        'lunas', 'settlement', 'capture' => 'paid',
        'ditolak', 'deny', 'cancel', 'expire' => 'rejected',
        'refund', 'partial_refund' => 'refunded',
        default => 'neutral',
    };

    $labelMetodePembayaran = match ($metodePembayaran) {
        'cash' => 'Cash',
        'transfer' => 'Online via Midtrans',
        default => $metodePembayaran
            ? ucwords(str_replace('_', ' ', $metodePembayaran))
            : 'Belum Ditentukan',
    };

    $labelMetodePengiriman = match ($pengiriman?->metode_pengiriman) {
        'ambil_di_kampus' => 'Ambil di Kampus',
        'antar' => 'Diantar',
        'ojek_online' => 'Ojek Online',
        default => $pesanan->lokasi_pengambilan ?? '-',
    };

    $labelStatusPengiriman = match ($pengiriman?->status_pengiriman) {
        'belum_dikirim' => 'Belum Dikirim',
        'diproses' => 'Sedang Diproses',
        'dikirim' => 'Sedang Dikirim',
        'selesai' => 'Selesai',
        default => '-',
    };

    $paymentType = $pembayaran?->payment_type
        ? ucwords(str_replace('_', ' ', $pembayaran->payment_type))
        : '-';

    $tanggalPesan = $pesanan->tanggal_pesan?->format('d M Y')
        ?? $pesanan->created_at?->format('d M Y')
        ?? '-';

    $tanggalPengambilan = $pesanan->tanggal_pengambilan?->format('d M Y')
        ?? '-';

    $jamPengambilan = $pesanan->jam_pengambilan?->format('H:i')
        ?? '-';

    $jumlahFile = $pesanan->detailPesanans->count();
    $jumlahRiwayat = $pesanan->riwayatStatusPesanans->count();
@endphp

@push('styles')
    <style>
        .order-show-page {
            --show-blue: var(--customer-blue, #155eef);
            --show-blue-dark: var(--customer-blue-dark, #1046b8);
            --show-blue-soft: var(--customer-blue-soft, #edf4ff);

            --show-orange: var(--customer-orange, #f97316);
            --show-orange-dark: var(--customer-orange-dark, #c2410c);
            --show-orange-soft: var(--customer-orange-soft, #fff1e7);

            --show-green: #16a34a;
            --show-green-dark: #15803d;
            --show-green-soft: #ecfdf3;

            --show-yellow: #d97706;
            --show-yellow-soft: #fffbeb;

            --show-red: #dc2626;
            --show-red-dark: #b42318;
            --show-red-soft: #fff1f2;

            --show-purple: #7c3aed;
            --show-purple-soft: #f3e8ff;

            --show-dark: #101828;
            --show-text: #344054;
            --show-muted: #667085;
            --show-white: #ffffff;
            --show-soft: #f7f9fc;
            --show-border: #e4e7ec;
            --show-border-dark: #d0d5dd;

            min-height: 100vh;
            overflow: hidden;
            background: #f8faff;
        }

        /*
        |--------------------------------------------------------------------------
        | Shared Buttons
        |--------------------------------------------------------------------------
        */

        .order-show-button {
            min-height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 15px;
            border: 1px solid transparent;
            border-radius: 13px;
            font-size: 10px;
            font-weight: 900;
            text-align: center;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease;
        }

        .order-show-button:hover {
            transform: translateY(-2px);
        }

        .order-show-button svg {
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
        }

        .order-show-button:disabled {
            cursor: not-allowed;
            opacity: 0.65;
            transform: none;
        }

        .order-show-button-primary {
            color: var(--show-white);
            background:
                linear-gradient(
                    135deg,
                    var(--show-blue),
                    #2b70ff
                );
            box-shadow:
                0 10px 23px rgba(21, 94, 239, 0.23);
        }

        .order-show-button-primary:hover {
            color: var(--show-white);
            background:
                linear-gradient(
                    135deg,
                    var(--show-blue-dark),
                    var(--show-blue)
                );
        }

        .order-show-button-orange {
            color: var(--show-white);
            background:
                linear-gradient(
                    135deg,
                    var(--show-orange),
                    #fb923c
                );
            box-shadow:
                0 10px 23px rgba(249, 115, 22, 0.23);
        }

        .order-show-button-orange:hover {
            color: var(--show-white);
            background:
                linear-gradient(
                    135deg,
                    var(--show-orange-dark),
                    var(--show-orange)
                );
        }

        .order-show-button-outline {
            color: var(--show-blue);
            border-color: #b9d0ff;
            background: var(--show-white);
        }

        .order-show-button-outline:hover {
            color: var(--show-blue-dark);
            border-color: var(--show-blue);
            background: var(--show-blue-soft);
        }

        .order-show-button-danger {
            color: var(--show-red-dark);
            border-color: #fecaca;
            background: var(--show-red-soft);
        }

        .order-show-button-danger:hover {
            color: var(--show-white);
            border-color: var(--show-red);
            background: var(--show-red);
        }

        .order-action-spinner {
            width: 16px;
            height: 16px;
            display: none;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: currentColor;
            border-radius: 999px;
            animation: order-show-spin 0.7s linear infinite;
        }

        .order-show-button.loading .order-action-spinner {
            display: inline-block;
        }

        .order-show-button.loading .order-action-icon {
            display: none;
        }

        @keyframes order-show-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .order-show-hero {
            position: relative;
            overflow: hidden;
            padding: 49px 0 77px;
            border-bottom: 1px solid var(--show-border);
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

        .order-show-hero::before {
            content: "";
            position: absolute;
            top: -110px;
            right: -85px;
            width: 290px;
            height: 290px;
            border: 44px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .order-show-hero::after {
            content: "";
            position: absolute;
            bottom: -125px;
            left: -100px;
            width: 280px;
            height: 280px;
            border: 43px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .order-show-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(330px, 0.48fr);
            gap: 45px;
            align-items: center;
        }

        .order-show-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 19px;
            color: var(--show-muted);
            font-size: 10px;
            font-weight: 800;
        }

        .order-show-breadcrumb a {
            color: var(--show-muted);
        }

        .order-show-breadcrumb a:hover {
            color: var(--show-blue);
        }

        .order-show-breadcrumb svg {
            width: 13px;
            height: 13px;
        }

        .order-show-breadcrumb strong {
            color: var(--show-blue);
        }

        .order-show-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 13px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--show-orange-dark);
            background: var(--show-orange-soft);
            font-size: 11px;
            font-weight: 900;
        }

        .order-show-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--show-white);
            background: var(--show-orange);
        }

        .order-show-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .order-show-hero h1 {
            margin: 20px 0 13px;
            color: var(--show-dark);
            font-size: clamp(36px, 5vw, 58px);
            line-height: 1.07;
            letter-spacing: -2px;
            overflow-wrap: anywhere;
        }

        .order-show-hero h1 span {
            position: relative;
            display: inline-block;
            color: var(--show-blue);
        }

        .order-show-hero h1 span::after {
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

        .order-show-description {
            max-width: 700px;
            margin: 0;
            color: var(--show-muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .order-show-hero-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 25px;
        }

        .order-show-hero-actions form {
            margin: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Status Card
        |--------------------------------------------------------------------------
        */

        .order-show-status-card {
            position: relative;
            padding: 24px;
            border: 1px solid rgba(228, 231, 236, 0.94);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            box-shadow:
                0 23px 62px rgba(16, 24, 40, 0.13);
        }

        .order-show-status-card::before {
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
                    var(--show-blue),
                    var(--show-orange)
                );
            transform: rotate(3deg);
        }

        .order-show-status-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .order-show-status-icon {
            width: 47px;
            height: 47px;
            flex: 0 0 47px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: var(--show-white);
            background:
                linear-gradient(
                    135deg,
                    var(--show-blue),
                    #2b70ff
                );
            box-shadow:
                0 9px 21px rgba(21, 94, 239, 0.2);
        }

        .order-show-status-icon svg {
            width: 23px;
            height: 23px;
        }

        .order-show-status-header strong,
        .order-show-status-header span {
            display: block;
        }

        .order-show-status-header strong {
            color: var(--show-dark);
            font-size: 16px;
        }

        .order-show-status-header span {
            margin-top: 3px;
            color: var(--show-muted);
            font-size: 9px;
        }

        .order-show-status-main {
            padding: 16px;
            border: 1px solid var(--show-border);
            border-radius: 16px;
            background: var(--show-soft);
        }

        .order-show-status-main > span {
            display: block;
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.055em;
        }

        .order-show-status-main > strong {
            display: block;
            margin-top: 7px;
            color: var(--show-dark);
            font-size: 16px;
        }

        .order-show-status-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
            margin-top: 10px;
        }

        .order-show-status-info {
            padding: 12px;
            border: 1px solid var(--show-border);
            border-radius: 13px;
            background: var(--show-white);
        }

        .order-show-status-info span,
        .order-show-status-info strong {
            display: block;
        }

        .order-show-status-info span {
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-show-status-info strong {
            margin-top: 5px;
            color: var(--show-dark);
            font-size: 10px;
            line-height: 1.4;
        }

        /*
        |--------------------------------------------------------------------------
        | Status Badges
        |--------------------------------------------------------------------------
        */

        .order-status-pill,
        .order-payment-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 30px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 900;
            white-space: nowrap;
        }

        .order-status-pill::before,
        .order-payment-pill::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 999px;
        }

        .order-status-pill.waiting {
            color: #92400e;
            background: var(--show-yellow-soft);
        }

        .order-status-pill.waiting::before {
            background: var(--show-yellow);
        }

        .order-status-pill.processing {
            color: var(--show-blue-dark);
            background: var(--show-blue-soft);
        }

        .order-status-pill.processing::before {
            background: var(--show-blue);
        }

        .order-status-pill.ready {
            color: #6b21a8;
            background: var(--show-purple-soft);
        }

        .order-status-pill.ready::before {
            background: var(--show-purple);
        }

        .order-status-pill.completed {
            color: #166534;
            background: var(--show-green-soft);
        }

        .order-status-pill.completed::before {
            background: var(--show-green);
        }

        .order-status-pill.cancelled {
            color: #991b1b;
            background: var(--show-red-soft);
        }

        .order-status-pill.cancelled::before {
            background: var(--show-red);
        }

        .order-status-pill.neutral,
        .order-payment-pill.neutral {
            color: var(--show-text);
            background: #f2f4f7;
        }

        .order-status-pill.neutral::before,
        .order-payment-pill.neutral::before {
            background: #98a2b3;
        }

        .order-payment-pill.unpaid {
            color: #92400e;
            background: var(--show-yellow-soft);
        }

        .order-payment-pill.unpaid::before {
            background: var(--show-yellow);
        }

        .order-payment-pill.verification {
            color: var(--show-blue-dark);
            background: var(--show-blue-soft);
        }

        .order-payment-pill.verification::before {
            background: var(--show-blue);
        }

        .order-payment-pill.paid {
            color: #166534;
            background: var(--show-green-soft);
        }

        .order-payment-pill.paid::before {
            background: var(--show-green);
        }

        .order-payment-pill.rejected {
            color: #991b1b;
            background: var(--show-red-soft);
        }

        .order-payment-pill.rejected::before {
            background: var(--show-red);
        }

        .order-payment-pill.refunded {
            color: #6b21a8;
            background: var(--show-purple-soft);
        }

        .order-payment-pill.refunded::before {
            background: var(--show-purple);
        }

        /*
        |--------------------------------------------------------------------------
        | Main Layout
        |--------------------------------------------------------------------------
        */

        .order-show-main {
            position: relative;
            z-index: 3;
            margin-top: -31px;
            padding-bottom: 84px;
        }

        .order-show-summary-strip {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid var(--show-border);
            border-radius: 21px;
            background: var(--show-white);
            box-shadow:
                0 16px 47px rgba(16, 24, 40, 0.09);
        }

        .order-show-summary-item {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
            padding: 18px;
            border-right: 1px solid var(--show-border);
        }

        .order-show-summary-item:last-child {
            border-right: 0;
        }

        .order-show-summary-icon {
            width: 41px;
            height: 41px;
            flex: 0 0 41px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--show-blue);
            background: var(--show-blue-soft);
        }

        .order-show-summary-item:nth-child(2)
        .order-show-summary-icon {
            color: var(--show-orange-dark);
            background: var(--show-orange-soft);
        }

        .order-show-summary-item:nth-child(3)
        .order-show-summary-icon {
            color: var(--show-purple);
            background: var(--show-purple-soft);
        }

        .order-show-summary-item:nth-child(4)
        .order-show-summary-icon {
            color: var(--show-green-dark);
            background: var(--show-green-soft);
        }

        .order-show-summary-icon svg {
            width: 20px;
            height: 20px;
        }

        .order-show-summary-copy {
            min-width: 0;
        }

        .order-show-summary-copy span,
        .order-show-summary-copy strong {
            display: block;
        }

        .order-show-summary-copy span {
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-show-summary-copy strong {
            margin-top: 4px;
            overflow-wrap: anywhere;
            color: var(--show-dark);
            font-size: 11px;
            line-height: 1.4;
        }

        .order-show-grid {
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(315px, 0.4fr);
            gap: 24px;
            align-items: start;
        }

        .order-show-content,
        .order-show-sidebar {
            display: grid;
            gap: 22px;
        }

        .order-show-sidebar {
            position: sticky;
            top: 105px;
        }

        /*
        |--------------------------------------------------------------------------
        | Section Card
        |--------------------------------------------------------------------------
        */

        .order-show-card {
            overflow: hidden;
            border: 1px solid var(--show-border);
            border-radius: 22px;
            background: var(--show-white);
            box-shadow:
                0 9px 31px rgba(16, 24, 40, 0.055);
        }

        .order-show-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 21px 23px;
            border-bottom: 1px solid var(--show-border);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.07),
                    transparent 35%
                ),
                #fcfcfd;
        }

        .order-show-card-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .order-show-card-title-icon {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--show-white);
            background:
                linear-gradient(
                    135deg,
                    var(--show-blue),
                    #2b70ff
                );
            box-shadow:
                0 8px 19px rgba(21, 94, 239, 0.18);
        }

        .order-show-card.files .order-show-card-title-icon {
            background:
                linear-gradient(
                    135deg,
                    var(--show-orange),
                    #fb923c
                );
            box-shadow:
                0 8px 19px rgba(249, 115, 22, 0.18);
        }

        .order-show-card.timeline-card
        .order-show-card-title-icon {
            background:
                linear-gradient(
                    135deg,
                    var(--show-purple),
                    #8b5cf6
                );
            box-shadow:
                0 8px 19px rgba(124, 58, 237, 0.18);
        }

        .order-show-card-title-icon svg {
            width: 21px;
            height: 21px;
        }

        .order-show-card-title h2 {
            margin: 0 0 4px;
            color: var(--show-dark);
            font-size: 19px;
        }

        .order-show-card-title p {
            margin: 0;
            color: var(--show-muted);
            font-size: 9px;
        }

        .order-show-card-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            min-height: 30px;
            padding: 5px 9px;
            border-radius: 999px;
            color: var(--show-blue-dark);
            background: var(--show-blue-soft);
            font-size: 9px;
            font-weight: 900;
        }

        .order-show-card-body {
            padding: 23px;
        }

        /*
        |--------------------------------------------------------------------------
        | Customer Information
        |--------------------------------------------------------------------------
        */

        .order-show-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .order-show-info-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px;
            border: 1px solid var(--show-border);
            border-radius: 15px;
            background: #fcfcfd;
        }

        .order-show-info-item.full {
            grid-column: 1 / -1;
        }

        .order-show-info-icon {
            width: 37px;
            height: 37px;
            flex: 0 0 37px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--show-blue);
            background: var(--show-blue-soft);
        }

        .order-show-info-item:nth-child(2)
        .order-show-info-icon {
            color: var(--show-orange-dark);
            background: var(--show-orange-soft);
        }

        .order-show-info-item:nth-child(3)
        .order-show-info-icon {
            color: var(--show-green-dark);
            background: var(--show-green-soft);
        }

        .order-show-info-icon svg {
            width: 17px;
            height: 17px;
        }

        .order-show-info-copy {
            min-width: 0;
        }

        .order-show-info-copy span,
        .order-show-info-copy strong,
        .order-show-info-copy small {
            display: block;
        }

        .order-show-info-copy span {
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-show-info-copy strong {
            margin-top: 5px;
            overflow-wrap: anywhere;
            color: var(--show-dark);
            font-size: 10px;
            line-height: 1.45;
        }

        .order-show-info-copy small {
            margin-top: 3px;
            color: var(--show-muted);
            font-size: 8px;
            line-height: 1.45;
        }

        /*
        |--------------------------------------------------------------------------
        | File Cards
        |--------------------------------------------------------------------------
        */

        .order-file-list {
            display: grid;
            gap: 14px;
        }

        .order-file-card {
            overflow: hidden;
            border: 1px solid var(--show-border);
            border-radius: 18px;
            background: var(--show-white);
        }

        .order-file-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            padding: 16px;
            border-bottom: 1px solid var(--show-border);
            background:
                linear-gradient(
                    135deg,
                    #fcfcfd,
                    var(--show-orange-soft)
                );
        }

        .order-file-identity {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .order-file-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--show-orange-dark);
            background: var(--show-white);
            box-shadow:
                0 7px 18px rgba(16, 24, 40, 0.07);
        }

        .order-file-icon svg {
            width: 21px;
            height: 21px;
        }

        .order-file-copy {
            min-width: 0;
        }

        .order-file-copy h3 {
            margin: 0;
            overflow: hidden;
            color: var(--show-dark);
            font-size: 13px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .order-file-copy p {
            margin: 4px 0 0;
            color: var(--show-muted);
            font-size: 9px;
            line-height: 1.45;
        }

        .order-file-extension {
            flex: 0 0 auto;
            padding: 6px 9px;
            border-radius: 999px;
            color: var(--show-orange-dark);
            background: var(--show-orange-soft);
            font-size: 8px;
            font-weight: 900;
        }

        .order-file-detail-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            padding: 4px 16px;
        }

        .order-file-detail-item {
            padding: 14px 12px;
            border-right: 1px solid var(--show-border);
            border-bottom: 1px solid var(--show-border);
        }

        .order-file-detail-item:nth-child(4n) {
            border-right: 0;
        }

        .order-file-detail-item:nth-last-child(-n + 3) {
            border-bottom: 0;
        }

        .order-file-detail-item span,
        .order-file-detail-item strong {
            display: block;
        }

        .order-file-detail-item span {
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-file-detail-item strong {
            margin-top: 5px;
            color: var(--show-dark);
            font-size: 10px;
            line-height: 1.4;
        }

        .order-file-detail-item.subtotal strong {
            color: var(--show-blue);
            font-size: 12px;
        }

        .order-file-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 0 16px 16px;
            padding: 12px;
            border: 1px solid #fed7aa;
            border-radius: 13px;
            color: var(--show-text);
            background: var(--show-orange-soft);
            font-size: 9px;
            line-height: 1.55;
        }

        .order-file-note svg {
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
            color: var(--show-orange);
        }

        /*
        |--------------------------------------------------------------------------
        | Timeline
        |--------------------------------------------------------------------------
        */

        .order-timeline {
            position: relative;
            display: grid;
        }

        .order-timeline::before {
            content: "";
            position: absolute;
            top: 20px;
            bottom: 20px;
            left: 20px;
            width: 2px;
            background:
                linear-gradient(
                    180deg,
                    var(--show-blue),
                    var(--show-orange),
                    var(--show-green)
                );
        }

        .order-timeline-item {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 10px 0;
        }

        .order-timeline-marker {
            position: relative;
            z-index: 2;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 5px solid var(--show-white);
            border-radius: 999px;
            color: var(--show-white);
            background: var(--show-blue);
            box-shadow:
                0 6px 16px rgba(21, 94, 239, 0.2);
        }

        .order-timeline-marker svg {
            width: 16px;
            height: 16px;
        }

        .order-timeline-item:last-child
        .order-timeline-marker {
            background: var(--show-green);
        }

        .order-timeline-content {
            flex: 1;
            padding: 13px 15px;
            border: 1px solid var(--show-border);
            border-radius: 15px;
            background: #fcfcfd;
        }

        .order-timeline-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .order-timeline-heading strong {
            color: var(--show-dark);
            font-size: 11px;
        }

        .order-timeline-heading time {
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 800;
            white-space: nowrap;
        }

        .order-timeline-content p {
            margin: 6px 0 0;
            color: var(--show-muted);
            font-size: 9px;
            line-height: 1.55;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */

        .order-show-empty {
            padding: 38px 20px;
            text-align: center;
        }

        .order-show-empty-icon {
            width: 60px;
            height: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            border-radius: 19px;
            color: var(--show-blue);
            background: var(--show-blue-soft);
        }

        .order-show-empty-icon svg {
            width: 28px;
            height: 28px;
        }

        .order-show-empty h3 {
            margin: 0 0 6px;
            color: var(--show-dark);
            font-size: 17px;
        }

        .order-show-empty p {
            margin: 0;
            color: var(--show-muted);
            font-size: 10px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | Sidebar Cards
        |--------------------------------------------------------------------------
        */

        .order-side-card {
            overflow: hidden;
            border: 1px solid var(--show-border);
            border-radius: 21px;
            background: var(--show-white);
            box-shadow:
                0 10px 34px rgba(16, 24, 40, 0.065);
        }

        .order-side-header {
            position: relative;
            overflow: hidden;
            padding: 20px;
            color: var(--show-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.4),
                    transparent 38%
                ),
                linear-gradient(
                    145deg,
                    var(--show-blue-dark),
                    var(--show-blue)
                );
        }

        .order-side-header.orange {
            background:
                radial-gradient(
                    circle at top right,
                    rgba(255, 255, 255, 0.16),
                    transparent 38%
                ),
                linear-gradient(
                    145deg,
                    var(--show-orange-dark),
                    var(--show-orange)
                );
        }

        .order-side-header.purple {
            background:
                radial-gradient(
                    circle at top right,
                    rgba(255, 255, 255, 0.15),
                    transparent 38%
                ),
                linear-gradient(
                    145deg,
                    #5b21b6,
                    var(--show-purple)
                );
        }

        .order-side-header h2 {
            position: relative;
            z-index: 2;
            margin: 0 0 4px;
            color: var(--show-white);
            font-size: 18px;
        }

        .order-side-header p {
            position: relative;
            z-index: 2;
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 9px;
            line-height: 1.5;
        }

        .order-side-body {
            padding: 18px;
        }

        /*
        |--------------------------------------------------------------------------
        | Cost Summary
        |--------------------------------------------------------------------------
        */

        .order-cost-rows {
            display: grid;
        }

        .order-cost-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 11px 0;
            border-bottom: 1px solid var(--show-border);
        }

        .order-cost-row span {
            color: var(--show-muted);
            font-size: 9px;
        }

        .order-cost-row strong {
            color: var(--show-dark);
            font-size: 10px;
            text-align: right;
        }

        .order-cost-total {
            margin-top: 15px;
            padding: 16px;
            border: 1px solid #cfe0ff;
            border-radius: 15px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.09),
                    transparent 38%
                ),
                var(--show-blue-soft);
        }

        .order-cost-total span,
        .order-cost-total strong {
            display: block;
        }

        .order-cost-total span {
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-cost-total strong {
            margin-top: 7px;
            color: var(--show-blue);
            font-size: 24px;
            line-height: 1;
        }

        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        */

        .order-payment-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 13px;
            padding: 14px;
            border: 1px solid var(--show-border);
            border-radius: 15px;
            background: var(--show-soft);
        }

        .order-payment-status-copy span,
        .order-payment-status-copy strong {
            display: block;
        }

        .order-payment-status-copy span {
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-payment-status-copy strong {
            margin-top: 5px;
            color: var(--show-dark);
            font-size: 11px;
        }

        .order-payment-data {
            display: grid;
        }

        .order-payment-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 10px 0;
            border-bottom: 1px solid var(--show-border);
        }

        .order-payment-row:last-child {
            border-bottom: 0;
        }

        .order-payment-row span {
            color: var(--show-muted);
            font-size: 8px;
        }

        .order-payment-row strong {
            max-width: 62%;
            color: var(--show-dark);
            font-size: 9px;
            line-height: 1.45;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .order-payment-actions {
            display: grid;
            gap: 9px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--show-border);
        }

        .order-payment-actions form,
        .order-payment-actions .order-show-button {
            width: 100%;
        }

        /*
        |--------------------------------------------------------------------------
        | Delivery
        |--------------------------------------------------------------------------
        */

        .order-delivery-box {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 14px;
            border: 1px solid #ddd6fe;
            border-radius: 15px;
            background: var(--show-purple-soft);
        }

        .order-delivery-icon {
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--show-purple);
            background: var(--show-white);
        }

        .order-delivery-icon svg {
            width: 19px;
            height: 19px;
        }

        .order-delivery-copy strong,
        .order-delivery-copy span {
            display: block;
        }

        .order-delivery-copy strong {
            color: var(--show-dark);
            font-size: 11px;
        }

        .order-delivery-copy span {
            margin-top: 4px;
            color: var(--show-muted);
            font-size: 9px;
            line-height: 1.5;
        }

        .order-delivery-data {
            display: grid;
            gap: 9px;
            margin-top: 13px;
        }

        .order-delivery-item {
            padding: 12px;
            border: 1px solid var(--show-border);
            border-radius: 13px;
            background: #fcfcfd;
        }

        .order-delivery-item span,
        .order-delivery-item strong {
            display: block;
        }

        .order-delivery-item span {
            color: var(--show-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-delivery-item strong {
            margin-top: 5px;
            color: var(--show-dark);
            font-size: 10px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

        .order-notes-card {
            padding: 18px;
            border: 1px solid #fed7aa;
            border-radius: 20px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.09),
                    transparent 38%
                ),
                var(--show-orange-soft);
        }

        .order-notes-icon {
            width: 41px;
            height: 41px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            border-radius: 13px;
            color: var(--show-white);
            background:
                linear-gradient(
                    135deg,
                    var(--show-orange),
                    #fb923c
                );
        }

        .order-notes-icon svg {
            width: 19px;
            height: 19px;
        }

        .order-notes-card h3 {
            margin: 0 0 6px;
            color: var(--show-dark);
            font-size: 15px;
        }

        .order-notes-card p {
            margin: 0;
            color: var(--show-muted);
            font-size: 9px;
            line-height: 1.6;
            white-space: pre-line;
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal
        |--------------------------------------------------------------------------
        */

        .order-show-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .order-show-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .order-show-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(300px, 0.45fr);
                gap: 34px;
            }

            .order-show-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(285px, 0.37fr);
            }

            .order-file-detail-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .order-file-detail-item:nth-child(4n) {
                border-right: 1px solid var(--show-border);
            }

            .order-file-detail-item:nth-child(3n) {
                border-right: 0;
            }

            .order-file-detail-item:nth-last-child(-n + 3) {
                border-bottom: 0;
            }
        }

        @media (max-width: 900px) {
            .order-show-hero-grid,
            .order-show-grid {
                grid-template-columns: 1fr;
            }

            .order-show-status-card {
                max-width: 650px;
            }

            .order-show-sidebar {
                position: static;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .order-side-card:first-child,
            .order-notes-card {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 760px) {
            .order-show-summary-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .order-show-summary-item:nth-child(2) {
                border-right: 0;
            }

            .order-show-summary-item:nth-child(-n + 2) {
                border-bottom: 1px solid var(--show-border);
            }

            .order-show-info-grid {
                grid-template-columns: 1fr;
            }

            .order-show-info-item.full {
                grid-column: auto;
            }

            .order-file-detail-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .order-file-detail-item,
            .order-file-detail-item:nth-child(3n),
            .order-file-detail-item:nth-child(4n) {
                border-right: 1px solid var(--show-border);
                border-bottom: 1px solid var(--show-border);
            }

            .order-file-detail-item:nth-child(2n) {
                border-right: 0;
            }

            .order-file-detail-item:last-child {
                border-bottom: 0;
            }

            .order-show-sidebar {
                grid-template-columns: 1fr;
            }

            .order-side-card:first-child,
            .order-notes-card {
                grid-column: auto;
            }
        }

        @media (max-width: 640px) {
            .order-show-hero {
                padding: 39px 0 65px;
            }

            .order-show-hero::before,
            .order-show-hero::after {
                display: none;
            }

            .order-show-hero h1 {
                margin-top: 17px;
                font-size: 34px;
                letter-spacing: -1.3px;
            }

            .order-show-description {
                font-size: 13px;
            }

            .order-show-hero-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .order-show-hero-actions form,
            .order-show-hero-actions .order-show-button {
                width: 100%;
            }

            .order-show-status-card {
                padding: 20px;
                border-radius: 21px;
            }

            .order-show-main {
                margin-top: -27px;
                padding-bottom: 63px;
            }

            .order-show-summary-strip {
                border-radius: 18px;
            }

            .order-show-summary-item {
                padding: 15px;
            }

            .order-show-card,
            .order-side-card {
                border-radius: 19px;
            }

            .order-show-card-header {
                align-items: flex-start;
                padding: 19px;
            }

            .order-show-card-title-icon {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
            }

            .order-show-card-body {
                padding: 19px;
            }

            .order-file-header {
                align-items: flex-start;
            }

            .order-file-copy h3 {
                max-width: 190px;
            }

            .order-timeline-heading {
                flex-direction: column;
                gap: 4px;
            }

            .order-timeline-heading time {
                white-space: normal;
            }
        }

        @media (max-width: 420px) {
            .order-show-summary-strip,
            .order-show-status-grid,
            .order-file-detail-grid {
                grid-template-columns: 1fr;
            }

            .order-show-summary-item {
                border-right: 0;
                border-bottom: 1px solid var(--show-border);
            }

            .order-show-summary-item:last-child {
                border-bottom: 0;
            }

            .order-file-detail-item,
            .order-file-detail-item:nth-child(2n),
            .order-file-detail-item:nth-child(3n),
            .order-file-detail-item:nth-child(4n) {
                border-right: 0;
                border-bottom: 1px solid var(--show-border);
            }

            .order-file-detail-item:last-child {
                border-bottom: 0;
            }

            .order-file-copy h3 {
                max-width: 150px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .order-show-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .order-show-button {
                transition: none;
            }

            .order-action-spinner {
                animation: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="order-show-page">
        {{-- Hero --}}
        <section class="order-show-hero">
            <div class="container order-show-hero-grid">
                <div class="order-show-reveal">
                    <nav
                        class="order-show-breadcrumb"
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

                        <a href="{{ route('customer.pesanan.index') }}">
                            Pesanan Saya
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

                        <strong>{{ $pesanan->kode_pesanan }}</strong>
                    </nav>

                    <span class="order-show-badge">
                        <span class="order-show-badge-icon">
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

                        Detail Pesanan
                    </span>

                    <h1>
                        Pesanan
                        <span>{{ $pesanan->kode_pesanan }}</span>
                    </h1>

                    <p class="order-show-description">
                        Periksa informasi pelanggan, detail file,
                        pembayaran, pengambilan, biaya, dan perkembangan
                        status pesanan pada halaman ini.
                    </p>

                    <div class="order-show-hero-actions">
                        <a
                            href="{{ route('customer.pesanan.index') }}"
                            class="order-show-button order-show-button-outline"
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

                        @if ($bisaBayarMidtrans)
                            <form
                                action="{{ route('customer.pesanan.pay-midtrans', $pesanan) }}"
                                method="POST"
                                data-order-action-form
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="order-show-button order-show-button-orange"
                                    data-order-action-button
                                >
                                    <span class="order-action-spinner"></span>

                                    <svg
                                        class="order-action-icon"
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

                                    <span data-action-text>
                                        Bayar Sekarang
                                    </span>
                                </button>
                            </form>
                        @endif

                        @if ($bisaCekMidtrans)
                            <form
                                action="{{ route('customer.pesanan.check-midtrans', $pesanan) }}"
                                method="POST"
                                data-order-action-form
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="order-show-button order-show-button-primary"
                                    data-order-action-button
                                >
                                    <span class="order-action-spinner"></span>

                                    <svg
                                        class="order-action-icon"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >
                                        <path d="M20 11a8.1 8.1 0 0 0-15.5-2M4 4v5h5"/>
                                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2M20 20v-5h-5"/>
                                    </svg>

                                    <span data-action-text>
                                        Cek Pembayaran
                                    </span>
                                </button>
                            </form>
                        @endif

                        @if ($bisaDibatalkan)
                            <form
                                action="{{ route('customer.pesanan.cancel', $pesanan) }}"
                                method="POST"
                                data-order-action-form
                                data-confirm-message="Pesanan {{ $pesanan->kode_pesanan }} akan dibatalkan. Lanjutkan?"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="order-show-button order-show-button-danger"
                                    data-order-action-button
                                >
                                    <span class="order-action-spinner"></span>

                                    <svg
                                        class="order-action-icon"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >
                                        <path d="M3 6h18"/>
                                        <path d="M8 6V4h8v2"/>
                                        <path d="m19 6-1 14H6L5 6"/>
                                        <path d="M10 11v5"/>
                                        <path d="M14 11v5"/>
                                    </svg>

                                    <span data-action-text>
                                        Batalkan Pesanan
                                    </span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <aside class="order-show-status-card order-show-reveal">
                    <div class="order-show-status-header">
                        <span class="order-show-status-icon">
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

                        <span>
                            <strong>Status Pesanan</strong>
                            <span>
                                Kondisi pesanan saat ini.
                            </span>
                        </span>
                    </div>

                    <div class="order-show-status-main">
                        <span>Status Pengerjaan</span>

                        <strong>
                            <span class="order-status-pill {{ $classStatusPesanan }}">
                                {{ $labelStatusPesanan }}
                            </span>
                        </strong>
                    </div>

                    <div class="order-show-status-grid">
                        <div class="order-show-status-info">
                            <span>Pembayaran</span>

                            <strong>
                                {{ $labelStatusPembayaran }}
                            </strong>
                        </div>

                        <div class="order-show-status-info">
                            <span>Total Pesanan</span>

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
                </aside>
            </div>
        </section>

        {{-- Main --}}
        <main class="order-show-main">
            <div class="container">
                {{-- Quick Summary --}}
                <section class="order-show-summary-strip order-show-reveal">
                    <div class="order-show-summary-item">
                        <span class="order-show-summary-icon">
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

                        <span class="order-show-summary-copy">
                            <span>Tanggal Pesan</span>
                            <strong>{{ $tanggalPesan }}</strong>
                        </span>
                    </div>

                    <div class="order-show-summary-item">
                        <span class="order-show-summary-icon">
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

                        <span class="order-show-summary-copy">
                            <span>Pengambilan</span>
                            <strong>
                                {{ $tanggalPengambilan }},
                                {{ $jamPengambilan }}
                            </strong>
                        </span>
                    </div>

                    <div class="order-show-summary-item">
                        <span class="order-show-summary-icon">
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
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                <path d="M14 2v6h6"/>
                            </svg>
                        </span>

                        <span class="order-show-summary-copy">
                            <span>Jumlah File</span>
                            <strong>{{ $jumlahFile }} file</strong>
                        </span>
                    </div>

                    <div class="order-show-summary-item">
                        <span class="order-show-summary-icon">
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

                        <span class="order-show-summary-copy">
                            <span>Metode Pembayaran</span>
                            <strong>{{ $labelMetodePembayaran }}</strong>
                        </span>
                    </div>
                </section>

                <div class="order-show-grid">
                    <div class="order-show-content">
                        {{-- Customer and Order Information --}}
                        <section class="order-show-card order-show-reveal">
                            <div class="order-show-card-header">
                                <div class="order-show-card-title">
                                    <span class="order-show-card-title-icon">
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

                                    <div>
                                        <h2>Informasi Pesanan</h2>

                                        <p>
                                            Identitas pelanggan dan
                                            jadwal pengambilan.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="order-show-card-body">
                                <div class="order-show-info-grid">
                                    <div class="order-show-info-item">
                                        <span class="order-show-info-icon">
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

                                        <span class="order-show-info-copy">
                                            <span>Nama Pelanggan</span>

                                            <strong>
                                                {{ $pesanan->nama_pelanggan }}
                                            </strong>
                                        </span>
                                    </div>

                                    <div class="order-show-info-item">
                                        <span class="order-show-info-icon">
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

                                        <span class="order-show-info-copy">
                                            <span>Email</span>

                                            <strong>
                                                {{ $pesanan->email ?? '-' }}
                                            </strong>
                                        </span>
                                    </div>

                                    <div class="order-show-info-item">
                                        <span class="order-show-info-icon">
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

                                        <span class="order-show-info-copy">
                                            <span>Nomor WhatsApp</span>

                                            <strong>
                                                {{
                                                    $pesanan->nomor_whatsapp
                                                    ?? '-'
                                                }}
                                            </strong>
                                        </span>
                                    </div>

                                    <div class="order-show-info-item">
                                        <span class="order-show-info-icon">
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

                                        <span class="order-show-info-copy">
                                            <span>Tanggal Pesan</span>

                                            <strong>
                                                {{ $tanggalPesan }}
                                            </strong>
                                        </span>
                                    </div>

                                    <div class="order-show-info-item">
                                        <span class="order-show-info-icon">
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

                                        <span class="order-show-info-copy">
                                            <span>Jadwal Pengambilan</span>

                                            <strong>
                                                {{ $tanggalPengambilan }}
                                            </strong>

                                            <small>
                                                Pukul {{ $jamPengambilan }}
                                            </small>
                                        </span>
                                    </div>

                                    <div class="order-show-info-item">
                                        <span class="order-show-info-icon">
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

                                        <span class="order-show-info-copy">
                                            <span>Lokasi Pengambilan</span>

                                            <strong>
                                                {{
                                                    $pesanan
                                                        ->lokasi_pengambilan
                                                    ?? '-'
                                                }}
                                            </strong>
                                        </span>
                                    </div>

                                    <div class="order-show-info-item full">
                                        <span class="order-show-info-icon">
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

                                        <span class="order-show-info-copy">
                                            <span>Status Pesanan</span>

                                            <strong>
                                                <span class="order-status-pill {{ $classStatusPesanan }}">
                                                    {{ $labelStatusPesanan }}
                                                </span>
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Files --}}
                        <section class="order-show-card files order-show-reveal">
                            <div class="order-show-card-header">
                                <div class="order-show-card-title">
                                    <span class="order-show-card-title-icon">
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
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                            <path d="M14 2v6h6"/>
                                        </svg>
                                    </span>

                                    <div>
                                        <h2>File dan Detail Layanan</h2>

                                        <p>
                                            Spesifikasi setiap dokumen
                                            dalam pesanan.
                                        </p>
                                    </div>
                                </div>

                                <span class="order-show-card-count">
                                    {{ $jumlahFile }}
                                </span>
                            </div>

                            <div class="order-show-card-body">
                                @forelse ($pesanan->detailPesanans as $detail)
                                    @php
                                        $extensionFile = strtoupper(
                                            pathinfo(
                                                $detail->nama_file ?? '',
                                                PATHINFO_EXTENSION
                                            ) ?: 'FILE'
                                        );

                                        $labelJenisPrint = match (
                                            $detail->jenis_print
                                        ) {
                                            'hitam_putih' => 'Hitam Putih',
                                            'warna' => 'Warna',
                                            default => '-',
                                        };
                                    @endphp

                                    <div class="order-file-list">
                                        <article class="order-file-card">
                                            <div class="order-file-header">
                                                <div class="order-file-identity">
                                                    <span class="order-file-icon">
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
                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                                            <path d="M14 2v6h6"/>
                                                            <path d="M8 13h8"/>
                                                            <path d="M8 17h5"/>
                                                        </svg>
                                                    </span>

                                                    <div class="order-file-copy">
                                                        <h3>
                                                            {{
                                                                $detail
                                                                    ->nama_file
                                                                ?? 'File pesanan'
                                                            }}
                                                        </h3>

                                                        <p>
                                                            {{
                                                                $detail
                                                                    ->layanan
                                                                    ?->nama_layanan
                                                                ?? 'Layanan tidak tersedia'
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <span class="order-file-extension">
                                                    {{ $extensionFile }}
                                                </span>
                                            </div>

                                            <div class="order-file-detail-grid">
                                                <div class="order-file-detail-item">
                                                    <span>Jenis Print</span>
                                                    <strong>
                                                        {{ $labelJenisPrint }}
                                                    </strong>
                                                </div>

                                                <div class="order-file-detail-item">
                                                    <span>Ukuran Kertas</span>
                                                    <strong>
                                                        {{
                                                            $detail
                                                                ->ukuran_kertas
                                                            ?? '-'
                                                        }}
                                                    </strong>
                                                </div>

                                                <div class="order-file-detail-item">
                                                    <span>Halaman</span>
                                                    <strong>
                                                        {{
                                                            $detail
                                                                ->jumlah_halaman
                                                            ?? 0
                                                        }}
                                                    </strong>
                                                </div>

                                                <div class="order-file-detail-item">
                                                    <span>Copy</span>
                                                    <strong>
                                                        {{
                                                            $detail
                                                                ->jumlah_copy
                                                            ?? 0
                                                        }}
                                                    </strong>
                                                </div>

                                                <div class="order-file-detail-item">
                                                    <span>Jilid</span>
                                                    <strong>
                                                        {{
                                                            $detail->pakai_jilid
                                                                ? 'Ya'
                                                                : 'Tidak'
                                                        }}
                                                    </strong>
                                                </div>

                                                <div class="order-file-detail-item">
                                                    <span>Laminating</span>
                                                    <strong>
                                                        {{
                                                            $detail
                                                                ->pakai_laminating
                                                                ? 'Ya'
                                                                : 'Tidak'
                                                        }}
                                                    </strong>
                                                </div>

                                                <div class="order-file-detail-item subtotal">
                                                    <span>Subtotal</span>
                                                    <strong>
                                                        Rp {{
                                                            number_format(
                                                                (float) $detail
                                                                    ->subtotal,
                                                                0,
                                                                ',',
                                                                '.'
                                                            )
                                                        }}
                                                    </strong>
                                                </div>
                                            </div>

                                            @if ($detail->catatan_detail)
                                                <div class="order-file-note">
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
                                                        <circle
                                                            cx="12"
                                                            cy="12"
                                                            r="9"
                                                        />
                                                        <path d="M12 11v5"/>
                                                        <path d="M12 8h.01"/>
                                                    </svg>

                                                    <span>
                                                        <strong>Catatan file:</strong>
                                                        {{ $detail->catatan_detail }}
                                                    </span>
                                                </div>
                                            @endif
                                        </article>
                                    </div>
                                @empty
                                    <div class="order-show-empty">
                                        <span class="order-show-empty-icon">
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
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                                <path d="M14 2v6h6"/>
                                            </svg>
                                        </span>

                                        <h3>Belum ada file</h3>

                                        <p>
                                            Detail file pesanan belum
                                            tersedia.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        {{-- Timeline --}}
                        <section class="order-show-card timeline-card order-show-reveal">
                            <div class="order-show-card-header">
                                <div class="order-show-card-title">
                                    <span class="order-show-card-title-icon">
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

                                    <div>
                                        <h2>Riwayat Status</h2>

                                        <p>
                                            Perjalanan status pesanan
                                            sejak dibuat.
                                        </p>
                                    </div>
                                </div>

                                <span class="order-show-card-count">
                                    {{ $jumlahRiwayat }}
                                </span>
                            </div>

                            <div class="order-show-card-body">
                                @if ($pesanan->riwayatStatusPesanans->isNotEmpty())
                                    <div class="order-timeline">
                                        @foreach ($pesanan->riwayatStatusPesanans as $riwayat)
                                            @php
                                                $labelRiwayat = match (
                                                    $riwayat->status
                                                ) {
                                                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                                    'diproses' => 'Sedang Diproses',
                                                    'siap_diambil' => 'Siap Diambil',
                                                    'selesai' => 'Selesai',
                                                    'dibatalkan' => 'Dibatalkan',
                                                    default => $riwayat->status
                                                        ? ucwords(
                                                            str_replace(
                                                                '_',
                                                                ' ',
                                                                $riwayat->status
                                                            )
                                                        )
                                                        : 'Perubahan Status',
                                                };
                                            @endphp

                                            <article class="order-timeline-item">
                                                <span class="order-timeline-marker">
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

                                                <div class="order-timeline-content">
                                                    <div class="order-timeline-heading">
                                                        <strong>
                                                            {{ $labelRiwayat }}
                                                        </strong>

                                                        <time>
                                                            {{
                                                                $riwayat
                                                                    ->waktu_status
                                                                    ?->format(
                                                                        'd M Y H:i'
                                                                    )
                                                                ?? '-'
                                                            }}
                                                        </time>
                                                    </div>

                                                    <p>
                                                        {{
                                                            $riwayat->catatan
                                                            ?? 'Tidak ada catatan tambahan.'
                                                        }}
                                                    </p>
                                                </div>
                                            </article>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="order-show-empty">
                                        <span class="order-show-empty-icon">
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

                                        <h3>Belum ada riwayat</h3>

                                        <p>
                                            Riwayat perubahan status
                                            belum tersedia.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>

                    {{-- Sidebar --}}
                    <aside class="order-show-sidebar">
                        {{-- Cost --}}
                        <section class="order-side-card order-show-reveal">
                            <div class="order-side-header">
                                <h2>Ringkasan Biaya</h2>

                                <p>
                                    Rincian nilai pesanan setelah
                                    perhitungan sistem.
                                </p>
                            </div>

                            <div class="order-side-body">
                                <div class="order-cost-rows">
                                    <div class="order-cost-row">
                                        <span>Subtotal</span>

                                        <strong>
                                            Rp {{
                                                number_format(
                                                    (float) $pesanan->subtotal,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        </strong>
                                    </div>

                                    <div class="order-cost-row">
                                        <span>Biaya Tambahan</span>

                                        <strong>
                                            Rp {{
                                                number_format(
                                                    (float) $pesanan
                                                        ->biaya_tambahan,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        </strong>
                                    </div>

                                    <div class="order-cost-row">
                                        <span>Biaya Pengiriman</span>

                                        <strong>
                                            Rp {{
                                                number_format(
                                                    (float) $pesanan
                                                        ->biaya_pengiriman,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        </strong>
                                    </div>
                                </div>

                                <div class="order-cost-total">
                                    <span>Total Pesanan</span>

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
                        </section>

                        {{-- Payment --}}
                        <section class="order-side-card order-show-reveal">
                            <div class="order-side-header orange">
                                <h2>Pembayaran</h2>

                                <p>
                                    Informasi metode dan transaksi
                                    pembayaran.
                                </p>
                            </div>

                            <div class="order-side-body">
                                <div class="order-payment-status">
                                    <span class="order-payment-status-copy">
                                        <span>Status Pembayaran</span>

                                        <strong>
                                            {{ $labelStatusPembayaran }}
                                        </strong>
                                    </span>

                                    <span class="order-payment-pill {{ $classStatusPembayaran }}">
                                        {{ $labelStatusPembayaran }}
                                    </span>
                                </div>

                                <div class="order-payment-data">
                                    <div class="order-payment-row">
                                        <span>Metode</span>
                                        <strong>
                                            {{ $labelMetodePembayaran }}
                                        </strong>
                                    </div>

                                    <div class="order-payment-row">
                                        <span>Channel</span>
                                        <strong>
                                            {{
                                                $pembayaran
                                                    ?->channel_pembayaran
                                                ?? '-'
                                            }}
                                        </strong>
                                    </div>

                                    <div class="order-payment-row">
                                        <span>Jumlah Bayar</span>
                                        <strong>
                                            Rp {{
                                                number_format(
                                                    (float) (
                                                        $pembayaran
                                                            ?->jumlah_bayar
                                                        ?? 0
                                                    ),
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}
                                        </strong>
                                    </div>

                                    <div class="order-payment-row">
                                        <span>Tanggal Bayar</span>
                                        <strong>
                                            {{
                                                $pembayaran
                                                    ?->tanggal_bayar
                                                    ?->format(
                                                        'd M Y H:i'
                                                    )
                                                ?? '-'
                                            }}
                                        </strong>
                                    </div>

                                    @if ($metodePembayaran === 'transfer')
                                        <div class="order-payment-row">
                                            <span>Midtrans Order ID</span>

                                            <strong>
                                                {{
                                                    $pembayaran
                                                        ?->midtrans_order_id
                                                    ?? '-'
                                                }}
                                            </strong>
                                        </div>

                                        <div class="order-payment-row">
                                            <span>Transaction ID</span>

                                            <strong>
                                                {{
                                                    $pembayaran
                                                        ?->transaction_id
                                                    ?? '-'
                                                }}
                                            </strong>
                                        </div>

                                        <div class="order-payment-row">
                                            <span>Payment Type</span>

                                            <strong>
                                                {{ $paymentType }}
                                            </strong>
                                        </div>
                                    @endif
                                </div>

                                @if ($bisaBayarMidtrans || $bisaCekMidtrans)
                                    <div class="order-payment-actions">
                                        @if ($bisaBayarMidtrans)
                                            <form
                                                action="{{ route('customer.pesanan.pay-midtrans', $pesanan) }}"
                                                method="POST"
                                                data-order-action-form
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="order-show-button order-show-button-orange"
                                                    data-order-action-button
                                                >
                                                    <span class="order-action-spinner"></span>

                                                    <svg
                                                        class="order-action-icon"
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

                                                    <span data-action-text>
                                                        Bayar Sekarang
                                                    </span>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($bisaCekMidtrans)
                                            <form
                                                action="{{ route('customer.pesanan.check-midtrans', $pesanan) }}"
                                                method="POST"
                                                data-order-action-form
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="order-show-button order-show-button-primary"
                                                    data-order-action-button
                                                >
                                                    <span class="order-action-spinner"></span>

                                                    <svg
                                                        class="order-action-icon"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        aria-hidden="true"
                                                    >
                                                        <path d="M20 11a8.1 8.1 0 0 0-15.5-2M4 4v5h5"/>
                                                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2M20 20v-5h-5"/>
                                                    </svg>

                                                    <span data-action-text>
                                                        Cek Status Midtrans
                                                    </span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </section>

                        {{-- Delivery --}}
                        <section class="order-side-card order-show-reveal">
                            <div class="order-side-header purple">
                                <h2>Pengiriman</h2>

                                <p>
                                    Metode penyerahan dan status
                                    pengiriman pesanan.
                                </p>
                            </div>

                            <div class="order-side-body">
                                <div class="order-delivery-box">
                                    <span class="order-delivery-icon">
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
                                            <path d="M3 7h13v10H3z"/>
                                            <path d="M16 10h3l2 3v4h-5z"/>
                                            <circle cx="7" cy="18" r="2"/>
                                            <circle cx="18" cy="18" r="2"/>
                                        </svg>
                                    </span>

                                    <span class="order-delivery-copy">
                                        <strong>
                                            {{ $labelMetodePengiriman }}
                                        </strong>

                                        <span>
                                            {{ $labelStatusPengiriman }}
                                        </span>
                                    </span>
                                </div>

                                <div class="order-delivery-data">
                                    <div class="order-delivery-item">
                                        <span>Lokasi Pengambilan</span>

                                        <strong>
                                            {{
                                                $pesanan
                                                    ->lokasi_pengambilan
                                                ?? '-'
                                            }}
                                        </strong>
                                    </div>

                                    @if ($pesanan->detail_lokasi)
                                        <div class="order-delivery-item">
                                            <span>Detail Lokasi</span>

                                            <strong>
                                                {{ $pesanan->detail_lokasi }}
                                            </strong>
                                        </div>
                                    @endif

                                    <div class="order-delivery-item">
                                        <span>Jadwal</span>

                                        <strong>
                                            {{ $tanggalPengambilan }}
                                            pukul
                                            {{ $jamPengambilan }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </section>

                        @if ($pesanan->catatan)
                            <section class="order-notes-card order-show-reveal">
                                <span class="order-notes-icon">
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

                                <h3>Catatan Pesanan</h3>

                                <p>{{ $pesanan->catatan }}</p>
                            </section>
                        @endif
                    </aside>
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
            | Action Forms
            |--------------------------------------------------------------------------
            */

            const actionForms = document.querySelectorAll(
                '[data-order-action-form]'
            );

            actionForms.forEach((form) => {
                form.addEventListener('submit', (event) => {
                    const confirmMessage =
                        form.dataset.confirmMessage;

                    if (
                        confirmMessage
                        && !window.confirm(confirmMessage)
                    ) {
                        event.preventDefault();
                        return;
                    }

                    const button = form.querySelector(
                        '[data-order-action-button]'
                    );

                    if (!button) {
                        return;
                    }

                    button.disabled = true;
                    button.classList.add('loading');

                    const actionText = button.querySelector(
                        '[data-action-text]'
                    );

                    if (actionText) {
                        actionText.textContent =
                            'Memproses...';
                    }
                });
            });

            /*
            |--------------------------------------------------------------------------
            | Reveal Animation
            |--------------------------------------------------------------------------
            */

            const revealElements = document.querySelectorAll(
                '.order-show-reveal'
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
        });
    </script>
@endpush