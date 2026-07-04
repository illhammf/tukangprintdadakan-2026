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

        return back()->with('success', 'Pesan berhasil dikirim. Admin akan menindaklanjuti pesan Anda.');
    }
}