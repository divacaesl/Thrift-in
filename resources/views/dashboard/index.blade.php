@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Total Barang -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Barang Titipan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBarang }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barang Terjual -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Barang Terjual</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $barangTerjual }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Penitip -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Penitip</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPenitip }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Penjualan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Penjualan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Barang Masuk Terbaru -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Barang Titipan Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="15%">Foto</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Harga Jual</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangBaru as $b)
                            <tr>
                                <td class="text-center">
                                    <img src="{{ asset('assets/uploads/' . $b->foto) }}" width="40" class="img-thumbnail">
                                </td>
                                <td>{{ $b->kode_barang }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $b->status == 'diterima' ? 'secondary' : ($b->status == 'ditampilkan' ? 'primary' : 'success') }}">{{ ucfirst($b->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transaksi Penjualan Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No Transaksi</th>
                                <th>Barang</th>
                                <th>Pembeli</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksiTerbaru as $t)
                            <tr>
                                <td>{{ $t->kode_transaksi }}</td>
                                <td>{{ $t->barang->nama_barang }}</td>
                                <td>{{ $t->nama_pembeli }}</td>
                                <td>Rp {{ number_format($t->harga_jual, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .border-left-primary { border-left: .25rem solid #4e73df !important; }
    .border-left-success { border-left: .25rem solid #1cc88a !important; }
    .border-left-info { border-left: .25rem solid #36b9cc !important; }
    .border-left-warning { border-left: .25rem solid #f6c23e !important; }
</style>
@endpush
