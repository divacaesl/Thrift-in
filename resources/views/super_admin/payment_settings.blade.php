@extends('layouts.super_admin')

@section('title', 'Payment Gateway Settings - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Payment Gateway Configurations</h2>
            <p class="text-muted mb-0">Manage credentials and activate/deactivate payment processors like Midtrans, Xendit, and QRIS.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Midtrans -->
        <div class="col-md-6">
            <div class="card h-100 border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white"><i class="fas fa-credit-card me-2 text-indigo"></i> Midtrans Gateway</h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Merchant ID</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white" value="M1029840283">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Server Key</label>
                        <input type="password" class="form-control bg-dark border-secondary text-white" value="Mid-server-SECRET_KEY_SAMPLE">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Client Key</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white" value="Mid-client-PUBLIC_KEY_SAMPLE">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Transaction Fee (Flat)</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white" value="Rp 4,000">
                    </div>
                    <button class="btn btn-primary mt-2">Save Midtrans Settings</button>
                </div>
            </div>
        </div>

        <!-- Xendit -->
        <div class="col-md-6">
            <div class="card h-100 border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white"><i class="fas fa-money-bill-transfer-vertical me-2 text-success"></i> Xendit (Alternative Escrow)</h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox">
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">API Secret Key</label>
                        <input type="password" class="form-control bg-dark border-secondary text-white" placeholder="Enter Xendit Secret Key...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Callback Verification Token</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white" placeholder="Enter Callback Token...">
                    </div>
                    <button class="btn btn-primary mt-4">Save Xendit Settings</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
