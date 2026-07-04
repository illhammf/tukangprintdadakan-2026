<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function edit()
    {
        return view('customer.profil');
    }

    public function update()
    {
        abort(501, 'Fitur update profil akan dibuat setelah auth pelanggan.');
    }
}