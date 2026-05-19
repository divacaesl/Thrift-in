@extends('layouts.buyer')

@section('title', 'Lanjut ke Pembayaran - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp

<h3 class="fw-bold mb-4 text-dark"><i class="fas fa-credit-card text-primary me-2"></i>{{ $lang == 'en' ? 'Checkout & Order Summary' : 'Checkout & Pembayaran' }}</h3>

<form action="{{ route('buyer.checkout.process') }}" method="POST" id="checkoutForm">
    @csrf
    <div class="row g-4">
        <!-- Left details column -->
        <div class="col-lg-8">
            <!-- 1. Shipping Address Section -->
            <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-location-dot text-primary me-2"></i>{{ $lang == 'en' ? 'Shipping Address' : 'Alamat Pengiriman' }}</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#addNewAddressForm">
                        + {{ $lang == 'en' ? 'New Address' : 'Alamat Baru' }}
                    </button>
                </div>

                <!-- Add New Address Form (inline collapse) -->
                <div class="collapse mb-3 p-3 bg-light rounded border border-dashed" id="addNewAddressForm">
                    <h6 class="fw-bold text-dark mb-3">{{ $lang == 'en' ? 'Add Delivery Address' : 'Tambah Alamat Pengiriman' }}</h6>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="text" id="addLabel" class="form-control form-control-sm" placeholder="Label (Rumah/Kantor)">
                        </div>
                        <div class="col-6">
                            <input type="text" id="addPenerima" class="form-control form-control-sm" placeholder="Nama Penerima">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="text" id="addHp" class="form-control form-control-sm" placeholder="Nomor HP Penerima">
                        </div>
                        <div class="col-6">
                            <input type="text" id="addKota" class="form-control form-control-sm" placeholder="Kota / Kabupaten">
                        </div>
                    </div>
                    <div class="mb-2">
                        <textarea id="addAlamat" class="form-control form-control-sm" rows="2" placeholder="Alamat Lengkap (Jalan, RT/RW, Kelurahan, Kecamatan)"></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <input type="text" id="addPos" class="form-control form-control-sm" placeholder="Kode Pos">
                        </div>
                        <div class="col-6 d-flex align-items-center">
                            <input type="checkbox" id="addUtama" class="form-check-input me-2">
                            <label for="addUtama" class="form-check-label small text-muted">Set Utama</label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" onclick="submitQuickAddress()">Simpan Alamat</button>
                </div>

                <!-- Address List selection -->
                @if($addresses->isEmpty())
                    <div class="alert alert-warning small border-0 py-3 mb-0" style="border-radius: 12px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $lang == 'en' ? 'No shipping addresses found. Please add a new address to continue.' : 'Anda belum memiliki alamat pengiriman. Silakan tambah alamat baru untuk melanjutkan.' }}
                    </div>
                @else
                    @foreach($addresses as $addr)
                        <div class="form-check p-3 rounded border mb-2 {{ $addr->is_utama ? 'border-primary bg-primary-subtle' : '' }}" style="position: relative;">
                            <input class="form-check-input ms-1" type="radio" name="alamat_id" id="addr_{{ $addr->id }}" value="{{ $addr->id }}" {{ $addr->is_utama ? 'checked' : '' }} required>
                            <label class="form-check-label d-block ms-4" for="addr_{{ $addr->id }}">
                                <span class="badge bg-secondary mb-1">{{ $addr->label }}</span>
                                @if($addr->is_utama)
                                    <span class="badge bg-primary mb-1">{{ $lang == 'en' ? 'Default' : 'Utama' }}</span>
                                @endif
                                <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $addr->nama_penerima }} ({{ $addr->no_hp }})</strong>
                                <span class="text-muted small d-block">{{ $addr->alamat_lengkap }}, {{ $addr->kota }}, {{ $addr->kode_pos }}</span>
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- 2. Expedition courier Selection -->
            <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-truck-fast text-primary me-2"></i>{{ $lang == 'en' ? 'Select Courier' : 'Pilih Kurir Pengiriman' }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check p-3 border rounded">
                            <input class="form-check-input" type="radio" name="ekspedisi" id="courier_jne" value="JNE" checked onclick="updateCosts(15000)">
                            <label class="form-check-label d-block ms-2" for="courier_jne">
                                <strong>JNE Express</strong>
                                <span class="text-muted small d-block">Rp 15.000 (Estimasi 2-3 hari)</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check p-3 border rounded">
                            <input class="form-check-input" type="radio" name="ekspedisi" id="courier_jnt" value="J&T" onclick="updateCosts(12000)">
                            <label class="form-check-label d-block ms-2" for="courier_jnt">
                                <strong>J&T Express</strong>
                                <span class="text-muted small d-block">Rp 12.000 (Estimasi 2-4 hari)</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check p-3 border rounded">
                            <input class="form-check-input" type="radio" name="ekspedisi" id="courier_sicepat" value="SiCepat" onclick="updateCosts(11000)">
                            <label class="form-check-label d-block ms-2" for="courier_sicepat">
                                <strong>SiCepat Regular</strong>
                                <span class="text-muted small d-block">Rp 11.000 (Estimasi 2-3 hari)</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check p-3 border rounded">
                            <input class="form-check-input" type="radio" name="ekspedisi" id="courier_anteraja" value="AnterAja" onclick="updateCosts(10000)">
                            <label class="form-check-label d-block ms-2" for="courier_anteraja">
                                <strong>AnterAja Eco</strong>
                                <span class="text-muted small d-block">Rp 10.000 (Estimasi 3-5 hari)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Payment Method Section -->
            <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-wallet text-primary me-2"></i>{{ $lang == 'en' ? 'Payment Method' : 'Metode Pembayaran' }}</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-check p-3 border rounded text-center">
                            <input class="form-check-input" type="radio" name="metode_bayar" id="pay_bank" value="bank_transfer" checked>
                            <label class="form-check-label d-block text-center mt-1" for="pay_bank">
                                <i class="fas fa-bank fs-4 mb-2 text-primary d-block"></i>
                                <strong>Bank Transfer</strong>
                                <span class="text-muted small d-block">(Simulasi Escrow)</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check p-3 border rounded text-center">
                            <input class="form-check-input" type="radio" name="metode_bayar" id="pay_qris" value="qris">
                            <label class="form-check-label d-block text-center mt-1" for="pay_qris">
                                <i class="fas fa-qrcode fs-4 mb-2 text-primary d-block"></i>
                                <strong>QRIS Wallet</strong>
                                <span class="text-muted small d-block">(Instan Digital)</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check p-3 border rounded text-center">
                            <input class="form-check-input" type="radio" name="metode_bayar" id="pay_cod" value="cod">
                            <label class="form-check-label d-block text-center mt-1" for="pay_cod">
                                <i class="fas fa-hand-holding-dollar fs-4 mb-2 text-primary d-block"></i>
                                <strong>Cash on Delivery</strong>
                                <span class="text-muted small d-block">(COD - Bayar Kurir)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Notes for Seller -->
            <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                <label for="catatan" class="form-label fw-bold text-dark"><i class="far fa-note-sticky text-primary me-2"></i>{{ $lang == 'en' ? 'Notes for Seller' : 'Catatan Tambahan untuk Seller' }}</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="2" placeholder="{{ $lang == 'en' ? 'e.g. Please wrap carefully or secure package' : 'misal: Tolong bungkus dengan bubble wrap tebal' }}" style="border-radius: 10px;"></textarea>
            </div>
        </div>

        <!-- Right Summary column -->
        <div class="col-lg-4">
            <!-- Voucher promo box -->
            <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-ticket-alt text-primary me-2"></i>{{ $lang == 'en' ? 'Use Promo Code' : 'Gunakan Voucher Promo' }}</h6>
                <div class="d-flex gap-2 mb-3">
                    <input type="text" name="voucher_code" id="voucherCodeInput" class="form-control form-control-sm" placeholder="NEWUSER10" style="border-radius: 8px;">
                    <button type="button" class="btn btn-sm btn-primary" onclick="applyVoucher()">{{ $lang == 'en' ? 'Apply' : 'Pakai' }}</button>
                </div>
                <!-- Vouchers suggestions -->
                <span class="small text-muted d-block mb-2">{{ $lang == 'en' ? 'Available Vouchers:' : 'Voucher Tersedia:' }}</span>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($vouchers as $v)
                        <button type="button" class="btn btn-xs btn-outline-warning text-dark py-1 px-2 small" onclick="useSuggestedVoucher('{{ $v->kode_voucher }}', {{ $v->diskon }}, {{ $v->min_beli }})" style="font-size: 0.75rem;">
                            {{ $v->kode_voucher }} (Diskon Rp {{ number_format($v->diskon, 0, ',', '.') }})
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Final Cost Details -->
            <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px; position: sticky; top: 90px;">
                <h5 class="fw-bold text-dark mb-4">{{ $lang == 'en' ? 'Billing Summary' : 'Rincian Tagihan' }}</h5>

                <!-- Items listed -->
                <div class="mb-4">
                    @foreach($cartItems as $item)
                        @php
                            $negotiatedPrice = session()->get('nego_price_' . $item->barang->id);
                            $price = $negotiatedPrice ?: $item->barang->harga_jual;
                        @endphp
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted text-truncate" style="max-width: 200px;">{{ $item->barang->nama_barang }} (x{{ $item->quantity }})</span>
                            <span class="text-dark fw-semibold">Rp {{ number_format($price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <hr class="my-3">

                <!-- Cost lines -->
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Subtotal</span>
                    <strong class="text-dark" id="subtotalVal">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">{{ $lang == 'en' ? 'Shipping Cost' : 'Ongkir Kurir' }}</span>
                    <strong class="text-dark" id="ongkirVal">Rp 15.000</strong>
                </div>

                <div class="d-flex justify-content-between mb-3 text-success">
                    <span class="small">{{ $lang == 'en' ? 'Voucher Discount' : 'Potongan Voucher' }}</span>
                    <strong id="diskonVal">- Rp 0</strong>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between mb-4">
                    <span class="text-dark fw-bold">{{ $lang == 'en' ? 'Total Pay' : 'Total Pembayaran' }}</span>
                    <strong class="text-primary fs-4" id="grandTotalVal">Rp {{ number_format($subtotal + 15000, 0, ',', '.') }}</strong>
                </div>

                @if(!$addresses->isEmpty())
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px;">
                        <i class="fas fa-shield-check me-2"></i>{{ $lang == 'en' ? 'Place Order & Pay' : 'Buat Pesanan & Bayar' }}
                    </button>
                @else
                    <button type="button" class="btn btn-light w-100 py-3 border" disabled style="border-radius: 12px;">
                        {{ $lang == 'en' ? 'Place Order & Pay' : 'Buat Pesanan & Bayar' }}
                    </button>
                @endif
                <span class="text-muted text-center d-block small mt-2"><i class="fas fa-shield-halved text-success me-1"></i>{{ $lang == 'en' ? 'Protected by Escrow System Guarantee' : 'Dilindungi Jaminan Rekening Bersama' }}</span>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    let subtotal = {{ $subtotal }};
    let shipping = 15000;
    let discount = 0;

    function updateCosts(shipCost) {
        shipping = shipCost;
        document.getElementById('ongkirVal').textContent = 'Rp ' + shipping.toLocaleString('id-ID');
        recalculateGrandTotal();
    }

    function useSuggestedVoucher(code, discAmt, minBeli) {
        if (subtotal < minBeli) {
            alert('{{ $lang == "en" ? "Minimum purchase for this voucher is Rp " : "Minimal belanja untuk voucher ini adalah Rp " }}' + minBeli.toLocaleString('id-ID'));
            return;
        }
        document.getElementById('voucherCodeInput').value = code;
        applyVoucherDirect(discAmt);
    }

    function applyVoucher() {
        const val = document.getElementById('voucherCodeInput').value.trim().toUpperCase();
        if (val === 'NEWUSER10' && subtotal >= 50000) {
            applyVoucherDirect(10000);
        } else if (val === 'ONGKIRGRATIS' && subtotal >= 100000) {
            applyVoucherDirect(15000);
        } else {
            alert('{{ $lang == "en" ? "Invalid promo code or minimum requirement not met." : "Kode promo tidak valid atau minimal belanja belum terpenuhi." }}');
        }
    }

    function applyVoucherDirect(discAmt) {
        discount = discAmt;
        document.getElementById('diskonVal').textContent = '- Rp ' + discount.toLocaleString('id-ID');
        recalculateGrandTotal();
    }

    function recalculateGrandTotal() {
        let total = subtotal + shipping - discount;
        if (total < 0) total = 0;
        document.getElementById('grandTotalVal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function submitQuickAddress() {
        const label = document.getElementById('addLabel').value.trim();
        const penerima = document.getElementById('addPenerima').value.trim();
        const hp = document.getElementById('addHp').value.trim();
        const kota = document.getElementById('addKota').value.trim();
        const alamat = document.getElementById('addAlamat').value.trim();
        const pos = document.getElementById('addPos').value.trim();
        const utama = document.getElementById('addUtama').checked ? 1 : 0;

        if (!label || !penerima || !hp || !kota || !alamat || !pos) {
            alert('{{ $lang == "en" ? "Please fill in all address fields" : "Mohon lengkapi seluruh kolom alamat" }}');
            return;
        }

        // Send address addition request via POST
        const data = {
            label: label,
            nama_penerima: penerima,
            no_hp: hp,
            alamat_lengkap: alamat,
            kota: kota,
            kode_pos: pos,
            is_utama: utama,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        fetch("{{ route('buyer.address.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(() => {
            window.location.reload();
        });
    }
</script>
@endpush
@endsection
