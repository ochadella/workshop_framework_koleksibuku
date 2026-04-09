<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TransaksiController extends Controller
{
    public function index()
    {
        // ✅ Vendor hanya melihat transaksi yang sudah lunas/paid
        $transaksis = Penjualan::where('status_pembayaran', 'paid')
            ->orderBy('id', 'desc')
            ->get();

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

        // ✅ Tambahan barcode, tidak menghapus code lama
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode(
            $generator->getBarcode((string) $transaksi->id, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('transaksi.struk', compact('transaksi', 'barcode'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream('struk-transaksi-' . $transaksi->id . '.pdf');
    }

    /**
     * ✅ Callback Midtrans (Webhook)
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $signature = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signature !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $penjualan = Penjualan::where('midtrans_order_id', $request->order_id)->first();

        if (!$penjualan) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
            $penjualan->update([
                'status_pembayaran' => 'paid',
                'paid_at' => now(),
            ]);
        } elseif ($request->transaction_status == 'pending') {
            $penjualan->update([
                'status_pembayaran' => 'pending',
            ]);
        } elseif (in_array($request->transaction_status, ['deny', 'cancel', 'expire'])) {
            $penjualan->update([
                'status_pembayaran' => 'failed',
            ]);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * ✅ Check status pembayaran dari halaman customer
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $penjualan = Penjualan::where('midtrans_order_id', $request->order_id)->first();

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan',
                'order_id' => $request->order_id,
            ], 404);
        }

        $penjualan->update([
            'status_pembayaran' => 'paid',
            'paid_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran berhasil',
            'status_pembayaran' => $penjualan->fresh()->status_pembayaran,
            'paid_at' => $penjualan->fresh()->paid_at,
            'order_id' => $penjualan->midtrans_order_id,
        ]);
    }
}