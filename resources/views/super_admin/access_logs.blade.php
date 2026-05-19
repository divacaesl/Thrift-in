@extends('layouts.super_admin')

@section('title', 'Access & Security Logs - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Access & Security Audit Logs</h2>
            <p class="text-muted mb-0">Track admin login locations, trace configuration adjustments, and check suspicious IP movements.</p>
        </div>
    </div>

    <!-- Security Audit List -->
    <div class="card border-0">
        <div class="card-header border-0 bg-transparent py-3">
            <h5 class="mb-0 text-white">Audited Admin Activities</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Timestamp</th>
                        <th>User Account</th>
                        <th>IP Address</th>
                        <th>Action Logged</th>
                        <th class="text-end pe-4">System Severity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4 text-muted">19 May 2026, 15:30:12</td>
                        <td class="text-white fw-bold">God Mode SA (super@thriftin.com)</td>
                        <td>127.0.0.1</td>
                        <td>Accessed Super Admin Dashboard</td>
                        <td class="text-end pe-4">
                            <span class="badge bg-secondary text-white">Low</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4 text-muted">19 May 2026, 14:12:45</td>
                        <td class="text-white fw-bold">Andi Keuangan (andi.keu@thriftin.com)</td>
                        <td>192.168.1.10</td>
                        <td>Approved payout withdrawal #SEL-104</td>
                        <td class="text-end pe-4">
                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">Medium</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
