@extends('layouts.super_admin')

@section('title', 'API Management - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Multiplatform API Keys</h2>
            <p class="text-muted mb-0">Generate authentication tokens for iOS/Android native applications, affiliate widgets, and webhooks.</p>
        </div>
        <form action="{{ route('superadmin.api.generate') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Generate New API Key</button>
        </form>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: rgba(16, 185, 129, 0.1); color: #059669;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Active Keys -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">Active Access Tokens</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Application/Client Name</th>
                        <th>API Key Token</th>
                        <th>Environment</th>
                        <th>Created At</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <h6 class="mb-0 fw-bold text-white">ThriftIn Native Android App</h6>
                            <small class="text-muted">ID: #API-001</small>
                        </td>
                        <td>
                            <code>thriftin_live_a1b2c3d4e5f6g7h8i9j0</code>
                        </td>
                        <td><span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">Production</span></td>
                        <td class="text-muted">Today, 10:45 AM</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Revoke</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4">
                            <h6 class="mb-0 fw-bold text-white">ThriftIn Native iOS App</h6>
                            <small class="text-muted">ID: #API-002</small>
                        </td>
                        <td>
                            <code>thriftin_live_k1l2m3n4o5p6q7r8s9t0</code>
                        </td>
                        <td><span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">Production</span></td>
                        <td class="text-muted">Today, 11:12 AM</td>
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
