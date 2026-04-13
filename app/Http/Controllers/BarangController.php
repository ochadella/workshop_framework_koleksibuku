<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Midtrans\Config;
use Midtrans\Snap;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarangController extends Controller
{
    /**
     * Tampilkan daftar barang
     */
    public function index()
    {
        $barangs = Barang::orderBy('id_barang', 'desc')->get();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Form tambah barang
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Simpan barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'deskripsi'   => 'nullable|string',
        ]);

        Barang::create($request->only(['nama_barang', 'harga', 'deskripsi']));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Form edit barang
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update barang
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'deskripsi'   => 'nullable|string',
        ]);

        $barang->update($request->only(['nama_barang', 'harga', 'deskripsi']));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diupdate');
    }

    /**
     * Hapus barang
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }

    /**
     * Ambil data barang berdasarkan kode untuk POS AJAX
     */
    public function getBarangByKode($kode)
    {
        $barang = Barang::where('id_barang', $kode)->first();

        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'harga' => $barang->harga,
                'deskripsi' => $barang->deskripsi,
            ]
        ]);
    }

    /**
     * Simpan transaksi POS
     */
    public function simpanTransaksi(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:1',
            'items' => 'required|array|min:1',
            'items.*.kode' => 'required|string',
            'items.*.nama_barang' => 'required|string',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $penjualan = Penjualan::create([
                'tanggal' => now(),
                'total' => $request->total,
                'status_pembayaran' => 'pending',
                'metode_pembayaran' => null,
                'midtrans_order_id' => 'ORDER-' . time() . '-' . rand(100, 999),
                'snap_token' => null,
                'paid_at' => null,
            ]);

            foreach ($request->items as $item) {
                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $item['kode'],
                    'nama_barang' => $item['nama_barang'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $penjualan->midtrans_order_id,
                    'gross_amount' => (int) $request->total,
                ],
                'customer_details' => [
                    'first_name' => 'Guest',
                ],
                'item_details' => collect($request->items)->map(function ($item) {
                    return [
                        'id' => $item['kode'],
                        'price' => (int) $item['harga'],
                        'quantity' => (int) $item['jumlah'],
                        'name' => $item['nama_barang'],
                    ];
                })->values()->toArray(),
            ];

            $snapToken = Snap::getSnapToken($params);

            $penjualan->update([
                'snap_token' => $snapToken,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan',
                'snap_token' => $snapToken,
                'order_id' => $penjualan->midtrans_order_id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Transaksi gagal disimpan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Checkout customer tanpa login
     */
    public function checkoutCustomer(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|integer',
            'total' => 'required|numeric|min:1',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.nama_menu' => 'required|string',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $lastGuest = User::where('name', 'like', 'Guest_%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;

            if ($lastGuest) {
                $parts = explode('_', $lastGuest->name);
                $lastNumber = isset($parts[1]) ? (int) $parts[1] : 0;
                $nextNumber = $lastNumber + 1;
            }

            $guestName = 'Guest_' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
            $guestEmail = strtolower($guestName) . '@guest.local';

            $guest = User::create([
                'name' => $guestName,
                'email' => $guestEmail,
                'password' => bcrypt(Str::random(12)),
            ]);

            $penjualan = Penjualan::create([
                'tanggal' => now(),
                'total' => $request->total,
                'status_pembayaran' => 'pending',
                'metode_pembayaran' => null,
                'midtrans_order_id' => 'ORDER-CUST-' . time() . '-' . rand(100, 999),
                'snap_token' => null,
                'paid_at' => null,
            ]);

            foreach ($request->items as $item) {
                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => 'MENU-' . $item['id'],
                    'nama_barang' => $item['nama_menu'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $penjualan->midtrans_order_id,
                    'gross_amount' => (int) $request->total,
                ],
                'customer_details' => [
                    'first_name' => $guest->name,
                    'email' => $guest->email,
                ],
                'item_details' => collect($request->items)->map(function ($item) {
                    return [
                        'id' => 'MENU-' . $item['id'],
                        'price' => (int) $item['harga'],
                        'quantity' => (int) $item['jumlah'],
                        'name' => $item['nama_menu'],
                    ];
                })->values()->toArray(),
            ];

            $snapToken = Snap::getSnapToken($params);

            $penjualan->update([
                'snap_token' => $snapToken,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Checkout berhasil dibuat',
                'guest_name' => $guest->name,
                'snap_token' => $snapToken,
                'order_id' => $penjualan->midtrans_order_id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Checkout customer gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cetak Label PDF (TnJ 108: 5 kolom x 8 baris = 40 label per halaman)
     */
    public function cetakLabel(Request $request)
    {
        $request->validate([
            'x'     => 'required|integer|min:1|max:5',
            'y'     => 'required|integer|min:1|max:8',
            'ids'   => 'required|array|min:1',
            'ids.*' => 'string',
        ]);

        $x = (int) $request->x;
        $y = (int) $request->y;

        $barangs = Barang::whereIn('id_barang', $request->ids)
            ->orderBy('id_barang', 'asc')
            ->get()
            ->values();

        if ($barangs->isEmpty()) {
            return redirect()->route('barang.index')
                ->with('error', 'Tidak ada barang yang dipilih atau data tidak ditemukan');
        }

        $generator = new BarcodeGeneratorPNG();

        foreach ($barangs as $barang) {
            $barang->barcode = 'data:image/png;base64,' . base64_encode(
                $generator->getBarcode($barang->id_barang, $generator::TYPE_CODE_128)
            );
        }

        $pdf = Pdf::loadView('barang.label', [
            'barangs' => $barangs,
            'x'       => $x,
            'y'       => $y,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('label-barang.pdf');
    }
}