@extends('layouts.app')

@section('title', 'Barang Titipan')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
            <i class="fas fa-plus"></i> Tambah Barang
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Penitip</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $b)
                    <tr>
                        <td class="text-center">
                            <img src="{{ asset('assets/uploads/' . $b->foto) }}" width="50" class="img-thumbnail">
                        </td>
                        <td>{{ $b->kode_barang }}</td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->penitip->nama }}</td>
                        <td>{{ $b->kategori->nama_kategori }}</td>
                        <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $b->status == 'diterima' ? 'secondary' : ($b->status == 'ditampilkan' ? 'primary' : 'success') }}">{{ ucfirst($b->status) }}</span>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus barang ini?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="tambahBarangModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Titipan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Jual (Rp)</label>
                            <input type="number" name="harga_jual" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penitip</label>
                            <select name="penitip_id" class="form-select" required>
                                <option value="">-- Pilih Penitip --</option>
                                @foreach($penitips as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->kode_penitip }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="baru">Baru</option>
                                <option value="seperti_baru">Seperti Baru</option>
                                <option value="bekas_layak" selected>Bekas Layak</option>
                                <option value="bekas">Bekas</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Foto Barang</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Catatan</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
