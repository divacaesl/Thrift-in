@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahTransaksiModal">
            <i class="fas fa-plus"></i> Transaksi Baru
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Barang</th>
                        <th>Pembeli</th>
                        <th>Harga Jual</th>
                        <th>Metode</th>
                        <th>Kasir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $t)
                    <tr>
                        <td>{{ $t->kode_transaksi }}</td>
                        <td>{{ $t->barang->nama_barang }}</td>
                        <td>{{ $t->nama_pembeli }}</td>
                        <td>Rp {{ number_format($t->harga_jual, 0, ',', '.') }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($t->metode_bayar) }}</span></td>
                        <td>{{ $t->kasir->nama }}</td>
                        <td>
                            <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Transaksi Baru -->
<div class="modal fade" id="tambahTransaksiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catat Penjualan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang</label>
                        <select name="barang_id" class="form-select" required>
                            <option value="">-- Pilih Barang (Status: Ditampilkan) --</option>
                            @foreach($barangs as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_barang }} - Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pembeli</label>
                        <input type="text" name="nama_pembeli" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP Pembeli (Opsional)</label>
                        <input type="text" name="no_hp_pembeli" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_bayar" class="form-select" required>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
