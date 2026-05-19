@extends('layouts.admin')

@section('title', 'Activity Logs - Admin')

@section('content')
<div class="card p-4">
    <h5 class="fw-bold mb-4"><i class="fas fa-server text-secondary me-2"></i> System Activity Logs</h5>
    
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle" style="font-size: 0.85rem;">
            <thead class="table-light">
                <tr>
                    <th>WAKTU</th>
                    <th>ADMIN</th>
                    <th>AKSI</th>
                    <th>DESKRIPSI</th>
                    <th>IP ADDRESS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-muted">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    <td class="fw-bold">{{ $log->admin->nama ?? 'Unknown' }}</td>
                    <td><span class="badge bg-secondary">{{ $log->action_type }}</span></td>
                    <td>{{ $log->description }}</td>
                    <td class="font-monospace text-muted">{{ $log->ip_address ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada aktivitas terekam.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
