@extends('layouts.public')

@section('title', 'Lupa Password - ' . ($website?->nama_website ?? 'Tukang Print Dadakan'))

@section('content')
    <section class="auth-section">
        <div class="container auth-grid">
            <div class="auth-copy">
                <span class="badge">Lupa Password</span>

                <h1>Buat password baru untuk akunmu.</h1>

                <p>
                    Masukkan email dan nomor WhatsApp yang terdaftar,
                    kemudian tentukan password baru.
                </p>

                <div class="auth-note">
                    Sudah ingat password?
                    <a href="{{ route('login') }}">
                        Kembali ke halaman login
                    </a>
                </div>
            </div>

            <div class="form-card auth-card">
                <h2>Reset Password</h2>

                @if ($errors->any())
                    <div class="alert alert-error">
                        Periksa kembali data yang dimasukkan.
                    </div>
                @endif

                <form
                    action="{{ route('password.direct-update') }}"
                    method="POST"
                    class="form-stack"
                >
                    @csrf

                    <div class="form-group">
                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            autocomplete="email"
                            autofocus
                            required
                        >

                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nomor_whatsapp">
                            Nomor WhatsApp Terdaftar
                        </label>

                        <input
                            type="text"
                            id="nomor_whatsapp"
                            name="nomor_whatsapp"
                            value="{{ old('nomor_whatsapp') }}"
                            placeholder="Contoh: 081234567890"
                            maxlength="30"
                            autocomplete="tel"
                            required
                        >

                        @error('nomor_whatsapp')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">
                            Konfirmasi Password Baru
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password baru"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-primary full">
                        Ganti Password
                    </button>

                    <div style="text-align: center;">
                        <a href="{{ route('login') }}">
                            Kembali ke halaman login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection