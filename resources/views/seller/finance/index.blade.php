@extends('layouts.seller')

@section('title', 'Keuangan Toko - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="row g-4 mb-4">
    <!-- Active Saldo -->
    <div class="col-md-4">
        <div class="card p-4 bg-primary text-white">
            <h6 class="text-white-50 mb-1 small">{{ $lang == 'en' ? 'Available Balance' : 'Saldo Dompet Toko' }}</h6>
            <h2 class="fw-bold mb-3">Rp {{ number_format($penitip->saldo, 0, ',', '.') }}</h2>
            <button type="button" class="btn btn-light btn-sm fw-semibold w-100" data-bs-toggle="modal" data-bs-target="#withdrawModal" {{ $penitip->saldo < 10000 ? 'disabled' : '' }}>
                <i class="fas fa-money-bill-transfer me-1"></i> {{ $lang == 'en' ? 'Withdraw Funds' : 'Tarik Saldo Payout' }}
            </button>
        </div>
    </div>

    <!-- Escrow held funds -->
    <div class="col-md-4">
        <div class="card p-4">
            <h6 class="text-muted mb-1 small">{{ $lang == 'en' ? 'Escrow / Pending Funds' : 'Dana Tertunda (Escrow)' }}</h6>
            <h2 class="fw-bold text-warning mb-3">Rp {{ number_format($danaTertunda, 0, ',', '.') }}</h2>
            <small class="text-muted">{{ $lang == 'en' ? 'Funds held until items are delivered' : 'Dana transaksi pembeli yang ditahan sementara' }}</small>
        </div>
    </div>

    <!-- Total Net Profit -->
    <div class="col-md-4">
        <div class="card p-4">
            <h6 class="text-muted mb-1 small">{{ $lang == 'en' ? 'Completed Earnings' : 'Total Keuntungan Selesai' }}</h6>
            <h2 class="fw-bold text-success mb-3">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</h2>
            <small class="text-muted">{{ $lang == 'en' ? 'Excludes commissions and refunds' : 'Pendapatan yang telah ditransfer ke Saldo' }}</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Withdrawal logs -->
    <div class="col-lg-7">
        <div class="card p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-history text-secondary me-2"></i>{{ $lang == 'en' ? 'Payout & Withdrawal History' : 'Riwayat Penarikan Dana' }}</h5>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ $lang == 'en' ? 'WD Code' : 'Kode Penarikan' }}</th>
                            <th>{{ $lang == 'en' ? 'Amount' : 'Jumlah' }}</th>
                            <th>{{ $lang == 'en' ? 'Method' : 'Metode / Tujuan' }}</th>
                            <th>{{ $lang == 'en' ? 'Status' : 'Status' }}</th>
                            <th>{{ $lang == 'en' ? 'Date' : 'Tanggal' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($withdrawals->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <p class="mb-0 small">{{ $lang == 'en' ? 'No withdrawal records.' : 'Belum ada pengajuan penarikan dana.' }}</p>
                                </td>
                            </tr>
                        @else
                            @foreach($withdrawals as $wd)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $wd->kode_pencairan }}</span></td>
                                    <td><strong>Rp {{ number_format($wd->jumlah, 0, ',', '.') }}</strong></td>
                                    <td class="small text-muted">{{ $wd->keterangan }}</td>
                                    <td>
                                        @if($wd->status == 'selesai')
                                            <span class="badge bg-success">SELESAI</span>
                                        @elseif($wd->status == 'pending')
                                            <span class="badge bg-warning text-dark">PENDING</span>
                                        @else
                                            <span class="badge bg-danger">{{ strtoupper($wd->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $wd->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Earnings logs -->
    <div class="col-lg-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-circle-dollar-to-slot text-success me-2"></i>{{ $lang == 'en' ? 'Earnings Breakdown' : 'Riwayat Transaksi Masuk' }}</h5>
            
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>{{ $lang == 'en' ? 'Order' : 'Pesanan' }}</th>
                            <th>{{ $lang == 'en' ? 'Net Payout' : 'Pendapatan' }}</th>
                            <th>{{ $lang == 'en' ? 'State' : 'Status' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($transactions->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <p class="mb-0 small">Belum ada transaksi.</p>
                                </td>
                            </tr>
                        @else
                            @foreach($transactions->take(6) as $tx)
                                <tr>
                                    <td>
                                        <span class="fw-bold small">{{ $tx->kode_transaksi }}</span>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $tx->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td><strong>Rp {{ number_format($tx->hasil_penitip, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if($tx->status_pesanan == 'sampai')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Selesai</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 text-dark">Tertahan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal (Section 8) -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary"><i class="fas fa-money-bill-transfer me-2"></i> {{ $lang == 'en' ? 'Submit Withdrawal Request' : 'Pengajuan Penarikan Saldo' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('seller.finance.withdraw') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 small mb-3">
                        <i class="fas fa-info-circle me-1"></i> Penarikan minimal Rp 10.000. Saldo Anda saat ini adalah <strong>Rp {{ number_format($penitip->saldo, 0, ',', '.') }}</strong>.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Jumlah Penarikan (Rp)</label>
                        <input type="number" name="jumlah" class="form-control" min="10000" max="{{ $penitip->saldo }}" placeholder="100000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Metode Pencairan</label>
                        <select name="metode" class="form-select" required>
                            <option value="transfer">Transfer Bank / E-Wallet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nama Bank / E-Wallet Payout</label>
                        <input type="text" name="nama_bank" class="form-control" value="{{ $penitip->nama_bank }}" placeholder="BCA / Mandiri / GoPay" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nomor Rekening / Wallet ID</label>
                        <input type="text" name="no_rekening" class="form-control" value="{{ $penitip->no_rekening }}" placeholder="1234567890" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Keterangan Tambahan (Opsional)</label>
                        <textarea name="keterangan" rows="2" class="form-control" placeholder="Catatan transfer..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Kirim Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
