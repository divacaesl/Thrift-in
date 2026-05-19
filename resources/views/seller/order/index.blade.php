@extends('layouts.seller')

@section('title', 'Manajemen Pesanan - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="card p-4">
    <h5 class="fw-bold mb-4"><i class="fas fa-receipt text-primary me-2"></i>{{ $lang == 'en' ? 'Manage Customer Orders' : 'Daftar Pesanan Pembeli' }}</h5>

    <!-- Filters -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('seller.order.index') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $lang == 'en' ? 'All Orders' : 'Semua Pesanan' }}
        </a>
        <a href="{{ route('seller.order.index', ['status' => 'menunggu_pembayaran']) }}" class="btn btn-sm {{ $status == 'menunggu_pembayaran' ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $lang == 'en' ? 'Unpaid' : 'Menunggu Pembayaran' }}
        </a>
        <a href="{{ route('seller.order.index', ['status' => 'diproses']) }}" class="btn btn-sm {{ $status == 'diproses' ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $lang == 'en' ? 'Processing' : 'Diproses' }}
        </a>
        <a href="{{ route('seller.order.index', ['status' => 'dikirim']) }}" class="btn btn-sm {{ $status == 'dikirim' ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $lang == 'en' ? 'Shipped' : 'Dikirim' }}
        </a>
        <a href="{{ route('seller.order.index', ['status' => 'sampai']) }}" class="btn btn-sm {{ $status == 'sampai' ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $lang == 'en' ? 'Completed' : 'Selesai' }}
        </a>
        <a href="{{ route('seller.order.index', ['status' => 'refund']) }}" class="btn btn-sm {{ $status == 'refund' ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $lang == 'en' ? 'Cancelled' : 'Dibatalkan' }}
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>{{ $lang == 'en' ? 'Order Details' : 'Rincian Pesanan' }}</th>
                    <th>{{ $lang == 'en' ? 'Product' : 'Nama Produk' }}</th>
                    <th>{{ $lang == 'en' ? 'Buyer / Destination' : 'Pembeli & Tujuan' }}</th>
                    <th>{{ $lang == 'en' ? 'Amount' : 'Total Bayar' }}</th>
                    <th>{{ $lang == 'en' ? 'Courier / Receipt' : 'Kurir & Resi' }}</th>
                    <th>{{ $lang == 'en' ? 'Status' : 'Status' }}</th>
                    <th class="text-center">{{ $lang == 'en' ? 'Action Operations' : 'Aksi Operasi' }}</th>
                </tr>
            </thead>
            <tbody>
                @if($orders->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-receipt fs-2 mb-2 text-secondary"></i>
                            <p class="mb-0 small">{{ $lang == 'en' ? 'No orders match this status.' : 'Tidak ada pesanan dengan status ini.' }}</p>
                        </td>
                    </tr>
                @else
                    @foreach($orders as $o)
                        <tr>
                            <td>
                                <span class="fw-bold text-primary">{{ $o->kode_transaksi }}</span>
                                <div class="text-muted small mt-1" style="font-size: 0.75rem;">{{ $o->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $o->barang->nama_barang ?? 'Barang Dihapus' }}</div>
                                <div class="text-muted small" style="font-size: 0.75rem;">Qty: 1 | Brand: {{ $o->barang->brand ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $o->nama_pembeli }}</div>
                                <div class="text-muted text-truncate small" style="max-width: 180px; font-size: 0.75rem;">{{ $o->alamat }}</div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rp {{ number_format($o->harga_jual, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($o->ekspedisi)
                                    <span class="badge bg-secondary">{{ $o->ekspedisi }}</span>
                                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">Resi: <strong>{{ $o->no_resi }}</strong></div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = 'bg-secondary';
                                    if ($o->status_pesanan == 'menunggu_pembayaran') $badge = 'bg-warning text-dark';
                                    elseif ($o->status_pesanan == 'diproses') $badge = 'bg-info text-dark';
                                    elseif ($o->status_pesanan == 'dikirim') $badge = 'bg-primary';
                                    elseif ($o->status_pesanan == 'sampai') $badge = 'bg-success';
                                    elseif ($o->status_pesanan == 'refund') $badge = 'bg-danger';
                                @endphp
                                <span class="badge {{ $badge }}">{{ strtoupper(str_replace('_', ' ', $o->status_pesanan)) }}</span>
                            </td>
                            <td class="text-center">
                                @if($o->status_pesanan == 'menunggu_pembayaran')
                                    <!-- Confirm or reject payment in escrow -->
                                    <a href="{{ route('seller.order.confirm', $o->id) }}" class="btn btn-sm btn-success mb-1 w-100"><i class="fas fa-check"></i> {{ $lang == 'en' ? 'Confirm Payment' : 'Konfirmasi Bayar' }}</a>
                                    <a href="{{ route('seller.order.cancel', $o->id) }}" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Batalkan pesanan ini?');">{{ $lang == 'en' ? 'Reject' : 'Batalkan' }}</a>
                                @elseif($o->status_pesanan == 'diproses')
                                    <!-- Input Resi / Deliver -->
                                    <button type="button" class="btn btn-sm btn-primary mb-1 w-100" data-bs-toggle="modal" data-bs-target="#shipModal{{ $o->id }}">
                                        <i class="fas fa-truck"></i> {{ $lang == 'en' ? 'Ship Item' : 'Kirim Barang' }}
                                    </button>
                                    <a href="{{ route('seller.order.label', $o->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100"><i class="fas fa-print"></i> {{ $lang == 'en' ? 'Print Label' : 'Cetak Label' }}</a>
                                @else
                                    <!-- Printable label / Invoice lookup -->
                                    <a href="{{ route('seller.order.label', $o->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100"><i class="fas fa-print"></i> {{ $lang == 'en' ? 'Print Info' : 'Cetak Struk' }}</a>
                                @endif
                            </td>
                        </tr>

                        <!-- Ship Item Modal (Section 6) -->
                        <div class="modal fade" id="shipModal{{ $o->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold text-primary"><i class="fas fa-truck-ramp-box me-2"></i> {{ $lang == 'en' ? 'Input Courier Shipping' : 'Input Pengiriman Barang' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('seller.order.ship', $o->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-secondary">Pilih Ekspedisi Kurir</label>
                                                <select name="ekspedisi" class="form-select" required>
                                                    <option value="J&T">J&T Express</option>
                                                    <option value="JNE">JNE Express</option>
                                                    <option value="SiCepat">SiCepat Express</option>
                                                    <option value="AnterAja">AnterAja</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-secondary">Nomor Resi Pengiriman</label>
                                                <input type="text" name="no_resi" class="form-control" placeholder="Contoh: JT1234567890" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm px-4">Kirim Pesanan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
