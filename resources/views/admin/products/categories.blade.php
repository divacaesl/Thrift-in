@extends('layouts.admin')

@section('title', 'Kategori Produk - Admin')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-tags text-primary me-2"></i> Manajemen Kategori Produk</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fas fa-plus me-1"></i> Tambah Kategori</button>
    </div>

    <div class="row">
        @foreach($categories as $cat)
        <div class="col-md-3 mb-3">
            <div class="border rounded p-3 d-flex justify-content-between align-items-center bg-light">
                <span class="fw-bold text-dark">{{ $cat->nama_kategori }}</span>
                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm text-danger border-0 p-0" onclick="return confirm('Hapus kategori ini? Semua produk terkait mungkin akan bermasalah.');"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-bold">Tambah Kategori Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <label class="form-label small fw-bold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Otomotif" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
