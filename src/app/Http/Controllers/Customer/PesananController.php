<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class PesananController extends Controller
{
    public function index()
    {
        return view('customer.pesanan.index');
    }

    public function create()
    {
        return view('customer.pesanan.create');
    }

    public function store()
    {
        abort(501, 'Fitur simpan pesanan akan dibuat pada tahap form pesanan.');
    }

    public function show()
    {
        return view('customer.pesanan.show');
    }

    public function cancel()
    {
        abort(501, 'Fitur pembatalan pesanan akan dibuat pada tahap detail pesanan.');
    }
}