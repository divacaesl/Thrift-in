@extends('layouts.admin')

@section('title', 'Admin Dashboard - ThriftIn')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-1">Platform Overview</h4>
        <p class="text-muted">Welcome back. Here's what's happening on ThriftIn today.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted mb-2 small fw-bold">TOTAL PENGGUNA</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalUsers) }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 fs-5">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="mt-3 text-muted small">
                <span>{{ $totalBuyers }} Buyers</span> • <span>{{ $totalSellers }} Sellers</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted mb-2 small fw-bold">PRODUK AKTIF</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($activeProducts) }}</h3>
                </div>
                <div class="bg-info bg-opacity-10 text-info p-2 rounded-3 fs-5">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>
            <div class="mt-3 text-muted small">
                <span>{{ $soldProducts }} Terjual</span> • <span class="text-danger">{{ $flaggedProducts }} Moderasi</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted mb-2 small fw-bold">TOTAL TRANSAKSI</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalTransactions) }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-2 rounded-3 fs-5">
                    <i class="fas fa-cart-shopping"></i>
                </div>
            </div>
            <div class="mt-3 text-muted small">
                <span>{{ $pendingPayouts }} Payout Pending</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted mb-2 small fw-bold">ESTIMASI PENDAPATAN</h6>
                    <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($platformRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3 fs-5">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="mt-3 text-muted small">
                <span>Dari Platform Fee</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="card p-4 h-100">
            <h6 class="fw-bold mb-4"><i class="fas fa-chart-line text-accent me-2"></i> Grafik Penjualan (7 Hari Terakhir)</h6>
            <div style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <h6 class="fw-bold mb-4"><i class="fas fa-fire text-danger me-2"></i> Kategori Terpopuler</h6>
            <div class="list-group list-group-flush">
                @foreach($topCategories as $idx => $cat)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-bottom-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fw-bold text-muted">#{{ $idx + 1 }}</div>
                            <div class="fw-semibold text-dark">{{ $cat->nama_kategori }}</div>
                        </div>
                        <span class="badge bg-light text-dark border px-2 py-1">{{ $cat->total_sold }} Terjual</span>
                    </div>
                @endforeach
                @if($topCategories->isEmpty())
                    <div class="text-center text-muted small py-4">Belum ada data penjualan.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['labels'] ?? []) !!},
                datasets: [{
                    label: 'Volume Transaksi (Rp)',
                    data: {!! json_encode($chartData['values'] ?? []) !!},
                    backgroundColor: '#3B82F6',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });
    }
</script>
@endpush
