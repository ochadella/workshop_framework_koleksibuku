<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    /**
     * Tampilkan daftar barang
     */
    public function index()
    {
        // ✅ FIX: orderBy menggunakan kolom yang benar (id_barang)
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
        // ✅ FIX: pakai only() bukan all() agar field lain (id_barang dari trigger) tidak ikut

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
        // ✅ FIX: pakai only() agar id_barang tidak bisa diubah via request

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

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan',
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
     * Cetak Label PDF (TnJ 108: 5 kolom x 8 baris = 40 label per halaman)
     */
    public function cetakLabel(Request $request)
    {
        $request->validate([
            'x'    => 'required|integer|min:1|max:5',
            'y'    => 'required|integer|min:1|max:8',
            // ✅ FIX: ids berisi string karena id_barang = 'BRG000001', bukan integer
            'ids'  => 'required|array|min:1',
            'ids.*' => 'string',
        ]);

        $x = (int) $request->x;
        $y = (int) $request->y;

        // ✅ FIX: whereIn pakai 'id_barang' bukan 'id'
        $barangs = Barang::whereIn('id_barang', $request->ids)
            ->orderBy('id_barang', 'asc')
            ->get()
            ->values(); // reset index ke 0..n

        // ✅ FIX: validasi barang ditemukan
        if ($barangs->isEmpty()) {
            return redirect()->route('barang.index')
                ->with('error', 'Tidak ada barang yang dipilih atau data tidak ditemukan');
        }

        $pdf = Pdf::loadView('barang.label', [
            'barangs' => $barangs,
            'x'       => $x,
            'y'       => $y,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('label-barang.pdf');
    }
}