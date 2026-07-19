<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\PengaturanBooking;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PengaturanWebsite;
use App\Services\MidtransService;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::query()
            ->where('user_id', Auth::id())
            ->with(['pembayaran'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = $request->q;

            $query->where(function ($subQuery) use ($keyword) {
                $subQuery
                    ->where('kode_pesanan', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $keyword . '%');
            });
        }

        $pesanans = $query
            ->paginate(8)
            ->withQueryString();

        return view('customer.pesanan.index', compact('pesanans'));
    }

    public function create(Request $request)
    {
        $layanans = Layanan::query()
            ->with('kategoriLayanan')
            ->where('status', true)
            ->where('bisa_online', true)
            ->whereHas('kategoriLayanan', fn ($query) => $query->where('status', true))
            ->orderBy('nama_layanan')
            ->get();

        $pengaturanBooking = PengaturanBooking::query()
            ->where('nama_pengaturan', 'Default Booking')
            ->first() ?? PengaturanBooking::query()->first();

        $selectedLayanan = null;

        if ($request->filled('layanan')) {
            $selectedLayanan = $layanans->firstWhere('id', (int) $request->layanan);
        }

        return view('customer.pesanan.create', compact(
            'layanans',
            'pengaturanBooking',
            'selectedLayanan'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'layanan_id' => ['required', 'exists:layanans,id'],
            'files' => ['required', 'array', 'max:5'],
            'files.*' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png', 'max:20480'],

            'jenis_print' => ['nullable', 'in:hitam_putih,warna'],
            'ukuran_kertas' => ['required', 'string', 'max:20'],
            'jumlah_halaman' => ['required', 'integer', 'min:1'],
            'jumlah_cetak' => ['required', 'integer', 'min:1'],

            'pakai_jilid' => ['nullable', 'boolean'],
            'pakai_laminating' => ['nullable', 'boolean'],

            'tanggal_pengambilan' => ['required', 'date'],
            'jam_pengambilan' => ['required', 'date_format:H:i'],
            'lokasi_pengambilan' => ['required', 'string', 'max:100'],
            'detail_lokasi' => ['nullable', 'string', 'max:500'],

            'catatan' => ['nullable', 'string', 'max:1000'],
            'catatan_detail' => ['nullable', 'string', 'max:1000'],

            'metode_pembayaran' => ['required', 'in:cash,transfer'],
            'channel_pembayaran' => ['nullable', 'string', 'max:100'],
        ]);

        $layanan = Layanan::query()
            ->where('status', true)
            ->where('bisa_online', true)
            ->whereHas('kategoriLayanan', fn ($query) => $query->where('status', true))
            ->findOrFail($validated['layanan_id']);

        $pengaturanBooking = PengaturanBooking::query()
            ->where('nama_pengaturan', 'Default Booking')
            ->first() ?? PengaturanBooking::query()->first();

        $this->validateUploadTotalSize($request);

        $this->validateBookingRules(
            $validated,
            $pengaturanBooking,
            count($request->file('files', []))
        );

        $pesanan = DB::transaction(function () use ($request, $validated, $layanan, $pengaturanBooking) {
            $user = Auth::user();

            $pesanan = Pesanan::query()->create([
                'user_id' => $user->id,
                'nama_pelanggan' => $user->name,
                'email' => $user->email,
                'nomor_whatsapp' => $user->nomor_whatsapp,
                'tanggal_pesan' => now(),
                'tanggal_pengambilan' => $validated['tanggal_pengambilan'],
                'jam_pengambilan' => $validated['jam_pengambilan'],
                'lokasi_pengambilan' => $validated['lokasi_pengambilan'],
                'detail_lokasi' => $validated['detail_lokasi'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'biaya_tambahan' => 0,
                'biaya_pengiriman' => $validated['lokasi_pengambilan'] === 'Diantar'
                    ? (float) ($pengaturanBooking?->ongkir_kampus ?? 0)
                    : 0,
                'status_pesanan' => 'menunggu_verifikasi',
            ]);

            foreach ($request->file('files', []) as $file) {
                $filePath = $file->store('pesanan/' . $pesanan->kode_pesanan, 'public');

                $hargaSatuan = (float) $layanan->harga_dasar;

                $subtotal = $hargaSatuan
                    * (int) $validated['jumlah_halaman']
                    * (int) $validated['jumlah_cetak'];

                if ($request->boolean('pakai_jilid')) {
                    $subtotal += (float) ($pengaturanBooking?->biaya_jilid ?? 0);
                }

                if ($request->boolean('pakai_laminating')) {
                    $subtotal += (float) ($pengaturanBooking?->biaya_laminating ?? 0);
                }

                $pesanan->detailPesanans()->create([
                    'layanan_id' => $layanan->id,
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'jenis_print' => $validated['jenis_print'] ?? null,
                    'ukuran_kertas' => $validated['ukuran_kertas'],
                    'jumlah_halaman' => $validated['jumlah_halaman'],
                    'jumlah_cetak' => $validated['jumlah_cetak'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'pakai_jilid' => $request->boolean('pakai_jilid'),
                    'pakai_laminating' => $request->boolean('pakai_laminating'),
                    'catatan_detail' => $validated['catatan_detail'] ?? null,
                ]);
            }

            $pesanan->updateRingkasanBiaya();
            $pesanan->refresh();

            Pembayaran::query()->create([
                'pesanan_id' => $pesanan->id,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'channel_pembayaran' => $validated['metode_pembayaran'] === 'transfer'
                    ? 'Midtrans'
                    : null,
                'jumlah_bayar' => $pesanan->total_harga,
                'status_pembayaran' => 'belum_bayar',
                'tanggal_bayar' => null,
            ]);

            return $pesanan;
        });

        $pesanan->load(['detailPesanans.layanan', 'pembayaran']);

        if ($pesanan->pembayaran?->metode_pembayaran === 'transfer') {
            $transaction = app(MidtransService::class)->createSnapTransaction($pesanan);

            if (! empty($transaction->redirect_url)) {
                return redirect()->away($transaction->redirect_url);
            }

            return redirect()
                ->route('customer.pesanan.show', $pesanan)
                ->with('error', 'Pesanan berhasil dibuat, tetapi halaman pembayaran Midtrans belum tersedia.');
        }

        $website = PengaturanWebsite::query()->first();

        $whatsappUrl = $this->buildAdminWhatsappUrl(
            $website?->nomor_whatsapp,
            $this->buildPesananWhatsappMessage($pesanan)
        );

        if ($whatsappUrl) {
            return redirect()->away($whatsappUrl);
        }

        return redirect()
            ->route('customer.pesanan.show', $pesanan)
            ->with('success', 'Pesanan berhasil dibuat. Silakan pantau status pesanan secara berkala.');
    }

    public function show(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== Auth::id(), 403);

        $pesanan->load([
            'detailPesanans.layanan',
            'pembayaran',
            'pengiriman',
            'riwayatStatusPesanans' => fn ($query) => $query->latest('waktu_status'),
        ]);

        return view('customer.pesanan.show', compact('pesanan'));
    }

    public function payMidtrans(Pesanan $pesanan) // Untuk membayar pesanan yang sudah dibuat tetapi belum dibayar melalui Midtrans
    {
        abort_if($pesanan->user_id !== Auth::id(), 403);

        $pesanan->load(['pembayaran', 'detailPesanans.layanan']);

        $pembayaran = $pesanan->pembayaran;

        if (! $pembayaran) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($pembayaran->metode_pembayaran !== 'transfer') {
            return back()->with('error', 'Pesanan ini tidak menggunakan pembayaran online Midtrans.');
        }

        if ($pembayaran->status_pembayaran === 'lunas') {
            return back()->with('success', 'Pembayaran pesanan ini sudah lunas.');
        }

        if ($pesanan->status_pesanan === 'dibatalkan') {
            return back()->with('error', 'Pesanan yang sudah dibatalkan tidak dapat dibayar.');
        }

        if (! empty($pembayaran->snap_redirect_url)) {
            return redirect()->away($pembayaran->snap_redirect_url);
        }

        $transaction = app(MidtransService::class)->createSnapTransaction($pesanan);

        if (! empty($transaction->redirect_url)) {
            return redirect()->away($transaction->redirect_url);
        }

        return back()->with('error', 'Halaman pembayaran Midtrans belum tersedia. Silakan coba beberapa saat lagi.');
    }

    public function cancel(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== Auth::id(), 403);

        if ($pesanan->status_pesanan !== 'menunggu_verifikasi') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        $pesanan->ubahStatus('dibatalkan', 'Pesanan dibatalkan oleh pelanggan.');

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    private function validateUploadTotalSize(Request $request): void
    {
        $totalSize = collect($request->file('files', []))
            ->sum(fn ($file) => $file->getSize());

        $maxTotalSize = 50 * 1024 * 1024;

        if ($totalSize > $maxTotalSize) {
            back()
                ->withErrors([
                    'files' => 'Total ukuran seluruh file maksimal 50 MB.',
                ])
                ->throwResponse();
        }
    }

    private function validateBookingRules(array $validated, ?PengaturanBooking $pengaturanBooking, int $jumlahFile): void
    {
        $tanggalPengambilan = Carbon::parse($validated['tanggal_pengambilan'])->startOfDay();
        $hariIni = now(config('app.timezone', 'Asia/Jakarta'))->startOfDay();

        if ($pengaturanBooking?->wajib_h_minus_satu && $tanggalPengambilan->lessThanOrEqualTo($hariIni)) {
            back()
                ->withErrors([
                    'tanggal_pengambilan' => 'Tanggal pengambilan minimal H-1 dari tanggal pemesanan.',
                ])
                ->throwResponse();
        }

        if ($pengaturanBooking?->tutup_sabtu && $tanggalPengambilan->isSaturday()) {
            back()
                ->withErrors([
                    'tanggal_pengambilan' => 'Tanggal pengambilan tidak tersedia pada hari Sabtu.',
                ])
                ->throwResponse();
        }

        if ($pengaturanBooking?->tutup_minggu && $tanggalPengambilan->isSunday()) {
            back()
                ->withErrors([
                    'tanggal_pengambilan' => 'Tanggal pengambilan tidak tersedia pada hari Minggu.',
                ])
                ->throwResponse();
        }

        if ($pengaturanBooking?->tutup_tanggal_merah) {
            $hariLibur = HariLibur::query()
                ->whereDate('tanggal', $tanggalPengambilan->toDateString())
                ->where('status', true)
                ->exists();

            if ($hariLibur) {
                back()
                    ->withErrors([
                        'tanggal_pengambilan' => 'Tanggal pengambilan tidak tersedia karena termasuk hari libur.',
                    ])
                    ->throwResponse();
            }
        }

        $totalLembar = (int) $validated['jumlah_halaman']
            * (int) $validated['jumlah_cetak']
            * $jumlahFile;

        if (
            $pengaturanBooking?->maksimal_lembar_per_order
            && $totalLembar > $pengaturanBooking->maksimal_lembar_per_order
        ) {
            back()
                ->withErrors([
                    'jumlah_halaman' => 'Total lembar melebihi batas maksimal per pesanan.',
                ])
                ->throwResponse();
        }
    }

    private function buildPesananWhatsappMessage(Pesanan $pesanan): string
    {
        $detailText = $pesanan->detailPesanans
            ->map(function ($detail, int $index) {
                $nomor = $index + 1;

                $jenisPrint = match ($detail->jenis_print) {
                    'hitam_putih' => 'Hitam Putih',
                    'warna' => 'Warna',
                    default => '-',
                };

                return "{$nomor}. {$detail->nama_file}\n"
                    . "   Layanan: " . ($detail->layanan?->nama_layanan ?? '-') . "\n"
                    . "   Jenis Print: {$jenisPrint}\n"
                    . "   Ukuran: {$detail->ukuran_kertas}\n"
                    . "   Halaman: {$detail->jumlah_halaman}\n"
                    . "   Cetak: {$detail->jumlah_cetak}\n"
                    . "   Jilid: " . ($detail->pakai_jilid ? 'Ya' : 'Tidak') . "\n"
                    . "   Laminating: " . ($detail->pakai_laminating ? 'Ya' : 'Tidak') . "\n"
                    . "   Subtotal: Rp " . number_format((float) $detail->subtotal, 0, ',', '.');
            })
            ->implode("\n\n");

        $adminUrl = url('/admin/pesanans/' . $pesanan->id . '/edit');

        return "Halo Admin Tukang Print Dadakan.\n\n"
            . "Ada pesanan baru masuk.\n\n"
            . "Kode Pesanan: {$pesanan->kode_pesanan}\n"
            . "Nama: {$pesanan->nama_pelanggan}\n"
            . "Email: " . ($pesanan->email ?? '-') . "\n"
            . "WhatsApp: " . ($pesanan->nomor_whatsapp ?? '-') . "\n\n"
            . "Tanggal Pesan: " . ($pesanan->tanggal_pesan?->format('d M Y') ?? '-') . "\n"
            . "Tanggal Pengambilan: " . ($pesanan->tanggal_pengambilan?->format('d M Y') ?? '-') . "\n"
            . "Jam Pengambilan: " . ($pesanan->jam_pengambilan?->format('H:i') ?? '-') . "\n"
            . "Lokasi: " . ($pesanan->lokasi_pengambilan ?? '-') . "\n"
            . "Detail Lokasi: " . ($pesanan->detail_lokasi ?? '-') . "\n\n"
            . "Detail File:\n"
            . ($detailText ?: '-') . "\n\n"
            . "Total Estimasi: Rp " . number_format((float) $pesanan->total_harga, 0, ',', '.') . "\n"
            . "Metode Pembayaran: " . ucfirst($pesanan->pembayaran?->metode_pembayaran ?? '-') . "\n\n"
            . "Catatan Pesanan:\n"
            . ($pesanan->catatan ?? '-') . "\n\n"
            . "Link Admin:\n{$adminUrl}";
    }

    private function buildAdminWhatsappUrl(?string $nomorWhatsapp, string $message): ?string
    {
        $nomor = $this->normalizeWhatsappNumber($nomorWhatsapp);

        if (! $nomor) {
            return null;
        }

        return 'https://wa.me/' . $nomor . '?text=' . urlencode($message);
    }

    private function normalizeWhatsappNumber(?string $nomorWhatsapp): ?string
    {
        if (! $nomorWhatsapp) {
            return null;
        }

        $nomor = preg_replace('/[^0-9]/', '', $nomorWhatsapp);

        if (! $nomor) {
            return null;
        }

        if (str_starts_with($nomor, '0')) {
            return '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}