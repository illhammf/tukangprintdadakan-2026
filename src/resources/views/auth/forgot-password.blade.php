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
                            placeholder="Contoh: 08.........."
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

                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Minimal 8 karakter"
                                minlength="8"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                data-target="password"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                            >
                                <svg
                                    class="icon-eye"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696C3.597 7.327 7.595 4 12 4c4.405 0 8.403 3.327 9.938 7.652a1 1 0 0 1 0 .696C20.403 16.673 16.405 20 12 20c-4.405 0-8.403-3.327-9.938-7.652"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                                <svg
                                    class="icon-eye-off"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                    hidden
                                >
                                    <path d="m2 2 20 20"/>
                                    <path d="M6.713 6.709C4.879 7.938 3.485 9.804 2.797 12c1.44 4.583 5.717 8 10.203 8 1.39 0 2.746-.328 3.968-.899"/>
                                    <path d="M10.73 5.073A10.8 10.8 0 0 1 13 4c4.486 0 8.763 3.417 10.203 8a11.4 11.4 0 0 1-1.778 3.138"/>
                                    <path d="M14.121 14.121A3 3 0 0 1 9.879 9.879"/>
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">
                            Konfirmasi Password Baru
                        </label>

                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi password baru"
                                minlength="8"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                data-target="password_confirmation"
                                aria-label="Tampilkan konfirmasi password"
                                aria-pressed="false"
                            >
                                <svg
                                    class="icon-eye"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696C3.597 7.327 7.595 4 12 4c4.405 0 8.403 3.327 9.938 7.652a1 1 0 0 1 0 .696C20.403 16.673 16.405 20 12 20c-4.405 0-8.403-3.327-9.938-7.652"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                                <svg
                                    class="icon-eye-off"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                    hidden
                                >
                                    <path d="m2 2 20 20"/>
                                    <path d="M6.713 6.709C4.879 7.938 3.485 9.804 2.797 12c1.44 4.583 5.717 8 10.203 8 1.39 0 2.746-.328 3.968-.899"/>
                                    <path d="M10.73 5.073A10.8 10.8 0 0 1 13 4c4.486 0 8.763 3.417 10.203 8a11.4 11.4 0 0 1-1.778 3.138"/>
                                    <path d="M14.121 14.121A3 3 0 0 1 9.879 9.879"/>
                                </svg>
                            </button>
                        </div>

                        @error('password_confirmation')
                            <small>{{ $message }}</small>
                        @enderror
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButtons = document.querySelectorAll('.password-toggle');

        toggleButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const targetId = button.dataset.target;
                const passwordInput = document.getElementById(targetId);

                if (!passwordInput) {
                    return;
                }

                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                button.setAttribute(
                    'aria-label',
                    isHidden ? 'Sembunyikan password' : 'Tampilkan password'
                );

                const eyeIcon = button.querySelector('.icon-eye');
                const eyeOffIcon = button.querySelector('.icon-eye-off');

                if (eyeIcon) {
                    eyeIcon.hidden = isHidden;
                }

                if (eyeOffIcon) {
                    eyeOffIcon.hidden = !isHidden;
                }
            });
        });
    });
</script>
@endsection