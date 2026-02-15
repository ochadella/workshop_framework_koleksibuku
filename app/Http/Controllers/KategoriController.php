<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Database\QueryException;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // BIAR DATA LAMA DI ATAS, BARU DI BAWAH (nomor 1,2,3 dst)
        $kategoris = Kategori::orderBy('id', 'asc')->get();

        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Kategori::create([
            'nama_kategori' => $validated['nama'],
        ]);

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori = Kategori::findOrFail($id);

        $kategori->update([
            'nama_kategori' => $validated['nama'],
        ]);

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return redirect()->route('kategori.index')
                             ->with('success', 'Kategori berhasil dihapus');
        } catch (QueryException $e) {
            // PostgreSQL FK violation: 23503
            if (($e->errorInfo[0] ?? null) === '23503') {
                return redirect()->route('kategori.index')
                                 ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh data buku.');
            }

            // Kalau error lain, lempar lagi biar kelihatan errornya
            throw $e;
        }
    }
}
