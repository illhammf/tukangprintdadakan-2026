<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PengaturanWebsite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerPasswordResetController extends Controller
{
    /**
     * Menampilkan halaman lupa password.
     */
    public function showForgotPassword(): View
    {
        $website = PengaturanWebsite::query()->first();

        return view('auth.forgot-password', compact('website'));
    }

    /**
     * Mengganti password secara langsung.
     *
     * Fitur ini hanya digunakan untuk demo pada environment local.
     */
    public function resetDirectly(Request $request): RedirectResponse
    {
        if (! app()->environment('local')) {
            return back()
                ->withErrors([
                    'email' => 'Reset password langsung hanya tersedia pada lingkungan lokal.',
                ])
                ->onlyInput('email', 'nomor_whatsapp');
        }

        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                    'max:150',
                ],
                'nomor_whatsapp' => [
                    'required',
                    'string',
                    'max:30',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'nomor_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
                'password.required' => 'Password baru wajib diisi.',
                'password.min' => 'Password baru minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
            ]
        );

        $user = User::query()
            ->where('email', $validated['email'])
            ->first();

        $nomorInput = $this->normalizeWhatsappNumber(
            $validated['nomor_whatsapp']
        );

        $nomorUser = $this->normalizeWhatsappNumber(
            $user?->nomor_whatsapp
        );

        if (! $user || ! $nomorInput || $nomorInput !== $nomorUser) {
            return back()
                ->withErrors([
                    'email' => 'Email atau nomor WhatsApp tidak cocok dengan data akun.',
                ])
                ->onlyInput('email', 'nomor_whatsapp');
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ]);

        $user->setRememberToken(Str::random(60));
        $user->save();

        /*
         * Menghapus token reset lama jika sebelumnya pernah dibuat.
         */
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        /*
         * Menghapus sesi login lama milik pengguna.
         * SESSION_DRIVER proyek menggunakan database.
         */
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Password berhasil diganti. Silakan login menggunakan password baru.'
            );
    }

    /**
     * Menyamakan format nomor WhatsApp.
     *
     * Contoh:
     * 081234567890 menjadi 6281234567890
     * 81234567890 menjadi 6281234567890
     */
    private function normalizeWhatsappNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $number = preg_replace('/[^0-9]/', '', $number);

        if (! $number) {
            return null;
        }

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        if (str_starts_with($number, '8')) {
            return '62' . $number;
        }

        return $number;
    }
}