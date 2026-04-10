<?php

namespace App\Http\Controllers;

use App\Models\Menu;
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
}