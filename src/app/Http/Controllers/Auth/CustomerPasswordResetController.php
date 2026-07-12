<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PengaturanWebsite;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerPasswordResetController extends Controller
{
    /**
     * Menampilkan halaman permintaan reset password.
     */
    public function showForgotPassword(): View
    {
        $website = PengaturanWebsite::query()->first();

        return view('auth.forgot-password', compact('website'));
    }

    /**
     * Mengirim tautan reset password ke email pengguna.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        $status = Password::sendResetLink([
            'email' => $validated['email'],
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'success',
                'Tautan reset password telah dikirim. Silakan periksa email Anda.'
            );
        }

        $message = $status === Password::RESET_THROTTLED
            ? 'Permintaan reset password terlalu sering. Silakan tunggu beberapa saat.'
            : 'Email tidak ditemukan pada sistem.';

        return back()
            ->withErrors([
                'email' => $message,
            ])
            ->onlyInput('email');
    }

    /**
     * Menampilkan formulir untuk membuat password baru.
     */
    public function showResetPassword(
        Request $request,
        string $token
    ): View {
        $website = PengaturanWebsite::query()->first();

        return view('auth.reset-password', [
            'website' => $website,
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Memvalidasi token dan mengganti password pengguna.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            [
                'email' => $validated['email'],
                'password' => $validated['password'],
                'password_confirmation' => $request->password_confirmation,
                'token' => $validated['token'],
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Password berhasil diperbarui. Silakan login menggunakan password baru.'
                );
        }

        $message = match ($status) {
            Password::INVALID_TOKEN => 'Tautan reset password tidak valid atau sudah kedaluwarsa.',
            Password::INVALID_USER => 'Email tidak ditemukan pada sistem.',
            default => 'Password gagal diperbarui. Silakan ulangi proses reset password.',
        };

        return back()
            ->withErrors([
                'email' => $message,
            ])
            ->withInput($request->only('email'));
    }
}