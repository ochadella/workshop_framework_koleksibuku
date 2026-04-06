@extends('layouts.app')

@section('title', 'Pemesanan Kantin')

@push('styles')
<style>
  .table td, .table th {
    vertical-align: middle;
  }

  .menu-empty-box {
    background: #f8f9fa;
    border: 1px solid #ebedf2;
    border-radius: 6px;
    padding: 14px 16px;
    color: #6c757d;
  }

  .summary-box {
    background: #fafbfc;
    border: 1px solid #ebedf2;
    border-radius: 8px;
    padding: 16px;
  }

  .btn-space-right {
    margin-right: 8px;
  }

  #btn-check-status {
    display: none;
  }

  .table-responsive {
    overflow-x: auto;
  }

  .text-total {
    font-size: 28px;
    font-weight: 700;
    color: #1f1f1f;
  }

  .customer-note {
    color: #6c757d;
    margin-bottom: 0;
  }
</style>
@endpush

@section('content')

  <div class="page-header">
    <h3 class="page-title">Pemesanan Kantin</h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pemesanan Kantin</li>
      </ul>
    </nav>
  </div>

  <div class="d-flex justify-content-between mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
      <i class="mdi mdi-arrow-left"></i> Kembali
    </a>
  </div>

  <div id="alert-status"></div>

  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Halaman Pemesanan Kantin</h4>
      <p class="customer-note">
        Customer dapat memesan tanpa login. Sistem akan membuat user guest otomatis saat checkout.
      </p>

      {{-- PILIH VENDOR --}}
      <div class="row mt-4">
        <div class="col-md-6 mb-3">
          <label class="mb-2">Pilih Vendor</label>
          <select id="vendor" class="form-control">
            <option value="">-- Pilih Vendor --</option>
            @foreach(\App\Models\Vendor::all() as $vendor)
              <option value="{{ $vendor->id }}">
                {{ $vendor->nama_vendor }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div id="info" class="mb-3 text-success font-weight-bold"></div>

      {{-- DAFTAR MENU --}}
      <div class="mt-4">
        <h5 class="mb-3">Daftar Menu</h5>
        <div id="menu-container">
          <div class="menu-empty-box">
            Silakan pilih vendor terlebih dahulu.
          </div>
        </div>
      </div>

      {{-- KERANJANG --}}
      <div class="mt-4">
        <h5 class="mb-3">Keranjang</h5>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th width="70">No</th>
                <th>Nama Menu</th>
                <th width="180">Harga</th>
                <th width="120">Jumlah</th>
                <th width="180">Subtotal</th>
              </tr>
            </thead>
            <tbody id="keranjang">
              <tr>
                <td colspan="5" class="text-center text-muted">Belum ada menu di keranjang.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- TOTAL --}}
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="summary-box text-right">
            <div class="mb-1 text-muted">Total Pembayaran</div>
            <div class="text-total">Rp <span id="total">0</span></div>
          </div>
        </div>
      </div>

      {{-- BUTTON --}}
      <div class="mt-4">
        <button class="btn btn-gradient-success btn-sm btn-space-right" id="btn-checkout">
          <i class="mdi mdi-cart-outline"></i> Checkout & Bayar
        </button>

        <button class="btn btn-primary btn-sm btn-space-right" id="btn-check-status">
          <i class="mdi mdi-refresh"></i> Cek Status Pembayaran
        </button>

        <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
          <i class="mdi mdi-arrow-left"></i> Kembali
        </a>
      </div>

    </div>
  </div>

@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
const menus = @json(\App\Models\Menu::all());

let nomorKeranjang = 1;
let total = 0;
let keranjangItems = [];
let currentOrderId = null;
let currentGuestName = null;

function formatRupiah(angka) {
    return parseInt(angka).toLocaleString('id-ID');
}

function updateTotal() {
    document.getElementById('total').innerText = formatRupiah(total);
}

