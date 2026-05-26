<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    // =============================================
    // GUEST — Halaman daftar antrian
    // =============================================

    public function guest()
    {
        $daftarPoli = Antrian::daftarPoli();
        return view('antrian.guest', compact('daftarPoli'));
    }

    public function daftar(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'kode_poli' => 'required|in:A,B,C,D',
        ]);

        $daftarPoli = Antrian::daftarPoli();
        $kodePoli   = $request->kode_poli;
        $namaPoli   = $daftarPoli[$kodePoli];

        $generate = Antrian::generateNomor($kodePoli);

        $antrian = Antrian::create([
            'nama'          => $request->nama,
            'kode_poli'     => $kodePoli,
            'nama_poli'     => $namaPoli,
            'nomor_antrian' => $generate['nomor'],
            'urutan'        => $generate['urutan'],
            'status'        => 'menunggu',
        ]);

        $this->updateCache();

        return redirect()->route('antrian.tiket', $antrian->id);
    }

    public function tiket(Antrian $antrian)
    {
        return view('antrian.tiket', compact('antrian'));
    }

    // =============================================
    // ADMIN
    // =============================================

    public function admin(Request $request)
    {
        $daftarPoli  = Antrian::daftarPoli();
        $poliAktif   = $request->get('poli', 'A');

        // 🔥 FIX: 1x query saja (lebih ringan)
        $all = Antrian::whereDate('created_at', today())
            ->whereIn('status', ['menunggu', 'dipanggil', 'terlambat'])
            ->get()
            ->groupBy('kode_poli');

        $group = $all[$poliAktif] ?? collect();

        $antrians = $group->whereIn('status', ['menunggu', 'dipanggil'])->values();
        $terlambat = $group->where('status', 'terlambat')->values();
        $sedangDipanggil = $group->firstWhere('status', 'dipanggil');

        return view('antrian.admin', compact(
            'daftarPoli',
            'poliAktif',
            'antrians',
            'terlambat',
            'sedangDipanggil'
        ));
    }

    public function panggil(Request $request)
    {
        $request->validate([
            'kode_poli' => 'required|in:A,B,C,D',
        ]);

        $kodePoli = $request->kode_poli;

        Antrian::where('kode_poli', $kodePoli)
            ->whereDate('created_at', today())
            ->where('status', 'dipanggil')
            ->update(['status' => 'terlambat']);

        $antrian = Antrian::where('kode_poli', $kodePoli)
            ->whereDate('created_at', today())
            ->where('status', 'menunggu')
            ->orderBy('urutan')
            ->first();

        if (!$antrian) {
            return response()->json(['message' => 'Tidak ada antrian menunggu'], 404);
        }

        $antrian->update(['status' => 'dipanggil']);

        $this->updateCache();

        return response()->json([
            'success'       => true,
            'nomor_antrian' => $antrian->nomor_antrian,
            'nama'          => $antrian->nama,
            'nama_poli'     => $antrian->nama_poli,
        ]);
    }

    public function panggilTerlambat(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:antrians,id',
        ]);

        $antrian = Antrian::findOrFail($request->id);
        $kodePoli = $antrian->kode_poli;

        Antrian::where('kode_poli', $kodePoli)
            ->whereDate('created_at', today())
            ->where('status', 'dipanggil')
            ->update(['status' => 'terlambat']);

        $antrian->update(['status' => 'dipanggil']);

        $this->updateCache();

        return response()->json([
            'success'       => true,
            'nomor_antrian' => $antrian->nomor_antrian,
            'nama'          => $antrian->nama,
            'nama_poli'     => $antrian->nama_poli,
        ]);
    }

    public function selesai(Request $request)
    {
        $request->validate([
            'kode_poli' => 'required|in:A,B,C,D',
        ]);

        Antrian::where('kode_poli', $request->kode_poli)
            ->whereDate('created_at', today())
            ->where('status', 'dipanggil')
            ->update(['status' => 'selesai']);

        $this->updateCache();

        return response()->json(['success' => true]);
    }

    // =============================================
    // PAPAN
    // =============================================

    public function papan()
    {
        $daftarPoli = Antrian::daftarPoli();
        return view('antrian.papan', compact('daftarPoli'));
    }

    // =============================================
    // SSE STREAM
    // =============================================

    public function stream(Request $request)
    {
        set_time_limit(0);

        return response()->stream(function () {
            $lastHash = '';

            while (true) {
                $data = Cache::get('antrian_state', []);
                $hash = md5(json_encode($data));

                if ($hash !== $lastHash) {
                    echo "event: queue-update\n";
                    echo "data: " . json_encode($data) . "\n\n";
                    ob_flush();
                    flush();
                    $lastHash = $hash;
                }

                echo ": keep-alive\n\n";
                ob_flush();
                flush();

                if (connection_aborted()) break;

                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    // =============================================
    // 🔥 FIX UTAMA PERFORMANCE (INI YANG NGURANGIN LAG BESAR)
    // =============================================

    private function updateCache(): void
    {
        $daftarPoli = Antrian::daftarPoli();

        // 🔥 cuma 1 query (WAJIB untuk performa)
        $all = Antrian::whereDate('created_at', today())->get()->groupBy('kode_poli');

        $state = [];

        foreach ($daftarPoli as $kode => $nama) {

            $group = $all[$kode] ?? collect();

            $dipanggil = $group->firstWhere('status', 'dipanggil');

            $state[$kode] = [
                'kode'      => $kode,
                'nama_poli' => $nama,
                'dipanggil' => $dipanggil ? [
                    'nomor' => $dipanggil->nomor_antrian,
                    'nama'  => $dipanggil->nama,
                ] : null,
                'menunggu'  => $group->where('status', 'menunggu')->count(),
                'terlambat' => $group->where('status', 'terlambat')->count(),
            ];
        }

        Cache::put('antrian_state', $state, now()->addHours(12));
    }
}