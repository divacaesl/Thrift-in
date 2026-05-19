@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - Admin')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-users-gear text-primary me-2"></i> Daftar Pengguna Platform</h5>
        
        <div class="d-flex gap-2">
            <!-- Filter Role -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                <select name="role" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="pembeli" {{ request('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                    <option value="penjual" {{ request('role') == 'penjual' ? 'selected' : '' }}>Penjual</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <div class="input-group input-group-sm w-auto">
                    <input type="text" name="search" class="form-control" placeholder="Cari email / nama..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th>USER INFO</th>
                    <th>ROLE</th>
                    <th>STATUS</th>
                    <th>LAST LOGIN</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('uploads/profiles/' . $user->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/person-circle.svg'" alt="profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->nama }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleBadge = 'bg-secondary';
                                if($user->role == 'pembeli') $roleBadge = 'bg-info text-dark';
                                elseif($user->role == 'penjual') $roleBadge = 'bg-success';
                                elseif(in_array($user->role, ['super_admin', 'admin', 'admin_produk', 'admin_keuangan', 'cs'])) $roleBadge = 'bg-primary';
                            @endphp
                            <span class="badge {{ $roleBadge }} text-uppercase">{{ str_replace('_', ' ', $user->role) }}</span>
                        </td>
                        <td>
                            @if($user->status == 'aktif')
                                <span class="badge badge-soft-success"><i class="fas fa-circle-check me-1"></i> Aktif</span>
                            @elseif($user->status == 'suspended')
                                <span class="badge badge-soft-danger" title="{{ $user->suspend_reason }}"><i class="fas fa-ban me-1"></i> Suspended</span>
                            @else
                                <span class="badge badge-soft-warning">{{ $user->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="small text-dark">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y, H:i') : '-' }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">IP: {{ $user->last_login_ip ?? '-' }}</div>
                        </td>
                        <td class="text-center">
                            @if($user->status == 'aktif')
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $user->id }}">Suspend</button>
                            @elseif($user->status == 'suspended')
                                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Aktifkan kembali akun ini?');">Activate</button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Suspend Modal -->
                    <div class="modal fade" id="suspendModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h6 class="fw-bold"><i class="fas fa-user-slash text-danger me-2"></i> Suspend Akun: {{ $user->email }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <p class="small text-muted mb-3">Akun yang disuspend tidak akan bisa login ke dalam platform. Pastikan Anda memiliki bukti yang cukup untuk memblokir akun ini.</p>
                                        <label class="form-label small fw-bold">Alasan Suspend / Banned</label>
                                        <textarea name="suspend_reason" rows="3" class="form-control" placeholder="Contoh: Terindikasi penipuan transaksi palsu..." required></textarea>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger btn-sm px-4">Blokir Akun</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Tidak ada data pengguna ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
