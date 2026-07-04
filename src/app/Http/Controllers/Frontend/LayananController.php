<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\PengaturanWebsite;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $website = PengaturanWebsite::query()->first();

        $kategoriLayanans = KategoriLayanan::query()
            ->where('status', true)
            ->withCount([
                'layanans' => fn ($query) => $query
                    ->where('status', true)
                    ->where('bisa_online', true),
            ])
            ->orderBy('nama_kategori')
            ->get();

        $layanans = Layanan::query()
            ->with('kategoriLayanan')
            ->where('status', true)
            ->where('bisa_online', true)
            ->whereHas('kategoriLayanan', fn ($query) => $query->where('status', true))
            ->when($request->filled('kategori'), function ($query) use ($request) {
                $query->whereHas('kategoriLayanan', function ($kategoriQuery) use ($request) {
                    $kategoriQuery->where('slug', $request->kategori);
                });
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->q;

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('nama_layanan', 'like', '%' . $keyword . '%')
                        ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('public.layanan.index', compact(
            'website',
            'kategoriLayanans',
            'layanans'
        ));
    }

    public function show(Layanan $layanan)
    {
        $layanan->load('kategoriLayanan');

        abort_if(
            ! $layanan->status || ! $layanan->bisa_online || ! $layanan->kategoriLayanan?->status,
            404
        );

        $website = PengaturanWebsite::query()->first();

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