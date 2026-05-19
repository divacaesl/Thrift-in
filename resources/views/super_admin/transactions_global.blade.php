@extends('layouts.super_admin')

@section('title', 'Global Transactions - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Global Transaction Ledger</h2>
            <p class="text-muted mb-0">Track payments, manage payouts, review escrow status, and resolve dispute refunds.</p>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">All Platform Transactions</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Invoice ID</th>
                        <th>Buyer</th>
                        <th>Seller</th>
                        <th>Amount</th>
                        <th>Escrow Status</th>
                        <th class="text-end pe-4">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4 text-white fw-bold">#TRX-98240</td>
                        <td>Budi Santoso</td>
                        <td>Sari Dewi Boutique</td>
                        <td class="text-white">Rp 365,000</td>
                        <td>
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">Released to Seller</span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-search-dollar"></i> Details</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4 text-white fw-bold">#TRX-98241</td>
                        <td>Rian Wijaya</td>
                        <td>Ahmad Zaki Store</td>
                        <td class="text-white">Rp 1,200,000</td>
                        <td>
                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">On Hold (Disputed)</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('superadmin.dispute.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-gavel"></i> Moderate Dispute</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
