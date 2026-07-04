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
                    {{ $website?->hero_subtitle ?? 'Pilih layanan, unggah file, lihat estimasi biaya, lalu pantau status pesanan langsung dari sistem tanpa harus chat admin berulang kali.' }}
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
                        <span>Layanan aktif</span>
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
                <h2>Kenapa menggunakan Tukang Print Dadakan?</h2>
                <p>
                    Sistem ini membantu pelanggan membuat pesanan cetak secara lebih terstruktur, transparan, dan mudah dipantau.
                </p>
            </div>

            <div class="card-grid">
                <div class="feature-card">
                    <div class="feature-icon">1</div>
                    <h3>Pesan Online</h3>
                    <p>
                        Pelanggan dapat memilih layanan dan membuat pesanan tanpa harus mengirim detail pesanan secara manual melalui chat.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">2</div>
                    <h3>Upload File</h3>
                    <p>
                        File pesanan tersimpan pada sistem sehingga lebih mudah dikelola dan diperiksa oleh admin.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">3</div>
                    <h3>Pantau Status</h3>
                    <p>
                        Pelanggan dapat melihat status pesanan mulai dari menunggu verifikasi, diproses, siap diambil, sampai selesai.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-white">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Populer</h2>
                <p>
                    Pilih layanan cetak yang sesuai dengan kebutuhan dokumen, tugas, laporan, atau kebutuhan akademik lainnya.
                </p>
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
                        <h3>Belum ada layanan aktif</h3>
                        <p>
                            Layanan akan tampil setelah admin menambahkan dan mengaktifkan layanan melalui dashboard.
                        </p>
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 28px; text-align: center;">
                <a href="{{ route('layanan.index') }}" class="btn-primary">
                    Lihat Semua Layanan
                </a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-title center">
                <h2>Alur Pemesanan</h2>
                <p>
                    Proses dibuat sederhana agar pelanggan dapat membuat pesanan dan memantau pengerjaan secara mandiri.
                </p>
            </div>

            <div class="card-grid">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <h3>Pilih Layanan</h3>
                    <p>
                        Buka halaman layanan, pilih jenis layanan yang dibutuhkan, lalu lihat informasi harga dan detailnya.
                    </p>
                </div>

                <div class="step-card">
                    <div class="step-number">02</div>
                    <h3>Upload File</h3>
                    <p>
                        Masuk ke akun pelanggan, lengkapi detail pesanan, dan unggah file sesuai ketentuan sistem.
                    </p>
                </div>

                <div class="step-card">
                    <div class="step-number">03</div>
                    <h3>Pantau Status</h3>
                    <p>
                        Setelah pesanan terkirim, pelanggan dapat melihat perkembangan status melalui dashboard pelanggan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-white">
        <div class="container">
            <div class="section-title center">
                <h2>Kategori Layanan</h2>
                <p>
                    Layanan dikelompokkan agar pelanggan lebih mudah menemukan kebutuhan cetaknya.
                </p>
            </div>

            <div class="card-grid">
                @forelse ($kategoriLayanans as $kategori)
                    <div class="info-card">
                        <h3>{{ $kategori->nama_kategori }}</h3>

                        <p>
                            {{ \Illuminate\Support\Str::limit($kategori->deskripsi ?? 'Kategori layanan cetak yang tersedia untuk pelanggan.', 100) }}
                        </p>

                        <p>
                            <strong>{{ $kategori->layanans->count() }}</strong> layanan tersedia
                        </p>
                    </div>
                @empty
                    <div class="info-card">
                        <h3>Belum ada kategori aktif</h3>
                        <p>Kategori layanan aktif akan tampil pada bagian ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="cta">
                <div>
                    <h2>Siap membuat pesanan?</h2>
                    <p>
                        Login atau registrasi terlebih dahulu untuk membuat pesanan, mengunggah file, dan memantau status pengerjaan.
                    </p>
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