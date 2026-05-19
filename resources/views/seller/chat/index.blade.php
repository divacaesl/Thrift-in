@extends('layouts.seller')

@section('title', 'Kotak Masuk Chat & Nego - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="row g-4">
    <!-- Contacts & Auto Reply Settings -->
    <div class="col-lg-4">
        <!-- Auto-Reply Config Box (Section 7) -->
        <div class="card p-3 mb-4">
            <h6 class="fw-bold mb-3"><i class="fas fa-robot text-primary me-2"></i>{{ $lang == 'en' ? 'Auto-Reply Settings' : 'Asisten Auto-Reply Toko' }}</h6>
            <form action="{{ route('seller.chat.autoreply') }}" method="POST">
                @csrf
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_auto_reply_enabled" id="is_auto_reply" {{ $penitip->is_auto_reply_enabled ? 'checked' : '' }}>
                    <label class="form-check-label small fw-semibold text-secondary" for="is_auto_reply">{{ $lang == 'en' ? 'Enable Auto-Reply' : 'Aktifkan Balasan Otomatis' }}</label>
                </div>
                <div class="mb-3">
                    <textarea name="auto_reply_message" rows="2" class="form-control small" style="font-size: 0.85rem;" required placeholder="Halo! Terima kasih sudah mampir...">{{ $penitip->auto_reply_message }}</textarea>
                </div>
                <button type="submit" class="btn btn-sm btn-primary w-100">Simpan Asisten</button>
            </form>
        </div>

        <!-- Contacts Sidebar -->
        <div class="card p-3">
            <h6 class="fw-bold mb-3"><i class="fas fa-comments text-secondary me-2"></i>{{ $lang == 'en' ? 'Conversations List' : 'Daftar Diskusi Pembeli' }}</h6>
            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                @if($contacts->isEmpty())
                    <div class="text-center py-4 text-muted small">Belum ada percakapan.</div>
                @else
                    @foreach($contacts as $c)
                        <a href="{{ route('seller.chat', ['contact_id' => $c->id]) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-2 py-3 border-0 rounded-3 mb-1 {{ ($activeContact && $activeContact->id == $c->id) ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}">
                            <img src="{{ asset('uploads/profiles/' . $c->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/person-circle.svg'" alt="profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            <div class="flex-grow-1 min-w-0">
                                <p class="mb-0 text-truncate text-dark fw-semibold" style="font-size: 0.9rem;">{{ $c->nama }}</p>
                                <small class="text-muted text-truncate d-block" style="font-size: 0.75rem;">Mulai chat dengan pembeli...</small>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Active Chat Window -->
    <div class="col-lg-8">
        @if($activeContact)
            <div class="card d-flex flex-column" style="height: 600px;">
                <!-- Header -->
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top-4">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('uploads/profiles/' . $activeContact->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/person-circle.svg'" alt="profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ $activeContact->nama }}</h6>
                            <small class="text-success"><i class="fas fa-circle" style="font-size: 0.6rem;"></i> Online</small>
                        </div>
                    </div>
                </div>

                <!-- Chat Offer Action Banner (Section 7) -->
                @if($activeNego)
                    <div class="alert alert-warning border-0 rounded-0 m-0 p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-tag fs-4 text-warning"></i>
                            <div>
                                <span class="fw-bold small text-dark">{{ $activeNego->barang->nama_barang }}</span>
                                <div class="small text-muted">{{ $lang == 'en' ? 'Offered Price:' : 'Ditawar sebesar:' }} <strong class="text-danger">Rp {{ number_format($activeNego->harga_tawaran, 0, ',', '.') }}</strong> (Harga Asli: Rp {{ number_format($activeNego->barang->harga_jual, 0, ',', '.') }})</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('seller.chat.accept-offer', $activeNego->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success px-3 fw-bold">{{ $lang == 'en' ? 'Accept' : 'Terima' }}</button>
                            </form>
                            <form action="{{ route('seller.chat.decline-offer', $activeNego->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger px-3 fw-bold">{{ $lang == 'en' ? 'Decline' : 'Tolak' }}</button>
                            </form>
                            <button type="button" class="btn btn-sm btn-primary px-3 fw-bold" data-bs-toggle="collapse" data-bs-target="#counterCollapse">{{ $lang == 'en' ? 'Counter' : 'Nego Balik' }}</button>
                        </div>
                    </div>

                    <!-- Counter Nego collapse area -->
                    <div class="collapse p-3 bg-light border-bottom" id="counterCollapse">
                        <form action="{{ route('seller.chat.counter-offer', $activeNego->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="number" name="harga_counter" class="form-control form-control-sm" min="1000" placeholder="Ketik tawaran balik Anda (Rp)" required>
                            <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold">Kirim Counter</button>
                        </form>
                    </div>
                @endif

                <!-- Chat history bubbles -->
                <div class="flex-grow-1 p-3 overflow-y-auto" style="background-color: #F8FAFC;">
                    @if($messages->isEmpty())
                        <div class="text-center py-5 text-muted small">Kirim pesan pertama Anda untuk memulai diskusi.</div>
                    @else
                        @foreach($messages as $msg)
                            <div class="d-flex mb-3 {{ $msg->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="rounded-3 p-3 shadow-sm" style="max-width: 70%; {{ $msg->sender_id == Auth::id() ? 'background-color: var(--primary); color: white;' : 'background-color: white; color: var(--text-dark); border: 1px solid var(--border-color);' }}">
                                    @if($msg->barang_id)
                                        <div class="p-2 mb-2 rounded bg-black bg-opacity-10 text-dark small d-flex align-items-center gap-2">
                                            <i class="fas fa-tag"></i> <span>Terkait barang: <strong>{{ $msg->barang->nama_barang }}</strong></span>
                                        </div>
                                    @endif

                                    @if($msg->gambar)
                                        <div class="mb-2">
                                            <img src="{{ asset('uploads/chats/' . $msg->gambar) }}" class="rounded img-fluid" style="max-height: 150px;" alt="chat attachment">
                                        </div>
                                    @endif

                                    <p class="mb-0" style="font-size: 0.9rem;">{!! nl2br(e($msg->pesan)) !!}</p>
                                    <small class="d-block text-end mt-1 {{ $msg->sender_id == Auth::id() ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.7rem;">{{ $msg->created_at->format('H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Input Message Bar -->
                <div class="p-3 border-top bg-white rounded-bottom-4">
                    <form action="{{ route('seller.chat.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeContact->id }}">
                        
                        <!-- Optional Item Reference -->
                        @if($activeNego)
                            <input type="hidden" name="barang_id" value="{{ $activeNego->barang_id }}">
                        @endif

                        <div class="input-group">
                            <input type="file" name="gambar_file" id="gambar_file" class="d-none" accept="image/*" onchange="alert('Gambar dipilih!')">
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('gambar_file').click()"><i class="fas fa-image"></i></button>
                            <input type="text" name="pesan" class="form-control" placeholder="Tulis balasan Anda..." required>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card p-5 text-center d-flex flex-column align-items-center justify-content-center" style="height: 600px;">
                <i class="fas fa-message fs-1 mb-3 text-secondary"></i>
                <h5>{{ $lang == 'en' ? 'Open a Chat Window' : 'Kotak Dialog Chat Kosong' }}</h5>
                <p class="text-muted small">{{ $lang == 'en' ? 'Select a customer thread from the left menu to view bids and conversations.' : 'Pilih diskusi pembeli dari menu kiri untuk memulai negosiasi penawaran.' }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
