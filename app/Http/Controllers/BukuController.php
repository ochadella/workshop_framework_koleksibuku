<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        // BIAR DATA LAMA DI ATAS, BARU DI BAWAH (nomor 1,2,3 dst)
        $bukus = Buku::with('kategori')->orderBy('id', 'asc')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('buku.index', compact('bukus', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:bukus,kode',
            'judul' => 'required|string|max:500',
            'pengarang' => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        Buku::create($request->only('kode','judul','pengarang','kategori_id'));

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:20|unique:bukus,kode,' . $buku->id,
            'judul' => 'required|string|max:500',
            'pengarang' => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $buku->update($request->only('kode','judul','pengarang','kategori_id'));

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus');
    }
}
