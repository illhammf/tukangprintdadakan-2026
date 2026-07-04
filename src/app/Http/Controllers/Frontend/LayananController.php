<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\PengaturanWebsite;

class LayananController extends Controller
{
    public function index()
    {
        $website = PengaturanWebsite::query()->first();

        $kategoriLayanans = KategoriLayanan::query()
            ->where('status', true)
            ->with([
                'layanans' => fn ($query) => $query
                    ->where('status', true)
                    ->where('bisa_online', true)
                    ->latest(),
            ])
            ->get();

        $layanans = Layanan::query()
            ->where('status', true)
            ->where('bisa_online', true)
            ->whereHas('kategoriLayanan', fn ($query) => $query->where('status', true))
            ->latest()
            ->paginate(9);

        return view('public.layanan.index', compact(
            'website',
            'kategoriLayanans',
            'layanans'
        ));
    }

    public function show(Layanan $layanan)
    {
        abort_if(
            ! $layanan->status || ! $layanan->bisa_online || ! $layanan->kategoriLayanan?->status,
            404
        );

        $website = PengaturanWebsite::query()->first();

        $layanan->load('kategoriLayanan');

        $layananTerkait = Layanan::query()
            ->where('id', '!=', $layanan->id)
            ->where('kategori_layanan_id', $layanan->kategori_layanan_id)
            ->where('status', true)
            ->where('bisa_online', true)
            ->limit(3)
            ->get();

        return view('public.layanan.show', compact(
            'website',
            'layanan',
            'layananTerkait'
        ));
    }
}