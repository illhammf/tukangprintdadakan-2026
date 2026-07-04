<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\PengaturanWebsite;

class HomeController extends Controller
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
                    ->latest()
                    ->limit(4),
            ])
            ->get();

        $layanans = Layanan::query()
            ->where('status', true)
            ->where('bisa_online', true)
            ->whereHas('kategoriLayanan', fn ($query) => $query->where('status', true))
            ->latest()
            ->limit(6)
            ->get();

        return view('public.home', compact(
            'website',
            'kategoriLayanans',
            'layanans'
        ));
    }

    public function tentang()
    {
        $website = PengaturanWebsite::query()->first();

        return view('public.tentang', compact('website'));
    }
}