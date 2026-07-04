@extends('layouts.public')

@section('title', 'Layanan - ' . ($website?->nama_website ?? 'Tukang Print Dadakan'))

@section('content')
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="badge">Daftar Layanan</span>

                <h1>Layanan cetak untuk kebutuhan mahasiswa</h1>

                <p>
                    Pilih layanan yang tersedia, lihat informasi harga, lalu lanjutkan ke proses pemesanan setelah login.
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="service-toolbar">
                <form action="{{ route('layanan.index') }}" method="GET" class="search-form">
                    @if (request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif

                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari layanan, contoh: print, jilid, laminating"
                    >

                    <button type="submit" class="btn-primary">
                        Cari
                    </button>
                </form>

                @if (request('q') || request('kategori'))
                    <a href="{{ route('layanan.index') }}" class="btn-outline">
                        Reset
                    </a>
                @endif
            </div>

            <div class="category-filter">
                <a
                    href="{{ route('layanan.index', request('q') ? ['q' => request('q')] : []) }}"
                    class="{{ request('kategori') ? '' : 'active' }}"
                >
                    Semua
                </a>

                @foreach ($kategoriLayanans as $kategori)
                    <a
                        href="{{ route('layanan.index', array_filter([
                            'kategori' => $kategori->slug,
                            'q' => request('q'),
                        ])) }}"
                        class="{{ request('kategori') === $kategori->slug ? 'active' : '' }}"
                    >
                        {{ $kategori->nama_kategori }}
                        <span>{{ $kategori->layanans_count }}</span>
                    </a>
                @endforeach
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

                        <div class="service-meta">
                            {{ $layanan->kategoriLayanan?->nama_kategori ?? 'Layanan' }}
                        </div>

                        <h3>{{ $layanan->nama_layanan }}</h3>

                        <p>
                            {{ \Illuminate\Support\Str::limit($layanan->deskripsi ?? 'Layanan cetak tersedia untuk kebutuhan dokumen mahasiswa.', 110) }}
                        </p>

                        <div class="price">
                            Rp {{ number_format((float) $layanan->harga_dasar, 0, ',', '.') }}
                            / {{ $layanan->satuan }}
                        </div>

                        <div class="service-actions">
                            <a href="{{ route('layanan.show', $layanan) }}" class="btn-outline">
                                Detail
                            </a>

                            @auth
                                <a href="{{ route('customer.pesanan.create', ['layanan' => $layanan->id]) }}" class="btn-primary">
                                    Pesan
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-primary">
                                    Login untuk Pesan
                                </a>
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <h3>Layanan tidak ditemukan</h3>
                        <p>
                            Tidak ada layanan yang sesuai dengan filter atau kata kunci pencarian.
                        </p>

                        <a href="{{ route('layanan.index') }}" class="btn-primary">
                            Lihat Semua Layanan
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="pagination-wrapper">
                {{ $layanans->links() }}
            </div>
        </div>
    </section>
@endsection
