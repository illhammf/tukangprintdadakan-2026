@extends('layouts.public')

@section('title', 'Dashboard Pelanggan - Tukang Print Dadakan')

@section('content')
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Dashboard Pelanggan</h2>
                <p>
                    Selamat datang, {{ auth()->user()->name }}. Dashboard pelanggan akan kita rapikan pada tahap berikutnya.
                </p>
            </div>

            <div class="card-grid">
                <div class="info-card">
                    <h3>Pesanan Saya</h3>
                    <p>Lihat daftar pesanan yang pernah dibuat.</p>
                    <a href="{{ route('customer.pesanan.index') }}" class="link-more">Lihat pesanan</a>
                </div>

                <div class="info-card">
                    <h3>Buat Pesanan</h3>
                    <p>Buat pesanan baru dan unggah file cetak.</p>
                    <a href="{{ route('customer.pesanan.create') }}" class="link-more">Buat sekarang</a>
                </div>

                <div class="info-card">
                    <h3>Profil</h3>
                    <p>Kelola data akun pelanggan.</p>
                    <a href="{{ route('customer.profil.edit') }}" class="link-more">Edit profil</a>
                </div>
            </div>
        </div>
    </section>
@endsection