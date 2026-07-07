<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PengaturanWebsite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        $website = PengaturanWebsite::query()->first();

        return view('auth.login', compact('website'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak sesuai.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->hasAnyRole(['super_admin', 'admin', 'pemilik_usaha'])) {
            return redirect('/admin');
        }

        return redirect()->intended(route('customer.dashboard'));
    }

    public function showRegister()
    {
        $website = PengaturanWebsite::query()->first();

        return view('auth.register', compact('website'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'nomor_whatsapp' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nomor_whatsapp' => $validated['nomor_whatsapp'],
            'password' => Hash::make($validated['password']),
        ]);

        Role::findOrCreate('pelanggan');

        $user->assignRole('pelanggan');

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('customer.dashboard')
            ->with('success', 'Registrasi berhasil. Selamat datang di Tukang Print Dadakan.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Anda berhasil logout.');
    }
}