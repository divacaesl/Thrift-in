@extends('layouts.super_admin')

@section('title', 'Manage Admins - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Manage Admins & Staff</h2>
            <p class="text-muted mb-0">Create, edit, suspend, and configure permissions for platform staff.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <i class="fas fa-user-plus me-2"></i> Add New Admin
        </button>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: rgba(16, 185, 129, 0.1); color: #059669;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Admin List -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">Active System Admins</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Role & Access Control</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-25 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                    AK
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">Andi Keuangan</h6>
                                    <small class="text-muted">andi.keu@thriftin.com</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">Admin Keuangan</span>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">Active</span>
                        </td>
                        <td class="text-muted">2 hours ago (192.168.1.10)</td>
                        <td class="text-end pe-4">
                            <form action="{{ route('superadmin.admins.suspend', 1) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger me-2"><i class="fas fa-ban me-1"></i> Suspend</button>
                            </form>
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-edit"></i> Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-25 text-success rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                    BP
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">Budi Produk</h6>
                                    <small class="text-muted">budi.prod@thriftin.com</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50">Admin Produk</span>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">Active</span>
                        </td>
                        <td class="text-muted">Yesterday</td>
                        <td class="text-end pe-4">
                            <form action="{{ route('superadmin.admins.suspend', 2) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger me-2"><i class="fas fa-ban me-1"></i> Suspend</button>
                            </form>
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-edit"></i> Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="background-color: var(--bg-card); color: var(--text-dark);">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title fw-bold text-white">Add New Platform Staff</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.admins.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Full Name</label>
                        <input type="text" name="nama" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Username</label>
                        <input type="text" name="username" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Email Address</label>
                        <input type="email" name="email" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Password</label>
                        <input type="password" name="password" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Assigned Role</label>
                        <select name="role" class="form-select bg-dark border-secondary text-white" required>
                            <option value="admin_produk">Admin Produk (Product Catalog Moderation)</option>
                            <option value="admin_keuangan">Admin Keuangan (Payouts & Fees)</option>
                            <option value="cs">Customer Service (Resolution & Chat Help)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 py-3">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Staff Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
