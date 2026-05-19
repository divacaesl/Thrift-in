@extends('layouts.super_admin')

@section('title', 'Finance System - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Marketplace Financial System</h2>
            <p class="text-muted mb-0">Monitor total commission splits, platform fees, pending seller withdrawals, and refunds.</p>
        </div>
    </div>

    <!-- Income Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card p-4 border-0">
                <span class="text-muted small text-uppercase">Net Platform Fee (Keep)</span>
                <h3 class="text-white fw-bold mb-1">Rp 148,250,000</h3>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i> 12.8% vs last month</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 border-0">
                <span class="text-muted small text-uppercase">Pending Withdrawals</span>
                <h3 class="text-white fw-bold mb-1">Rp 25,410,000</h3>
                <small class="text-muted">4 pending requests</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 border-0">
                <span class="text-muted small text-uppercase">Processed Payouts</span>
                <h3 class="text-white fw-bold mb-1">Rp 1.12B</h3>
                <small class="text-success"><i class="fas fa-check-circle me-1"></i> Completed</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 border-0">
                <span class="text-muted small text-uppercase">Escrow Balance</span>
                <h3 class="text-white fw-bold mb-1">Rp 480,950,000</h3>
                <small class="text-muted">Secured via Midtrans</small>
            </div>
        </div>
    </div>

    <!-- Withdrawal Queue -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">Seller Withdrawal Requests Queue</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Seller/Shop</th>
                        <th>Amount Requested</th>
                        <th>Bank Details</th>
                        <th>Request Time</th>
                        <th class="text-end pe-4">Process</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <h6 class="mb-0 fw-bold text-white">Sari Dewi Boutique</h6>
                            <small class="text-muted">ID: #SEL-104</small>
                        </td>
                        <td class="text-white fw-bold">Rp 8,500,000</td>
                        <td>Bank Central Asia (BCA) - 8920140283 a.n Sari Dewi</td>
                        <td class="text-muted">3 hours ago</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-primary me-1"><i class="fas fa-check-double"></i> Approve & Transfer</button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Reject</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
