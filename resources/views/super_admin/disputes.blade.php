@extends('layouts.super_admin')

@section('title', 'Dispute Resolution Center - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Dispute Resolution Center</h2>
            <p class="text-muted mb-0">Review buyer complaints, check evidence photos, moderate conversations, and trigger escrow refunds or releases.</p>
        </div>
    </div>

    <!-- Active Disputes -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">Open Escrow Disputes Queue</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Dispute Details</th>
                        <th>Buyer Claim</th>
                        <th>Evidence</th>
                        <th>Time Lodged</th>
                        <th class="text-end pe-4">Resolution Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <h6 class="mb-0 fw-bold text-white">#TRX-98241</h6>
                            <small class="text-muted">Seller: Ahmad Zaki Store</small>
                        </td>
                        <td>
                            <div class="text-white">Product is not authentic (counterfeit).</div>
                            <small class="text-muted">Buyer: Rian Wijaya</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary text-white" style="cursor: pointer;"><i class="fas fa-image me-1"></i> View Photo Evidence</span>
                        </td>
                        <td class="text-muted">Yesterday</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-success me-1"><i class="fas fa-money-bill-wave"></i> Refund Buyer</button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-check"></i> Release to Seller</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
