@extends('layouts.super_admin')

@section('title', 'Broadcast Notifications - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Broadcast System & Notifications</h2>
            <p class="text-muted mb-0">Blast push notifications, marketing promos, and system warnings to targeted audiences via Email/SMS/In-App.</p>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: rgba(16, 185, 129, 0.1); color: #059669;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Composer -->
        <div class="col-md-5">
            <div class="card border-0">
                <div class="card-header"><h5 class="mb-0 text-white">Compose Message</h5></div>
                <div class="card-body">
                    <form action="{{ route('superadmin.communication.broadcast.send') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase">Broadcast Title</label>
                            <input type="text" name="title" class="form-control bg-dark border-secondary text-white" required placeholder="E.g., Weekend Flash Sale Alert!">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase">Channel</label>
                            <select name="channel" class="form-select bg-dark border-secondary text-white">
                                <option value="email">Email Campaign</option>
                                <option value="push">Web Push Notification</option>
                                <option value="in_app">In-App Banner Notification</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase">Target Audience</label>
                            <select name="target_audience" class="form-select bg-dark border-secondary text-white">
                                <option value="all">All Platform Users</option>
                                <option value="buyers">Buyers Only</option>
                                <option value="sellers">Sellers Only</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase">Message Content</label>
                            <textarea name="content" class="form-control bg-dark border-secondary text-white" rows="5" required placeholder="Compose message body..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i> Send Broadcast Queue</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- History -->
        <div class="col-md-7">
            <div class="card border-0">
                <div class="card-header"><h5 class="mb-0 text-white">Broadcast History & Status</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Campaign Title</th>
                                    <th>Target</th>
                                    <th>Channel</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <h6 class="mb-0 fw-bold text-white">Server Maintenance Scheduled</h6>
                                        <small class="text-muted">Sent today at 10:00 AM</small>
                                    </td>
                                    <td>All Users</td>
                                    <td><span class="badge bg-secondary text-white">In-App</span></td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-success bg-opacity-25 text-success">Sent (12.4k users)</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
