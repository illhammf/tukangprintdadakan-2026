@extends('layouts.public')

@section('title', 'Registrasi - ' . ($website?->nama_website ?? 'Tukang Print Dadakan'))

@section('content')
    <section class="auth-section">
        <div class="container auth-grid">
            <div class="auth-copy">
                <span class="badge">Registrasi Pelanggan</span>

                <h1>Buat akun untuk mulai memesan layanan cetak.</h1>

                <p>
                    Setelah registrasi, pelanggan dapat membuat pesanan, mengunggah file, dan melihat status pengerjaan secara mandiri.
                </p>

                <div class="auth-note">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Login di sini</a>
                </div>
            </div>

            <div class="form-card auth-card">
                <h2>Registrasi</h2>

                <form action="{{ route('register.store') }}" method="POST" class="form-stack">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >

                        @error('name')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
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
                            value="{{ old('nomor_whatsapp') }}"
                            placeholder="08xxxxxxxxxx"
                            required
                        >

                        @error('nomor_whatsapp')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                        >

                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-primary full">
                        Daftar
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection