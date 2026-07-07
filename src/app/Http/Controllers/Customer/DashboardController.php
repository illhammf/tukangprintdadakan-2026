<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $now = now(config('app.timezone', 'Asia/Jakarta'));
        $hour = (int) $now->format('H');

        if ($hour >= 5 && $hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        $totalPesanan = Pesanan::query()
            ->where('user_id', $user->id)
            ->count();

        $pesananAktif = Pesanan::query()
            ->where('user_id', $user->id)
            ->whereIn('status_pesanan', ['menunggu_verifikasi', 'diproses', 'siap_diambil'])
            ->count();

        $pesananSelesai = Pesanan::query()
            ->where('user_id', $user->id)
            ->where('status_pesanan', 'selesai')
            ->count();

        $pesananTerbaru = Pesanan::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact(
            'user',
            'greeting',
            'totalPesanan',
            'pesananAktif',
            'pesananSelesai',
            'pesananTerbaru'
        ));
    }
}