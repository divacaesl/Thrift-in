@extends('layouts.app')

@section('title', 'Pencairan Dana')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pencairan</h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahPencairanModal">
            <i class="fas fa-plus"></i> Input Pencairan
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Penitip</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Admin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pencairans as $p)
                    <tr>
                        <td>{{ $p->kode_pencairan }}</td>
                        <td>{{ $p->penitip->nama }}</td>
                        <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($p->metode) }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'selesai' ? 'success' : ($p->status == 'pending' ? 'warning' : 'info') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>{{ $p->admin->nama }}</td>
                        <td>
                            <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Pencairan -->
<div class="modal fade" id="tambahPencairanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Pencairan Dana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pencairan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Penitip</label>
                        <select name="penitip_id" class="form-select" required>
                            <option value="">-- Pilih Penitip --</option>
                            @foreach($penitips as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->kode_penitip }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Pencairan (Rp)</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode" class="form-select" required>
                            <option value="transfer">Transfer Bank</option>
                            <option value="tunai">Tunai / Cash</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pencairan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
