@extends('layouts.customer')

@section('title', 'Reset Password - Tukang Print Dadakan')

@section('content')
    <section class="section">
        <div class="container">
            <div class="section-title">
                <span class="badge">Reset Password</span>

                <h2>Buat Password Baru</h2>

                <p>
                    Masukkan email dan password baru untuk memulihkan akun Anda.
                </p>
            </div>

            <div class="form-card" style="max-width: 520px; margin: 0 auto;">
                @if ($errors->any())
                    <div class="alert alert-error">
                        Password gagal diperbarui. Periksa kembali data yang dimasukkan.
                    </div>
                @endif

                <form
                    action="{{ route('password.update') }}"
                    method="POST"
                    class="form-stack"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="token"
                        value="{{ $token }}"
                    >

                    <div class="form-group">
                        <label for="email">Email</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $email) }}"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            required
                        >

                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>

                        <input
                            type="password"
                            id="password"
                            name="password"
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
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-primary full">
                        Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection