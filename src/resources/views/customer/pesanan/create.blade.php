@extends('layouts.customer')

@section('title', 'Buat Pesanan - Tukang Print Dadakan')

@php
    $biayaJilid = (float) ($pengaturanBooking?->biaya_jilid ?? 0);
    $biayaLaminating = (float) ($pengaturanBooking?->biaya_laminating ?? 0);

    $layananTerpilihId = (int) old(
        'layanan_id',
        $selectedLayanan?->id
    );
@endphp

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Order Create Variables
        |--------------------------------------------------------------------------
        */

        .order-create-page {
            --order-blue: var(--customer-blue, #155eef);
            --order-blue-dark: var(--customer-blue-dark, #1046b8);
            --order-blue-soft: var(--customer-blue-soft, #edf4ff);

            --order-orange: var(--customer-orange, #f97316);
            --order-orange-dark: var(--customer-orange-dark, #c2410c);
            --order-orange-soft: var(--customer-orange-soft, #fff1e7);

            --order-green: #16a34a;
            --order-green-dark: #15803d;
            --order-green-soft: #ecfdf3;

            --order-yellow: #d97706;
            --order-yellow-soft: #fffbeb;

            --order-red: #dc2626;
            --order-red-soft: #fff1f2;

            --order-purple: #7c3aed;
            --order-purple-soft: #f3e8ff;

            --order-dark: #101828;
            --order-text: #344054;
            --order-muted: #667085;

            --order-white: #ffffff;
            --order-soft: #f7f9fc;
            --order-border: #e4e7ec;
            --order-border-dark: #d0d5dd;

            min-height: 100vh;
            overflow: hidden;
            background: #f8faff;
        }

        /*
        |--------------------------------------------------------------------------
        | Hero
        |--------------------------------------------------------------------------
        */

        .order-create-hero {
            position: relative;
            overflow: hidden;
            padding: 55px 0 75px;
            border-bottom: 1px solid var(--order-border);
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

        .order-create-hero::before {
            content: "";
            position: absolute;
            top: -110px;
            right: -85px;
            width: 290px;
            height: 290px;
            border: 44px solid rgba(249, 115, 22, 0.055);
            border-radius: 999px;
        }

        .order-create-hero::after {
            content: "";
            position: absolute;
            bottom: -125px;
            left: -100px;
            width: 280px;
            height: 280px;
            border: 43px solid rgba(21, 94, 239, 0.05);
            border-radius: 999px;
        }

        .order-create-hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns:
                minmax(0, 1.08fr)
                minmax(330px, 0.52fr);
            gap: 48px;
            align-items: center;
        }

        .order-create-hero-content {
            max-width: 770px;
        }

        .order-create-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 19px;
            color: var(--order-muted);
            font-size: 10px;
            font-weight: 800;
        }

        .order-create-breadcrumb a {
            color: var(--order-muted);
        }

        .order-create-breadcrumb a:hover {
            color: var(--order-blue);
        }

        .order-create-breadcrumb svg {
            width: 13px;
            height: 13px;
        }

        .order-create-breadcrumb strong {
            color: var(--order-blue);
        }

        .order-create-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 13px;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            color: var(--order-orange-dark);
            background: var(--order-orange-soft);
            font-size: 11px;
            font-weight: 900;
        }

        .order-create-badge-icon {
            width: 27px;
            height: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--order-white);
            background: var(--order-orange);
        }

        .order-create-badge-icon svg {
            width: 15px;
            height: 15px;
        }

        .order-create-hero h1 {
            margin: 20px 0 15px;
            color: var(--order-dark);
            font-size: clamp(38px, 5vw, 60px);
            line-height: 1.06;
            letter-spacing: -2px;
        }

        .order-create-hero h1 span {
            position: relative;
            display: inline-block;
            color: var(--order-blue);
        }

        .order-create-hero h1 span::after {
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

        .order-create-hero-description {
            max-width: 690px;
            margin: 0;
            color: var(--order-muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .order-create-hero-points {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 25px;
        }

        .order-create-hero-point {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 35px;
            padding: 7px 11px;
            border: 1px solid var(--order-border);
            border-radius: 11px;
            color: var(--order-text);
            background: rgba(255, 255, 255, 0.82);
            font-size: 10px;
            font-weight: 850;
        }

        .order-create-hero-point svg {
            width: 15px;
            height: 15px;
            color: var(--order-blue);
        }

        .order-create-hero-point:nth-child(2) svg {
            color: var(--order-orange);
        }

        .order-create-hero-point:nth-child(3) svg {
            color: var(--order-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Hero Guide
        |--------------------------------------------------------------------------
        */

        .order-create-guide {
            position: relative;
            padding: 24px;
            border: 1px solid rgba(228, 231, 236, 0.93);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.93);
            backdrop-filter: blur(16px);
            box-shadow:
                0 23px 62px rgba(16, 24, 40, 0.13);
        }

        .order-create-guide::before {
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
                    var(--order-blue),
                    var(--order-orange)
                );
            transform: rotate(3deg);
        }

        .order-create-guide-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .order-create-guide-icon {
            width: 47px;
            height: 47px;
            flex: 0 0 47px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: var(--order-white);
            background:
                linear-gradient(
                    135deg,
                    var(--order-blue),
                    #2b70ff
                );
            box-shadow:
                0 9px 21px rgba(21, 94, 239, 0.2);
        }

        .order-create-guide-icon svg {
            width: 23px;
            height: 23px;
        }

        .order-create-guide-header strong,
        .order-create-guide-header span {
            display: block;
        }

        .order-create-guide-header strong {
            color: var(--order-dark);
            font-size: 16px;
        }

        .order-create-guide-header span {
            margin-top: 3px;
            color: var(--order-muted);
            font-size: 10px;
        }

        .order-create-guide-list {
            display: grid;
            gap: 9px;
        }

        .order-create-guide-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px;
            border: 1px solid var(--order-border);
            border-radius: 13px;
            background: var(--order-soft);
        }

        .order-create-guide-number {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: var(--order-white);
            background: var(--order-blue);
            font-size: 8px;
            font-weight: 900;
        }

        .order-create-guide-item:nth-child(2)
        .order-create-guide-number {
            background: var(--order-orange);
        }

        .order-create-guide-item:nth-child(3)
        .order-create-guide-number {
            background: var(--order-green);
        }

        .order-create-guide-copy strong,
        .order-create-guide-copy span {
            display: block;
        }

        .order-create-guide-copy strong {
            color: var(--order-dark);
            font-size: 10px;
        }

        .order-create-guide-copy span {
            margin-top: 2px;
            color: var(--order-muted);
            font-size: 9px;
            line-height: 1.45;
        }

        /*
        |--------------------------------------------------------------------------
        | Step Navigation
        |--------------------------------------------------------------------------
        */

        .order-step-wrapper {
            position: relative;
            z-index: 3;
            margin-top: -34px;
        }

        .order-step-card {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            overflow: hidden;
            border: 1px solid var(--order-border);
            border-radius: 21px;
            background: var(--order-white);
            box-shadow:
                0 16px 47px rgba(16, 24, 40, 0.09);
        }

        .order-step-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 18px;
            border-right: 1px solid var(--order-border);
        }

        .order-step-item:last-child {
            border-right: 0;
        }

        .order-step-number {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--order-blue);
            background: var(--order-blue-soft);
            font-size: 10px;
            font-weight: 900;
        }

        .order-step-item:nth-child(2)
        .order-step-number {
            color: var(--order-orange-dark);
            background: var(--order-orange-soft);
        }

        .order-step-item:nth-child(3)
        .order-step-number {
            color: var(--order-purple);
            background: var(--order-purple-soft);
        }

        .order-step-item:nth-child(4)
        .order-step-number {
            color: var(--order-green-dark);
            background: var(--order-green-soft);
        }

        .order-step-copy strong,
        .order-step-copy span {
            display: block;
        }

        .order-step-copy strong {
            color: var(--order-dark);
            font-size: 11px;
        }

        .order-step-copy span {
            margin-top: 3px;
            color: var(--order-muted);
            font-size: 8px;
            line-height: 1.4;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Section
        |--------------------------------------------------------------------------
        */

        .order-create-section {
            padding: 36px 0 85px;
        }

        .order-error-summary {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 22px;
            padding: 15px;
            border: 1px solid #fecaca;
            border-radius: 16px;
            color: #991b1b;
            background: var(--order-red-soft);
            font-size: 11px;
            font-weight: 800;
            line-height: 1.55;
        }

        .order-error-summary-icon {
            width: 27px;
            height: 27px;
            flex: 0 0 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--order-white);
            background: var(--order-red);
            font-size: 13px;
            font-weight: 900;
        }

        .order-form-layout {
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(320px, 0.4fr);
            gap: 25px;
            align-items: start;
        }

        .order-form-main {
            display: grid;
            gap: 22px;
        }

        /*
        |--------------------------------------------------------------------------
        | Form Section Card
        |--------------------------------------------------------------------------
        */

        .order-form-card {
            overflow: hidden;
            border: 1px solid var(--order-border);
            border-radius: 23px;
            background: var(--order-white);
            box-shadow:
                0 9px 31px rgba(16, 24, 40, 0.055);
        }

        .order-form-card-header {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 22px 24px;
            border-bottom: 1px solid var(--order-border);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.075),
                    transparent 35%
                ),
                #fcfcfd;
        }

        .order-form-card-number {
            width: 45px;
            height: 45px;
            flex: 0 0 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: var(--order-white);
            background:
                linear-gradient(
                    135deg,
                    var(--order-blue),
                    #2b70ff
                );
            box-shadow:
                0 8px 20px rgba(21, 94, 239, 0.19);
            font-size: 11px;
            font-weight: 900;
        }

        .order-form-card:nth-child(2)
        .order-form-card-number {
            background:
                linear-gradient(
                    135deg,
                    var(--order-orange),
                    #fb923c
                );
            box-shadow:
                0 8px 20px rgba(249, 115, 22, 0.19);
        }

        .order-form-card:nth-child(3)
        .order-form-card-number {
            background:
                linear-gradient(
                    135deg,
                    var(--order-purple),
                    #8b5cf6
                );
            box-shadow:
                0 8px 20px rgba(124, 58, 237, 0.19);
        }

        .order-form-card-heading h2 {
            margin: 0 0 5px;
            color: var(--order-dark);
            font-size: 20px;
        }

        .order-form-card-heading p {
            margin: 0;
            color: var(--order-muted);
            font-size: 10px;
            line-height: 1.55;
        }

        .order-form-card-body {
            display: grid;
            gap: 19px;
            padding: 24px;
        }

        /*
        |--------------------------------------------------------------------------
        | Form Controls
        |--------------------------------------------------------------------------
        */

        .order-form-grid-two {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .order-form-group {
            display: grid;
            gap: 7px;
        }

        .order-form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .order-form-label {
            color: var(--order-text);
            font-size: 11px;
            font-weight: 900;
        }

        .order-required {
            color: var(--order-orange);
        }

        .order-optional {
            color: var(--order-muted);
            font-size: 8px;
            font-weight: 750;
        }

        .order-field-wrapper {
            position: relative;
        }

        .order-field-icon {
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

        .order-textarea-wrapper
        .order-field-icon {
            top: 15px;
            transform: none;
        }

        .order-input,
        .order-select,
        .order-textarea {
            width: 100%;
            border: 1px solid var(--order-border-dark);
            border-radius: 14px;
            color: var(--order-dark);
            background: #fcfcfd;
            outline: none;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .order-input,
        .order-select {
            min-height: 49px;
            padding: 11px 14px;
        }

        .order-input.has-icon,
        .order-select.has-icon {
            padding-left: 43px;
        }

        .order-textarea {
            min-height: 105px;
            padding: 13px 14px;
            resize: vertical;
            line-height: 1.6;
        }

        .order-textarea.has-icon {
            padding-left: 43px;
        }

        .order-input::placeholder,
        .order-textarea::placeholder {
            color: #98a2b3;
        }

        .order-input:hover,
        .order-select:hover,
        .order-textarea:hover {
            border-color: #98a2b3;
        }

        .order-input:focus,
        .order-select:focus,
        .order-textarea:focus {
            border-color: var(--order-blue);
            background: var(--order-white);
            box-shadow:
                0 0 0 4px rgba(21, 94, 239, 0.11);
        }

        .order-input.is-invalid,
        .order-select.is-invalid,
        .order-textarea.is-invalid {
            border-color: var(--order-red);
            background: #fffafa;
        }

        .order-field-error {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--order-red);
            font-size: 9px;
            font-weight: 850;
        }

        .order-field-error::before {
            content: "!";
            width: 16px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: var(--order-white);
            background: var(--order-red);
            font-size: 9px;
        }

        .order-field-help {
            color: var(--order-muted);
            font-size: 9px;
            line-height: 1.55;
        }

        /*
        |--------------------------------------------------------------------------
        | Selected Service Preview
        |--------------------------------------------------------------------------
        */

        .order-service-preview {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 17px;
            padding: 15px;
            border: 1px solid #cfe0ff;
            border-radius: 16px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.08),
                    transparent 38%
                ),
                var(--order-blue-soft);
        }

        .order-service-preview.visible {
            display: flex;
        }

        .order-service-preview-main {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .order-service-preview-icon {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--order-white);
            background:
                linear-gradient(
                    135deg,
                    var(--order-blue),
                    #2b70ff
                );
        }

        .order-service-preview-icon svg {
            width: 21px;
            height: 21px;
        }

        .order-service-preview-copy {
            min-width: 0;
        }

        .order-service-preview-copy span,
        .order-service-preview-copy strong {
            display: block;
        }

        .order-service-preview-copy span {
            color: var(--order-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-service-preview-copy strong {
            margin-top: 4px;
            overflow: hidden;
            color: var(--order-dark);
            font-size: 12px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .order-service-preview-price {
            flex: 0 0 auto;
            text-align: right;
        }

        .order-service-preview-price strong,
        .order-service-preview-price span {
            display: block;
        }

        .order-service-preview-price strong {
            color: var(--order-blue);
            font-size: 14px;
        }

        .order-service-preview-price span {
            margin-top: 3px;
            color: var(--order-muted);
            font-size: 8px;
        }

        /*
        |--------------------------------------------------------------------------
        | Quantity Controls
        |--------------------------------------------------------------------------
        */

        .order-number-wrapper {
            position: relative;
        }

        .order-number-label {
            position: absolute;
            top: 50%;
            right: 14px;
            color: var(--order-muted);
            font-size: 8px;
            font-weight: 850;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .order-number-wrapper .order-input {
            padding-right: 65px;
        }

        /*
        |--------------------------------------------------------------------------
        | Additional Services
        |--------------------------------------------------------------------------
        */

        .order-addon-heading {
            margin-bottom: -5px;
        }

        .order-addon-heading strong {
            display: block;
            color: var(--order-dark);
            font-size: 12px;
        }

        .order-addon-heading span {
            display: block;
            margin-top: 4px;
            color: var(--order-muted);
            font-size: 9px;
        }

        .order-addon-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .order-addon-card {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            border: 1px solid var(--order-border);
            border-radius: 17px;
            background: #fcfcfd;
            cursor: pointer;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }

        .order-addon-card:hover {
            border-color: #b9d0ff;
            background: var(--order-white);
            transform: translateY(-2px);
        }

        .order-addon-card:has(input:checked) {
            border-color: var(--order-blue);
            background: var(--order-blue-soft);
            box-shadow:
                0 0 0 3px rgba(21, 94, 239, 0.08);
        }

        .order-addon-card:nth-child(2):has(input:checked) {
            border-color: var(--order-orange);
            background: var(--order-orange-soft);
            box-shadow:
                0 0 0 3px rgba(249, 115, 22, 0.08);
        }

        .order-addon-input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
        }

        .order-addon-check {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--order-border-dark);
            border-radius: 8px;
            color: transparent;
            background: var(--order-white);
            transition:
                color 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease;
        }

        .order-addon-check svg {
            width: 14px;
            height: 14px;
        }

        .order-addon-input:checked
        + .order-addon-check {
            color: var(--order-white);
            border-color: var(--order-blue);
            background: var(--order-blue);
        }

        .order-addon-card:nth-child(2)
        .order-addon-input:checked
        + .order-addon-check {
            border-color: var(--order-orange);
            background: var(--order-orange);
        }

        .order-addon-copy strong,
        .order-addon-copy span,
        .order-addon-copy small {
            display: block;
        }

        .order-addon-copy strong {
            color: var(--order-dark);
            font-size: 11px;
        }

        .order-addon-copy span {
            margin-top: 4px;
            color: var(--order-muted);
            font-size: 9px;
            line-height: 1.45;
        }

        .order-addon-copy small {
            margin-top: 8px;
            color: var(--order-blue);
            font-size: 9px;
            font-weight: 900;
        }

        .order-addon-card:nth-child(2)
        .order-addon-copy small {
            color: var(--order-orange-dark);
        }

        /*
        |--------------------------------------------------------------------------
        | File Upload
        |--------------------------------------------------------------------------
        */

        .order-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0;
        }

        .order-upload-zone {
            position: relative;
            display: block;
            padding: 29px 21px;
            border: 2px dashed #b9d0ff;
            border-radius: 19px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.07),
                    transparent 34%
                ),
                #f8fbff;
            text-align: center;
            cursor: pointer;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .order-upload-zone:hover,
        .order-upload-zone.dragging {
            border-color: var(--order-blue);
            background: var(--order-blue-soft);
            transform: translateY(-2px);
        }

        .order-upload-icon {
            width: 60px;
            height: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            border-radius: 19px;
            color: var(--order-blue);
            background: var(--order-white);
            box-shadow:
                0 9px 24px rgba(21, 94, 239, 0.13);
        }

        .order-upload-icon svg {
            width: 29px;
            height: 29px;
        }

        .order-upload-zone strong {
            display: block;
            color: var(--order-dark);
            font-size: 13px;
        }

        .order-upload-zone p {
            margin: 6px 0 0;
            color: var(--order-muted);
            font-size: 9px;
            line-height: 1.55;
        }

        .order-upload-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 37px;
            margin-top: 14px;
            padding: 8px 13px;
            border-radius: 11px;
            color: var(--order-white);
            background: var(--order-blue);
            font-size: 9px;
            font-weight: 900;
        }

        .order-upload-button svg {
            width: 14px;
            height: 14px;
        }

        .order-file-rules {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 9px;
            margin-top: 12px;
        }

        .order-file-rule {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            border: 1px solid var(--order-border);
            border-radius: 12px;
            background: #fcfcfd;
            color: var(--order-muted);
            font-size: 8px;
            font-weight: 800;
        }

        .order-file-rule svg {
            width: 15px;
            height: 15px;
            flex: 0 0 15px;
            color: var(--order-blue);
        }

        .order-file-rule:nth-child(2) svg {
            color: var(--order-orange);
        }

        .order-file-rule:nth-child(3) svg {
            color: var(--order-green);
        }

        /*
        |--------------------------------------------------------------------------
        | Selected Files
        |--------------------------------------------------------------------------
        */

        .order-selected-files {
            display: none;
            padding: 15px;
            border: 1px solid var(--order-border);
            border-radius: 16px;
            background: #fcfcfd;
        }

        .order-selected-files.visible {
            display: block;
        }

        .order-selected-files-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 11px;
        }

        .order-selected-files-header strong {
            color: var(--order-dark);
            font-size: 11px;
        }

        .order-selected-files-header span {
            color: var(--order-muted);
            font-size: 8px;
            font-weight: 800;
        }

        .order-selected-file-list {
            display: grid;
            gap: 8px;
        }

        .order-selected-file-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 1px solid var(--order-border);
            border-radius: 12px;
            background: var(--order-white);
        }

        .order-selected-file-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            color: var(--order-blue);
            background: var(--order-blue-soft);
        }

        .order-selected-file-icon svg {
            width: 17px;
            height: 17px;
        }

        .order-selected-file-copy {
            min-width: 0;
        }

        .order-selected-file-copy strong,
        .order-selected-file-copy span {
            display: block;
        }

        .order-selected-file-copy strong {
            overflow: hidden;
            color: var(--order-dark);
            font-size: 9px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .order-selected-file-copy span {
            margin-top: 3px;
            color: var(--order-muted);
            font-size: 8px;
        }

        .order-file-validation {
            display: none;
            align-items: flex-start;
            gap: 8px;
            padding: 11px;
            border: 1px solid #fecaca;
            border-radius: 13px;
            color: #991b1b;
            background: var(--order-red-soft);
            font-size: 9px;
            font-weight: 800;
            line-height: 1.5;
        }

        .order-file-validation.visible {
            display: flex;
        }

        .order-file-validation svg {
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Information
        |--------------------------------------------------------------------------
        */

        .order-payment-info {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 14px;
            border: 1px solid #cfe0ff;
            border-radius: 15px;
            background: var(--order-blue-soft);
        }

        .order-payment-info.transfer {
            border-color: #fed7aa;
            background: var(--order-orange-soft);
        }

        .order-payment-info-icon {
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: var(--order-blue);
            background: var(--order-white);
        }

        .order-payment-info.transfer
        .order-payment-info-icon {
            color: var(--order-orange-dark);
        }

        .order-payment-info-icon svg {
            width: 19px;
            height: 19px;
        }

        .order-payment-info-copy strong,
        .order-payment-info-copy p {
            display: block;
        }

        .order-payment-info-copy strong {
            color: var(--order-dark);
            font-size: 11px;
        }

        .order-payment-info-copy p {
            margin: 4px 0 0;
            color: var(--order-muted);
            font-size: 9px;
            line-height: 1.55;
        }

        /*
        |--------------------------------------------------------------------------
        | Location Hint
        |--------------------------------------------------------------------------
        */

        .order-location-hint {
            display: none;
            align-items: flex-start;
            gap: 9px;
            padding: 12px;
            border: 1px solid #fed7aa;
            border-radius: 13px;
            color: var(--order-text);
            background: var(--order-orange-soft);
            font-size: 9px;
            line-height: 1.55;
        }

        .order-location-hint.visible {
            display: flex;
        }

        .order-location-hint svg {
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
            color: var(--order-orange);
        }

        /*
        |--------------------------------------------------------------------------
        | Order Summary
        |--------------------------------------------------------------------------
        */

        .order-summary-column {
            position: sticky;
            top: 105px;
            display: grid;
            gap: 17px;
        }

        .order-summary-card {
            overflow: hidden;
            border: 1px solid var(--order-border);
            border-radius: 23px;
            background: var(--order-white);
            box-shadow:
                0 16px 45px rgba(16, 24, 40, 0.09);
        }

        .order-summary-header {
            position: relative;
            overflow: hidden;
            padding: 23px;
            color: var(--order-white);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.41),
                    transparent 38%
                ),
                linear-gradient(
                    145deg,
                    var(--order-blue-dark),
                    var(--order-blue)
                );
        }

        .order-summary-header::before {
            content: "";
            position: absolute;
            top: -58px;
            right: -52px;
            width: 155px;
            height: 155px;
            border: 25px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
        }

        .order-summary-header-content {
            position: relative;
            z-index: 2;
        }

        .order-summary-header-icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: var(--order-white);
            background: rgba(255, 255, 255, 0.12);
        }

        .order-summary-header-icon svg {
            width: 22px;
            height: 22px;
        }

        .order-summary-header h2 {
            margin: 0 0 5px;
            color: var(--order-white);
            font-size: 20px;
        }

        .order-summary-header p {
            margin: 0;
            color: #dbeafe;
            font-size: 9px;
            line-height: 1.55;
        }

        .order-summary-service {
            margin: 17px 19px 0;
            padding: 13px;
            border: 1px solid var(--order-border);
            border-radius: 14px;
            background: var(--order-soft);
        }

        .order-summary-service span,
        .order-summary-service strong {
            display: block;
        }

        .order-summary-service span {
            color: var(--order-muted);
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-summary-service strong {
            margin-top: 4px;
            overflow: hidden;
            color: var(--order-dark);
            font-size: 11px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .order-summary-rows {
            display: grid;
            gap: 0;
            padding: 11px 19px;
        }

        .order-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid var(--order-border);
        }

        .order-summary-row:last-child {
            border-bottom: 0;
        }

        .order-summary-row span {
            color: var(--order-muted);
            font-size: 9px;
            font-weight: 750;
        }

        .order-summary-row strong {
            color: var(--order-dark);
            font-size: 10px;
            text-align: right;
        }

        .order-summary-total {
            margin: 0 19px;
            padding: 17px;
            border: 1px solid #cfe0ff;
            border-radius: 16px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.09),
                    transparent 38%
                ),
                var(--order-blue-soft);
        }

        .order-summary-total-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .order-summary-total-label span {
            color: var(--order-muted);
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .order-summary-total strong {
            display: block;
            margin-top: 7px;
            color: var(--order-blue);
            font-size: 25px;
            line-height: 1;
            letter-spacing: -0.7px;
        }

        .order-summary-total small {
            display: block;
            margin-top: 6px;
            color: var(--order-muted);
            font-size: 8px;
            line-height: 1.5;
        }

        .order-summary-footer {
            display: grid;
            gap: 11px;
            padding: 19px;
        }

        .order-submit-button {
            width: 100%;
            min-height: 51px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 17px;
            border: 0;
            border-radius: 14px;
            color: var(--order-white);
            background:
                linear-gradient(
                    135deg,
                    var(--order-orange),
                    #fb923c
                );
            box-shadow:
                0 11px 25px rgba(249, 115, 22, 0.24);
            font-size: 11px;
            font-weight: 900;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .order-submit-button:hover {
            background:
                linear-gradient(
                    135deg,
                    var(--order-orange-dark),
                    var(--order-orange)
                );
            box-shadow:
                0 15px 32px rgba(249, 115, 22, 0.3);
            transform: translateY(-2px);
        }

        .order-submit-button:disabled {
            cursor: not-allowed;
            opacity: 0.65;
            transform: none;
        }

        .order-submit-button svg {
            width: 18px;
            height: 18px;
        }

        .order-submit-spinner {
            width: 17px;
            height: 17px;
            display: none;
            border: 2px solid rgba(255, 255, 255, 0.42);
            border-top-color: var(--order-white);
            border-radius: 999px;
            animation: order-spin 0.75s linear infinite;
        }

        .order-submit-button.loading
        .order-submit-spinner {
            display: inline-block;
        }

        .order-submit-button.loading
        .order-submit-icon {
            display: none;
        }

        @keyframes order-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .order-summary-security {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: var(--order-muted);
            font-size: 8px;
            line-height: 1.5;
        }

        .order-summary-security svg {
            width: 15px;
            height: 15px;
            flex: 0 0 15px;
            color: var(--order-green);
        }

        .order-back-link {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border: 1px solid #b9d0ff;
            border-radius: 12px;
            color: var(--order-blue);
            background: var(--order-white);
            font-size: 9px;
            font-weight: 900;
        }

        .order-back-link:hover {
            color: var(--order-blue-dark);
            border-color: var(--order-blue);
            background: var(--order-blue-soft);
        }

        .order-back-link svg {
            width: 15px;
            height: 15px;
        }

        /*
        |--------------------------------------------------------------------------
        | Important Note
        |--------------------------------------------------------------------------
        */

        .order-important-card {
            padding: 19px;
            border: 1px solid #fed7aa;
            border-radius: 20px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(249, 115, 22, 0.09),
                    transparent 38%
                ),
                var(--order-orange-soft);
        }

        .order-important-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 13px;
            border-radius: 14px;
            color: var(--order-white);
            background:
                linear-gradient(
                    135deg,
                    var(--order-orange),
                    #fb923c
                );
        }

        .order-important-icon svg {
            width: 20px;
            height: 20px;
        }

        .order-important-card h3 {
            margin: 0 0 6px;
            color: var(--order-dark);
            font-size: 15px;
        }

        .order-important-card p {
            margin: 0;
            color: var(--order-muted);
            font-size: 9px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | Reveal Animation
        |--------------------------------------------------------------------------
        */

        .order-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition:
                opacity 0.55s ease,
                transform 0.55s ease;
        }

        .order-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1050px) {
            .order-create-hero-grid {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(300px, 0.5fr);
                gap: 35px;
            }

            .order-form-layout {
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(290px, 0.38fr);
            }
        }

        @media (max-width: 900px) {
            .order-create-hero {
                padding: 48px 0 72px;
            }

            .order-create-hero-grid,
            .order-form-layout {
                grid-template-columns: 1fr;
            }

            .order-create-guide {
                max-width: 650px;
            }

            .order-summary-column {
                position: static;
                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(260px, 0.52fr);
                align-items: start;
            }
        }

        @media (max-width: 760px) {
            .order-step-card {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .order-step-item:nth-child(2) {
                border-right: 0;
            }

            .order-step-item:nth-child(-n + 2) {
                border-bottom: 1px solid var(--order-border);
            }

            .order-form-grid-two,
            .order-addon-grid {
                grid-template-columns: 1fr;
            }

            .order-file-rules {
                grid-template-columns: 1fr;
            }

            .order-summary-column {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .order-create-hero {
                padding: 39px 0 65px;
            }

            .order-create-hero::before,
            .order-create-hero::after {
                display: none;
            }

            .order-create-breadcrumb {
                margin-bottom: 16px;
            }

            .order-create-hero h1 {
                margin-top: 17px;
                font-size: 36px;
                letter-spacing: -1.4px;
            }

            .order-create-hero-description {
                font-size: 13px;
            }

            .order-create-hero-points {
                display: grid;
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .order-create-guide {
                padding: 20px;
                border-radius: 21px;
            }

            .order-step-wrapper {
                margin-top: -29px;
            }

            .order-step-card {
                border-radius: 18px;
            }

            .order-step-item {
                padding: 14px;
            }

            .order-step-number {
                width: 34px;
                height: 34px;
                flex-basis: 34px;
                border-radius: 11px;
            }

            .order-step-copy span {
                display: none;
            }

            .order-create-section {
                padding: 30px 0 62px;
            }

            .order-form-card,
            .order-summary-card {
                border-radius: 20px;
            }

            .order-form-card-header {
                padding: 19px;
            }

            .order-form-card-number {
                width: 41px;
                height: 41px;
                flex-basis: 41px;
                border-radius: 13px;
            }

            .order-form-card-heading h2 {
                font-size: 18px;
            }

            .order-form-card-body {
                padding: 19px;
            }

            .order-service-preview {
                align-items: flex-start;
                flex-direction: column;
            }

            .order-service-preview-price {
                padding-left: 54px;
                text-align: left;
            }

            .order-upload-zone {
                padding: 24px 16px;
            }

            .order-summary-header {
                padding: 20px;
            }

            .order-important-card {
                border-radius: 18px;
            }
        }

        @media (max-width: 420px) {
            .order-step-card {
                grid-template-columns: 1fr;
            }

            .order-step-item {
                border-right: 0;
                border-bottom: 1px solid var(--order-border);
            }

            .order-step-item:nth-child(2) {
                border-right: 0;
            }

            .order-step-item:last-child {
                border-bottom: 0;
            }

            .order-form-card-header {
                gap: 10px;
            }

            .order-form-card-number {
                width: 38px;
                height: 38px;
                flex-basis: 38px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .order-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .order-upload-zone,
            .order-addon-card,
            .order-submit-button {
                transition: none;
            }

            .order-submit-spinner {
                animation: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="order-create-page">
        {{-- Hero --}}
        <section class="order-create-hero">
            <div class="container order-create-hero-grid">
                <div class="order-create-hero-content order-reveal">
                    <nav
                        class="order-create-breadcrumb"
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

                        <strong>Buat Pesanan</strong>
                    </nav>

                    <span class="order-create-badge">
                        <span class="order-create-badge-icon">
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

                        Pemesanan Layanan Cetak
                    </span>

                    <h1>
                        Siapkan dokumen dan buat
                        <span>pesanan baru</span>
                    </h1>

                    <p class="order-create-hero-description">
                        Pilih layanan, tentukan kebutuhan cetak,
                        unggah file, dan atur jadwal pengambilan.
                        Estimasi biaya akan diperbarui secara langsung
                        sebelum pesanan dikirim.
                    </p>

                    <div class="order-create-hero-points">
                        <span class="order-create-hero-point">
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

                            Maksimal 5 file
                        </span>

                        <span class="order-create-hero-point">
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

                            Estimasi otomatis
                        </span>

                        <span class="order-create-hero-point">
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

                            Status dapat dipantau
                        </span>
                    </div>
                </div>

                <aside class="order-create-guide order-reveal">
                    <div class="order-create-guide-header">
                        <span class="order-create-guide-icon">
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

                        <span>
                            <strong>Persiapan Pesanan</strong>
                            <span>
                                Pastikan informasi berikut sudah siap.
                            </span>
                        </span>
                    </div>

                    <div class="order-create-guide-list">
                        <div class="order-create-guide-item">
                            <span class="order-create-guide-number">
                                01
                            </span>

                            <span class="order-create-guide-copy">
                                <strong>File sudah final</strong>
                                <span>
                                    Periksa kembali isi, urutan halaman,
                                    dan format dokumen.
                                </span>
                            </span>
                        </div>

                        <div class="order-create-guide-item">
                            <span class="order-create-guide-number">
                                02
                            </span>

                            <span class="order-create-guide-copy">
                                <strong>Detail cetak jelas</strong>
                                <span>
                                    Tentukan warna, ukuran, halaman,
                                    copy, dan layanan tambahan.
                                </span>
                            </span>
                        </div>

                        <div class="order-create-guide-item">
                            <span class="order-create-guide-number">
                                03
                            </span>

                            <span class="order-create-guide-copy">
                                <strong>Jadwal sudah sesuai</strong>
                                <span>
                                    Pilih waktu dan metode pengambilan
                                    yang dapat dipenuhi.
                                </span>
                            </span>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        {{-- Steps --}}
        <section class="order-step-wrapper">
            <div class="container">
                <div class="order-step-card order-reveal">
                    <div class="order-step-item">
                        <span class="order-step-number">01</span>

                        <span class="order-step-copy">
                            <strong>Pilih Layanan</strong>
                            <span>Jenis dan spesifikasi cetak</span>
                        </span>
                    </div>

                    <div class="order-step-item">
                        <span class="order-step-number">02</span>

                        <span class="order-step-copy">
                            <strong>Upload File</strong>
                            <span>Dokumen yang akan dicetak</span>
                        </span>
                    </div>

                    <div class="order-step-item">
                        <span class="order-step-number">03</span>

                        <span class="order-step-copy">
                            <strong>Atur Pengambilan</strong>
                            <span>Jadwal dan metode pembayaran</span>
                        </span>
                    </div>

                    <div class="order-step-item">
                        <span class="order-step-number">04</span>

                        <span class="order-step-copy">
                            <strong>Kirim Pesanan</strong>
                            <span>Periksa estimasi dan konfirmasi</span>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Form Section --}}
        <section class="order-create-section">
            <div class="container">
                @if ($errors->any())
                    <div
                        class="order-error-summary"
                        role="alert"
                    >
                        <span class="order-error-summary-icon">
                            !
                        </span>

                        <span>
                            Pesanan belum dapat dikirim. Periksa
                            kembali kolom yang masih salah atau belum
                            dilengkapi.
                        </span>
                    </div>
                @endif

                <form
                    action="{{ route('customer.pesanan.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="order-form-layout"
                    id="orderCreateForm"
                >
                    @csrf

                    <div class="order-form-main">
                        {{-- Service Information --}}
                        <section class="order-form-card order-reveal">
                            <div class="order-form-card-header">
                                <span class="order-form-card-number">
                                    01
                                </span>

                                <div class="order-form-card-heading">
                                    <h2>Informasi Layanan</h2>

                                    <p>
                                        Pilih layanan dan tentukan
                                        spesifikasi dokumen yang akan
                                        dicetak.
                                    </p>
                                </div>
                            </div>

                            <div class="order-form-card-body">
                                <div class="order-form-group">
                                    <div class="order-form-label-row">
                                        <label
                                            for="layanan_id"
                                            class="order-form-label"
                                        >
                                            Layanan
                                            <span class="order-required">*</span>
                                        </label>

                                        <span class="order-optional">
                                            Pilih satu layanan
                                        </span>
                                    </div>

                                    <div class="order-field-wrapper">
                                        <svg
                                            class="order-field-icon"
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

                                        <select
                                            id="layanan_id"
                                            name="layanan_id"
                                            class="order-select has-icon {{
                                                $errors->has('layanan_id')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            required
                                        >
                                            <option value="">
                                                Pilih layanan cetak
                                            </option>

                                            @foreach ($layanans as $layanan)
                                                <option
                                                    value="{{ $layanan->id }}"
                                                    data-nama="{{ $layanan->nama_layanan }}"
                                                    data-harga="{{ (float) $layanan->harga_dasar }}"
                                                    data-satuan="{{ $layanan->satuan }}"
                                                    {{
                                                        $layananTerpilihId
                                                        === $layanan->id
                                                            ? 'selected'
                                                            : ''
                                                    }}
                                                >
                                                    {{ $layanan->nama_layanan }}
                                                    - Rp {{
                                                        number_format(
                                                            (float) $layanan->harga_dasar,
                                                            0,
                                                            ',',
                                                            '.'
                                                        )
                                                    }}
                                                    / {{ $layanan->satuan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @error('layanan_id')
                                        <span class="order-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div
                                    class="order-service-preview"
                                    id="orderServicePreview"
                                >
                                    <div class="order-service-preview-main">
                                        <span class="order-service-preview-icon">
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

                                        <span class="order-service-preview-copy">
                                            <span>Layanan dipilih</span>

                                            <strong id="previewServiceName">
                                                Belum dipilih
                                            </strong>
                                        </span>
                                    </div>

                                    <span class="order-service-preview-price">
                                        <strong id="previewServicePrice">
                                            Rp 0
                                        </strong>

                                        <span id="previewServiceUnit">
                                            per satuan
                                        </span>
                                    </span>
                                </div>

                                <div class="order-form-grid-two">
                                    <div class="order-form-group">
                                        <div class="order-form-label-row">
                                            <label
                                                for="jenis_print"
                                                class="order-form-label"
                                            >
                                                Jenis Print
                                            </label>

                                            <span class="order-optional">
                                                Opsional
                                            </span>
                                        </div>

                                        <select
                                            id="jenis_print"
                                            name="jenis_print"
                                            class="order-select"
                                        >
                                            <option value="">
                                                Tidak ditentukan
                                            </option>

                                            <option
                                                value="hitam_putih"
                                                {{
                                                    old('jenis_print')
                                                    === 'hitam_putih'
                                                        ? 'selected'
                                                        : ''
                                                }}
                                            >
                                                Hitam Putih
                                            </option>

                                            <option
                                                value="warna"
                                                {{
                                                    old('jenis_print')
                                                    === 'warna'
                                                        ? 'selected'
                                                        : ''
                                                }}
                                            >
                                                Warna
                                            </option>
                                        </select>

                                        @error('jenis_print')
                                            <span class="order-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="order-form-group">
                                        <div class="order-form-label-row">
                                            <label
                                                for="ukuran_kertas"
                                                class="order-form-label"
                                            >
                                                Ukuran Kertas
                                                <span class="order-required">*</span>
                                            </label>
                                        </div>

                                        <select
                                            id="ukuran_kertas"
                                            name="ukuran_kertas"
                                            class="order-select {{
                                                $errors->has('ukuran_kertas')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            required
                                        >
                                            @foreach (['A4', 'F4'] as $ukuran)
                                                <option
                                                    value="{{ $ukuran }}"
                                                    {{
                                                        old(
                                                            'ukuran_kertas',
                                                            'A4'
                                                        ) === $ukuran
                                                            ? 'selected'
                                                            : ''
                                                    }}
                                                >
                                                    {{ $ukuran }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('ukuran_kertas')
                                            <span class="order-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="order-form-grid-two">
                                    <div class="order-form-group">
                                        <div class="order-form-label-row">
                                            <label
                                                for="jumlah_halaman"
                                                class="order-form-label"
                                            >
                                                Jumlah Halaman
                                                <span class="order-required">*</span>
                                            </label>
                                        </div>

                                        <div class="order-number-wrapper">
                                            <input
                                                type="number"
                                                id="jumlah_halaman"
                                                name="jumlah_halaman"
                                                value="{{
                                                    old(
                                                        'jumlah_halaman',
                                                        1
                                                    )
                                                }}"
                                                class="order-input {{
                                                    $errors->has('jumlah_halaman')
                                                        ? 'is-invalid'
                                                        : ''
                                                }}"
                                                min="1"
                                                inputmode="numeric"
                                                required
                                            >

                                            <span class="order-number-label">
                                                halaman
                                            </span>
                                        </div>

                                        @error('jumlah_halaman')
                                            <span class="order-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="order-form-group">
                                        <div class="order-form-label-row">
                                            <label
                                                for="jumlah_copy"
                                                class="order-form-label"
                                            >
                                                Jumlah Copy
                                                <span class="order-required">*</span>
                                            </label>
                                        </div>

                                        <div class="order-number-wrapper">
                                            <input
                                                type="number"
                                                id="jumlah_copy"
                                                name="jumlah_copy"
                                                value="{{
                                                    old(
                                                        'jumlah_copy',
                                                        1
                                                    )
                                                }}"
                                                class="order-input {{
                                                    $errors->has('jumlah_copy')
                                                        ? 'is-invalid'
                                                        : ''
                                                }}"
                                                min="1"
                                                inputmode="numeric"
                                                required
                                            >

                                            <span class="order-number-label">
                                                copy
                                            </span>
                                        </div>

                                        @error('jumlah_copy')
                                            <span class="order-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="order-addon-heading">
                                    <strong>Layanan Tambahan</strong>

                                    <span>
                                        Tambahkan jilid atau laminating
                                        apabila dibutuhkan.
                                    </span>
                                </div>

                                <div class="order-addon-grid">
                                    <label class="order-addon-card">
                                        <input
                                            type="checkbox"
                                            id="pakai_jilid"
                                            name="pakai_jilid"
                                            value="1"
                                            class="order-addon-input"
                                            data-biaya="{{ $biayaJilid }}"
                                            {{
                                                old('pakai_jilid')
                                                    ? 'checked'
                                                    : ''
                                            }}
                                        >

                                        <span class="order-addon-check">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="3"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                aria-hidden="true"
                                            >
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>

                                        <span class="order-addon-copy">
                                            <strong>Pakai Jilid</strong>

                                            <span>
                                                Tambahkan jilid pada
                                                dokumen pesanan.
                                            </span>

                                            <small>
                                                + Rp {{
                                                    number_format(
                                                        $biayaJilid,
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                }}
                                            </small>
                                        </span>
                                    </label>

                                    <label class="order-addon-card">
                                        <input
                                            type="checkbox"
                                            id="pakai_laminating"
                                            name="pakai_laminating"
                                            value="1"
                                            class="order-addon-input"
                                            data-biaya="{{ $biayaLaminating }}"
                                            {{
                                                old('pakai_laminating')
                                                    ? 'checked'
                                                    : ''
                                            }}
                                        >

                                        <span class="order-addon-check">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="3"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                aria-hidden="true"
                                            >
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>

                                        <span class="order-addon-copy">
                                            <strong>Pakai Laminating</strong>

                                            <span>
                                                Lindungi dokumen dengan
                                                lapisan laminating.
                                            </span>

                                            <small>
                                                + Rp {{
                                                    number_format(
                                                        $biayaLaminating,
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                }}
                                            </small>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </section>

                        {{-- Upload File --}}
                        <section class="order-form-card order-reveal">
                            <div class="order-form-card-header">
                                <span class="order-form-card-number">
                                    02
                                </span>

                                <div class="order-form-card-heading">
                                    <h2>Upload File Pesanan</h2>

                                    <p>
                                        Unggah dokumen final yang akan
                                        diproses oleh admin.
                                    </p>
                                </div>
                            </div>

                            <div class="order-form-card-body">
                                <div class="order-form-group">
                                    <div class="order-form-label-row">
                                        <label
                                            for="files"
                                            class="order-form-label"
                                        >
                                            File Dokumen
                                            <span class="order-required">*</span>
                                        </label>

                                        <span class="order-optional">
                                            Maksimal 5 file
                                        </span>
                                    </div>

                                    <input
                                        type="file"
                                        id="files"
                                        name="files[]"
                                        class="order-file-input"
                                        multiple
                                        accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png"
                                        required
                                    >

                                    <label
                                        for="files"
                                        class="order-upload-zone"
                                        id="orderUploadZone"
                                    >
                                        <span class="order-upload-icon">
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
                                                <path d="M12 16V4"/>
                                                <path d="m7 9 5-5 5 5"/>
                                                <path d="M5 20h14"/>
                                            </svg>
                                        </span>

                                        <strong>
                                            Pilih atau jatuhkan file di sini
                                        </strong>

                                        <p>
                                            Pastikan file sudah final
                                            dan dapat dibuka dengan baik.
                                        </p>

                                        <span class="order-upload-button">
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

                                            Pilih File
                                        </span>
                                    </label>

                                    <div class="order-file-rules">
                                        <span class="order-file-rule">
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

                                            Maksimal 5 file
                                        </span>

                                        <span class="order-file-rule">
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

                                            20 MB per file
                                        </span>

                                        <span class="order-file-rule">
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

                                            Total maksimal 50 MB
                                        </span>
                                    </div>

                                    <div
                                        class="order-selected-files"
                                        id="orderSelectedFiles"
                                    >
                                        <div class="order-selected-files-header">
                                            <strong>File Dipilih</strong>

                                            <span id="orderSelectedFilesInfo">
                                                0 file
                                            </span>
                                        </div>

                                        <div
                                            class="order-selected-file-list"
                                            id="orderSelectedFileList"
                                        ></div>
                                    </div>

                                    <div
                                        class="order-file-validation"
                                        id="orderFileValidation"
                                        role="alert"
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
                                            <path d="M12 7v6"/>
                                            <path d="M12 17h.01"/>
                                        </svg>

                                        <span id="orderFileValidationText"></span>
                                    </div>

                                    @error('files')
                                        <span class="order-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                    @error('files.*')
                                        <span class="order-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="order-form-group">
                                    <div class="order-form-label-row">
                                        <label
                                            for="catatan_detail"
                                            class="order-form-label"
                                        >
                                            Catatan File
                                        </label>

                                        <span class="order-optional">
                                            Opsional
                                        </span>
                                    </div>

                                    <div class="order-field-wrapper order-textarea-wrapper">
                                        <svg
                                            class="order-field-icon"
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

                                        <textarea
                                            id="catatan_detail"
                                            name="catatan_detail"
                                            rows="4"
                                            class="order-textarea has-icon {{
                                                $errors->has('catatan_detail')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="Contoh: halaman 1 sampai 10 warna, sisanya hitam putih."
                                        >{{ old('catatan_detail') }}</textarea>
                                    </div>

                                    @error('catatan_detail')
                                        <span class="order-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                    <span class="order-field-help">
                                        Jelaskan pembagian halaman,
                                        urutan file, atau instruksi khusus
                                        agar admin tidak salah memproses.
                                    </span>
                                </div>
                            </div>
                        </section>

                        {{-- Schedule and Pickup --}}
                        <section class="order-form-card order-reveal">
                            <div class="order-form-card-header">
                                <span class="order-form-card-number">
                                    03
                                </span>

                                <div class="order-form-card-heading">
                                    <h2>Jadwal dan Pengambilan</h2>

                                    <p>
                                        Tentukan jadwal, lokasi, dan
                                        metode pembayaran pesanan.
                                    </p>
                                </div>
                            </div>

                            <div class="order-form-card-body">
                                <div class="order-form-grid-two">
                                    <div class="order-form-group">
                                        <div class="order-form-label-row">
                                            <label
                                                for="tanggal_pengambilan"
                                                class="order-form-label"
                                            >
                                                Tanggal Pengambilan
                                                <span class="order-required">*</span>
                                            </label>
                                        </div>

                                        <div class="order-field-wrapper">
                                            <svg
                                                class="order-field-icon"
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

                                            <input
                                                type="date"
                                                id="tanggal_pengambilan"
                                                name="tanggal_pengambilan"
                                                value="{{ old('tanggal_pengambilan') }}"
                                                class="order-input has-icon {{
                                                    $errors->has('tanggal_pengambilan')
                                                        ? 'is-invalid'
                                                        : ''
                                                }}"
                                                required
                                            >
                                        </div>

                                        @error('tanggal_pengambilan')
                                            <span class="order-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="order-form-group">
                                        <div class="order-form-label-row">
                                            <label
                                                for="jam_pengambilan"
                                                class="order-form-label"
                                            >
                                                Jam Pengambilan
                                                <span class="order-required">*</span>
                                            </label>
                                        </div>

                                        <div class="order-field-wrapper">
                                            <svg
                                                class="order-field-icon"
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

                                            <input
                                                type="time"
                                                id="jam_pengambilan"
                                                name="jam_pengambilan"
                                                value="{{ old('jam_pengambilan') }}"
                                                class="order-input has-icon {{
                                                    $errors->has('jam_pengambilan')
                                                        ? 'is-invalid'
                                                        : ''
                                                }}"
                                                required
                                            >
                                        </div>

                                        @error('jam_pengambilan')
                                            <span class="order-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="order-form-grid-two">
                                    <div class="order-form-group">
                                        <div class="order-form-label-row">
                                            <label
                                                for="lokasi_pengambilan"
                                                class="order-form-label"
                                            >
                                                Lokasi Pengambilan
                                                <span class="order-required">*</span>
                                            </label>
                                        </div>

                                        <select
                                            id="lokasi_pengambilan"
                                            name="lokasi_pengambilan"
                                            class="order-select {{
                                                $errors->has('lokasi_pengambilan')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            required
                                        >
                                            <option value="">
                                                Pilih lokasi
                                            </option>

                                            <option
                                                value="Kampus UEU Tangerang"
                                                {{
                                                    old('lokasi_pengambilan')
                                                    === 'Kampus UEU Tangerang'
                                                        ? 'selected'
                                                        : ''
                                                }}
                                            >
                                                Kampus UEU Tangerang
                                            </option>

                                            <option
                                                value="Diantar"
                                                {{
                                                    old('lokasi_pengambilan')
                                                    === 'Diantar'
                                                        ? 'selected'
                                                        : ''
                                                }}
                                            >
                                                Diantar
                                            </option>

                                            <option
                                                value="Ojek Online"
                                                {{
                                                    old('lokasi_pengambilan')
                                                    === 'Ojek Online'
                                                        ? 'selected'
                                                        : ''
                                                }}
                                            >
                                                Ojek Online
                                            </option>
                                        </select>

                                        @error('lokasi_pengambilan')
                                            <span class="order-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="order-form-group">
                                        <div class="order-form-label-row">
                                            <label
                                                for="metode_pembayaran"
                                                class="order-form-label"
                                            >
                                                Metode Pembayaran
                                                <span class="order-required">*</span>
                                            </label>
                                        </div>

                                        <select
                                            id="metode_pembayaran"
                                            name="metode_pembayaran"
                                            class="order-select {{
                                                $errors->has('metode_pembayaran')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            required
                                        >
                                            <option
                                                value="cash"
                                                {{
                                                    old(
                                                        'metode_pembayaran',
                                                        'cash'
                                                    ) === 'cash'
                                                        ? 'selected'
                                                        : ''
                                                }}
                                            >
                                                Cash
                                            </option>

                                            <option
                                                value="transfer"
                                                {{
                                                    old('metode_pembayaran')
                                                    === 'transfer'
                                                        ? 'selected'
                                                        : ''
                                                }}
                                            >
                                                Online via Midtrans
                                            </option>
                                        </select>

                                        @error('metode_pembayaran')
                                            <span class="order-field-error">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div
                                    class="order-payment-info"
                                    id="orderPaymentInfo"
                                >
                                    <span class="order-payment-info-icon">
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

                                    <span class="order-payment-info-copy">
                                        <strong id="orderPaymentTitle">
                                            Cash
                                        </strong>

                                        <p id="orderPaymentDescription">
                                            Pembayaran dilakukan langsung
                                            kepada admin saat pesanan
                                            diambil atau sesuai konfirmasi
                                            admin.
                                        </p>
                                    </span>
                                </div>

                                <input
                                    type="hidden"
                                    id="channel_pembayaran"
                                    name="channel_pembayaran"
                                    value="{{ old('channel_pembayaran') }}"
                                >

                                <div
                                    class="order-location-hint"
                                    id="orderLocationHint"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria0/svg"
                                        viewBox="0 0 24 24"
                                       -hidden="true"
                                    >
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 11v5"/>
                                        <path d="M12 8h.01"/>
                                    </svg>

                                    <span>
                                        Isi detail lokasi secara lengkap
                                        agar admin atau pengemudi dapat
                                        menemukan alamat tujuan.
                                    </span>
                                </div>

                                <div class="order-form-group">
                                    <div class="order-form-label-row">
                                        <label
                                            for="detail_lokasi"
                                            class="order-form-label"
                                        >
                                            Detail Lokasi
                                        </label>

                                        <span class="order-optional">
                                            Isi untuk pengantaran
                                        </span>
                                    </div>

                                    <div class="order-field-wrapper order-textarea-wrapper">
                                        <svg
                                            class="order-field-icon"
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

                                        <textarea
                                            id="detail_lokasi"
                                            name="detail_lokasi"
                                            rows="3"
                                            class="order-textarea has-icon {{
                                                $errors->has('detail_lokasi')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="Isi alamat lengkap, patokan lokasi, atau titik pertemuan."
                                        >{{ old('detail_lokasi') }}</textarea>
                                    </div>

                                    @error('detail_lokasi')
                                        <span class="order-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="order-form-group">
                                    <div class="order-form-label-row">
                                        <label
                                            for="catatan"
                                            class="order-form-label"
                                        >
                                            Catatan Pesanan
                                        </label>

                                        <span class="order-optional">
                                            Opsional
                                        </span>
                                    </div>

                                    <div class="order-field-wrapper order-textarea-wrapper">
                                        <svg
                                            class="order-field-icon"
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
                                            id="catatan"
                                            name="catatan"
                                            rows="4"
                                            class="order-textarea has-icon {{
                                                $errors->has('catatan')
                                                    ? 'is-invalid'
                                                    : ''
                                            }}"
                                            placeholder="Tambahkan catatan umum untuk admin."
                                        >{{ old('catatan') }}</textarea>
                                    </div>

                                    @error('catatan')
                                        <span class="order-field-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- Summary --}}
                    <aside class="order-summary-column">
                        <section class="order-summary-card order-reveal">
                            <div class="order-summary-header">
                                <div class="order-summary-header-content">
                                    <span class="order-summary-header-icon">
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

                                    <h2>Estimasi Pesanan</h2>

                                    <p>
                                        Ringkasan diperbarui otomatis
                                        berdasarkan data form.
                                    </p>
                                </div>
                            </div>

                            <div class="order-summary-service">
                                <span>Layanan</span>

                                <strong id="summaryServiceName">
                                    Belum memilih layanan
                                </strong>
                            </div>

                            <div class="order-summary-rows">
                                <div class="order-summary-row">
                                    <span>Harga layanan</span>
                                    <strong id="summaryHarga">
                                        Rp 0
                                    </strong>
                                </div>

                                <div class="order-summary-row">
                                    <span>Jumlah file</span>
                                    <strong id="summaryFile">
                                        0
                                    </strong>
                                </div>

                                <div class="order-summary-row">
                                    <span>Halaman × copy</span>
                                    <strong id="summaryHalaman">
                                        1 × 1
                                    </strong>
                                </div>

                                <div class="order-summary-row">
                                    <span>Biaya jilid</span>
                                    <strong id="summaryJilid">
                                        Rp 0
                                    </strong>
                                </div>

                                <div class="order-summary-row">
                                    <span>Biaya laminating</span>
                                    <strong id="summaryLaminating">
                                        Rp 0
                                    </strong>
                                </div>

                                <div class="order-summary-row">
                                    <span>Pembayaran</span>
                                    <strong id="summaryPayment">
                                        Cash
                                    </strong>
                                </div>
                            </div>

                            <div class="order-summary-total">
                                <div class="order-summary-total-label">
                                    <span>Total Estimasi</span>

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="17"
                                        height="17"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                        style="color: var(--order-blue);"
                                    >
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 7v5l3 2"/>
                                    </svg>
                                </div>

                                <strong id="summaryTotal">
                                    Rp 0
                                </strong>

                                <small>
                                    Estimasi dapat berubah setelah
                                    admin memeriksa file dan detail
                                    pesanan.
                                </small>
                            </div>

                            <div class="order-summary-footer">
                                <button
                                    type="submit"
                                    class="order-submit-button"
                                    id="orderSubmitButton"
                                >
                                    <span class="order-submit-spinner"></span>

                                    <svg
                                        class="order-submit-icon"
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

                                    <span data-order-submit-text>
                                        Kirim Pesanan
                                    </span>
                                </button>

                                <span class="order-summary-security">
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

                                    File dan detail pesanan akan
                                    tersimpan pada akun pelangganmu.
                                </span>

                                <a
                                    href="{{ route('customer.pesanan.index') }}"
                                    class="order-back-link"
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

                                    Kembali ke Pesanan Saya
                                </a>
                            </div>
                        </section>

                        <section class="order-important-card order-reveal">
                            <span class="order-important-icon">
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

                            <h3>Periksa Sebelum Mengirim</h3>

                            <p>
                                Pastikan file dapat dibuka, jumlah
                                halaman sudah benar, dan jadwal
                                pengambilan sesuai kebutuhan. Perubahan
                                setelah pesanan diproses harus
                                dikonfirmasi kepada admin.
                            </p>
                        </section>
                    </aside>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /*
            |--------------------------------------------------------------------------
            | Elements
            |--------------------------------------------------------------------------
            */

            const form = document.getElementById(
                'orderCreateForm'
            );

            const layananInput = document.getElementById(
                'layanan_id'
            );

            const halamanInput = document.getElementById(
                'jumlah_halaman'
            );

            const copyInput = document.getElementById(
                'jumlah_copy'
            );

            const fileInput = document.getElementById(
                'files'
            );

            const jilidInput = document.getElementById(
                'pakai_jilid'
            );

            const laminatingInput = document.getElementById(
                'pakai_laminating'
            );

            const pembayaranInput = document.getElementById(
                'metode_pembayaran'
            );

            const channelPembayaranInput = document.getElementById(
                'channel_pembayaran'
            );

            const lokasiInput = document.getElementById(
                'lokasi_pengambilan'
            );

            const detailLokasiInput = document.getElementById(
                'detail_lokasi'
            );

            const submitButton = document.getElementById(
                'orderSubmitButton'
            );

            const uploadZone = document.getElementById(
                'orderUploadZone'
            );

            const selectedFilesContainer = document.getElementById(
                'orderSelectedFiles'
            );

            const selectedFileList = document.getElementById(
                'orderSelectedFileList'
            );

            const selectedFilesInfo = document.getElementById(
                'orderSelectedFilesInfo'
            );

            const fileValidation = document.getElementById(
                'orderFileValidation'
            );

            const fileValidationText = document.getElementById(
                'orderFileValidationText'
            );

            let fileSelectionIsValid = true;
            let formIsSubmitting = false;

            /*
            |--------------------------------------------------------------------------
            | Formatting Helpers
            |--------------------------------------------------------------------------
            */

            const rupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0,
                }).format(Number(number) || 0);
            };

            const fileSize = (bytes) => {
                const value = Number(bytes) || 0;

                if (value < 1024) {
                    return `${value} B`;
                }

                if (value < 1024 * 1024) {
                    return `${(value / 1024).toFixed(1)} KB`;
                }

                return `${(
                    value / (1024 * 1024)
                ).toFixed(1)} MB`;
            };

            const positiveNumber = (value, fallback = 1) => {
                const parsedValue = Number(value);

                if (
                    !Number.isFinite(parsedValue)
                    || parsedValue < 1
                ) {
                    return fallback;
                }

                return parsedValue;
            };

            /*
            |--------------------------------------------------------------------------
            | Service Preview
            |--------------------------------------------------------------------------
            */

            const updateServicePreview = () => {
                if (!layananInput) {
                    return;
                }

                const selectedOption =
                    layananInput.options[
                        layananInput.selectedIndex
                    ];

                const serviceName =
                    selectedOption?.dataset?.nama || '';

                const servicePrice = Number(
                    selectedOption?.dataset?.harga || 0
                );

                const serviceUnit =
                    selectedOption?.dataset?.satuan || '';

                const preview = document.getElementById(
                    'orderServicePreview'
                );

                const previewName = document.getElementById(
                    'previewServiceName'
                );

                const previewPrice = document.getElementById(
                    'previewServicePrice'
                );

                const previewUnit = document.getElementById(
                    'previewServiceUnit'
                );

                const summaryServiceName =
                    document.getElementById(
                        'summaryServiceName'
                    );

                if (serviceName) {
                    preview?.classList.add('visible');

                    if (previewName) {
                        previewName.textContent = serviceName;
                    }

                    if (previewPrice) {
                        previewPrice.textContent =
                            rupiah(servicePrice);
                    }

                    if (previewUnit) {
                        previewUnit.textContent =
                            `per ${serviceUnit}`;
                    }

                    if (summaryServiceName) {
                        summaryServiceName.textContent =
                            serviceName;
                    }
                } else {
                    preview?.classList.remove('visible');

                    if (summaryServiceName) {
                        summaryServiceName.textContent =
                            'Belum memilih layanan';
                    }
                }
            };

            /*
            |--------------------------------------------------------------------------
            | Payment Information
            |--------------------------------------------------------------------------
            */

            const updatePaymentInformation = () => {
                if (!pembayaranInput) {
                    return;
                }

                const paymentInfo = document.getElementById(
                    'orderPaymentInfo'
                );

                const paymentTitle = document.getElementById(
                    'orderPaymentTitle'
                );

                const paymentDescription =
                    document.getElementById(
                        'orderPaymentDescription'
                    );

                const summaryPayment = document.getElementById(
                    'summaryPayment'
                );

                const isTransfer =
                    pembayaranInput.value === 'transfer';

                paymentInfo?.classList.toggle(
                    'transfer',
                    isTransfer
                );

                if (isTransfer) {
                    if (paymentTitle) {
                        paymentTitle.textContent =
                            'Online via Midtrans';
                    }

                    if (paymentDescription) {
                        paymentDescription.textContent =
                            'Setelah pesanan dibuat, kamu akan diarahkan ke proses pembayaran Midtrans. Kanal pembayaran mengikuti metode yang aktif pada Midtrans.';
                    }

                    if (channelPembayaranInput) {
                        channelPembayaranInput.value =
                            'Midtrans';
                    }

                    if (summaryPayment) {
                        summaryPayment.textContent =
                            'Midtrans';
                    }
                } else {
                    if (paymentTitle) {
                        paymentTitle.textContent = 'Cash';
                    }

                    if (paymentDescription) {
                        paymentDescription.textContent =
                            'Pembayaran dilakukan langsung kepada admin saat pesanan diambil atau sesuai konfirmasi admin.';
                    }

                    if (channelPembayaranInput) {
                        channelPembayaranInput.value = '';
                    }

                    if (summaryPayment) {
                        summaryPayment.textContent = 'Cash';
                    }
                }
            };

            /*
            |--------------------------------------------------------------------------
            | Location Information
            |--------------------------------------------------------------------------
            */

            const updateLocationInformation = () => {
                if (!lokasiInput) {
                    return;
                }

                const locationHint = document.getElementById(
                    'orderLocationHint'
                );

                const deliverySelected = [
                    'Diantar',
                    'Ojek Online',
                ].includes(lokasiInput.value);

                locationHint?.classList.toggle(
                    'visible',
                    deliverySelected
                );

                if (!detailLokasiInput) {
                    return;
                }

                if (lokasiInput.value === 'Diantar') {
                    detailLokasiInput.placeholder =
                        'Masukkan alamat lengkap, patokan, nama penerima, dan nomor yang dapat dihubungi.';
                } else if (
                    lokasiInput.value === 'Ojek Online'
                ) {
                    detailLokasiInput.placeholder =
                        'Masukkan titik pengantaran, patokan, dan informasi penerima untuk ojek online.';
                } else {
                    detailLokasiInput.placeholder =
                        'Isi alamat lengkap, patokan lokasi, atau titik pertemuan.';
                }
            };

            /*
            |--------------------------------------------------------------------------
            | File Validation and Preview
            |--------------------------------------------------------------------------
            */

            const validateAndRenderFiles = () => {
                if (
                    !fileInput
                    || !selectedFilesContainer
                    || !selectedFileList
                ) {
                    return;
                }

                const files = Array.from(
                    fileInput.files || []
                );

                const maximumFileCount = 5;
                const maximumFileSize =
                    20 * 1024 * 1024;
                const maximumTotalSize =
                    50 * 1024 * 1024;

                let validationMessage = '';

                const totalSize = files.reduce(
                    (total, file) => total + file.size,
                    0
                );

                const oversizedFile = files.find(
                    (file) => file.size > maximumFileSize
                );

                if (files.length > maximumFileCount) {
                    validationMessage =
                        `Maksimal ${maximumFileCount} file. Saat ini kamu memilih ${files.length} file.`;
                } else if (oversizedFile) {
                    validationMessage =
                        `File "${oversizedFile.name}" melebihi batas 20 MB per file.`;
                } else if (
                    totalSize > maximumTotalSize
                ) {
                    validationMessage =
                        `Total ukuran file ${fileSize(totalSize)} melebihi batas 50 MB.`;
                }

                fileSelectionIsValid =
                    validationMessage === '';

                fileValidation?.classList.toggle(
                    'visible',
                    !fileSelectionIsValid
                );

                if (fileValidationText) {
                    fileValidationText.textContent =
                        validationMessage;
                }

                if (!files.length) {
                    selectedFilesContainer.classList.remove(
                        'visible'
                    );

                    selectedFileList.innerHTML = '';

                    if (selectedFilesInfo) {
                        selectedFilesInfo.textContent =
                            '0 file';
                    }
                } else {
                    selectedFilesContainer.classList.add(
                        'visible'
                    );

                    selectedFileList.innerHTML = '';

                    files.forEach((file) => {
                        const fileItem =
                            document.createElement('div');

                        fileItem.className =
                            'order-selected-file-item';

                        const fileIcon =
                            document.createElement('span');

                        fileIcon.className =
                            'order-selected-file-icon';

                        fileIcon.innerHTML = `
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
                        `;

                        const fileCopy =
                            document.createElement('span');

                        fileCopy.className =
                            'order-selected-file-copy';

                        const fileName =
                            document.createElement('strong');

                        fileName.textContent = file.name;

                        const fileMeta =
                            document.createElement('span');

                        fileMeta.textContent =
                            fileSize(file.size);

                        fileCopy.append(
                            fileName,
                            fileMeta
                        );

                        fileItem.append(
                            fileIcon,
                            fileCopy
                        );

                        selectedFileList.append(fileItem);
                    });

                    if (selectedFilesInfo) {
                        selectedFilesInfo.textContent =
                            `${files.length} file • ${fileSize(totalSize)}`;
                    }
                }

                if (
                    submitButton
                    && !formIsSubmitting
                ) {
                    submitButton.disabled =
                        !fileSelectionIsValid;
                }
            };

            /*
            |--------------------------------------------------------------------------
            | Cost Summary
            |--------------------------------------------------------------------------
            */

            const updateSummary = () => {
                if (!layananInput) {
                    return;
                }

                const selectedOption =
                    layananInput.options[
                        layananInput.selectedIndex
                    ];

                const harga = Number(
                    selectedOption?.dataset?.harga || 0
                );

                const jumlahHalaman = positiveNumber(
                    halamanInput?.value,
                    1
                );

                const jumlahCopy = positiveNumber(
                    copyInput?.value,
                    1
                );

                const jumlahFile =
                    fileInput?.files?.length || 0;

                const biayaJilid =
                    jilidInput?.checked
                        ? Number(
                            jilidInput.dataset.biaya || 0
                        )
                        : 0;

                const biayaLaminating =
                    laminatingInput?.checked
                        ? Number(
                            laminatingInput.dataset.biaya
                            || 0
                        )
                        : 0;

                const totalPerFile =
                    (
                        harga
                        * jumlahHalaman
                        * jumlahCopy
                    )
                    + biayaJilid
                    + biayaLaminating;

                const total =
                    totalPerFile
                    * Math.max(jumlahFile, 1);

                const summaryHarga =
                    document.getElementById(
                        'summaryHarga'
                    );

                const summaryFile =
                    document.getElementById(
                        'summaryFile'
                    );

                const summaryHalaman =
                    document.getElementById(
                        'summaryHalaman'
                    );

                const summaryJilid =
                    document.getElementById(
                        'summaryJilid'
                    );

                const summaryLaminating =
                    document.getElementById(
                        'summaryLaminating'
                    );

                const summaryTotal =
                    document.getElementById(
                        'summaryTotal'
                    );

                if (summaryHarga) {
                    summaryHarga.textContent =
                        rupiah(harga);
                }

                if (summaryFile) {
                    summaryFile.textContent =
                        String(jumlahFile);
                }

                if (summaryHalaman) {
                    summaryHalaman.textContent =
                        `${jumlahHalaman} × ${jumlahCopy}`;
                }

                if (summaryJilid) {
                    summaryJilid.textContent =
                        rupiah(biayaJilid);
                }

                if (summaryLaminating) {
                    summaryLaminating.textContent =
                        rupiah(biayaLaminating);
                }

                if (summaryTotal) {
                    summaryTotal.textContent =
                        rupiah(total);
                }

                updateServicePreview();
            };

            /*
            |--------------------------------------------------------------------------
            | Drag State
            |--------------------------------------------------------------------------
            */

            if (uploadZone) {
                [
                    'dragenter',
                    'dragover',
                ].forEach((eventName) => {
                    uploadZone.addEventListener(
                        eventName,
                        () => {
                            uploadZone.classList.add(
                                'dragging'
                            );
                        }
                    );
                });

                [
                    'dragleave',
                    'drop',
                ].forEach((eventName) => {
                    uploadZone.addEventListener(
                        eventName,
                        () => {
                            uploadZone.classList.remove(
                                'dragging'
                            );
                        }
                    );
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Events
            |--------------------------------------------------------------------------
            */

            [
                layananInput,
                halamanInput,
                copyInput,
                jilidInput,
                laminatingInput,
            ].forEach((element) => {
                if (!element) {
                    return;
                }

                element.addEventListener(
                    'change',
                    updateSummary
                );

                element.addEventListener(
                    'input',
                    updateSummary
                );
            });

            fileInput?.addEventListener(
                'change',
                () => {
                    validateAndRenderFiles();
                    updateSummary();
                }
            );

            pembayaranInput?.addEventListener(
                'change',
                updatePaymentInformation
            );

            lokasiInput?.addEventListener(
                'change',
                updateLocationInformation
            );

            /*
            |--------------------------------------------------------------------------
            | Form Submission
            |--------------------------------------------------------------------------
            */

            form?.addEventListener('submit', (event) => {
                validateAndRenderFiles();

                if (!fileSelectionIsValid) {
                    event.preventDefault();

                    fileValidation?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                    });

                    return;
                }

                if (!form.checkValidity()) {
                    event.preventDefault();
                    form.reportValidity();
                    return;
                }

                if (!submitButton) {
                    return;
                }

                formIsSubmitting = true;
                submitButton.disabled = true;
                submitButton.classList.add('loading');

                const submitText =
                    submitButton.querySelector(
                        '[data-order-submit-text]'
                    );

                if (submitText) {
                    submitText.textContent =
                        'Mengirim Pesanan...';
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Reveal Animation
            |--------------------------------------------------------------------------
            */

            const revealElements =
                document.querySelectorAll(
                    '.order-reveal'
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
                const revealObserver =
                    new IntersectionObserver(
                        (entries, observer) => {
                            entries.forEach((entry) => {
                                if (!entry.isIntersecting) {
                                    return;
                                }

                                entry.target.classList.add(
                                    'is-visible'
                                );

                                observer.unobserve(
                                    entry.target
                                );
                            });
                        },
                        {
                            threshold: 0.08,
                            rootMargin:
                                '0px 0px -35px 0px',
                        }
                    );

                revealElements.forEach((element) => {
                    revealObserver.observe(element);
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Initial State
            |--------------------------------------------------------------------------
            */

            updatePaymentInformation();
            updateLocationInformation();
            validateAndRenderFiles();
            updateSummary();
        });
    </script>
@endpush