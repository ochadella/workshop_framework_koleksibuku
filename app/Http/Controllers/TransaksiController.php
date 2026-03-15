<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Penjualan::orderBy('id', 'desc')->get();

        return view('transaksi.index', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = Penjualan::with('details')->findOrFail($id);

        return view('transaksi.show', compact('transaksi'));
    }

    public function cetakStruk($id)
    {
        $transaksi = Penjualan::with('details')->findOrFail($id);

        $pdf = Pdf::loadView('transaksi.struk', compact('transaksi'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream('struk-transaksi-' . $transaksi->id . '.pdf');
    }
}