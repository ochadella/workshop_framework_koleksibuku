<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\Pesanan;

class AdminVendorController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('vendor.index')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $vendors = Vendor::with('user')->orderBy('id', 'desc')->get();

        return view('adminvendor.index', compact('vendors'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('vendor.index')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $users = User::where('role', 'vendor')
            ->whereDoesntHave('vendor')
            ->orderBy('name')
            ->get();

        return view('adminvendor.create', compact('users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('vendor.index')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'iduser' => 'required|exists:users,id|unique:vendors,iduser',
        ]);

        Vendor::create([
            'nama_vendor' => $request->nama_vendor,
            'iduser' => $request->iduser,
        ]);

        return redirect()->route('adminvendor.index')
            ->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('vendor.index')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $vendor = Vendor::with('user')->findOrFail($id);

        return view('adminvendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('vendor.index')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $request->validate([
            'nama_vendor' => 'required|string|max:255',
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'nama_vendor' => $request->nama_vendor,
        ]);

        return redirect()->route('adminvendor.index')
            ->with('success', 'Vendor berhasil diupdate.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('vendor.index')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('adminvendor.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }

        public function pesanan($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('vendor.index')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $vendor = Vendor::findOrFail($id);

        $pesanan = Pesanan::with(['detailPesanan.menu'])
            ->whereHas('detailPesanan.menu', function ($q) use ($id) {
                $q->where('vendor_id', $id);
            })
            ->orderByDesc('idpesanan')
            ->get();

        return view('adminvendor.pesanan', compact('vendor', 'pesanan'));
    }

    public function pesananDetail($vendorId, $pesananId)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('vendor.index')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $vendor = Vendor::findOrFail($vendorId);

        $pesanan = Pesanan::with(['detailPesanan.menu'])
            ->where('idpesanan', $pesananId)
            ->whereHas('detailPesanan.menu', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->firstOrFail();

        return view('adminvendor.pesanan-detail', compact('vendor', 'pesanan'));
    }
}