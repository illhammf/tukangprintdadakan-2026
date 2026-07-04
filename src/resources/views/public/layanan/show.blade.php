@extends('layouts.public')

@section('title', $layanan->nama_layanan . ' - ' . ($website?->nama_website ?? 'Tukang Print Dadakan'))

@section('content')
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>{{ $layanan->nama_layanan }}</h2>
                <p>Halaman detail layanan akan kita rapikan pada tahap berikutnya.</p>
            </div>
        </div>
    </section>
@endsection