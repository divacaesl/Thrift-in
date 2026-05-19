@extends('layouts.buyer')

@section('title', 'Dashboard Pembeli - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp

<div class="row g-4">
    <!-- User summary card -->
    <div class="col-lg-3">
        <div class="card p-4 border-0 shadow-sm text-center mb-4" style="border-radius: 16px;">
            <div class="position-relative d-inline-block mx-auto mb-3">
                <img src="{{ asset('uploads/profiles/' . auth()->user()->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/person-circle.svg'" class="rounded-circle border" alt="Profile avatar" style="width: 90px; height: 90px; object-fit: cover;">
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ auth()->user()->nama }}</h5>
            <span class="badge bg-light text-muted border mb-3">{{ auth()->user()->email }}</span>
            
            <a href="{{ route('buyer.profile') }}" class="btn btn-outline-primary btn-sm w-100 py-2" style="border-radius: 10px;">
                <i class="fas fa-user-pen me-2"></i>{{ $lang == 'en' ? 'Edit Profile' : 'Edit Profil' }}
            </a>
        </div>

        <!-- Sidebar Navigation Shortcuts -->
        <div class="card border-0 shadow-sm p-2" style="border-radius: 16px;">
            <div class="list-group list-group-flush small" id="dashboardTabs" role="tablist">
                <button class="list-group-item list-group-item-action active border-0 py-3 fw-bold rounded" data-bs-toggle="tab" data-bs-target="#purchases-pane" type="button" role="tab">
                    <i class="fas fa-bag-shopping me-3 text-primary"></i>{{ $lang == 'en' ? 'Purchases History' : 'Riwayat Pembelian' }}
                </button>
                <button class="list-group-item list-group-item-action border-0 py-3 fw-bold rounded" data-bs-toggle="tab" data-bs-target="#wishlist-pane" type="button" role="tab" id="wishlist-tab-shortcut">
                    <i class="fas fa-heart me-3 text-primary"></i>Wishlist / Favorit
                </button>
                <button class="list-group-item list-group-item-action border-0 py-3 fw-bold rounded" data-bs-toggle="tab" data-bs-target="#vouchers-pane" type="button" role="tab">
                    <i class="fas fa-ticket-alt me-3 text-primary"></i>Voucher & Promo
                </button>
                <button class="list-group-item list-group-item-action border-0 py-3 fw-bold rounded" data-bs-toggle="tab" data-bs-target="#notifs-pane" type="button" role="tab" id="notifications-tab-shortcut">
                    <i class="fas fa-bell me-3 text-primary"></i>{{ $lang == 'en' ? 'Activity Log' : 'Log Notifikasi' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Right Content Area -->
    <div class="col-lg-9">
        <div class="tab-content" id="dashboardTabsContent">
            
            <!-- Purchases Pane -->
            <div class="tab-pane fade show active" id="purchases-pane" role="tabpanel">
                <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                    <h5 class="fw-bold text-dark mb-4">{{ $lang == 'en' ? 'My Purchases' : 'Daftar Pembelian Saya' }}</h5>
                    
                    @if($purchases->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bag-shopping fs-1 mb-3"></i>
                            <h6 class="fw-bold">{{ $lang == 'en' ? 'No transactions yet' : 'Belum ada transaksi pembelian' }}</h6>
                            <p class="small mb-3">{{ $lang == 'en' ? 'Buy some products and track them here.' : 'Silakan cari barang preloved yang Anda minati di beranda.' }}</p>
                            <a href="{{ url('/') }}" class="btn btn-primary btn-sm px-4" style="border-radius: 10px;">{{ $lang == 'en' ? 'Shop Now' : 'Mulai Belanja' }}</a>
                        </div>
                    @else
                        @foreach($purchases as $p)
                            <div class="p-3 border rounded-4 mb-4 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-dark fw-bold">{{ $p->kode_transaksi }}</span>
                                        <span class="text-muted small">{{ $p->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <!-- Order status badges -->
                                    <span class="badge @if($p->status_pesanan == 'menunggu_pembayaran') bg-danger @elseif($p->status_pesanan == 'diproses') bg-warning text-dark @elseif($p->status_pesanan == 'dikirim') bg-info @elseif($p->status_pesanan == 'sampai') bg-success @else bg-secondary @endif" style="font-size: 0.85rem;">
                                        @if($p->status_pesanan == 'menunggu_pembayaran') Menunggu Pembayaran
                                        @elseif($p->status_pesanan == 'diproses') Pembayaran Terverifikasi / Diproses
                                        @elseif($p->status_pesanan == 'dikirim') Paket Sedang Dikirim
                                        @elseif($p->status_pesanan == 'sampai') Selesai / Diterima
                                        @elseif($p->status_pesanan == 'refund') Refund / Komplain CS
                                        @else {{ $p->status_pesanan }} @endif
                                    </span>
                                </div>

                                <div class="row align-items-center g-3">
                                    <div class="col-3 col-md-2 text-center">
                                        <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=150" class="img-fluid rounded border" alt="{{ $p->barang->nama_barang }}" style="height: 60px; object-fit: cover;">
                                    </div>
                                    <div class="col-9 col-md-6">
                                        <span class="text-primary small fw-bold text-uppercase" style="font-size: 0.75rem;">{{ $p->barang->brand ?: 'Preloved' }}</span>
                                        <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $p->barang->nama_barang }}</h6>
                                        <span class="text-muted small d-block">{{ $lang == 'en' ? 'Seller:' : 'Penjual:' }} {{ $p->barang->penitip->nama }}</span>
                                        <span class="text-muted small d-block">{{ $lang == 'en' ? 'Total Paid:' : 'Total Bayar:' }} <strong>Rp {{ number_format($p->harga_jual + $p->ongkir, 0, ',', '.') }}</strong></span>
                                    </div>
                                    <div class="col-12 col-md-4 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                                        @if($p->status_pesanan == 'menunggu_pembayaran')
                                            <!-- Upload proof link -->
                                            <a href="{{ route('buyer.payment.confirm', $p->id) }}" class="btn btn-sm btn-danger w-100">
                                                {{ $lang == 'en' ? 'Upload Receipt' : 'Upload Bukti Transfer' }}
                                            </a>
                                        @elseif($p->status_pesanan == 'dikirim')
                                            <!-- Tracking number and Confirm receipt button -->
                                            <div class="w-100 text-start text-md-end mb-2">
                                                <span class="small text-muted d-block">Resi {{ $p->ekspedisi }}:</span>
                                                <strong class="text-dark small">{{ $p->no_resi }}</strong>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary w-100 mb-1" data-bs-toggle="modal" data-bs-target="#trackingModal_{{ $p->id }}">
                                                <i class="fas fa-truck-fast me-1"></i>{{ $lang == 'en' ? 'Track Shipment' : 'Lacak Pengiriman' }}
                                            </button>
                                            <form action="{{ route('buyer.receipt.confirm', $p->id) }}" method="POST" class="w-100">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Apakah barang telah Anda terima dengan baik? Dana escrow akan diteruskan ke seller.')">
                                                    {{ $lang == 'en' ? 'Confirm Receipt' : 'Selesai / Barang Diterima' }}
                                                </button>
                                            </form>
                                        @elseif($p->status_pesanan == 'sampai')
                                            <!-- Written review or write review -->
                                            @php
                                                $reviewed = \App\Models\Ulasan::where('user_id', auth()->id())->where('barang_id', $p->barang_id)->exists();
                                            @endphp
                                            @if(!$reviewed)
                                                <a href="{{ route('buyer.review.create', $p->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                                    <i class="far fa-star me-1"></i>{{ $lang == 'en' ? 'Write Review' : 'Tulis Ulasan' }}
                                                </a>
                                            @else
                                                <span class="text-success small fw-semibold d-block w-100"><i class="fas fa-check-double me-1"></i>{{ $lang == 'en' ? 'Reviewed' : 'Ulasan Telah Dikirim' }}</span>
                                            @endif
                                        @endif
                                        
                                        <!-- Simulation controls to progress delivery statuses -->
                                        @if(in_array($p->status_pesanan, ['diproses', 'dikemas', 'dikirim']))
                                            <div class="mt-2 w-100 p-2 border rounded bg-white text-center">
                                                <span class="small text-muted d-block mb-1 fw-bold">SIMULATION TOOL:</span>
                                                @if($p->status_pesanan == 'diproses')
                                                    <a href="{{ url('/buyer/simulate-shipment/'.$p->id.'/dikemas') }}" class="btn btn-xs btn-outline-warning py-0" style="font-size:0.75rem;">Set Dikemas</a>
                                                @elseif($p->status_pesanan == 'dikemas')
                                                    <a href="{{ url('/buyer/simulate-shipment/'.$p->id.'/dikirim') }}" class="btn btn-xs btn-outline-info py-0" style="font-size:0.75rem;">Set Dikirim (Resi)</a>
                                                @elseif($p->status_pesanan == 'dikirim')
                                                    <a href="{{ url('/buyer/simulate-shipment/'.$p->id.'/sampai') }}" class="btn btn-xs btn-outline-success py-0" style="font-size:0.75rem;">Set Sampai (Tujuan)</a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Tracking Modal -->
                                <div class="modal fade" id="trackingModal_{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 16px;">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-truck-fast me-2 text-primary"></i>Detail Pelacakan Resi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-4">
                                                <div class="mb-3 border-bottom pb-2">
                                                    <span class="text-muted small">Ekspedisi:</span>
                                                    <h6 class="fw-bold mb-0 text-dark">{{ $p->ekspedisi }} - {{ $p->no_resi }}</h6>
                                                </div>
                                                
                                                <!-- Vertical Timeline -->
                                                <div class="position-relative ps-4 border-start ms-2 py-1">
                                                    <div class="mb-3 position-relative">
                                                        <div class="position-absolute bg-primary rounded-circle" style="width: 10px; height: 10px; left: -26px; top: 6px;"></div>
                                                        <strong class="small text-dark d-block">Paket Sedang Dikirim ke Kota Tujuan (Kurir Transit)</strong>
                                                        <span class="text-muted small" style="font-size:0.75rem;">Hari ini, 08:30 WIB</span>
                                                    </div>
                                                    <div class="mb-3 position-relative">
                                                        <div class="position-absolute bg-secondary rounded-circle" style="width: 10px; height: 10px; left: -26px; top: 6px;"></div>
                                                        <strong class="small text-muted d-block">Paket Diterima di Sortation Hub Surabaya</strong>
                                                        <span class="text-muted small" style="font-size:0.75rem;">Kemarin, 14:15 WIB</span>
                                                    </div>
                                                    <div class="position-relative">
                                                        <div class="position-absolute bg-secondary rounded-circle" style="width: 10px; height: 10px; left: -26px; top: 6px;"></div>
                                                        <strong class="small text-muted d-block">Paket Telah Diserahkan Penjual ke Kurir</strong>
                                                        <span class="text-muted small" style="font-size:0.75rem;">2 Hari Lalu, 10:00 WIB</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Wishlist Pane -->
            <div class="tab-pane fade" id="wishlist-pane" role="tabpanel">
                <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-heart text-danger me-2"></i>Barang Favorit Saya</h5>
                    
                    @if($wishlist->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="far fa-heart fs-1 mb-3"></i>
                            <h6 class="fw-bold">{{ $lang == 'en' ? 'Wishlist is empty' : 'Belum ada barang di wishlist' }}</h6>
                            <p class="small mb-3">{{ $lang == 'en' ? 'Add items you like from details page.' : 'Jelajahi produk di beranda dan klik tombol hati untuk menyimpannya di sini.' }}</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($wishlist as $wl)
                                <div class="col-md-4 col-6">
                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; position: relative;">
                                        <!-- Wishlist toggle -->
                                        <form action="{{ route('buyer.wishlist.toggle', $wl->barang_id) }}" method="POST" class="position-absolute" style="top: 8px; right: 8px; z-index: 10;">
                                            @csrf
                                            <button type="submit" class="btn btn-light rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center border-0" style="width: 32px; height: 32px;">
                                                <i class="fas fa-heart text-danger"></i>
                                            </button>
                                        </form>

                                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=200" class="card-img-top img-fluid" alt="{{ $wl->barang->nama_barang }}" style="height: 130px; object-fit: cover;">
                                        <div class="card-body p-2 text-center">
                                            <span class="text-primary small fw-bold text-uppercase d-block" style="font-size: 0.7rem;">{{ $wl->barang->brand ?: 'Preloved' }}</span>
                                            <h6 class="text-dark small text-truncate mb-1 fw-bold">{{ $wl->barang->nama_barang }}</h6>
                                            <span class="fw-bold text-dark small d-block">Rp {{ number_format($wl->barang->harga_jual, 0, ',', '.') }}</span>
                                            <a href="{{ route('buyer.detail', $wl->barang_id) }}" class="btn btn-primary btn-xs w-100 mt-2 py-1" style="font-size:0.75rem;">{{ $lang == 'en' ? 'Buy / Details' : 'Beli / Detail' }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vouchers Pane -->
            <div class="tab-pane fade" id="vouchers-pane" role="tabpanel">
                <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-ticket-alt text-warning me-2"></i>Kupon & Voucher Saya</h5>
                    <div class="row g-3">
                        @foreach($vouchers as $v)
                            <div class="col-md-6">
                                <div class="p-3 border border-warning rounded-4 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <strong class="text-warning d-block" style="font-size: 1.1rem;">Rp {{ number_format($v->diskon, 0, ',', '.') }}</strong>
                                        <span class="text-dark fw-bold small">KODE: {{ $v->kode_voucher }}</span>
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Min. Belanja: Rp {{ number_format($v->min_beli, 0, ',', '.') }}</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-warning text-dark py-1 px-3" onclick="navigator.clipboard.writeText('{{ $v->kode_voucher }}'); alert('Kode Voucher disalin!')">
                                        {{ $lang == 'en' ? 'Copy Code' : 'Salin Kode' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Notifications Pane -->
            <div class="tab-pane fade" id="notifs-pane" role="tabpanel">
                <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-bell text-primary me-2"></i>Aktivitas Terbaru</h5>
                        <form action="{{ route('buyer.notif.readall') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">{{ $lang == 'en' ? 'Clear Notifications' : 'Tandai Semua Dibaca' }}</button>
                        </form>
                    </div>

                    @if($notifications->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bell-slash fs-1 mb-3"></i>
                            <p class="small mb-0">{{ $lang == 'en' ? 'No new updates.' : 'Belum ada notifikasi atau pemberitahuan terbaru.' }}</p>
                        </div>
                    @else
                        @foreach($notifications as $n)
                            <div class="p-3 rounded border mb-2 d-flex justify-content-between align-items-start {{ !$n->is_read ? 'bg-light border-start border-primary border-4' : '' }}" onclick="markAsRead({{ $n->id }})">
                                <div>
                                    <strong class="text-dark small d-block">{{ $n->judul }}</strong>
                                    <p class="mb-0 text-muted small mt-1">{{ $n->pesan }}</p>
                                </div>
                                <span class="text-muted small" style="font-size:0.75rem;">{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handles tab jumping from page anchors
    window.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash;
        if (hash) {
            let triggerEl = null;
            if (hash === '#wishlist') {
                triggerEl = document.getElementById('wishlist-tab-shortcut');
            } else if (hash === '#notifications') {
                triggerEl = document.getElementById('notifications-tab-shortcut');
            }
            if (triggerEl) {
                bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            }
        }
    });
</script>
@endpush
@endsection
