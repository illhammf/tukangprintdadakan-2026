@extends('layouts.customer')

@section('title', 'Lupa Password - Tukang Print Dadakan')

@section('content')
    <section class="section">
        <div class="container">
            <div class="section-title">
                <span class="badge">Lupa Password</span>

                <h2>Atur Ulang Password</h2>

                <p>
                    Masukkan email yang terdaftar. Sistem akan mengirimkan tautan untuk membuat password baru.
                </p>
            </div>

            <div class="form-card" style="max-width: 520px; margin: 0 auto;">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        Periksa kembali email yang Anda masukkan.
                    </div>
                @endif

                <form
                    action="{{ route('password.email') }}"
                    method="POST"
                    class="form-stack"
                >
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            autofocus
                            required
                        >

                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary full">
                        Kirim Tautan Reset Password
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