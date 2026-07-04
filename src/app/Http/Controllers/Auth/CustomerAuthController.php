<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login()
    {
        abort(501, 'Fitur login pelanggan akan dibuat pada tahap auth.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register()
    {
        abort(501, 'Fitur registrasi pelanggan akan dibuat pada tahap auth.');
    }

    public function logout()
    {
        abort(501, 'Fitur logout pelanggan akan dibuat pada tahap auth.');
    }
}