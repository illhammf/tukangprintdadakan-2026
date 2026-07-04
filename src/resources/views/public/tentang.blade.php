@extends('layouts.public')

@section('title', 'Tentang Kami - ' . ($website?->nama_website ?? 'Tukang Print Dadakan'))

@section('content')
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Tentang Kami</h2>
                <p>
                    {{ $website?->nama_website ?? 'Tukang Print Dadakan' }} adalah sistem pemesanan layanan cetak mahasiswa berbasis web yang membantu pelanggan memilih layanan, mengunggah file, melihat estimasi biaya, dan memantau status pesanan secara mandiri.
                </p>
            </div>

            <div class="card-grid">
                <div class="info-card">
                    <h3>Fokus Layanan</h3>
                    <p>Print dokumen, fotokopi, jilid, laminating, dan layanan pendukung kebutuhan akademik mahasiswa.</p>
                </div>

                <div class="info-card">
                    <h3>Lebih Terstruktur</h3>
                    <p>Pesanan dan file pelanggan tersimpan dalam sistem sehingga tidak tercecer di percakapan chat.</p>
                </div>

                <div class="info-card">
                    <h3>Transparan</h3>
                    <p>Pelanggan dapat memantau status pengerjaan pesanan tanpa harus selalu menghubungi admin.</p>
                </div>
            </div>
        </div>
    </section>
@endsection