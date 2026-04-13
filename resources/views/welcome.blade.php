<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Menu</title>

    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">

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
</head>
<body>
<div class="container-scroller">

    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo" href="#">
                <img src="{{ asset('images/logo.svg') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="#">
                <img src="{{ asset('images/logo-mini.svg') }}" alt="logo" />
            </a>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item me-2">
                    <a href="{{ route('login') }}" class="btn btn-primary px-4">
                        Login
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="btn btn-success px-4">
                        Register
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
        <div class="main-panel w-100">
            <div class="content-wrapper" style="margin-top: 40px;">
                <div class="container py-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold mb-0">Pesan Online</h3>
                            <small class="text-muted">Pesan makanan favoritmu lewat sini</small>
                        </div>
                    </div>

                    <div id="alert-status"></div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Halaman Pemesanan Kantin</h4>
                            <p class="customer-note">
                                Customer dapat memesan tanpa login. Silakan pilih customer terlebih dahulu sebelum checkout.
                            </p>

                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <label class="mb-2">Pilih Customer</label>
                                    <select id="customer_id" class="form-control">
                                        <option value="">-- Pilih Customer --</option>
                                        @foreach(\App\Models\Customer::orderBy('nama_customer')->get() as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->nama_customer }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="mb-2">Pilih Vendor</label>
                                    <select id="vendor" class="form-control">
                                        <option value="">-- Pilih Vendor --</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">
                                                {{ $vendor->nama_vendor }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="info" class="mb-3 text-success font-weight-bold"></div>

                            <div class="mt-4">
                                <h5 class="mb-3">Daftar Menu</h5>
                                <div id="menu-container">
                                    <div class="menu-empty-box">
                                        Silakan pilih vendor terlebih dahulu.
                                    </div>
                                </div>
                            </div>

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
                                            <th width="120">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody id="keranjang">
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada menu di keranjang.</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="summary-box text-right">
                                        <div class="mb-1 text-muted">Total Pembayaran</div>
                                        <div class="text-total">Rp <span id="total">0</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-gradient-success btn-sm btn-space-right" id="btn-checkout">
                                    <i class="mdi mdi-cart-outline"></i> Checkout & Bayar
                                </button>

                                <button class="btn btn-primary btn-sm btn-space-right" id="btn-check-status">
                                    <i class="mdi mdi-refresh"></i> Cek Status Pembayaran
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                        Copyright &copy; {{ date('Y') }} Aplikasi Koleksi Buku. All rights reserved.
                    </span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
                        Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i>
                    </span>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="{{ asset('purple/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('purple/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('purple/assets/js/misc.js') }}"></script>

@if(env('MIDTRANS_IS_PRODUCTION', false))
    <script src="https://app.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endif

<script>
    let menus = [];
    let keranjangItems = [];
    let total = 0;
    let currentOrderId = null;

    function formatRupiah(angka) {
        return parseInt(angka || 0).toLocaleString('id-ID');
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
        total = 0;

        if (keranjangItems.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada menu di keranjang.</td>
                </tr>
            `;
            updateTotal();
            return;
        }

        keranjangItems.forEach((item, index) => {
            total += item.subtotal;

            let row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_menu}</td>
                    <td>Rp ${formatRupiah(item.harga)}</td>
                    <td>${item.jumlah}</td>
                    <td>Rp ${formatRupiah(item.subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem(${index})">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;

            tbody.innerHTML += row;
        });

        updateTotal();
    }

    function resetKeranjang() {
        keranjangItems = [];
        total = 0;
        renderKeranjang();
    }

    function resetCheckoutState() {
        currentOrderId = null;
        sessionStorage.removeItem('currentOrderId');
        document.getElementById('btn-check-status').style.display = 'none';
    }

    document.getElementById('vendor').addEventListener('change', function () {
        let vendorId = this.value;
        let info = document.getElementById('info');
        let menuContainer = document.getElementById('menu-container');

        resetKeranjang();
        resetCheckoutState();
        clearAlert();

        if (!vendorId) {
            info.innerText = '';
            menuContainer.innerHTML = `
                <div class="menu-empty-box">
                    Silakan pilih vendor terlebih dahulu.
                </div>
            `;
            return;
        }

        info.innerText = 'Vendor dipilih ID: ' + vendorId;

        fetch('/get-menu/' + vendorId)
            .then(res => res.json())
            .then(data => {
                menus = data;

                if (menus.length === 0) {
                    menuContainer.innerHTML = `
                        <div class="alert alert-warning">
                            Vendor ini belum memiliki menu.
                        </div>
                    `;
                    return;
                }

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

                menus.forEach((menu, index) => {
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
            });
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-pesan')) {
            let id = e.target.dataset.id;
            let nama = e.target.dataset.nama;
            let harga = parseInt(e.target.dataset.harga);

            let existingItem = keranjangItems.find(item => item.menu_id == id);

            if (existingItem) {
                existingItem.jumlah += 1;
                existingItem.subtotal = existingItem.harga * existingItem.jumlah;
            } else {
                keranjangItems.push({
                    menu_id: parseInt(id),
                    nama_menu: nama,
                    harga: harga,
                    jumlah: 1,
                    subtotal: harga
                });
            }

            renderKeranjang();
        }
    });

    function hapusItem(index) {
        keranjangItems.splice(index, 1);
        renderKeranjang();
    }

    document.getElementById('btn-checkout').addEventListener('click', async function () {
        clearAlert();

        const customerId = document.getElementById('customer_id').value;
        const vendorId = document.getElementById('vendor').value;

        if (!customerId) {
            alert('Pilih customer terlebih dahulu!');
            return;
        }

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
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    customer_id: customerId,
                    vendor_id: vendorId,
                    total: total,
                    items: keranjangItems.map(item => ({
                        menu_id: item.menu_id,
                        qty: item.jumlah,
                        harga: item.harga,
                        subtotal: item.subtotal
                    }))
                })
            });

            const result = await response.json();

            if (!response.ok || result.status === false) {
                alert((result.message || 'Checkout gagal') + (result.error ? '\n' + result.error : ''));
                return;
            }

            currentOrderId = result.order_id;
            sessionStorage.setItem('currentOrderId', currentOrderId);

            if (result.snap_token) {
                snap.pay(result.snap_token, {
                    onSuccess: function () {
                        showAlert('Pembayaran berhasil. Status: LUNAS.', 'success');
                        resetKeranjang();
                        resetCheckoutState();
                        location.reload();
                    },
                    onPending: function () {
                        showAlert('Pembayaran masih pending. Klik tombol cek status pembayaran.', 'warning');
                        document.getElementById('btn-check-status').style.display = 'inline-block';
                    },
                    onError: function () {
                        showAlert('Pembayaran gagal.', 'danger');
                    },
                    onClose: function () {
                        showAlert('Popup pembayaran ditutup. Klik cek status pembayaran.', 'secondary');
                        document.getElementById('btn-check-status').style.display = 'inline-block';
                    }
                });
            }
        } catch (error) {
            alert('Terjadi kesalahan saat checkout');
            console.error(error);
        }
    });

    document.getElementById('btn-check-status').addEventListener('click', async function () {
        if (!currentOrderId) {
            alert('Order ID tidak ditemukan');
            return;
        }

        try {
            const response = await fetch("{{ route('customer.checkStatus') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    order_id: currentOrderId
                })
            });

            const result = await response.json();

            if (!response.ok) {
                alert('Gagal cek status: ' + (result.message || 'Unknown error'));
                return;
            }

            if (result.status === true && result.status_pembayaran === 'paid') {
                showAlert('Pembayaran berhasil. Status: LUNAS.', 'success');

                await fetch('/bayar-sukses/' + currentOrderId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });

                resetKeranjang();
                resetCheckoutState();
                location.reload();
            } else {
                showAlert('Pembayaran masih ' + result.status_pembayaran + '.', 'warning');
            }
        } catch (error) {
            alert('Terjadi kesalahan saat cek status pembayaran');
            console.error(error);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const savedOrderId = sessionStorage.getItem('currentOrderId');

        if (savedOrderId) {
            currentOrderId = savedOrderId;
            document.getElementById('btn-check-status').style.display = 'inline-block';
        }
    });
</script>
</body>
</html>