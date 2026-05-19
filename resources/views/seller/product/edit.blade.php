@extends('layouts.seller')

@section('title', 'Edit Produk - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="card p-4">
    <h5 class="fw-bold mb-4"><i class="fas fa-edit text-primary me-2"></i>{{ $lang == 'en' ? 'Edit Preloved Product' : 'Ubah Produk Preloved' }}</h5>

    @if($errors->any())
        <div class="alert alert-danger border-0 small mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('seller.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Left Pane -->
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Product Title' : 'Nama Produk / Judul' }}</label>
                        <input type="text" name="nama_barang" class="form-control" value="{{ $product->nama_barang }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-select" required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ $product->kategori_id == $c->id ? 'selected' : '' }}>{{ $c->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Brand / Merek</label>
                        <input type="text" name="brand" id="brand" class="form-control" value="{{ $product->brand }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Selling Price (IDR)' : 'Harga Jual (Rp)' }}</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual" id="harga_jual" class="form-control" value="{{ intval($product->harga_jual) }}" required>
                            <button type="button" class="btn btn-outline-secondary" id="btnAiPrice"><i class="fas fa-robot text-primary"></i> AI Price</button>
                        </div>
                        
                        <!-- AI Pricing Box -->
                        <div id="aiPriceBox" class="mt-2 p-3 bg-light rounded-3 border" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-primary small"><i class="fas fa-wand-magic-sparkles"></i> AI Price Recommendation</span>
                                <button type="button" class="btn-close" style="font-size: 0.75rem;" onclick="document.getElementById('aiPriceBox').style.display='none'"></button>
                            </div>
                            <p class="text-muted mb-2" style="font-size: 0.8rem;" id="aiMessage">Menganalisis produk serupa...</p>
                            <div class="d-flex gap-3 justify-content-between text-center border-top pt-2" style="font-size: 0.85rem;">
                                <div>
                                    <div class="text-muted small">Min</div>
                                    <div class="fw-semibold text-secondary" id="aiMin">Rp 0</div>
                                </div>
                                <div>
                                    <div class="text-muted small">Rata-rata Pasar</div>
                                    <div class="fw-bold text-dark" id="aiAvg">Rp 0</div>
                                </div>
                                <div>
                                    <div class="text-muted small">Max</div>
                                    <div class="fw-semibold text-secondary" id="aiMax">Rp 0</div>
                                </div>
                            </div>
                            <div class="mt-2 text-end">
                                <a href="#" class="btn btn-sm btn-primary py-0 px-2 fw-semibold" style="font-size: 0.75rem;" id="aiApply">Gunakan Harga</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Stock Quantity' : 'Jumlah Stok' }}</label>
                        <input type="number" name="stok" class="form-control" value="{{ $product->stok }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Kondisi</label>
                        <select name="kondisi" class="form-select" required>
                            <option value="baru" {{ $product->kondisi == 'baru' ? 'selected' : '' }}>Baru / New</option>
                            <option value="seperti_baru" {{ $product->kondisi == 'seperti_baru' ? 'selected' : '' }}>Seperti Baru / Like New</option>
                            <option value="bekas_layak" {{ $product->kondisi == 'bekas_layak' ? 'selected' : '' }}>Bekas Layak / Good</option>
                            <option value="bekas" {{ $product->kondisi == 'bekas' ? 'selected' : '' }}>Bekas / Fair</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Size' : 'Ukuran' }}</label>
                        <input type="text" name="ukuran" class="form-control" value="{{ $product->ukuran }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Color' : 'Warna' }}</label>
                        <input type="text" name="warna" class="form-control" value="{{ $product->warna }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Material / Bahan</label>
                        <input type="text" name="material" class="form-control" value="{{ $product->material }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Weight (grams)' : 'Berat Produk (gram)' }}</label>
                        <input type="number" name="berat" class="form-control" value="{{ $product->berat }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Item Location' : 'Lokasi Pengiriman Barang' }}</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ $product->lokasi }}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold small">Tags Produk</label>
                        <input type="text" name="tags" class="form-control" value="{{ $product->tags }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Description' : 'Deskripsi Barang' }}</label>
                        <textarea name="deskripsi" rows="4" class="form-control">{{ $product->deskripsi }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Pane -->
            <div class="col-lg-4 border-start">
                <div class="mb-4">
                    <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-images me-1"></i> Media & Foto</h6>
                    <div class="mb-3">
                        <div class="mb-2">
                            <img src="{{ asset('uploads/products/' . $product->foto) }}" class="rounded border p-1" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Update Photo' : 'Ganti Foto Utama' }}</label>
                        <input type="file" name="foto_file" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Add Extra Photos' : 'Tambah Foto Tambahan (Multiple)' }}</label>
                        <input type="file" name="multiple_fotos_files[]" class="form-control" accept="image/*" multiple>
                    </div>
                    <div class="mb-3">
                        @if($product->video)
                            <div class="mb-2 small text-success"><i class="fas fa-video me-1"></i> Video terunggah</div>
                        @endif
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Update Video' : 'Ganti Video Barang' }}</label>
                        <input type="file" name="video_file" class="form-control" accept="video/*">
                    </div>
                </div>

                <div class="mb-4 border-top pt-3">
                    <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-circle-info me-1"></i> Preloved & Authenticity Info</h6>
                    
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Usage Duration' : 'Lama Penggunaan' }}</label>
                        <input type="text" name="lama_penggunaan" class="form-control" value="{{ $product->lama_penggunaan }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Usage Frequency' : 'Frekuensi Penggunaan' }}</label>
                        <input type="text" name="frekuensi_penggunaan" class="form-control" value="{{ $product->frekuensi_penggunaan }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Upload Invoice / Receipt' : 'Ganti Invoice Asli' }}</label>
                        <input type="file" name="invoice_keaslian_file" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Upload Certificate' : 'Ganti Sertifikat Keaslian' }}</label>
                        <input type="file" name="sertifikat_keaslian_file" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Defect Description' : 'Minus / Defect Detail' }}</label>
                        <textarea name="defect_description" rows="2" class="form-control">{{ $product->defect_description }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end border-top pt-3 mt-4">
            <a href="{{ route('seller.product.index') }}" class="btn btn-outline-secondary me-2">{{ $lang == 'en' ? 'Cancel' : 'Batal' }}</a>
            <button type="submit" class="btn btn-primary px-4">{{ $lang == 'en' ? 'Save Changes' : 'Simpan Perubahan' }}</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('btnAiPrice').addEventListener('click', function() {
        const catId = document.getElementById('kategori_id').value;
        const brand = document.getElementById('brand').value;
        const box = document.getElementById('aiPriceBox');
        
        box.style.display = 'block';
        document.getElementById('aiMessage').innerText = 'Menganalisis data pasar...';

        fetch("{{ route('seller.product.ai-recommendation') }}?kategori_id=" + catId + "&brand=" + brand)
            .then(res => res.json())
            .then(data => {
                document.getElementById('aiMessage').innerText = data.message;
                document.getElementById('aiMin').innerText = 'Rp ' + Number(data.min).toLocaleString('id-ID');
                document.getElementById('aiAvg').innerText = 'Rp ' + Number(data.average).toLocaleString('id-ID');
                document.getElementById('aiMax').innerText = 'Rp ' + Number(data.max).toLocaleString('id-ID');

                const applyBtn = document.getElementById('aiApply');
                applyBtn.onclick = function(e) {
                    e.preventDefault();
                    document.getElementById('harga_jual').value = data.average;
                    box.style.display = 'none';
                };
            })
            .catch(() => {
                document.getElementById('aiMessage').innerText = 'Gagal menganalisis harga. Coba pilih Kategori atau isi Brand dahulu.';
            });
    });
</script>
@endpush