function showAlert(message, type = 'info') {
    document.getElementById('alert-status').innerHTML = `
        <div class="alert alert-${type}">
            ${message}
        </div>
    `;
}

function clearAlert() {
    document.getElementById('alert-status').innerHTML = '';
}

function renderKeranjang() {
    const tbody = document.getElementById('keranjang');
    tbody.innerHTML = '';
    nomorKeranjang = 1;
    total = 0;

    if (keranjangItems.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted">Belum ada menu di keranjang.</td>
            </tr>
        `;
        updateTotal();
        return;
    }

    keranjangItems.forEach(item => {
        total += item.subtotal;

        let row = `
            <tr>
                <td>${nomorKeranjang}</td>
                <td>${item.nama_menu}</td>
                <td>Rp ${formatRupiah(item.harga)}</td>
                <td>${item.jumlah}</td>
                <td>Rp ${formatRupiah(item.subtotal)}</td>
            </tr>
        `;

        tbody.innerHTML += row;
        nomorKeranjang++;
    });

    updateTotal();
}

function resetKeranjang() {
    keranjangItems = [];
    nomorKeranjang = 1;
    total = 0;
    renderKeranjang();
}

function resetCheckoutState() {
    currentOrderId = null;
    currentGuestName = null;
    sessionStorage.removeItem('currentOrderId');
    sessionStorage.removeItem('currentGuestName');
    document.getElementById('btn-check-status').style.display = 'none';
}

document.getElementById('vendor').addEventListener('change', function() {
    let vendorId = this.value;
    let info = document.getElementById('info');
    let menuContainer = document.getElementById('menu-container');

    resetKeranjang();
    resetCheckoutState();
    clearAlert();

    if (vendorId) {
        info.innerText = "Vendor dipilih ID: " + vendorId;

        let filteredMenus = menus.filter(menu => menu.vendor_id == vendorId);

        if (filteredMenus.length > 0) {
            let html = `
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th width="70">No</th>
                        <th>Nama Menu</th>
                        <th width="180">Harga</th>
                        <th width="140">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
            `;

            filteredMenus.forEach((menu, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${menu.nama_menu}</td>
                        <td>Rp ${formatRupiah(menu.harga)}</td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-sm btn-gradient-primary btn-pesan"
                                data-id="${menu.id}"
                                data-nama="${menu.nama_menu}"
                                data-harga="${menu.harga}">
                                Pesan
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                  </table>
                </div>
            `;

            menuContainer.innerHTML = html;
        } else {
            menuContainer.innerHTML = `
                <div class="alert alert-warning">
                    Vendor ini belum memiliki menu.
                </div>
            `;
        }
    } else {
        info.innerText = "";
        menuContainer.innerHTML = `
            <div class="menu-empty-box">
                Silakan pilih vendor terlebih dahulu.
            </div>
        `;
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-pesan')) {
        let id = e.target.dataset.id;
        let nama = e.target.dataset.nama;
        let harga = parseInt(e.target.dataset.harga);

        let existingItem = keranjangItems.find(item => item.id == id);

        if (existingItem) {
            existingItem.jumlah += 1;
            existingItem.subtotal = existingItem.harga * existingItem.jumlah;
        } else {
            keranjangItems.push({
                id: id,
                nama_menu: nama,
                harga: harga,
                jumlah: 1,
                subtotal: harga
            });
        }

        renderKeranjang();
    }
});

