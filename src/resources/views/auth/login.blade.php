@extends('layouts.public')

@section('title', 'Login - ' . ($website?->nama_website ?? 'Tukang Print Dadakan'))

@section('content')
    <section class="auth-section">
        <div class="container auth-grid">
            <div class="auth-copy">
                <span class="badge">Login Pelanggan</span>

                <h1>Masuk untuk memantau pesananmu.</h1>

                <p>
                    Login digunakan pelanggan untuk membuat pesanan, mengunggah file, melihat estimasi biaya, dan memantau status pesanan.
                </p>

                <div class="auth-note">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Registrasi sekarang</a>
                </div>
            </div>

            <div class="form-card auth-card">
                <h2>Login</h2>

                <form action="{{ route('login.store') }}" method="POST" class="form-stack">
                    @csrf

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
                        <label for="password">Password</label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                        >

                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div style="text-align: right;">
                        <a href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    </div>

                    <button type="submit" class="btn-primary full">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection