@extends('layouts.super_admin')

@section('title', 'Global Control Panel - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #F8FAFC;">Global Control Panel</h2>
            <p class="text-muted mb-0">Overview of ThriftIn ecosystem across all platforms.</p>
        </div>
        <div>
            <button class="btn btn-primary shadow-sm"><i class="fas fa-download me-2"></i> Export Global Report</button>
        </div>
    </div>

    <!-- Metrics Cards (Pilar 2) -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.02)); border-left: 4px solid var(--accent) !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total Users</p>
                            <h3 class="fw-bold mb-0 text-white">12,450</h3>
                        </div>
                        <div class="icon-wrap rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.2); color: var(--accent);">
                            <i class="fas fa-users fs-5"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-success" style="font-size: 0.85rem;">
                        <i class="fas fa-arrow-up me-1"></i> 15.3% <span class="text-muted ms-1">Since last month</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.02)); border-left: 4px solid #3B82F6 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Verified Sellers</p>
                            <h3 class="fw-bold mb-0 text-white">3,820</h3>
                        </div>
                        <div class="icon-wrap rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.2); color: #3B82F6;">
                            <i class="fas fa-store fs-5"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-success" style="font-size: 0.85rem;">
                        <i class="fas fa-arrow-up me-1"></i> 8.2% <span class="text-muted ms-1">Since last month</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.02)); border-left: 4px solid #F59E0B !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total Transactions</p>
                            <h3 class="fw-bold mb-0 text-white">45,912</h3>
                        </div>
                        <div class="icon-wrap rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.2); color: #F59E0B;">
                            <i class="fas fa-exchange-alt fs-5"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-warning" style="font-size: 0.85rem;">
                        <i class="fas fa-arrow-up me-1"></i> 2.4% <span class="text-muted ms-1">Since last month</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.02)); border-left: 4px solid #8B5CF6 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Platform Revenue</p>
                            <h3 class="fw-bold mb-0 text-white">Rp 1.4B</h3>
                        </div>
                        <div class="icon-wrap rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(139, 92, 246, 0.2); color: #8B5CF6;">
                            <i class="fas fa-wallet fs-5"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-success" style="font-size: 0.85rem;">
                        <i class="fas fa-arrow-up me-1"></i> 22.5% <span class="text-muted ms-1">Since last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Server Monitor (Pilar 11, 15) -->
    <div class="row g-4 mb-4">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white">Revenue & Sales Analytics</h6>
                    <select class="form-select form-select-sm" style="width: 120px; background-color: var(--bg-body); color: var(--text-dark); border-color: var(--border-color);">
                        <option>This Year</option>
                        <option>Last Year</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- System Health -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0 text-white">System Health Monitor</h6>
                </div>
                <div class="card-body">
                    <!-- CPU -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">CPU Usage (Web Server)</span>
                            <span class="text-white small fw-bold">45%</span>
                        </div>
                        <div class="progress bg-dark" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 45%;"></div>
                        </div>
                    </div>
                    <!-- RAM -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Memory Usage</span>
                            <span class="text-white small fw-bold">68%</span>
                        </div>
                        <div class="progress bg-dark" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 68%;"></div>
                        </div>
                    </div>
                    <!-- Database -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Database Storage</span>
                            <span class="text-white small fw-bold">1.2 TB / 2 TB</span>
                        </div>
                        <div class="progress bg-dark" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 60%;"></div>
                        </div>
                    </div>
                    <!-- Anti Fraud -->
                    <div class="p-3 mt-4 rounded" style="background: rgba(239, 68, 68, 0.05); border: 1px dashed rgba(239, 68, 68, 0.3);">
                        <h6 class="text-danger mb-2"><i class="fas fa-shield-virus me-2"></i>Anti-Fraud Alert</h6>
                        <p class="text-muted small mb-0">Detected 12 suspicious transactions from IP block 192.168.x.x in the last hour.</p>
                        <a href="#" class="text-danger small fw-bold text-decoration-none mt-2 d-inline-block">Investigate Now &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin & Platform Overview -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white">Active Super Admins & Moderators</h6>
                    <a href="{{ route('superadmin.admins.index') }}" class="btn btn-sm btn-outline-light">Manage</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Admin Name</th>
                                    <th>Role</th>
                                    <th>Last Login</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-primary bg-opacity-25 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">SD</div>
                                            <span class="text-white">System Developer</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50">Super Admin</span></td>
                                    <td class="text-muted">Just now</td>
                                    <td class="text-end pe-4"><span class="badge-soft-success px-2 py-1">Active</span></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-success bg-opacity-25 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">AK</div>
                                            <span class="text-white">Admin Keuangan 1</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">Finance</span></td>
                                    <td class="text-muted">2 hours ago</td>
                                    <td class="text-end pe-4"><span class="badge-soft-success px-2 py-1">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white">Cross-Platform Traffic (Realtime)</h6>
                    <span class="badge bg-success shadow-sm" style="animation: pulse 2s infinite;"><i class="fas fa-circle me-1" style="font-size: 6px;"></i> LIVE</span>
                </div>
                <div class="card-body">
                    <div class="row text-center h-100 align-items-center">
                        <div class="col-4 border-end border-secondary border-opacity-25">
                            <i class="fab fa-chrome fs-1 text-muted mb-3"></i>
                            <h4 class="text-white fw-bold mb-1">5,420</h4>
                            <span class="text-muted small text-uppercase">Web App</span>
                        </div>
                        <div class="col-4 border-end border-secondary border-opacity-25">
                            <i class="fab fa-android fs-1 text-success mb-3"></i>
                            <h4 class="text-white fw-bold mb-1">12,980</h4>
                            <span class="text-muted small text-uppercase">Android Native</span>
                        </div>
                        <div class="col-4">
                            <i class="fab fa-apple fs-1 text-light mb-3"></i>
                            <h4 class="text-white fw-bold mb-1">8,340</h4>
                            <span class="text-muted small text-uppercase">iOS App</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endsection

@push('scripts')
<script>
    // Mock Data for Analytics Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Gross Volume (IDR Billions)',
                        data: [0.8, 0.9, 1.2, 1.1, 1.4, 1.6, 1.5, 1.8, 1.7, 2.1, 2.4, 2.8],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#10B981',
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Platform Revenue (IDR Billions)',
                        data: [0.08, 0.09, 0.12, 0.11, 0.14, 0.16, 0.15, 0.18, 0.17, 0.21, 0.24, 0.28],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#94A3B8' } }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#94A3B8' }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#94A3B8' }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
