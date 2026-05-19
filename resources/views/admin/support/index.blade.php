@extends('layouts.admin')

@section('title', 'Support Tickets - Admin')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-headset text-primary me-2"></i> Layanan Bantuan (Ticketing)</h5>
            <p class="text-muted small mb-0">Kelola laporan fraud, sengketa transaksi, dan komplain pelanggan.</p>
        </div>
        
        <form action="{{ route('admin.support.index') }}" method="GET">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open (Baru)</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light text-muted small">
                <tr>
                    <th>TIKET INFO</th>
                    <th>PELAPOR</th>
                    <th>PRIORITAS</th>
                    <th>STATUS</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $ticket->subjek }}</div>
                            <div class="small text-muted">ID: {{ $ticket->kode_tiket }} • {{ $ticket->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $ticket->user->nama ?? 'Unknown' }}</div>
                            <div class="small text-muted">{{ $ticket->user->email ?? '-' }}</div>
                        </td>
                        <td>
                            @php
                                $prioColors = ['rendah' => 'success', 'sedang' => 'primary', 'tinggi' => 'warning', 'kritis' => 'danger'];
                                $c = $prioColors[$ticket->prioritas] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $c }} text-uppercase">{{ $ticket->prioritas }}</span>
                        </td>
                        <td>
                            @if($ticket->status == 'open')
                                <span class="badge badge-soft-danger"><i class="fas fa-envelope me-1"></i> Baru</span>
                            @elseif($ticket->status == 'in_progress')
                                <span class="badge badge-soft-warning text-dark"><i class="fas fa-spinner fa-spin me-1"></i> Diproses</span>
                            @elseif($ticket->status == 'resolved')
                                <span class="badge badge-soft-success"><i class="fas fa-check me-1"></i> Selesai</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-lock me-1"></i> Ditutup</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replyModal{{ $ticket->id }}">Lihat / Balas</button>
                        </td>
                    </tr>

                    <!-- Reply Modal -->
                    <div class="modal fade" id="replyModal{{ $ticket->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom">
                                    <h6 class="fw-bold mb-0">Tiket: {{ $ticket->kode_tiket }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="bg-light p-3 rounded mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold text-dark">{{ $ticket->user->nama ?? 'Pelapor' }}</span>
                                            <span class="small text-muted">{{ $ticket->created_at->format('d M, H:i') }}</span>
                                        </div>
                                        <p class="mb-0 text-dark" style="font-size: 0.95rem;">{{ $ticket->deskripsi }}</p>
                                    </div>
                                    
                                    <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Balasan Anda</label>
                                            <textarea name="pesan" rows="4" class="form-control" placeholder="Ketik balasan untuk pengguna ini..." required></textarea>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Tetap Open</option>
                                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Tandai In Progress</option>
                                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Tandai Selesai (Resolved)</option>
                                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Tutup Tiket</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <button type="submit" class="btn btn-primary px-4">Kirim Balasan <i class="fas fa-paper-plane ms-2"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Tidak ada tiket dukungan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
