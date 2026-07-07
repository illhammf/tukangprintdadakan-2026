@extends('layouts.public')

@section('title', $layanan->nama_layanan . ' - ' . ($website?->nama_website ?? 'Tukang Print Dadakan'))

@section('content')
    @php
        $gambarLayanan = $layanan->gambar
            ? \Illuminate\Support\Facades\Storage::url($layanan->gambar)
            : asset('images/placeholder.png');
    @endphp

    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="badge">
                    {{ $layanan->kategoriLayanan?->nama_kategori ?? 'Detail Layanan' }}
                </span>

                <h1>{{ $layanan->nama_layanan }}</h1>

                <p>
                    Lihat informasi layanan, harga dasar, dan ketentuan pemesanan sebelum mengirim file.
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container service-detail-grid">
            <div class="service-detail-image-wrap">
                <img
                    src="{{ $gambarLayanan }}"
                    alt="{{ $layanan->nama_layanan }}"
                    class="service-detail-image"
                >
            </div>

            <div class="service-detail-panel">
                <div class="service-meta">
                    {{ $layanan->kategoriLayanan?->nama_kategori ?? 'Layanan' }}
                </div>

                <h2>{{ $layanan->nama_layanan }}</h2>

                <p>
                    {{ $layanan->deskripsi ?? 'Layanan cetak tersedia untuk kebutuhan dokumen mahasiswa.' }}
                </p>

                <div class="detail-price-box">
                    <span>Harga Dasar</span>

                    <strong>
                        Rp {{ number_format((float) $layanan->harga_dasar, 0, ',', '.') }}
                        / {{ $layanan->satuan }}
                    </strong>
                </div>

                <div class="detail-list">
                    <div>
                        <span>Kategori</span>
                        <strong>{{ $layanan->kategoriLayanan?->nama_kategori ?? '-' }}</strong>
                    </div>

                    <div>
                        <span>Upload File</span>
                        <strong>{{ $layanan->butuh_upload_file ? 'Diperlukan' : 'Tidak diperlukan' }}</strong>
                    </div>

                    <div>
                        <span>Pemesanan Online</span>
                        <strong>{{ $layanan->bisa_online ? 'Tersedia' : 'Tidak tersedia' }}</strong>
                    </div>

                    <div>
                        <span>Status Layanan</span>
                        <strong>{{ $layanan->status ? 'Aktif' : 'Tidak Aktif' }}</strong>
                    </div>
                </div>

                <div class="detail-actions">
                    @auth
                        <a href="{{ route('customer.pesanan.create', ['layanan' => $layanan->id]) }}" class="btn-primary">
                            Buat Pesanan
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary">
                            Login untuk Memesan
                        </a>
                    @endauth

                    <a href="{{ route('layanan.index') }}" class="btn-outline">
                        Kembali ke Layanan
                    </a>
                </div>

                <p class="detail-note">
                    Catatan: estimasi akhir dapat berubah berdasarkan jumlah halaman, jumlah copy, jilid, laminating, dan hasil verifikasi admin.
                </p>
            </div>
        </div>
    </section>

    @if ($layananTerkait->isNotEmpty())
        <section class="section section-white">
            <div class="container">
                <div class="section-title">
                    <h2>Layanan Terkait</h2>
                    <p>Layanan lain dari kategori yang sama.</p>
                </div>

                <div class="card-grid">
                    @foreach ($layananTerkait as $item)
                        @php
                            $gambarItem = $item->gambar
                                ? \Illuminate\Support\Facades\Storage::url($item->gambar)
                                : asset('images/placeholder.png');
                        @endphp

                        <article class="service-card">
                            <img
                                src="{{ $gambarItem }}"
                                alt="{{ $item->nama_layanan }}"
                                class="service-image"
                            >

                            <h3>{{ $item->nama_layanan }}</h3>

                            <p>
                                {{ \Illuminate\Support\Str::limit($item->deskripsi ?? 'Layanan cetak tersedia untuk kebutuhan dokumen mahasiswa.', 95) }}
                            </p>

                            <div class="price">
                                Rp {{ number_format((float) $item->harga_dasar, 0, ',', '.') }}
                                / {{ $item->satuan }}
                            </div>

                            <a href="{{ route('layanan.show', $item) }}" class="link-more">
                                Lihat detail
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection