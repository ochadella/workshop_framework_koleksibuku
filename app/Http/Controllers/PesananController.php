<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PesananController extends Controller
{
    /**
     * Halaman daftar pesanan untuk vendor
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->vendor) {
            return redirect()->route('dashboard')
                ->with('error', 'Akun ini belum terhubung ke vendor.');
        }

        $vendorId = $user->vendor->id;
        $status = $request->status;

        $query = Pesanan::with(['detailPesanan.menu'])
            ->whereHas('detailPesanan.menu', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            });

        if ($status === 'lunas') {
            $query->where('status_bayar', 1);
        }

        $pesanan = $query->orderByDesc('idpesanan')->get();

        return view('vendor.pesanan', compact('pesanan'));
    }

    /**
     * Vendor ubah status pesanan jadi lunas
     */
    public function lunas($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $pesanan->update([
            'status_bayar' => 1,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pesanan sudah dibayar.');
    }

    /**
     * Checkout customer dari halaman welcome / guest
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'total' => 'required|numeric|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // generate nama guest: Guest_0001, Guest_0002, dst
            $lastPesanan = Pesanan::orderByDesc('idpesanan')->first();
            $nextNumber = $lastPesanan ? ($lastPesanan->idpesanan + 1) : 1;
            $guestName = 'Guest_' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $pesanan = Pesanan::create([
                'nama' => $guestName,
                'tanggal' => now(),
                'total' => (int) $request->total,
                'metode_bayar' => 'qris',
                'status_bayar' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $itemDetails = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);

                DetailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu' => $menu->id,
                    'jumlah' => (int) $item['qty'],
                    'harga' => (int) $item['harga'],
                    'subtotal' => (int) $item['subtotal'],
                    'catatan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $itemDetails[] = [
                    'id' => 'MENU-' . $menu->id,
                    'price' => (int) $item['harga'],
                    'quantity' => (int) $item['qty'],
                    'name' => $menu->nama_menu,
                ];
            }

            $orderId = 'ORDER-' . $pesanan->idpesanan;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $request->total,
                ],
                'customer_details' => [
                    'first_name' => $guestName,
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Checkout berhasil dibuat',
                'guest_name' => $guestName,
                'snap_token' => $snapToken,
                'order_id' => $pesanan->idpesanan,
                'midtrans_order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Checkout gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Callback Midtrans
     */
    public function callback(Request $request)
    {
        $orderId = $request->order_id;

        if (!$orderId) {
            return response()->json(['message' => 'Order ID tidak ditemukan'], 400);
        }

        $id = str_replace('ORDER-', '', $orderId);

        $pesanan = Pesanan::where('idpesanan', $id)->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        if (in_array($request->transaction_status, ['settlement', 'capture'])) {
            $pesanan->update([
                'status_bayar' => 1,
                'updated_at' => now(),
            ]);
        } elseif ($request->transaction_status === 'pending') {
            $pesanan->update([
                'status_bayar' => 0,
                'updated_at' => now(),
            ]);
        } elseif (in_array($request->transaction_status, ['deny', 'cancel', 'expire'])) {
            $pesanan->update([
                'status_bayar' => 0,
                'updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Simulasi / update sukses setelah bayar
     */
    public function bayarSukses($id)
    {
        $pesanan = Pesanan::where('idpesanan', $id)->firstOrFail();

        $pesanan->update([
            'status_bayar' => 1,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Check status pembayaran dari halaman guest
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);

        $id = $request->order_id;

        // kalau yang dikirim "ORDER-5", ubah ke 5
        if (str_starts_with($id, 'ORDER-')) {
            $id = str_replace('ORDER-', '', $id);
        }

        $pesanan = Pesanan::where('idpesanan', $id)->first();

        if (!$pesanan) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Status pesanan ditemukan',
            'status_pembayaran' => $pesanan->status_bayar == 1 ? 'paid' : 'pending',
            'order_id' => 'ORDER-' . $pesanan->idpesanan,
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user || !$user->vendor) {
            return redirect()->route('dashboard')
                ->with('error', 'Akun ini belum terhubung ke vendor.');
        }

        $vendorId = $user->vendor->id;

        $pesanan = Pesanan::with(['detailPesanan.menu'])
            ->where('idpesanan', $id)
            ->whereHas('detailPesanan.menu', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->firstOrFail();

        return view('vendor.pesanan-detail', compact('pesanan'));
    }

    public function cetakStruk($id)
    {
        $user = Auth::user();

        if (!$user || !$user->vendor) {
            return redirect()->route('dashboard')
                ->with('error', 'Akun ini belum terhubung ke vendor.');
        }

        $vendorId = $user->vendor->id;

        $pesanan = Pesanan::with(['detailPesanan.menu'])
            ->where('idpesanan', $id)
            ->whereHas('detailPesanan.menu', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->firstOrFail();

        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode(
            $generator->getBarcode((string) $pesanan->idpesanan, $generator::TYPE_CODE_128)
        );

        $pdf = Pdf::loadView('vendor.struk-pesanan', compact('pesanan', 'barcode'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream('struk-pesanan-' . $pesanan->idpesanan . '.pdf');
    }
}