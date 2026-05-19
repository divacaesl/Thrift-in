@extends('layouts.admin')

@section('title', 'Banners & Promos - Admin')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-image text-primary me-2"></i> Banners Homepage</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBannerModal"><i class="fas fa-plus me-1"></i> Tambah Banner</button>
    </div>

    <div class="row">
        @forelse($banners as $banner)
        <div class="col-md-4 mb-4">
            <div class="card border shadow-sm">
                <img src="https://via.placeholder.com/600x300?text=Banner+{{ $banner->id }}" class="card-img-top" alt="Banner" style="height: 150px; object-fit: cover;">
                <div class="card-body">
                    <h6 class="fw-bold">{{ $banner->judul }}</h6>
                    <p class="small text-muted mb-3 text-truncate">{{ $banner->link_url ?? 'Tidak ada link' }}</p>
                    <form action="{{ route('admin.content.banners.destroy', $banner->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Hapus banner ini?');">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">Belum ada banner promo.</div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addBannerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('admin.content.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <h6 class="fw-bold mb-3">Upload Banner Baru</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Judul Banner</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">File Gambar (Simulasi)</label>
                            <!-- Note: Because this is a simulated demo, file will be ignored in controller logic but validation requires it -->
                            <input type="file" name="gambar_file" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Link URL (Opsional)</label>
                            <input type="url" name="link_url" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
