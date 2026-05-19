@extends('layouts.admin')

@section('title', 'Keuangan & Pencairan - Admin')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-4"><i class="fas fa-building-columns text-primary me-2"></i> Permintaan Pencairan Dana (Withdrawals)</h5>
            
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th>ID & TANGGAL</th>
                            <th>NAMA TOKO (SELLER)</th>
                            <th>REKENING / TUJUAN</th>
                            <th>NOMINAL</th>
                            <th>STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $wd)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $wd->kode_pencairan }}</div>
                                    <div class="small text-muted">{{ \Carbon\Carbon::parse($wd->tgl_pencairan)->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $wd->penitip->nama ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="small fw-bold text-dark">{{ $wd->penitip->nama_bank ?? '-' }}</div>
                                    <div class="small text-muted">{{ $wd->penitip->no_rekening ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">Rp {{ number_format($wd->jumlah, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    @if($wd->status == 'selesai')
                                        <span class="badge badge-soft-success"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                                    @elseif($wd->status == 'ditolak')
                                        <span class="badge badge-soft-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge badge-soft-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($wd->status == 'pending')
                                        <button type="button" class="btn btn-sm btn-success px-3" data-bs-toggle="modal" data-bs-target="#approveModal{{ $wd->id }}" title="Approve"><i class="fas fa-check"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $wd->id }}" title="Reject"><i class="fas fa-times"></i></button>
                                    @else
                                        <button class="btn btn-sm btn-light border text-muted" disabled>Done</button>
                                    @endif
                                </td>
                            </tr>

                            @if($wd->status == 'pending')
                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $wd->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="fw-bold"><i class="fas fa-check-circle text-success me-2"></i> Approve Pencairan: {{ $wd->kode_pencairan }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.finance.process', $wd->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <div class="modal-body p-4">
                                                <p class="small text-muted mb-3">Pastikan Anda telah mentransfer dana sebesar <strong>Rp {{ number_format($wd->jumlah, 0, ',', '.') }}</strong> ke rekening <strong>{{ $wd->penitip->nama_bank }} - {{ $wd->penitip->no_rekening }}</strong> sebelum menyetujui permintaan ini. Saldo seller akan otomatis terpotong.</p>
                                                <label class="form-label small fw-bold">Keterangan / Bukti (Opsional)</label>
                                                <textarea name="notes" rows="2" class="form-control" placeholder="No. Referensi Transfer: ..."></textarea>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success btn-sm px-4">Transfer Selesai</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $wd->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="fw-bold"><i class="fas fa-times-circle text-danger me-2"></i> Tolak Pencairan: {{ $wd->kode_pencairan }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.finance.process', $wd->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <div class="modal-body p-4">
                                                <label class="form-label small fw-bold">Alasan Penolakan</label>
                                                <textarea name="notes" rows="3" class="form-control" placeholder="Contoh: Nomor rekening tidak valid..." required></textarea>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger btn-sm px-4">Tolak Pencairan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat pencairan dana.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $withdrawals->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4 h-100">
            <h6 class="fw-bold mb-4"><i class="fas fa-percent text-warning me-2"></i> Konfigurasi Keuangan</h6>
            <form action="{{ route('admin.system.settings.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Platform Fee (%)</label>
                    <div class="input-group">
                        <input type="number" name="platform_fee_percent" class="form-control" value="{{ $platformFee }}" min="0" max="100" step="0.1">
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text small" style="font-size: 0.75rem;">Potongan admin yang dikenakan kepada seller setiap transaksi berhasil (Selesai).</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Fee</button>
            </form>

            <hr class="my-4">
            
            <h6 class="fw-bold mb-3">Rekapitulasi</h6>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Pending Payouts</span>
                <span class="fw-bold">{{ $pendingCount }} Antrean</span>
            </div>
            <!-- Export Laporan -->
            <button class="btn btn-outline-secondary w-100 mt-3" disabled><i class="fas fa-file-pdf me-2"></i> Export Laporan Keuangan</button>
        </div>
    </div>
</div>
@endsection
