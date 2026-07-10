@extends('layouts.customer')

@section('title', 'Detail Pesanan - Tukang Print Dadakan')

@section('content')
    <section class="section">
        <div class="container">
            <div class="detail-order-header">
                <div>
                    <span class="badge">Detail Pesanan</span>

                    <h2>{{ $pesanan->kode_pesanan }}</h2>

                    <p>
                        Berikut detail pesanan, file, pembayaran, pengiriman, dan riwayat status pesanan.
                    </p>
                </div>

                <div class="detail-order-actions">
                    <a href="{{ route('customer.pesanan.index') }}" class="btn-outline">
                        Kembali
                    </a>

                    @if (
                        $pesanan->pembayaran?->metode_pembayaran === 'transfer'
                        && $pesanan->pembayaran?->status_pembayaran !== 'lunas'
                        && $pesanan->pembayaran?->midtrans_order_id
                        && $pesanan->status_pesanan !== 'dibatalkan'
                    )
                        <form action="{{ route('customer.pesanan.check-midtrans', $pesanan) }}" method="POST">
                            @csrf

                            <button type="submit" class="btn-primary">
                                Cek Status Pembayaran
                            </button>
                        </form>
                    @endif

                    @if (
                        $pesanan->status_pesanan === 'menunggu_verifikasi'
                        && $pesanan->pembayaran?->status_pembayaran !== 'lunas'
                    )
                        <form action="{{ route('customer.pesanan.cancel', $pesanan) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button type="submit" class="btn-danger" onclick="return confirm('Batalkan pesanan ini?')">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="order-detail-grid">
                <div class="order-detail-main">
                    <div class="form-card">
                        <h3>Informasi Pesanan</h3>

                        <div class="detail-list">
                            <div>
                                <span>Nama Pelanggan</span>
                                <strong>{{ $pesanan->nama_pelanggan }}</strong>
                            </div>

                            <div>
                                <span>Email</span>
                                <strong>{{ $pesanan->email ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Nomor WhatsApp</span>
                                <strong>{{ $pesanan->nomor_whatsapp ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Tanggal Pesan</span>
                                <strong>{{ $pesanan->tanggal_pesan?->format('d M Y') ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Tanggal Pengambilan</span>
                                <strong>{{ $pesanan->tanggal_pengambilan?->format('d M Y') ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Jam Pengambilan</span>
                                <strong>{{ $pesanan->jam_pengambilan?->format('H:i') ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Lokasi Pengambilan</span>
                                <strong>{{ $pesanan->lokasi_pengambilan ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Status Pesanan</span>
                                <strong>
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
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3>File dan Detail Layanan</h3>

                        @forelse ($pesanan->detailPesanans as $detail)
                            <div class="file-detail-card">
                                <div>
                                    <h4>{{ $detail->nama_file }}</h4>

                                    <p>
                                        {{ $detail->layanan?->nama_layanan ?? 'Layanan tidak tersedia' }}
                                    </p>
                                </div>

                                <div class="file-detail-grid">
                                    <div>
                                        <span>Jenis Print</span>
                                        <strong>
                                            {{ match ($detail->jenis_print) {
                                                'hitam_putih' => 'Hitam Putih',
                                                'warna' => 'Warna',
                                                default => '-',
                                            } }}
                                        </strong>
                                    </div>

                                    <div>
                                        <span>Ukuran</span>
                                        <strong>{{ $detail->ukuran_kertas }}</strong>
                                    </div>

                                    <div>
                                        <span>Halaman</span>
                                        <strong>{{ $detail->jumlah_halaman }}</strong>
                                    </div>

                                    <div>
                                        <span>Copy</span>
                                        <strong>{{ $detail->jumlah_copy }}</strong>
                                    </div>

                                    <div>
                                        <span>Jilid</span>
                                        <strong>{{ $detail->pakai_jilid ? 'Ya' : 'Tidak' }}</strong>
                                    </div>

                                    <div>
                                        <span>Laminating</span>
                                        <strong>{{ $detail->pakai_laminating ? 'Ya' : 'Tidak' }}</strong>
                                    </div>

                                    <div>
                                        <span>Subtotal</span>
                                        <strong>Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}</strong>
                                    </div>
                                </div>

                                @if ($detail->catatan_detail)
                                    <p class="detail-note">
                                        Catatan: {{ $detail->catatan_detail }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="empty-state">
                                <h3>Belum ada file</h3>
                                <p>Detail file pesanan belum tersedia.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="form-card">
                        <h3>Riwayat Status</h3>

                        <div class="timeline">
                            @forelse ($pesanan->riwayatStatusPesanans as $riwayat)
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>

                                    <div>
                                        <strong>
                                            {{ match ($riwayat->status) {
                                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                                'diproses' => 'Diproses',
                                                'siap_diambil' => 'Siap Diambil',
                                                'selesai' => 'Selesai',
                                                'dibatalkan' => 'Dibatalkan',
                                                default => ucfirst($riwayat->status),
                                            } }}
                                        </strong>

                                        <p>{{ $riwayat->catatan ?? '-' }}</p>

                                        <span>
                                            {{ $riwayat->waktu_status?->format('d M Y H:i') ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p>Belum ada riwayat status.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <aside class="order-detail-side">
                    <div class="order-summary-card">
                        <h3>Ringkasan Biaya</h3>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <strong>Rp {{ number_format((float) $pesanan->subtotal, 0, ',', '.') }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Biaya Tambahan</span>
                            <strong>Rp {{ number_format((float) $pesanan->biaya_tambahan, 0, ',', '.') }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Biaya Pengiriman</span>
                            <strong>Rp {{ number_format((float) $pesanan->biaya_pengiriman, 0, ',', '.') }}</strong>
                        </div>

                        <div class="summary-total">
                            <span>Total</span>
                            <strong>Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="order-summary-card">
                        <h3>Pembayaran</h3>

                        @php
                            $pembayaran = $pesanan->pembayaran;

                            $statusPembayaran = $pembayaran?->status_pembayaran;

                            $labelStatusPembayaran = match ($statusPembayaran) {
                                'belum_bayar' => 'Belum Bayar',
                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                'lunas' => 'Lunas',
                                'ditolak' => 'Ditolak',
                                default => '-',
                            };

                            $labelMetodePembayaran = match ($pembayaran?->metode_pembayaran) {
                                'cash' => 'Cash',
                                'transfer' => 'Online via Midtrans',
                                default => '-',
                            };

                            $paymentType = $pembayaran?->payment_type
                                ? ucwords(str_replace('_', ' ', $pembayaran->payment_type))
                                : '-';
                        @endphp

                        <div class="payment-status-box payment-status-{{ $statusPembayaran ?? 'unknown' }}">
                            <span>Status Pembayaran</span>
                            <strong>{{ $labelStatusPembayaran }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Metode</span>
                            <strong>{{ $labelMetodePembayaran }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Channel</span>
                            <strong>{{ $pembayaran?->channel_pembayaran ?? '-' }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Jumlah Bayar</span>
                            <strong>Rp {{ number_format((float) ($pembayaran?->jumlah_bayar ?? 0), 0, ',', '.') }}</strong>
                        </div>

                        <div class="summary-row">
                            <span>Tanggal Bayar</span>
                            <strong>{{ $pembayaran?->tanggal_bayar?->format('d M Y H:i') ?? '-' }}</strong>
                        </div>

                        @if ($pembayaran?->metode_pembayaran === 'transfer')
                            <div class="summary-row">
                                <span>Midtrans Order ID</span>
                                <strong class="break-text">{{ $pembayaran?->midtrans_order_id ?? '-' }}</strong>
                            </div>

                            <div class="summary-row">
                                <span>Transaction ID</span>
                                <strong class="break-text">{{ $pembayaran?->transaction_id ?? '-' }}</strong>
                            </div>

                            <div class="summary-row">
                                <span>Payment Type</span>
                                <strong>{{ $paymentType }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="order-summary-card">
                        <h3>Pengiriman</h3>

                        <div class="summary-row">
                            <span>Metode</span>
                            <strong>
                                {{ match ($pesanan->pengiriman?->metode_pengiriman) {
                                    'ambil_di_kampus' => 'Ambil di Kampus',
                                    'antar' => 'Diantar',
                                    'ojek_online' => 'Ojek Online',
                                    default => '-',
                                } }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Status</span>
                            <strong>
                                {{ match ($pesanan->pengiriman?->status_pengiriman) {
                                    'belum_dikirim' => 'Belum Dikirim',
                                    'diproses' => 'Diproses',
                                    'dikirim' => 'Dikirim',
                                    'selesai' => 'Selesai',
                                    default => '-',
                                } }}
                            </strong>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection