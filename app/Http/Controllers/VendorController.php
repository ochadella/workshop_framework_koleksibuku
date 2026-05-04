<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return redirect()->route('dashboard')
                ->with('error', 'Kamu tidak punya akses ke halaman vendor.');
        }

        if (!$user->vendor) {
            return redirect()->route('dashboard')
                ->with('error', 'Akun vendor ini belum terhubung ke data vendor.');
        }

        $vendor = $user->vendor;
        $menus = Menu::where('vendor_id', $vendor->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('vendor.index', compact('vendor', 'menus'));
    }

    public function storeMenu(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return redirect()->route('dashboard')
                ->with('error', 'Kamu tidak punya akses.');
        }

        if (!$user->vendor) {
            return redirect()->route('dashboard')
                ->with('error', 'Akun vendor ini belum terhubung ke data vendor.');
        }

        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        Menu::create([
            'vendor_id' => $user->vendor->id,
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
        ]);

        return redirect()->route('vendor.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function deleteMenu($id)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor' || !$user->vendor) {
            return redirect()->route('dashboard')
                ->with('error', 'Kamu tidak punya akses.');
        }

        $menu = Menu::where('id', $id)
            ->where('vendor_id', $user->vendor->id)
            ->firstOrFail();

        $menu->delete();

        return redirect()->route('vendor.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * MODUL 8 - Halaman scan QR Code pesanan customer
     */
    public function scanPesanan()
    {
        return view('vendor.scan-pesanan');
    }

    /**
     * MODUL 8 - Cari detail pesanan berdasarkan hasil scan QR Code
     */
    public function cariPesanan($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'vendor' || !$user->vendor) {
            return response()->json([
                'status' => false,
                'message' => 'Kamu tidak punya akses.'
            ], 403);
        }

        if (str_starts_with($id, 'ORDER-')) {
            $id = str_replace('ORDER-', '', $id);
        }

        $vendorId = $user->vendor->id;

        $pesanan = Pesanan::with(['detailPesanan.menu', 'customer'])
            ->where('idpesanan', $id)
            ->whereHas('detailPesanan.menu', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'idpesanan' => $pesanan->idpesanan,
                'nama_customer' => $pesanan->customer->nama_customer ?? $pesanan->nama ?? '-',
                'status_bayar' => $pesanan->status_bayar == 1 ? 'Lunas' : 'Belum Lunas',
                'menu' => $pesanan->detailPesanan->map(function ($detail) {
                    return [
                        'nama_menu' => $detail->menu->nama_menu ?? '-',
                        'jumlah' => $detail->jumlah,
                        'subtotal' => $detail->subtotal,
                    ];
                })
            ]
        ]);
    }
}