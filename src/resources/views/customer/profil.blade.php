@extends('layouts.customer')

@section('title', 'Profil Saya - Tukang Print Dadakan')

@section('content')
    <section class="section">
        <div class="container">
            <div class="page-row">
                <div class="section-title compact">
                    <span class="badge">Profil Saya</span>
                    <h2>Kelola Data Akun</h2>
                    <p>
                        Perbarui nama, email, nomor WhatsApp, dan password akun pelanggan.
                    </p>
                </div>

                <a href="{{ route('customer.dashboard') }}" class="btn-outline">
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="profile-grid">
                <div class="profile-summary-card">
                    <div class="profile-avatar large">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <h3>{{ $user->name }}</h3>
                    <p>{{ $user->email }}</p>
                    <p>{{ $user->nomor_whatsapp ?? 'Nomor WhatsApp belum diisi' }}</p>

                    <div class="profile-meta">
                        <div>
                            <span>Role</span>
                            <strong>{{ $user->roles->pluck('name')->implode(', ') ?: 'Pelanggan' }}</strong>
                        </div>

                        <div>
                            <span>Bergabung</span>
                            <strong>{{ $user->created_at?->format('d M Y') ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <h3>Informasi Profil</h3>

                    <form action="{{ route('customer.profil.update') }}" method="POST" class="form-stack">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
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
                                value="{{ old('email', $user->email) }}"
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
                                value="{{ old('nomor_whatsapp', $user->nomor_whatsapp) }}"
                                required
                            >

                            @error('nomor_whatsapp')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-divider"></div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Kosongkan jika tidak ingin mengganti password"
                            >

                            @error('password')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi password baru"
                            >
                        </div>

                        <button type="submit" class="btn-primary full">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection