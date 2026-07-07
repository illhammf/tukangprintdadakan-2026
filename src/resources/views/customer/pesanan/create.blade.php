@extends('layouts.customer')

@section('title', 'Buat Pesanan - Tukang Print Dadakan')

@section('content')
    <section class="section">
        <div class="container">
            <div class="section-title">
                <span class="badge">Buat Pesanan</span>
                <h2>Form Pemesanan Layanan Cetak</h2>
                <p>
                    Lengkapi data pesanan, unggah file, dan lihat estimasi biaya sebelum pesanan dikirim.
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    Periksa kembali data pesanan. Ada input yang belum sesuai.
                </div>
            @endif

            <form action="{{ route('customer.pesanan.store') }}" method="POST" enctype="multipart/form-data" class="order-form-grid">
                @csrf

                <div class="order-form-main">
                    <div class="form-card">
                        <h3>Informasi Layanan</h3>

                        <div class="form-stack">
                            <div class="form-group">
                                <label for="layanan_id">Layanan</label>
                                <select id="layanan_id" name="layanan_id" required>
                                    <option value="">Pilih layanan</option>
                                    @foreach ($layanans as $layanan)
                                        <option
                                            value="{{ $layanan->id }}"
                                            data-harga="{{ (float) $layanan->harga_dasar }}"
                                            data-satuan="{{ $layanan->satuan }}"
                                            {{ (int) old('layanan_id', $selectedLayanan?->id) === $layanan->id ? 'selected' : '' }}
                                        >
                                            {{ $layanan->nama_layanan }}
                                            - Rp {{ number_format((float) $layanan->harga_dasar, 0, ',', '.') }}
                                            / {{ $layanan->satuan }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('layanan_id')
                                    <small>{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="jenis_print">Jenis Print</label>
                                    <select id="jenis_print" name="jenis_print">
                                        <option value="">Tidak ditentukan</option>
                                        <option value="hitam_putih" {{ old('jenis_print') === 'hitam_putih' ? 'selected' : '' }}>Hitam Putih</option>
                                        <option value="warna" {{ old('jenis_print') === 'warna' ? 'selected' : '' }}>Warna</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="ukuran_kertas">Ukuran Kertas</label>
                                    <select id="ukuran_kertas" name="ukuran_kertas" required>
                                        @foreach (['A4', 'F4'] as $ukuran)
                                            <option value="{{ $ukuran }}" {{ old('ukuran_kertas', 'A4') === $ukuran ? 'selected' : '' }}>
                                                {{ $ukuran }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('ukuran_kertas')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="jumlah_halaman">Jumlah Halaman</label>
                                    <input
                                        type="number"
                                        id="jumlah_halaman"
                                        name="jumlah_halaman"
                                        value="{{ old('jumlah_halaman', 1) }}"
                                        min="1"
                                        required
                                    >

                                    @error('jumlah_halaman')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_copy">Jumlah Copy</label>
                                    <input
                                        type="number"
                                        id="jumlah_copy"
                                        name="jumlah_copy"
                                        value="{{ old('jumlah_copy', 1) }}"
                                        min="1"
                                        required
                                    >

                                    @error('jumlah_copy')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="checkbox-card-grid">
                                <label class="checkbox-card">
                                    <input
                                        type="checkbox"
                                        id="pakai_jilid"
                                        name="pakai_jilid"
                                        value="1"
                                        data-biaya="{{ (float) ($pengaturanBooking?->biaya_jilid ?? 0) }}"
                                        {{ old('pakai_jilid') ? 'checked' : '' }}
                                    >
                                    <span>
                                        <strong>Pakai Jilid</strong>
                                        <small>Biaya tambahan Rp {{ number_format((float) ($pengaturanBooking?->biaya_jilid ?? 0), 0, ',', '.') }}</small>
                                    </span>
                                </label>

                                <label class="checkbox-card">
                                    <input
                                        type="checkbox"
                                        id="pakai_laminating"
                                        name="pakai_laminating"
                                        value="1"
                                        data-biaya="{{ (float) ($pengaturanBooking?->biaya_laminating ?? 0) }}"
                                        {{ old('pakai_laminating') ? 'checked' : '' }}
                                    >
                                    <span>
                                        <strong>Pakai Laminating</strong>
                                        <small>Biaya tambahan Rp {{ number_format((float) ($pengaturanBooking?->biaya_laminating ?? 0), 0, ',', '.') }}</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3>Upload File</h3>

                        <div class="form-stack">
                            <div class="form-group">
                                <label for="files">File Pesanan</label>
                                <input
                                    type="file"
                                    id="files"
                                    name="files[]"
                                    multiple
                                    required
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png"
                                >

                                <p class="form-help">
                                    Format: PDF, DOC, DOCX, PPT, PPTX, JPG, JPEG, PNG. Maksimal 5 file, 20 MB per file, dan total 50 MB.
                                </p>

                                @error('files')
                                    <small>{{ $message }}</small>
                                @enderror

                                @error('files.*')
                                    <small>{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="catatan_detail">Catatan File</label>
                                <textarea
                                    id="catatan_detail"
                                    name="catatan_detail"
                                    rows="4"
                                    placeholder="Contoh: halaman 1-10 warna, sisanya hitam putih."
                                >{{ old('catatan_detail') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3>Jadwal dan Pengambilan</h3>

                        <div class="form-stack">
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="tanggal_pengambilan">Tanggal Pengambilan</label>
                                    <input
                                        type="date"
                                        id="tanggal_pengambilan"
                                        name="tanggal_pengambilan"
                                        value="{{ old('tanggal_pengambilan') }}"
                                        required
                                    >

                                    @error('tanggal_pengambilan')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jam_pengambilan">Jam Pengambilan</label>
                                    <input
                                        type="time"
                                        id="jam_pengambilan"
                                        name="jam_pengambilan"
                                        value="{{ old('jam_pengambilan') }}"
                                        required
                                    >

                                    @error('jam_pengambilan')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="lokasi_pengambilan">Lokasi Pengambilan</label>
                                    <select id="lokasi_pengambilan" name="lokasi_pengambilan" required>
                                        <option value="">Pilih lokasi</option>
                                        <option value="Kampus UEU Tangerang" {{ old('lokasi_pengambilan') === 'Kampus UEU Tangerang' ? 'selected' : '' }}>Kampus UEU Tangerang</option>
                                        <option value="Diantar" {{ old('lokasi_pengambilan') === 'Diantar' ? 'selected' : '' }}>Diantar</option>
                                        <option value="Ojek Online" {{ old('lokasi_pengambilan') === 'Ojek Online' ? 'selected' : '' }}>Ojek Online</option>
                                    </select>

                                    @error('lokasi_pengambilan')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="metode_pembayaran">Metode Pembayaran</label>
                                    <select id="metode_pembayaran" name="metode_pembayaran" required>
                                        <option value="cash" {{ old('metode_pembayaran', 'cash') === 'cash' ? 'selected' : '' }}>
                                            Cash
                                        </option>

                                        <option value="transfer" {{ old('metode_pembayaran') === 'transfer' ? 'selected' : '' }}>
                                            Online via Midtrans
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="payment-info-box" id="paymentInfoBox">
                                <strong>Cash</strong>
                                <p>
                                    Pembayaran dilakukan langsung kepada admin saat pesanan diambil atau sesuai konfirmasi admin.
                                </p>
                            </div>

                            <input type="hidden" id="channel_pembayaran" name="channel_pembayaran" value="{{ old('channel_pembayaran') }}">

                            <div class="form-group">
                                <label for="detail_lokasi">Detail Lokasi</label>
                                <textarea
                                    id="detail_lokasi"
                                    name="detail_lokasi"
                                    rows="3"
                                    placeholder="Isi jika memilih diantar atau ojek online."
                                >{{ old('detail_lokasi') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="catatan">Catatan Pesanan</label>
                                <textarea
                                    id="catatan"
                                    name="catatan"
                                    rows="4"
                                    placeholder="Tambahkan catatan umum untuk admin."
                                >{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="order-summary">
                    <div class="order-summary-card">
                        <h3>Estimasi Biaya</h3>

                        <div class="summary-row">
                            <span>Harga layanan</span>
                            <strong id="summaryHarga">Rp 0</strong>
                        </div>

                        <div class="summary-row">
                            <span>Jumlah file</span>
                            <strong id="summaryFile">0</strong>
                        </div>

                        <div class="summary-row">
                            <span>Halaman × copy</span>
                            <strong id="summaryHalaman">1 × 1</strong>
                        </div>

                        <div class="summary-row">
                            <span>Jilid</span>
                            <strong id="summaryJilid">Rp 0</strong>
                        </div>

                        <div class="summary-row">
                            <span>Laminating</span>
                            <strong id="summaryLaminating">Rp 0</strong>
                        </div>

                        <div class="summary-total">
                            <span>Total Estimasi</span>
                            <strong id="summaryTotal">Rp 0</strong>
                        </div>

                        <p>
                            Estimasi dapat berubah setelah admin melakukan verifikasi file dan detail pesanan.
                        </p>

                        <button type="submit" class="btn-primary full">
                            Kirim Pesanan
                        </button>
                    </div>
                </aside>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const layanan = document.querySelector('#layanan_id');
            const halaman = document.querySelector('#jumlah_halaman');
            const copy = document.querySelector('#jumlah_copy');
            const files = document.querySelector('#files');
            const jilid = document.querySelector('#pakai_jilid');
            const laminating = document.querySelector('#pakai_laminating');
            const metodePembayaran = document.querySelector('#metode_pembayaran');
            const paymentInfoBox = document.querySelector('#paymentInfoBox');
            const channelPembayaran = document.querySelector('#channel_pembayaran');

            const updatePaymentInfo = () => {
                if (!metodePembayaran || !paymentInfoBox || !channelPembayaran) {
                    return;
                }

                if (metodePembayaran.value === 'transfer') {
                    paymentInfoBox.innerHTML = `
                        <strong>Online via Midtrans</strong>
                        <p>
                            Setelah pesanan dikirim, kamu akan diarahkan ke halaman pembayaran Midtrans.
                            Pilihan pembayaran seperti bank transfer, e-wallet, atau metode lain akan mengikuti kanal yang aktif di Midtrans.
                        </p>
                    `;

                    channelPembayaran.value = 'Midtrans';
                } else {
                    paymentInfoBox.innerHTML = `
                        <strong>Cash</strong>
                        <p>
                            Pembayaran dilakukan langsung kepada admin saat pesanan diambil atau sesuai konfirmasi admin.
                        </p>
                    `;

                    channelPembayaran.value = '';
                }
            };

            metodePembayaran?.addEventListener('change', updatePaymentInfo);
            updatePaymentInfo();

            const rupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0,
                }).format(number || 0);
            };

            const updateSummary = () => {
                const selectedOption = layanan.options[layanan.selectedIndex];
                const harga = Number(selectedOption?.dataset?.harga || 0);
                const jumlahHalaman = Number(halaman.value || 1);
                const jumlahCopy = Number(copy.value || 1);
                const jumlahFile = files.files.length || 0;

                const biayaJilid = jilid.checked ? Number(jilid.dataset.biaya || 0) : 0;
                const biayaLaminating = laminating.checked ? Number(laminating.dataset.biaya || 0) : 0;

                const totalPerFile = (harga * jumlahHalaman * jumlahCopy) + biayaJilid + biayaLaminating;
                const total = totalPerFile * Math.max(jumlahFile, 1);

                document.querySelector('#summaryHarga').textContent = rupiah(harga);
                document.querySelector('#summaryFile').textContent = jumlahFile;
                document.querySelector('#summaryHalaman').textContent = `${jumlahHalaman} × ${jumlahCopy}`;
                document.querySelector('#summaryJilid').textContent = rupiah(biayaJilid);
                document.querySelector('#summaryLaminating').textContent = rupiah(biayaLaminating);
                document.querySelector('#summaryTotal').textContent = rupiah(total);
            };

            [layanan, halaman, copy, files, jilid, laminating].forEach((element) => {
                element.addEventListener('change', updateSummary);
                element.addEventListener('input', updateSummary);
            });

            updateSummary();
        });
    </script>
@endsection