document.getElementById('btn-checkout').addEventListener('click', async function() {
    const vendorId = document.getElementById('vendor').value;

    clearAlert();

    if (!vendorId) {
        alert('Pilih vendor terlebih dahulu!');
        return;
    }

    if (keranjangItems.length === 0) {
        alert('Keranjang kosong!');
        return;
    }

    try {
        const response = await fetch("{{ route('customer.checkout') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                vendor_id: vendorId,
                total: total,
                items: keranjangItems
            })
        });

        const result = await response.json();
        console.log('CHECKOUT RESULT:', result);

        if (!response.ok) {
            alert((result.message || 'Checkout gagal') + (result.error ? '\n' + result.error : ''));
            return;
        }

        currentOrderId = result.order_id;
        currentGuestName = result.guest_name;

        sessionStorage.setItem('currentOrderId', currentOrderId);
        sessionStorage.setItem('currentGuestName', currentGuestName ?? '');

        if (result.snap_token) {
            snap.pay(result.snap_token, {
                onSuccess: function(res) {
                    showAlert('Pembayaran berhasil untuk ' + currentGuestName + '. Status: <strong>LUNAS</strong>.', 'success');
                    resetKeranjang();
                    resetCheckoutState();
                },
                onPending: function(res) {
                    showAlert('Pembayaran pending untuk ' + currentGuestName + '. Klik tombol <strong>Cek Status Pembayaran</strong>.', 'warning');
                    document.getElementById('btn-check-status').style.display = 'inline-block';
                },
                onError: function(res) {
                    showAlert('Pembayaran gagal.', 'danger');
                },
                onClose: function() {
                    showAlert('Popup pembayaran ditutup. Klik tombol <strong>Cek Status Pembayaran</strong>.', 'secondary');
                    document.getElementById('btn-check-status').style.display = 'inline-block';
                }
            });
        } else {
            alert('Snap token tidak ditemukan');
        }
    } catch (error) {
        alert('Terjadi kesalahan saat checkout');
        console.error(error);
    }
});

document.getElementById('btn-check-status').addEventListener('click', async function() {
    if (!currentOrderId) {
        alert('Order ID tidak ditemukan');
        return;
    }

    try {
        const response = await fetch("{{ route('customer.checkStatus') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                order_id: currentOrderId
            })
        });

        const result = await response.json();
        console.log('CHECK STATUS RESULT:', result);

        if (!response.ok) {
            alert('Gagal cek status: ' + (result.message || 'Unknown error'));
            return;
        }

        if (result.status === true && (result.status_pembayaran === 'paid' || result.status_pembayaran === 'lunas')) {
            showAlert('Pembayaran berhasil untuk ' + (currentGuestName ?? 'Guest') + '. Status: <strong>LUNAS</strong>.', 'success');
            alert('Pembayaran berhasil. Status sekarang: ' + result.status_pembayaran);
            resetKeranjang();
            resetCheckoutState();
        } else {
            showAlert('Pembayaran masih <strong>' + result.status_pembayaran + '</strong>.', 'warning');
        }
    } catch (error) {
        alert('Terjadi kesalahan saat cek status pembayaran');
        console.error(error);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const savedOrderId = sessionStorage.getItem('currentOrderId');
    const savedGuestName = sessionStorage.getItem('currentGuestName');

    if (savedOrderId) {
        currentOrderId = savedOrderId;
        currentGuestName = savedGuestName;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    const transactionStatus = urlParams.get('transaction_status');
    const statusCode = urlParams.get('status_code');

    if (orderId && transactionStatus) {
        currentOrderId = orderId;
        sessionStorage.setItem('currentOrderId', orderId);

        if (transactionStatus === 'settlement' || transactionStatus === 'capture') {
            showAlert('Pembayaran berhasil. Status: <strong>LUNAS</strong>.', 'success');
            resetKeranjang();
            resetCheckoutState();
        } else if (transactionStatus === 'pending') {
            showAlert('Pembayaran masih pending. Klik tombol <strong>Cek Status Pembayaran</strong>.', 'warning');
            document.getElementById('btn-check-status').style.display = 'inline-block';
        } else if (transactionStatus === 'deny' || transactionStatus === 'cancel' || transactionStatus === 'expire') {
            showAlert('Pembayaran gagal atau dibatalkan.', 'danger');
            document.getElementById('btn-check-status').style.display = 'none';
        }

        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (currentOrderId) {
        document.getElementById('btn-check-status').style.display = 'inline-block';
    }
});
</script>
@endpush