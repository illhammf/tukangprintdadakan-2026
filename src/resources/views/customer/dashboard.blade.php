@extends('layouts.public')

@section('title', 'Dashboard Pelanggan - Tukang Print Dadakan')

@section('content')
    <section class="customer-hero">
        <div class="container customer-hero-grid">
            <div>
                <span class="badge">Dashboard Pelanggan</span>

                <h1>{{ $greeting }}, {{ $user->name }}</h1>

                <p>
                    Pantau pesanan, buat pesanan baru, dan lihat perkembangan status pengerjaan layanan cetakmu di sini.
                </p>
            </div>

            <div class="customer-profile-card">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <strong>{{ $user->name }}</strong>
                    <span>{{ $user->email }}</span>
                    <span>{{ $user->nomor_whatsapp ?? 'Nomor WhatsApp belum diisi' }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="dashboard-stats">
                <div class="dashboard-stat-card">
                    <span>Total Pesanan</span>
                    <strong>{{ $totalPesanan }}</strong>
                    <p>Seluruh pesanan yang pernah dibuat.</p>
                </div>

                <div class="dashboard-stat-card">
                    <span>Pesanan Aktif</span>
                    <strong>{{ $pesananAktif }}</strong>
                    <p>Pesanan yang masih diproses atau menunggu verifikasi.</p>
                </div>

                <div class="dashboard-stat-card">
                    <span>Pesanan Selesai</span>
                    <strong>{{ $pesananSelesai }}</strong>
                    <p>Pesanan yang sudah selesai.</p>
                </div>
            </div>

            <div class="customer-action-grid">
                <a href="{{ route('customer.pesanan.create') }}" class="customer-action-card">
                    <h3>Buat Pesanan</h3>
                    <p>Mulai pesanan baru dan unggah file cetak.</p>
                    <span>Buat sekarang</span>
                </a>

                <a href="{{ route('customer.pesanan.index') }}" class="customer-action-card">
                    <h3>Pesanan Saya</h3>
                    <p>Lihat daftar dan status pesanan yang sudah dibuat.</p>
                    <span>Lihat pesanan</span>
                </a>

                <a href="{{ route('customer.profil.edit') }}" class="customer-action-card">
                    <h3>Profil</h3>
                    <p>Kelola nama, email, dan nomor WhatsApp akunmu.</p>
                    <span>Edit profil</span>
                </a>
            </div>

            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>Pesanan Terbaru</h2>
                        <p>Daftar pesanan terakhir yang kamu buat.</p>
                    </div>

                    <a href="{{ route('customer.pesanan.index') }}" class="btn-outline">
                        Lihat Semua
                    </a>
                </div>

                @if ($pesananTerbaru->isNotEmpty())
                    <div class="responsive-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Tanggal Pesan</th>
                                    <th>Pengambilan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pesananTerbaru as $pesanan)
                                    <tr>
                                        <td>
                                            <strong>{{ $pesanan->kode_pesanan }}</strong>
                                        </td>

                                        <td>
                                            {{ $pesanan->tanggal_pesan?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $pesanan->tanggal_pengambilan?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td>
                                            Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}
                                        </td>

                                        <td>
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <h3>Belum ada pesanan</h3>
                        <p>Kamu belum membuat pesanan. Mulai buat pesanan pertama melalui tombol di bawah ini.</p>

                        <a href="{{ route('customer.pesanan.create') }}" class="btn-primary">
                            Buat Pesanan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection