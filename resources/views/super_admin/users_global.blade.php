@extends('layouts.super_admin')

@section('title', 'Global Users - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Global Users Directory</h2>
            <p class="text-muted mb-0">Search, verify, suspend, ban, or reset passwords for any account in the network.</p>
        </div>
    </div>

    <!-- Stats summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card p-3 border-0 bg-opacity-25" style="background-color: rgba(79, 70, 229, 0.05);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small text-uppercase">Total Buyers</span>
                        <h4 class="text-white fw-bold mb-0">8,630</h4>
                    </div>
                    <i class="fas fa-shopping-bag text-indigo fs-3" style="color: #4F46E5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 border-0 bg-opacity-25" style="background-color: rgba(16, 185, 129, 0.05);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small text-uppercase">Total Sellers</span>
                        <h4 class="text-white fw-bold mb-0">3,820</h4>
                    </div>
                    <i class="fas fa-store text-success fs-3"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 border-0 bg-opacity-25" style="background-color: rgba(239, 68, 68, 0.05);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small text-uppercase">Suspended Accounts</span>
                        <h4 class="text-white fw-bold mb-0">15</h4>
                    </div>
                    <i class="fas fa-user-slash text-danger fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">All Platform Accounts</h5>
            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="Search accounts..." style="width: 250px;">
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Date Registered</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; color: #fff;">
                                    B
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">Budi Santoso</h6>
                                    <small class="text-muted">budi@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary text-white">Pembeli</span></td>
                        <td><span class="badge bg-success bg-opacity-25 text-success">Aktif</span></td>
                        <td class="text-muted">12 May 2026</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-danger me-1"><i class="fas fa-ban"></i> Suspend</button>
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-key"></i> Reset Pass</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; color: #fff;">
                                    S
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">Sari Dewi Boutique</h6>
                                    <small class="text-muted">seller@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-success text-white">Penjual</span></td>
                        <td><span class="badge bg-success bg-opacity-25 text-success">Aktif</span></td>
                        <td class="text-muted">10 May 2026</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-danger me-1"><i class="fas fa-ban"></i> Suspend</button>
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-key"></i> Reset Pass</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
