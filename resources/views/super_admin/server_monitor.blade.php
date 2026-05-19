@extends('layouts.super_admin')

@section('title', 'Server Monitor & System - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Server Monitor & System Controls</h2>
            <p class="text-muted mb-0">Review live container resources, trigger database backup/restore cycles, and switch on maintenance modes.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Live resources -->
        <div class="col-md-8">
            <div class="card border-0 mb-4">
                <div class="card-header"><h5 class="mb-0 text-white">Live System Hardware Monitoring</h5></div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <span class="text-muted small">CPU core load</span>
                            <h3 class="text-success fw-bold mt-1">45%</h3>
                        </div>
                        <div class="col-4 border-start border-secondary border-opacity-25">
                            <span class="text-muted small">RAM used</span>
                            <h3 class="text-warning fw-bold mt-1">5.4 GB / 8 GB</h3>
                        </div>
                        <div class="col-4 border-start border-secondary border-opacity-25">
                            <span class="text-muted small">Disk I/O rate</span>
                            <h3 class="text-success fw-bold mt-1">12 MB/s</h3>
                        </div>
                    </div>
                    <hr class="border-secondary border-opacity-25 mb-4">
                    <h6>Realtime CPU load history</h6>
                    <div style="height: 150px; background-color: rgba(255,255,255,0.01); border-radius: 8px;" class="d-flex align-items-end justify-content-between p-2">
                        <div style="width: 10px; height: 30%; background-color: var(--accent);"></div>
                        <div style="width: 10px; height: 35%; background-color: var(--accent);"></div>
                        <div style="width: 10px; height: 42%; background-color: var(--accent);"></div>
                        <div style="width: 10px; height: 45%; background-color: var(--accent);"></div>
                        <div style="width: 10px; height: 38%; background-color: var(--accent);"></div>
                        <div style="width: 10px; height: 50%; background-color: var(--accent);"></div>
                        <div style="width: 10px; height: 55%; background-color: var(--accent);"></div>
                        <div style="width: 10px; height: 45%; background-color: var(--accent);"></div>
                    </div>
                </div>
            </div>

            <!-- Backups -->
            <div class="card border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Database Backup & Recovery Logs</h5>
                    <button class="btn btn-sm btn-primary"><i class="fas fa-database me-1"></i> Trigger Instant Backup</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Backup Filename</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4 text-white">thriftin-backup-2026-05-19.sql</td>
                                    <td>125.4 MB</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">Successful</span></td>
                                    <td class="text-muted">Today, 03:00 AM</td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-light"><i class="fas fa-trash"></i></button>
                                        <button class="btn btn-sm btn-outline-success"><i class="fas fa-clock-rotate-left"></i> Restore</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance panel -->
        <div class="col-md-4">
            <div class="card border-0 mb-4" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.02)); border-left: 4px solid #F59E0B !important;">
                <div class="card-body p-4">
                    <h5 class="text-warning mb-2"><i class="fas fa-triangle-exclamation me-2"></i> Maintenance System</h5>
                    <p class="text-muted small">Turning on maintenance mode locks all buyer storefronts and seller dashboards. Only Super Admin and developers can bypass.</p>
                    <div class="form-check form-switch mt-3 mb-3">
                        <input class="form-check-input" type="checkbox" id="maintSwitch">
                        <label class="form-check-label text-white small" for="maintSwitch">ENABLE MAINTENANCE MODE</label>
                    </div>
                    <button class="btn btn-warning btn-sm w-100">Apply System Blockage</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
