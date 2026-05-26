<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NfcLog;

class NfcController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Simpan Hasil Scan NFC
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        $request->validate([
            'serial_number' => 'nullable|string',
            'isi_nfc'       => 'nullable|string',
        ]);

        NfcLog::create([

            'serial_number' => $request->serial_number,

            'isi_nfc'       => $request->isi_nfc,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data NFC berhasil disimpan'
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Riwayat Scan NFC
    |--------------------------------------------------------------------------
    */
    public function index()
    {

        $logs = NfcLog::latest()->get();

        return view('nfc.riwayat', compact('logs'));

    }
}