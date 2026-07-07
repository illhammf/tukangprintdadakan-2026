<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KontakMasuk;
use App\Models\PengaturanWebsite;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function index()
    {
        $website = PengaturanWebsite::query()->first();

        return view('public.kontak', compact('website'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'nomor_whatsapp' => ['required', 'string', 'max:30'],
            'subjek' => ['required', 'string', 'max:150'],
            'pesan' => ['required', 'string', 'max:2000'],
        ]);

        KontakMasuk::query()->create([
            ...$validated,
            'status_pesan' => 'baru',
        ]);

        $website = PengaturanWebsite::query()->first();

        $whatsappUrl = $this->buildAdminWhatsappUrl(
            $website?->nomor_whatsapp,
            "Halo Admin Tukang Print Dadakan.\n\n"
            . "Ada pesan masuk baru dari halaman kontak.\n\n"
            . "Nama: {$validated['nama']}\n"
            . "Email: {$validated['email']}\n"
            . "WhatsApp: {$validated['nomor_whatsapp']}\n"
            . "Subjek: {$validated['subjek']}\n\n"
            . "Pesan:\n{$validated['pesan']}\n\n"
            . "Mohon ditindaklanjuti."
        );

        if ($whatsappUrl) {
            return redirect()->away($whatsappUrl);
        }

        return back()->with('success', 'Pesan berhasil dikirim. Admin akan menindaklanjuti pesan Anda.');
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