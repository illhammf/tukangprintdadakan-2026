@extends('layouts.public')

@section('title', ($website?->nama_website ?? 'Tukang Print Dadakan') . ' - Layanan Print Mahasiswa')

@section('content')
    @php
        $heroImage = $website?->hero_image
            ? \Illuminate\Support\Facades\Storage::url($website->hero_image)
            : asset('images/placeholder.png');
    @endphp

    <section class="hero">
        <div class="container hero-grid">
            <div>
                <span class="badge">Print cepat untuk mahasiswa</span>

                <h1>
                    {{ $website?->hero_title ?? 'Cetak tugas, laporan, dan dokumen jadi lebih mudah.' }}
                </h1>

                <p>
                    {{ $website?->hero_subtitle ?? 'Pilih layanan, unggah file, lihat estimasi biaya, lalu pantau status pesanan langsung dari sistem.' }}
                </p>

                <div class="hero-actions">
                    <a href="{{ route('layanan.index') }}" class="btn-primary">
                        Lihat Layanan
                    </a>

                    @auth
                        <a href="{{ route('customer.pesanan.create') }}" class="btn-outline">
                            Buat Pesanan
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-outline">
                            Mulai Pesan
                        </a>
                    @endauth
                </div>
            </div>

            <div class="hero-card">
                <img src="{{ $heroImage }}" alt="Tukang Print Dadakan" class="hero-image">

                <div class="stats-row">
                    <div class="stat-box">
                        <strong>{{ $layanans->count() }}</strong>
                        <span>Layanan tersedia</span>
                    </div>

                    <div class="stat-box">
                        <strong>5</strong>
                        <span>Status pesanan</span>
                    </div>

                    <div class="stat-box">
                        <strong>50MB</strong>
                        <span>Total upload</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-title center">
                <h2>Kenapa pakai Tukang Print Dadakan?</h2>
                <p>
                    Sistem ini membantu proses pemesanan cetak menjadi lebih terstruktur, transparan, dan mudah dipantau.
                </p>
            </div>

            <div class="card-grid">
                <div class="feature-card">
                    <div class="feature-icon">1</div>
                    <h3>Pesan Online</h3>
                    <p>Pelanggan dapat memilih layanan dan membuat pesanan tanpa harus menghubungi admin secara manual.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">2</div>
                    <h3>Upload File</h3>
                    <p>File pesanan tersimpan di sistem sehingga lebih rapi dan mudah diperiksa oleh admin.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">3</div>
                    <h3>Pantau Status</h3>
                    <p>Pelanggan dapat melihat perkembangan pesanan mulai dari menunggu verifikasi sampai selesai.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-white">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Populer</h2>
                <p>Pilih layanan yang sesuai dengan kebutuhan cetak dokumen mahasiswa.</p>
            </div>

            <div class="card-grid">
                @forelse ($layanans as $layanan)
                    @php
                        $gambarLayanan = $layanan->gambar
                            ? \Illuminate\Support\Facades\Storage::url($layanan->gambar)
                            : asset('images/placeholder.png');
                    @endphp

                    <article class="service-card">
                        <img src="{{ $gambarLayanan }}" alt="{{ $layanan->nama_layanan }}" class="service-image">

                        <h3>{{ $layanan->nama_layanan }}</h3>

                        <p>
                            {{ \Illuminate\Support\Str::limit($layanan->deskripsi ?? 'Layanan cetak tersedia untuk kebutuhan dokumen mahasiswa.', 95) }}
                        </p>

                        <div class="price">
                            Rp {{ number_format((float) $layanan->harga_dasar, 0, ',', '.') }}
                            / {{ $layanan->satuan }}
                        </div>

                        <a href="{{ route('layanan.show', $layanan) }}" class="link-more">
                            Lihat detail
                        </a>
                    </article>
                @empty
                    <div class="feature-card">
                        <h3>Belum ada layanan</h3>
                        <p>Layanan aktif akan tampil di bagian ini setelah ditambahkan oleh admin.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-title center">
                <h2>Alur Pemesanan</h2>
                <p>Proses pemesanan dibuat sederhana agar pelanggan bisa mengirim file dan memantau pesanan dengan mudah.</p>
            </div>

            <div class="card-grid">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <h3>Pilih Layanan</h3>
                    <p>Pilih layanan print, fotokopi, jilid, laminating, atau layanan lain yang tersedia.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">02</div>
                    <h3>Upload File</h3>
                    <p>Unggah file dengan format yang didukung dan lengkapi detail pesanan.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">03</div>
                    <h3>Pantau Status</h3>
                    <p>Lihat status pesanan secara mandiri melalui dashboard pelanggan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="cta">
                <div>
                    <h2>Siap membuat pesanan?</h2>
                    <p>Masuk atau daftar terlebih dahulu untuk mulai membuat pesanan layanan cetak.</p>
                </div>

                @auth
                    <a href="{{ route('customer.pesanan.create') }}" class="btn-secondary">
                        Buat Pesanan
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-secondary">
                        Registrasi Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </section>
@endsection