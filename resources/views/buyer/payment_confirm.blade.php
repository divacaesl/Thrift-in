@extends('layouts.buyer')

@section('title', 'Konfirmasi Pembayaran - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
    $totalTransfer = $transaksi->harga_jual + $transaksi->ongkir;
@endphp

<div class="row justify-content-center py-5">
    <div class="col-md-7">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body text-center">
                <i class="fas fa-wallet text-primary fs-1 mb-3"></i>
                <h4 class="fw-bold text-dark mb-1">{{ $lang == 'en' ? 'Complete Your Payment' : 'Selesaikan Pembayaran Anda' }}</h4>
                <p class="text-muted small mb-4">{{ $lang == 'en' ? 'Please transfer the exact amount below to our escrow account' : 'Silakan transfer nominal berikut secara presisi ke rekening bersama kami' }}</p>

                <!-- Billing Box -->
                <div class="p-3 mb-4 rounded-4 bg-light border text-center">
                    <span class="text-muted small d-block mb-1">TOTAL TAGIHAN</span>
                    <h2 class="fw-extrabold text-primary mb-1">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</h2>
                    <span class="badge bg-warning text-dark fw-bold small"><i class="fas fa-clock me-1"></i>Kode Transaksi: {{ $transaksi->kode_transaksi }}</span>
                </div>

                <!-- Payment target details -->
                <div class="row g-3 text-start mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-white h-100">
                            <span class="text-muted small d-block mb-1">METODE TRANSFER</span>
                            <strong class="text-dark d-block">Bank BCA (Escrow Account)</strong>
                            <span class="text-muted small">No Rekening:</span>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <strong class="text-primary" id="rekNumber">7700981245</strong>
                                <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2" onclick="navigator.clipboard.writeText('7700981245'); alert('Salin berhasil')">Copy</button>
                            </div>
                            <span class="text-muted small d-block mt-1">a/n PT ThriftIn Jaya Mandiri</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-white h-100 text-center d-flex flex-column align-items-center justify-content-center">
                            <span class="text-muted small d-block mb-1">QRIS PAY (INSTANT)</span>
                            <div class="bg-dark p-2 rounded mb-1" style="width: 80px; height: 80px;">
                                <i class="fas fa-qrcode text-white fs-1"></i>
                            </div>
                            <span class="text-muted small" style="font-size: 0.7rem;">Scan QR code di atas menggunakan e-wallet Anda</span>
                        </div>
                    </div>
                </div>

                <!-- File Upload Proof form -->
                <div class="p-4 border border-dashed rounded-4 text-start bg-light mb-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-cloud-arrow-up text-primary me-2"></i>{{ $lang == 'en' ? 'Upload Payment Receipt' : 'Unggah Bukti Transfer' }}</h6>
                    
                    <form action="{{ route('buyer.payment.upload', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control" type="file" name="bukti_transfer" id="bukti_transfer" required accept="image/*" style="border-radius: 8px;">
                            <span class="text-muted small mt-1 d-block" style="font-size: 0.75rem;">Mendukung format JPG, PNG, JPEG. Ukuran maks. 2MB.</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">
                            {{ $lang == 'en' ? 'Verify & Confirm' : 'Verifikasi & Konfirmasi Pembayaran' }}
                        </button>
                    </form>
                </div>

                <div class="text-muted small">
                    <i class="fas fa-shield-halved text-success me-1"></i>{{ $lang == 'en' ? 'Escrow system holds your funds safely. We only release it once you confirm receipt.' : 'Sistem escrow menahan dana Anda dengan aman. Kami baru meneruskannya setelah Anda mengonfirmasi penerimaan barang.' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
