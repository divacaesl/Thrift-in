@extends('layouts.super_admin')

@section('title', 'Seller Verification - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Seller KYC & Verification</h2>
            <p class="text-muted mb-0">Approve or reject shop registrations, validate identity, and manage seller status badges.</p>
        </div>
    </div>

    <!-- Verification Queue -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">Pending KYC Request Queue</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Shop & Seller Details</th>
                        <th>KTP / Identity Validation</th>
                        <th>Selfie Check</th>
                        <th>Request Date</th>
                        <th class="text-end pe-4">KYC Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning bg-opacity-25 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">Barokah Secondhand Store</h6>
                                    <small class="text-muted">Seller: Ahmad Zaki (zaki@email.com)</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary text-white py-2 px-3" style="cursor: pointer;"><i class="fas fa-image me-1"></i> View KTP Card</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary text-white py-2 px-3" style="cursor: pointer;"><i class="fas fa-portrait me-1"></i> View Selfie Photo</span>
                        </td>
                        <td class="text-muted">18 May 2026</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-success me-1"><i class="fas fa-check"></i> Approve & Verify</button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Reject</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
