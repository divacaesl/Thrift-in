@extends('layouts.super_admin')

@section('title', 'Analytics & BI - Super Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-dark);">Business Intelligence & Platform Performance</h2>
            <p class="text-muted mb-0">Evaluate growth rates, top categories, monthly GMV, user acquisition funnels, and retention levels.</p>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 mb-4">
                <div class="card-header"><h5 class="mb-0 text-white">Monthly GMV Growth History</h5></div>
                <div class="card-body">
                    <canvas id="gmvChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-header"><h5 class="mb-0 text-white">Top Sales Categories</h5></div>
                <div class="card-body">
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // GMV Chart
        const gmvCtx = document.getElementById('gmvChart');
        if (gmvCtx) {
            new Chart(gmvCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Gross Merchandise Volume (IDR Millions)',
                        data: [450, 520, 610, 580, 720, 890],
                        backgroundColor: '#4F46E5',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#94A3B8' } }
                    },
                    scales: {
                        y: { ticks: { color: '#94A3B8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                        x: { ticks: { color: '#94A3B8' }, grid: { display: false } }
                    }
                }
            });
        }

        // Category Chart
        const catCtx = document.getElementById('categoryChart');
        if (catCtx) {
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Jackets', 'Shirts', 'Shoes', 'Pants'],
                    datasets: [{
                        data: [40, 25, 20, 15],
                        backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#94A3B8' } }
                    }
                }
            });
        }
    });
</script>
@endpush
