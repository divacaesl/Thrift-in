@extends('layouts.app')

@section('title', 'Data Penitip')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Penitip</h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahPenitipModal">
            <i class="fas fa-plus"></i> Tambah Penitip
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Bank / Rekening</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penitips as $p)
                    <tr>
                        <td>{{ $p->kode_penitip }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->no_hp }}</td>
                        <td>{{ $p->nama_bank }} - {{ $p->no_rekening }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'aktif' ? 'success' : 'danger' }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('penitip.destroy', $p->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus penitip ini?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Penitip -->
<div class="modal fade" id="tambahPenitipModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penitip Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('penitip.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP (WhatsApp)</label>
                        <input type="text" name="no_hp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control" placeholder="Misal: BCA, Mandiri" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Rekening</label>
                        <input type="text" name="no_rekening" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat (Opsional)</label>
                        <textarea name="alamat" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
