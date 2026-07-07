@extends('layouts.customer')

@section('title', 'Pesanan Saya - Tukang Print Dadakan')

@section('content')
    <section class="section">
        <div class="container">
            <div class="page-row">
                <div class="section-title compact">
                    <span class="badge">Pesanan Saya</span>
                    <h2>Daftar Pesanan</h2>
                    <p>
                        Pantau semua pesanan yang pernah dibuat, termasuk status pengerjaan dan pembayaran.
                    </p>
                </div>

                <a href="{{ route('customer.pesanan.create') }}" class="btn-primary">
                    Buat Pesanan Baru
                </a>
            </div>

            <div class="filter-panel">
                <form action="{{ route('customer.pesanan.index') }}" method="GET" class="filter-form">
                    <div class="form-group">
                        <label for="q">Cari Pesanan</label>
                        <input
                            type="text"
                            id="q"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari kode pesanan"
                        >
                    </div>

                    <div class="form-group">
                        <label for="status">Status Pesanan</label>
                        <select id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>
                                Menunggu Verifikasi
                            </option>
                            <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>
                                Diproses
                            </option>
                            <option value="siap_diambil" {{ request('status') === 'siap_diambil' ? 'selected' : '' }}>
                                Siap Diambil
                            </option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>
                            <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan
                            </option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-primary">
                            Filter
                        </button>

                        @if (request('q') || request('status'))
                            <a href="{{ route('customer.pesanan.index') }}" class="btn-outline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="order-list">
                @forelse ($pesanans as $pesanan)
                    <article class="order-card">
                        <div class="order-card-main">
                            <div>
                                <span class="order-code">{{ $pesanan->kode_pesanan }}</span>

                                <h3>
                                    Pesanan {{ $pesanan->tanggal_pesan?->format('d M Y') ?? '-' }}
                                </h3>

                                <p>
                                    Pengambilan:
                                    <strong>{{ $pesanan->tanggal_pengambilan?->format('d M Y') ?? '-' }}</strong>
                                    pukul
                                    <strong>{{ $pesanan->jam_pengambilan?->format('H:i') ?? '-' }}</strong>
                                </p>
                            </div>

                            <div class="order-card-status">
                                <span class="status-pill status-{{ $pesanan->status_pesanan }}">
                                    {{ match ($pesanan->status_pesanan) {
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                        'diproses' => 'Diproses',
                                        'siap_diambil' => 'Siap Diambil',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                        default => ucfirst($pesanan->status_pesanan),
                                    } }}
                                </span>

                                <span class="payment-pill">
                                    {{ match ($pesanan->pembayaran?->status_pembayaran) {
                                        'belum_bayar' => 'Belum Bayar',
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                        'lunas' => 'Lunas',
                                        'ditolak' => 'Ditolak',
                                        default => 'Belum Ada Pembayaran',
                                    } }}
                                </span>
                            </div>
                        </div>

                        <div class="order-card-footer">
                            <div>
                                <span>Total Pesanan</span>
                                <strong>Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}</strong>
                            </div>

                            <div>
                                <span>Lokasi</span>
                                <strong>{{ $pesanan->lokasi_pengambilan ?? '-' }}</strong>
                            </div>

                            <a href="{{ route('customer.pesanan.show', $pesanan) }}" class="btn-outline">
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <h3>Belum ada pesanan</h3>
                        <p>
                            Kamu belum membuat pesanan. Mulai buat pesanan pertama dengan memilih layanan dan mengunggah file.
                        </p>

                        <a href="{{ route('customer.pesanan.create') }}" class="btn-primary">
                            Buat Pesanan
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="pagination-wrapper">
                {{ $pesanans->links() }}
            </div>
        </div>
    </section>
@endsection