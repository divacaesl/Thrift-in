@extends('layouts.super_admin')

@section('title', 'Promos & Vouchers - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Global Promos & Vouchers</h2>
            <p class="text-muted mb-0">Launch platform-wide discount codes, control maximum cashbacks, and audit active coupon allocations.</p>
        </div>
        <button class="btn btn-primary"><i class="fas fa-plus me-2"></i> Create New Voucher</button>
    </div>

    <!-- Active Vouchers -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">Active Promotional Coupons</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Promo Code</th>
                        <th>Discount Value</th>
                        <th>Usage Limit</th>
                        <th>Expiry Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-primary text-white py-2 px-3 fw-bold" style="font-size: 0.9rem;">THRIFTMERDEKA</span>
                        </td>
                        <td class="text-white fw-bold">15% Off (Max Rp 50,000)</td>
                        <td>452 / 1000 Used</td>
                        <td class="text-muted">31 August 2026</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Revoke</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
