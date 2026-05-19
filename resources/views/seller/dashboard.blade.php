@extends('layouts.seller')

@section('title', 'Seller Dashboard - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="row g-4 mb-4">
    <!-- Total Products -->
    <div class="col-md-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary fs-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1 small">{{ $lang == 'en' ? 'Total Products' : 'Total Produk' }}</h6>
                <h4 class="mb-0 fw-bold">{{ $totalProduk }}</h4>
            </div>
        </div>
    </div>
    <!-- Total Sales -->
    <div class="col-md-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success fs-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-hand-holding-dollar"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1 small">{{ $lang == 'en' ? 'Net Revenue' : 'Pendapatan Bersih' }}</h6>
                <h4 class="mb-0 fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <!-- Orders Placed -->
    <div class="col-md-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning fs-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-cart-shopping"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1 small">{{ $lang == 'en' ? 'Total Orders' : 'Jumlah Pesanan' }}</h6>
                <h4 class="mb-0 fw-bold">{{ $jumlahPesananCount }}</h4>
            </div>
        </div>
    </div>
    <!-- Products Sold -->
    <div class="col-md-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info fs-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-circle-check"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1 small">{{ $lang == 'en' ? 'Products Sold' : 'Produk Terjual' }}</h6>
                <h4 class="mb-0 fw-bold">{{ $produkTerjual }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Sales chart -->
    <div class="col-lg-8">
        <div class="card p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-chart-line text-primary me-2"></i>{{ $lang == 'en' ? 'Revenue Chart (Last 7 Days)' : 'Grafik Pendapatan (7 Hari Terakhir)' }}</h5>
            <canvas id="salesChart" style="max-height: 300px;"></canvas>
        </div>
    </div>
    
    <!-- Store Followers & Wishlists (Section 12) -->
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="fas fa-heart text-danger me-2"></i>{{ $lang == 'en' ? 'Engagement Stats' : 'Statistik Interaksi' }}</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                    <div>
                        <h6 class="mb-0 fw-bold"><i class="fas fa-users text-primary me-2"></i>{{ $lang == 'en' ? 'Shop Followers' : 'Pengikut Toko' }}</h6>
                        <small class="text-muted">{{ $lang == 'en' ? 'Total users following your shop' : 'Jumlah pembeli setia yang mengikuti toko' }}</small>
                    </div>
                    <span class="badge bg-primary rounded-pill fs-6">{{ $followersCount }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                    <div>
                        <h6 class="mb-0 fw-bold"><i class="fas fa-heart text-danger me-2"></i>{{ $lang == 'en' ? 'Product Likes' : 'Jumlah Produk Favorit' }}</h6>
                        <small class="text-muted">{{ $lang == 'en' ? 'Total times items were wishlisted' : 'Total produk Anda dimasukkan ke wishlist' }}</small>
                    </div>
                    <span class="badge bg-danger rounded-pill fs-6">{{ $wishlistsCount }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Recent Orders table -->
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="fas fa-receipt text-warning me-2"></i>{{ $lang == 'en' ? 'Recent Active Orders' : 'Pesanan Masuk Terbaru' }}</h5>
        <a href="{{ route('seller.order.index') }}" class="btn btn-sm btn-outline-primary">{{ $lang == 'en' ? 'View All Orders' : 'Lihat Semua Pesanan' }}</a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ $lang == 'en' ? 'Order ID' : 'Kode Transaksi' }}</th>
                    <th>{{ $lang == 'en' ? 'Product' : 'Nama Produk' }}</th>
                    <th>{{ $lang == 'en' ? 'Buyer' : 'Nama Pembeli' }}</th>
                    <th>{{ $lang == 'en' ? 'Total Bill' : 'Total Bayar' }}</th>
                    <th>{{ $lang == 'en' ? 'Order Status' : 'Status Pesanan' }}</th>
                    <th>{{ $lang == 'en' ? 'Date' : 'Tanggal' }}</th>
                </tr>
            </thead>
            <tbody>
                @if($recentOrders->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-basket-shopping fs-3 mb-2"></i>
                            <p class="mb-0 small">{{ $lang == 'en' ? 'No recent orders yet.' : 'Belum ada pesanan masuk.' }}</p>
                        </td>
                    </tr>
                @else
                    @foreach($recentOrders as $order)
                        <tr>
                            <td><span class="fw-bold text-primary">{{ $order->kode_transaksi }}</span></td>
                            <td>{{ $order->barang->nama_barang ?? 'Barang Dihapus' }}</td>
                            <td>{{ $order->nama_pembeli }}</td>
                            <td>Rp {{ number_format($order->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badge = 'bg-secondary';
                                    if ($order->status_pesanan == 'menunggu_pembayaran') $badge = 'bg-warning text-dark';
                                    elseif ($order->status_pesanan == 'diproses') $badge = 'bg-info text-dark';
                                    elseif ($order->status_pesanan == 'dikirim') $badge = 'bg-primary';
                                    elseif ($order->status_pesanan == 'sampai') $badge = 'bg-success';
                                    elseif ($order->status_pesanan == 'refund') $badge = 'bg-danger';
                                @endphp
                                <span class="badge {{ $badge }}">{{ strtoupper(str_replace('_', ' ', $order->status_pesanan)) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels'] ?? []) !!},
                datasets: [{
                    label: '{{ $lang == "en" ? "Net Earnings (Rp)" : "Pendapatan Bersih (Rp)" }}',
                    data: {!! json_encode($chartData['values'] ?? []) !!},
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endpush
