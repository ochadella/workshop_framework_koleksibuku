<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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

        $query = Pesanan::with(['detailPesanan.menu', 'customer'])
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
     * Checkout customer dari halaman welcome / customer login
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'vendor_id' => 'required|integer|exists:vendors,id',
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

            $customer = Customer::findOrFail($request->customer_id);
            $vendorId = (int) $request->vendor_id;

            $pesanan = Pesanan::create([
                'nama' => $customer->nama_customer,
                'customer_id' => $customer->id,
                'tanggal' => now(),
                'total' => (int) $request->total,
                'metode_bayar' => 'qris',
                'status_bayar' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $itemDetails = [];

            foreach ($request->items as $item) {
                $menu = Menu::where('id', $item['menu_id'])
                    ->where('vendor_id', $vendorId)
                    ->first();

                if (!$menu) {
                    throw new \Exception('Menu tidak valid atau tidak sesuai dengan vendor yang dipilih.');
                }

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
                    'first_name' => $customer->nama_customer,
                    'email' => $customer->email ?? '',
                    'phone' => $customer->no_hp ?? '',
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Checkout berhasil dibuat',
                'customer_name' => $customer->nama_customer,
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
     * Check status pembayaran dari halaman customer
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);

        $id = $request->order_id;

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

        $pesanan = Pesanan::with(['detailPesanan.menu', 'customer'])
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

        $pesanan = Pesanan::with(['detailPesanan.menu', 'customer'])
            ->where('idpesanan', $id)
            ->whereHas('detailPesanan.menu', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->firstOrFail();

        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: 'ORDER-' . $pesanan->idpesanan,
            size: 150,
            margin: 5
        );

        $result = $writer->write($qrCode);
        $qrcode = base64_encode($result->getString());

        $pdf = Pdf::loadView('vendor.struk-pesanan', compact('pesanan', 'qrcode'))
            ->setPaper([0, 0, 226.77, 700], 'portrait');

        return $pdf->stream('struk-pesanan-' . $pesanan->idpesanan . '.pdf');
    }

    /**
     * MODUL 8 - Halaman QR Code pesanan customer agar bisa diakses ulang
     */
    public function showQrCode($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.menu', 'customer'])
            ->where('idpesanan', $id)
            ->firstOrFail();

        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: 'ORDER-' . $pesanan->idpesanan,
            size: 200,
            margin: 10
        );

        $result = $writer->write($qrCode);
        $qrcode = base64_encode($result->getString());

        return view('pesanan.qrcode', compact('pesanan', 'qrcode'));
    }

    /**
     * Customer - Riwayat pembelian
     */
    public function riwayatCustomer()
    {
        $customer = Customer::where('email', auth()->user()->email)->firstOrFail();

        $pesanan = Pesanan::with(['detailPesanan.menu', 'customer'])
            ->where('customer_id', $customer->id)
            ->orderByDesc('idpesanan')
            ->get();

        return view('customer.riwayat', compact('pesanan'));
    }

    /**
     * Customer - Cetak struk jika sudah lunas
     */
    public function cetakStrukCustomer($id)
    {
        $customer = Customer::where('email', auth()->user()->email)->firstOrFail();

        $pesanan = Pesanan::with(['detailPesanan.menu', 'customer'])
            ->where('idpesanan', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        if ($pesanan->status_bayar != 1) {
            return redirect()->route('customer.riwayat')
                ->with('error', 'Struk hanya bisa dicetak jika pesanan sudah lunas.');
        }

        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: 'ORDER-' . $pesanan->idpesanan,
            size: 150,
            margin: 5
        );

        $result = $writer->write($qrCode);
        $qrcode = base64_encode($result->getString());

        $pdf = Pdf::loadView('vendor.struk-pesanan', compact('pesanan', 'qrcode'))
            ->setPaper([0, 0, 226.77, 700], 'portrait');

        return $pdf->stream('struk-customer-' . $pesanan->idpesanan . '.pdf');
    }
}