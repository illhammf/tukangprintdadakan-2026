@extends('layouts.public')

@section('title', 'Kontak - ' . ($website?->nama_website ?? 'Tukang Print Dadakan'))

@section('content')
    @php
        $nomorWhatsapp = $website?->nomor_whatsapp;
        $nomorWhatsappClean = $nomorWhatsapp ? preg_replace('/[^0-9]/', '', $nomorWhatsapp) : null;

        if ($nomorWhatsappClean && str_starts_with($nomorWhatsappClean, '0')) {
            $nomorWhatsappClean = '62' . substr($nomorWhatsappClean, 1);
        }
    @endphp

    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="badge">Kontak</span>

                <h1>Hubungi Tukang Print Dadakan</h1>

                <p>
                    Kirim pertanyaan, konfirmasi layanan, atau sampaikan kebutuhan khusus melalui formulir kontak.
                    Pesan akan diterima oleh admin melalui dashboard.
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container contact-grid">
            <div class="contact-info">
                <div class="info-card">
                    <h3>Informasi Kontak</h3>

                    <div class="contact-list">
                        <div>
                            <span>WhatsApp</span>
                            <strong>{{ $website?->nomor_whatsapp ?? '-' }}</strong>
                        </div>

                        <div>
                            <span>Email</span>
                            <strong>{{ $website?->email ?? '-' }}</strong>
                        </div>

                        <div>
                            <span>Jam Operasional</span>
                            <strong>{{ $website?->jam_operasional ?? '-' }}</strong>
                        </div>

                        <div>
                            <span>Lokasi Pengambilan</span>
                            <strong>{{ $website?->alamat ?? '-' }}</strong>
                        </div>
                    </div>

                    @if ($nomorWhatsappClean)
                        <a
                            href="https://wa.me/{{ $nomorWhatsappClean }}"
                            target="_blank"
                            class="btn-primary full"
                        >
                            Chat WhatsApp
                        </a>
                    @endif
                </div>

                <div class="info-card">
                    <h3>Catatan Pemesanan</h3>

                    <p>
                        Untuk membuat pesanan resmi, pelanggan disarankan login terlebih dahulu agar file,
                        estimasi biaya, dan status pesanan dapat tercatat di sistem.
                    </p>

                    @auth
                        <a href="{{ route('customer.pesanan.create') }}" class="link-more">
                            Buat pesanan sekarang
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="link-more">
                            Login untuk membuat pesanan
                        </a>
                    @endauth
                </div>
            </div>

            <div class="form-card">
                <h3>Form Pertanyaan</h3>

                <form action="{{ route('kontak.store') }}" method="POST" class="form-stack">
                    @csrf

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="{{ old('nama', auth()->user()?->name) }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >

                        @error('nama')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', auth()->user()?->email) }}"
                                placeholder="contoh@email.com"
                                required
                            >

                            @error('email')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nomor_whatsapp">Nomor WhatsApp</label>
                            <input
                                type="text"
                                id="nomor_whatsapp"
                                name="nomor_whatsapp"
                                value="{{ old('nomor_whatsapp', auth()->user()?->nomor_whatsapp) }}"
                                placeholder="08xxxxxxxxxx"
                                required
                            >

                            @error('nomor_whatsapp')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subjek">Subjek</label>
                        <input
                            type="text"
                            id="subjek"
                            name="subjek"
                            value="{{ old('subjek') }}"
                            placeholder="Contoh: Tanya estimasi print laporan"
                            required
                        >

                        @error('subjek')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pesan">Pesan</label>
                        <textarea
                            id="pesan"
                            name="pesan"
                            rows="6"
                            placeholder="Tulis pertanyaan atau kebutuhan Anda di sini"
                            required
                        >{{ old('pesan') }}</textarea>

                        @error('pesan')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary full">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection