@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Laporan Penjualan</h6>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" name="end_date">
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-print me-2"></i> Cetak Laporan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
