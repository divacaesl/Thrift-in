@extends('layouts.admin')

@section('title', 'Transaksi & Dispute - Admin')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-money-check-dollar text-success me-2"></i> Pantau Transaksi & Sengketa</h5>
            <p class="text-muted small mb-0">Uang pembeli tertahan di Escrow Platform sampai transaksi selesai. Admin berhak memutus sengketa jika terjadi masalah.</p>
        </div>
        
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Bayar</option>
                <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim (Escrow Hold)</option>
                <option value="sampai" {{ request('status') == 'sampai' ? 'selected' : '' }}>Selesai</option>
                <option value="refund" {{ request('status') == 'refund' ? 'selected' : '' }}>Refunded</option>
            </select>
            <div class="form-check form-switch ms-2 d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox" name="dispute" value="1" id="flexSwitchCheckDefault" {{ request('dispute') ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label small fw-bold text-danger" for="flexSwitchCheckDefault">Only Disputes</label>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th>ORDER ID</th>
                    <th>PRODUK & SELLER</th>
                    <th>NOMINAL (ESCROW)</th>
                    <th>STATUS ORDER</th>
                    <th>DISPUTE STATUS</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    <tr class="{{ $trx->dispute_status && !str_starts_with($trx->dispute_status, 'resolved') ? 'bg-danger bg-opacity-10' : '' }}">
                        <td>
                            <div class="fw-bold text-dark">{{ $trx->kode_transaksi }}</div>
                            <div class="small text-muted">{{ $trx->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $trx->barang->nama_barang ?? '-' }}</div>
                            <div class="small text-muted">Seller: {{ $trx->barang->penitip->nama ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-success">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</div>
                            <div class="small text-muted" style="font-size:0.7rem;">(Termasuk Ongkir + Fee)</div>
                        </td>
                        <td>
                            @if($trx->status_pesanan == 'sampai')
                                <span class="badge badge-soft-success">Selesai</span>
                            @elseif(in_array($trx->status_pesanan, ['diproses', 'dikemas', 'dikirim']))
                                <span class="badge badge-soft-warning"><i class="fas fa-lock me-1"></i> In Escrow</span>
                                <div class="small text-muted mt-1" style="font-size: 0.7rem;">(Meringankan: {{ ucfirst($trx->status_pesanan) }})</div>
                            @elseif($trx->status_pesanan == 'refund')
                                <span class="badge bg-secondary">Refunded</span>
                            @else
                                <span class="badge bg-light text-dark border">{{ str_replace('_', ' ', $trx->status_pesanan) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($trx->dispute_status)
                                @if(str_starts_with($trx->dispute_status, 'resolved'))
                                    <span class="badge badge-soft-success"><i class="fas fa-gavel me-1"></i> Resolved ({{ str_replace('resolved_', '', $trx->dispute_status) }})</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-gavel me-1"></i> Open Dispute</span>
                                @endif
                                @if($trx->dispute_notes)
                                    <div class="small text-muted mt-1" style="font-size: 0.7rem;">Note: {{ Str::limit($trx->dispute_notes, 30) }}</div>
                                @endif
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($trx->dispute_status && !str_starts_with($trx->dispute_status, 'resolved'))
                                <button type="button" class="btn btn-sm btn-danger px-3" data-bs-toggle="modal" data-bs-target="#resolveModal{{ $trx->id }}">Resolve Dispute</button>
                            @else
                                <button class="btn btn-sm btn-light border text-muted" disabled>No Action</button>
                            @endif
                        </td>
                    </tr>

                    @if($trx->dispute_status && !str_starts_with($trx->dispute_status, 'resolved'))
                    <!-- Resolve Dispute Modal -->
                    <div class="modal fade" id="resolveModal{{ $trx->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h6 class="fw-bold"><i class="fas fa-gavel text-danger me-2"></i> Putusan Sengketa: {{ $trx->kode_transaksi }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.transactions.dispute', $trx->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <p class="small text-muted">Sebagai admin, Anda berhak memutuskan siapa yang memenangkan sengketa ini setelah melakukan investigasi melalui Support Tiket/Chat.</p>
                                        
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Pemenang Sengketa</label>
                                            <select name="resolution" class="form-select" required>
                                                <option value="">-- Pilih Putusan --</option>
                                                <option value="buyer_win">Pembeli Benar (Refund Saldo ke Pembeli)</option>
                                                <option value="seller_win">Penjual Benar (Cairkan Escrow ke Penjual)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Catatan Putusan</label>
                                            <textarea name="notes" rows="3" class="form-control" placeholder="Jelaskan alasan putusan ini..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm px-4">Eksekusi Putusan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada transaksi ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
