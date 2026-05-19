@extends('layouts.buyer')

@section('title', 'Tanya Penjual & Nego - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp

<div class="row g-4" style="height: calc(100vh - 180px); min-height: 500px;">
    <!-- Left Pane: Conversations List -->
    <div class="col-md-4 col-lg-3">
        <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 16px; display: flex; flex-direction: column;">
            <div class="p-3 border-bottom bg-light">
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-comments text-primary me-2"></i>{{ $lang == 'en' ? 'Conversations' : 'Daftar Chat' }}</h6>
            </div>
            
            <div class="list-group list-group-flush overflow-auto flex-grow-1">
                @if($contacts->isEmpty())
                    <p class="text-muted small text-center my-4">{{ $lang == 'en' ? 'No chats yet.' : 'Belum ada obrolan.' }}</p>
                @else
                    @foreach($contacts as $contact)
                        <a href="{{ route('buyer.chat', ['contact_id' => $contact->id]) }}" class="list-group-item list-group-item-action py-3 px-3 border-0 border-bottom {{ $activeContact && $activeContact->id == $contact->id ? 'bg-primary-subtle border-start border-primary border-4' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary border" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark small text-truncate" style="max-width: 120px;">{{ $contact->nama }}</strong>
                                        <span class="text-muted small" style="font-size: 0.7rem;">Active</span>
                                    </div>
                                    <p class="mb-0 text-muted small text-truncate" style="font-size: 0.8rem;">
                                        {{ $lang == 'en' ? 'Click to open messages' : 'Klik untuk melihat pesan' }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Right Pane: Active Chat Room -->
    <div class="col-md-8 col-lg-9">
        @if(!$activeContact)
            <div class="card h-100 border-0 shadow-sm d-flex align-items-center justify-content-center text-center p-5" style="border-radius: 16px;">
                <i class="far fa-comments fs-1 text-muted mb-3"></i>
                <h5 class="fw-bold mb-1">{{ $lang == 'en' ? 'Select a conversation' : 'Pilih percakapan' }}</h5>
                <p class="text-muted small mb-0">{{ $lang == 'en' ? 'Choose a store on the left to start negotiation or chat.' : 'Silakan pilih kontak toko di sebelah kiri untuk berkirim pesan.' }}</p>
            </div>
        @else
            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 16px; display: flex; flex-direction: column;">
                <!-- Header -->
                <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary border" style="width: 40px; height: 40px;">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $activeContact->nama }}</h6>
                            <span class="text-muted small" style="font-size: 0.75rem;"><i class="fas fa-circle text-success me-1"></i>Online (Simulasi)</span>
                        </div>
                    </div>
                </div>

                <!-- Product context top helper -->
                @if($contextProduct)
                    <div class="p-3 bg-warning-subtle border-bottom d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=100" class="rounded border" alt="Context Product" style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold text-dark small mb-0">{{ $contextProduct->nama_barang }}</h6>
                                <strong class="text-primary small">Rp {{ number_format($contextProduct->harga_jual, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <!-- Nego trigger inside chat -->
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#chatNegoModal">
                            <i class="fas fa-tag me-1"></i>{{ $lang == 'en' ? 'Offer Price' : 'Tawarkan Harga' }}
                        </button>
                    </div>
                @endif

                <!-- Message Bubbles list -->
                <div class="flex-grow-1 p-3 overflow-auto d-flex flex-column gap-3 bg-white" id="messagesBox">
                    @foreach($messages as $msg)
                        @php
                            $isMe = $msg->sender_id === auth()->id();
                        @endphp
                        <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="p-3 rounded-4 {{ $isMe ? 'bg-primary text-white' : 'bg-light text-dark border' }}" style="max-width: 75%; border-bottom-{{ $isMe ? 'right' : 'left' }}-radius: 2px;">
                                @if($msg->barang)
                                    <!-- Context label in bubble -->
                                    <div class="p-2 mb-2 rounded bg-white text-dark small border mb-1">
                                        <i class="fas fa-bag-shopping text-primary me-1"></i>
                                        <strong class="small">{{ $msg->barang->nama_barang }}</strong>
                                    </div>
                                @endif
                                <p class="mb-0 small" style="white-space: pre-wrap;">{{ $msg->pesan }}</p>
                                @if($msg->gambar)
                                    <img src="{{ asset($msg->gambar) }}" class="rounded img-fluid mt-2" alt="chat attachment" style="max-height: 150px;">
                                @endif
                                <span class="text-muted d-block text-end mt-1" style="font-size: 0.65rem; color: {{ $isMe ? 'rgba(255,255,255,0.7)' : 'var(--text-muted)' }} !important;">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer input box -->
                <div class="p-3 border-top bg-light">
                    <form action="{{ route('buyer.chat.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeContact->id }}">
                        @if($contextProduct)
                            <input type="hidden" name="barang_id" value="{{ $contextProduct->id }}">
                        @endif

                        <div class="input-group">
                            <!-- Image attach button -->
                            <label class="btn btn-outline-secondary border-0" for="chat_img_input" title="Attach image">
                                <i class="fas fa-image fs-5"></i>
                            </label>
                            <input type="file" name="gambar" id="chat_img_input" class="d-none" accept="image/*" onchange="previewChatImage(this)">
                            
                            <input type="text" name="pesan" id="pesanInput" class="form-control border-0 px-3" placeholder="{{ $lang == 'en' ? 'Type your message here...' : 'Tulis pesan Anda...' }}" required style="border-radius: 20px 0 0 20px; outline: none; box-shadow: none;">
                            
                            <button type="submit" class="btn btn-primary px-4" style="border-radius: 0 20px 20px 0;">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <div id="imagePreviewContainer" class="mt-2 text-start" style="display: none;">
                            <span class="badge bg-secondary position-relative">
                                Image ready to send
                                <button type="button" class="btn-close btn-close-white ms-2 small" onclick="clearChatImagePreview()" style="font-size: 0.5rem;"></button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

@if($contextProduct)
<!-- Inline Chat Nego Modal -->
<div class="modal fade" id="chatNegoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">{{ $lang == 'en' ? 'Offer Price Negotiation' : 'Nego Penawaran Harga' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('buyer.chat.offer') }}" method="POST">
                @csrf
                <input type="hidden" name="barang_id" value="{{ $contextProduct->id }}">
                <div class="modal-body py-4">
                    <p class="text-muted small mb-4">{{ $lang == 'en' ? 'Submit your target price. Our simulated seller system will evaluate your offer immediately!' : 'Masukkan target harga penawaran Anda. Sistem penjual simulasi kami akan langsung menentukan persetujuan!' }}</p>
                    
                    <div class="mb-3">
                        <span class="small text-muted d-block mb-1">{{ $lang == 'en' ? 'Original Price' : 'Harga Asli' }}</span>
                        <strong class="fs-5 text-dark">Rp {{ number_format($contextProduct->harga_jual, 0, ',', '.') }}</strong>
                    </div>

                    <div class="mb-3">
                        <label for="harga_tawaran_chat" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Your Offer Price' : 'Harga Penawaran Anda' }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="harga_tawaran" class="form-control" id="harga_tawaran_chat" min="1" required placeholder="{{ $contextProduct->harga_jual - 10000 }}" style="border-radius: 0 10px 10px 0;">
                        </div>
                        <span class="text-muted small mt-1 d-block" style="font-size: 0.75rem;"><i class="fas fa-circle-info me-1"></i>{{ $lang == 'en' ? 'Offers above 85% of original price are usually accepted.' : 'Penawaran di atas 85% dari harga asli biasanya langsung disetujui.' }}</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">{{ $lang == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px;">{{ $lang == 'en' ? 'Submit Offer' : 'Ajukan Tawaran' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Scroll chats box to bottom automatically
    const box = document.getElementById('messagesBox');
    if (box) {
        box.scrollTop = box.scrollHeight;
    }

    function previewChatImage(input) {
        const preview = document.getElementById('imagePreviewContainer');
        if (input.files && input.files[0]) {
            preview.style.display = 'block';
        }
    }

    function clearChatImagePreview() {
        const input = document.getElementById('chat_img_input');
        const preview = document.getElementById('imagePreviewContainer');
        input.value = '';
        preview.style.display = 'none';
    }
</script>
@endpush
@endsection
