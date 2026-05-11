<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LokasiToko;

class KunjunganTokoController extends Controller
{
    public function index()
    {
        $tokos = LokasiToko::orderBy('id', 'desc')->get();

        return view('kunjungan.index', compact('tokos'));
    }

    public function store(Request $request)
    {
        LokasiToko::create([
            'barcode' => $request->barcode,
            'nama_toko' => $request->nama_toko,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
        ]);

        return redirect()->back()->with('success', 'Data toko berhasil disimpan');
    }

    public function cariBarcode($barcode)
    {
        $toko = LokasiToko::where('barcode', $barcode)->first();

        if (!$toko) {
            return response()->json([
                'status' => false,
                'message' => 'Data toko tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $toko
        ]);
    }

    public function cekKunjungan(Request $request)
    {
        $toko = LokasiToko::where('barcode', $request->barcode)->first();

        if (!$toko) {
            return response()->json([
                'status' => false,
                'message' => 'Data toko tidak ditemukan'
            ]);
        }

        $jarakAktual = $this->haversine(
            $toko->latitude,
            $toko->longitude,
            $request->latitude_sales,
            $request->longitude_sales
        );

        $threshold = 300;
        $thresholdEfektif = $threshold + $toko->accuracy + $request->accuracy_sales;

        if ($jarakAktual <= $thresholdEfektif) {
            $hasil = 'DITERIMA';
        } else {
            $hasil = 'DITOLAK';
        }

        return response()->json([
            'status' => true,
            'hasil' => $hasil,

            'barcode' => $toko->barcode,
            'nama_toko' => $toko->nama_toko,

            'latitude_toko' => $toko->latitude,
            'longitude_toko' => $toko->longitude,
            'accuracy_toko' => $toko->accuracy,

            'latitude_sales' => $request->latitude_sales,
            'longitude_sales' => $request->longitude_sales,
            'accuracy_sales' => $request->accuracy_sales,

            'jarak_aktual' => round($jarakAktual, 2),
            'threshold' => $threshold,
            'threshold_efektif' => round($thresholdEfektif, 2),
        ]);
    }

    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $R = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }
}