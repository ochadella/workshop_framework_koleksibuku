<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        // Card stats lama
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $buku7Hari = Buku::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Tambahan dashboard penjualan
        $totalBarang = Barang::count();

        $totalTransaksiHariIni = Penjualan::whereDate('tanggal', Carbon::today())->count();

        $totalOmzetHariIni = Penjualan::whereDate('tanggal', Carbon::today())
            ->sum('total');

        $jumlahItemTerjualHariIni = PenjualanDetail::whereHas('penjualan', function ($query) {
            $query->whereDate('tanggal', Carbon::today());
        })->sum('jumlah');

        // Recent books untuk table
        $recentBooks = Buku::with('kategori')
            ->latest()
            ->take(6)
            ->get();

        // Chart 1: Buku per bulan (tahun ini)
        $tahun = Carbon::now()->year;

        $bukuPerBulanRaw = Buku::selectRaw('EXTRACT(MONTH FROM created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bukuPerBulan = array_fill(1, 12, 0);
        foreach ($bukuPerBulanRaw as $row) {
            $bukuPerBulan[(int)$row->bulan] = (int)$row->total;
        }

        // Chart 2: Top kategori (berdasarkan jumlah buku)
        $topKategori = Kategori::leftJoin('bukus', 'kategoris.id', '=', 'bukus.kategori_id')
            ->select('kategoris.nama_kategori', DB::raw('COUNT(bukus.id) as total'))
            ->groupBy('kategoris.nama_kategori')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $kategoriLabels = $topKategori->pluck('nama_kategori')->toArray();
        $kategoriTotals = $topKategori->pluck('total')->map(fn ($v) => (int)$v)->toArray();

        // Chart 3: Omzet penjualan 7 hari terakhir
        $penjualan7HariLabels = [];
        $penjualan7HariTotals = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            $penjualan7HariLabels[] = $tanggal->format('d M');

            $totalHarian = Penjualan::whereDate('tanggal', $tanggal)->sum('total');
            $penjualan7HariTotals[] = (int) $totalHarian;
        }

        return view('dashboard', compact(
            'totalBuku',
            'totalKategori',
            'buku7Hari',
            'totalBarang',
            'totalTransaksiHariIni',
            'totalOmzetHariIni',
            'jumlahItemTerjualHariIni',
            'recentBooks',
            'bukuPerBulan',
            'kategoriLabels',
            'kategoriTotals',
            'penjualan7HariLabels',
            'penjualan7HariTotals',
            'tahun'
        ));
    }
}