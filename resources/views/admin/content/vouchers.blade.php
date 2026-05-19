@extends('layouts.admin')

@section('title', 'Vouchers - Admin')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-ticket text-primary me-2"></i> Kupon & Voucher</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVoucherModal"><i class="fas fa-plus me-1"></i> Buat Voucher</button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>KODE VOUCHER</th>
                    <th>NILAI DISKON</th>
                    <th>MIN. BELANJA</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $v)
                <tr>
                    <td><span class="badge bg-dark fs-6 font-monospace">{{ $v->kode_voucher }}</span></td>
                    <td class="fw-bold text-success">Rp {{ number_format($v->diskon, 0, ',', '.') }}</td>
                    <td class="text-muted">Rp {{ number_format($v->min_beli, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-success">Aktif</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addVoucherModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('admin.content.vouchers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <h6 class="fw-bold mb-3">Buat Voucher Baru</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kode Voucher</label>
                            <input type="text" name="kode_voucher" class="form-control text-uppercase" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Potongan (Rp)</label>
                            <input type="number" name="diskon" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Minimal Belanja (Rp)</label>
                            <input type="number" name="min_beli" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
