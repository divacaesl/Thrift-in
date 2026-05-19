@extends('layouts.buyer')

@section('title', 'Pusat Bantuan CS & Refund - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp

<h3 class="fw-bold mb-4 text-dark"><i class="fas fa-headset text-primary me-2"></i>{{ $lang == 'en' ? 'Support Center & CS Live Chat' : 'Pusat Bantuan & Live CS' }}</h3>

<div class="row g-4">
    <!-- Left Column: FAQ & Complaint Form -->
    <div class="col-lg-6">
        <!-- FAQ Accordion -->
        <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <h5 class="fw-bold text-dark mb-3"><i class="far fa-circle-question text-primary me-2"></i>Frequently Asked Questions</h5>
            
            <div class="accordion" id="faqAccordion">
                @foreach($faqs as $index => $faq)
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="faqHeading_{{ $index }}">
                            <button class="accordion-button collapsed bg-white fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse_{{ $index }}" aria-expanded="false" aria-controls="faqCollapse_{{ $index }}" style="box-shadow: none;">
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faqCollapse_{{ $index }}" class="accordion-collapse collapse" aria-labelledby="faqHeading_{{ $index }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small pt-0 pb-3">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- CS Complaint Form -->
        <div id="complaint" class="card p-4 border-0 shadow-sm animate-fade" style="border-radius: 16px;">
            <h5 class="fw-bold text-dark mb-1"><i class="fas fa-triangle-exclamation text-danger me-2"></i>{{ $lang == 'en' ? 'File CS Complaint / Refund' : 'Form Pengembalian & Komplain' }}</h5>
            <p class="text-muted small mb-4">{{ $lang == 'en' ? 'If your preloved item is defected or wrong, file here. Escrow holds seller funds.' : 'Jika barang preloved rusak atau tidak sesuai, ajukan di sini. Dana escrow seller akan ditahan sementara.' }}</p>
            
            @auth
                @if($orders->isEmpty())
                    <div class="alert alert-info border-0 py-3 mb-0" style="border-radius: 12px;">
                        {{ $lang == 'en' ? 'You must complete an order first to submit a complaint.' : 'Anda belum memiliki transaksi pembelian untuk diajukan komplain.' }}
                    </div>
                @else
                    <form action="{{ route('buyer.support.complaint') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="transaksi_id" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Select Transaction Order' : 'Pilih Transaksi Pembelian' }}</label>
                            <select name="transaksi_id" id="transaksi_id" class="form-select form-select-sm" required style="border-radius: 8px;">
                                <option value="">-- Pilih Transaksi --</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}">{{ $order->kode_transaksi }} - {{ $order->barang->nama_barang }} (Rp {{ number_format($order->harga_jual, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="alasan" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Reason for Refund/Complaint' : 'Alasan Komplain Detail' }}</label>
                            <textarea name="alasan" id="alasan" class="form-control form-control-sm" rows="3" required placeholder="Jelaskan secara rinci kerusakan atau ketidaksesuaian barang..." style="border-radius: 8px;"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="foto_defect" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Upload Photo Proof' : 'Unggah Bukti Foto Defect' }}</label>
                            <input class="form-control form-control-sm" type="file" id="foto_defect" name="foto" required accept="image/*" style="border-radius: 8px;">
                        </div>

                        <button type="submit" class="btn btn-danger btn-sm w-100 py-2 fw-bold" style="border-radius: 8px;">
                            {{ $lang == 'en' ? 'Submit Refund Form' : 'Kirim Form Refund / Komplain' }}
                        </button>
                    </form>
                @endif
            @else
                <div class="text-center py-3">
                    <a href="{{ route('buyer.login') }}" class="btn btn-outline-primary btn-sm px-4">{{ $lang == 'en' ? 'Login to file complaint' : 'Login untuk Mengajukan Komplain' }}</a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Right Column: Interactive Live CS Chat -->
    <div class="col-lg-6" id="chat">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; height: 500px; display: flex; flex-direction: column;">
            <!-- Chat Header -->
            <div class="p-3 border-bottom bg-light d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center text-primary border" style="width: 40px; height: 40px;">
                    <i class="fas fa-headset fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Live Support CS</h6>
                    <span class="text-muted small" style="font-size: 0.75rem;"><i class="fas fa-circle text-success me-1"></i>CS Agent Online</span>
                </div>
            </div>

            <!-- Chat box -->
            <div class="flex-grow-1 p-3 overflow-auto d-flex flex-column gap-3 bg-white" id="supportMessagesContainer">
                <!-- Welcome messages -->
                <div class="d-flex justify-content-start">
                    <div class="p-3 rounded-4 bg-light text-dark border" style="max-width: 80%; border-bottom-left-radius: 2px;">
                        <p class="mb-0 small">Halo! Selamat datang di Pusat Bantuan ThriftIn. Kami siap melayani kendala belanja Anda.</p>
                        <span class="text-muted d-block text-end mt-1" style="font-size: 0.65rem;">System</span>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <div class="p-3 rounded-4 bg-light text-dark border" style="max-width: 80%; border-bottom-left-radius: 2px;">
                        <p class="mb-0 small">Silakan tulis kendala Anda (misal: terkait "refund", "resi", "pembayaran"). CS kami akan merespon instan!</p>
                        <span class="text-muted d-block text-end mt-1" style="font-size: 0.65rem;">System</span>
                    </div>
                </div>
            </div>

            <!-- Chat Input footer -->
            <div class="p-3 border-top bg-light">
                <div class="input-group">
                    <input type="text" id="csInputText" class="form-control border-0 px-3" placeholder="{{ $lang == 'en' ? 'Type help question...' : 'Tulis pertanyaan bantuan...' }}" style="border-radius: 20px 0 0 20px; outline: none; box-shadow: none;" onkeypress="handleEnter(event)">
                    <button type="button" class="btn btn-primary px-4" style="border-radius: 0 20px 20px 0;" onclick="sendCSMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function handleEnter(e) {
        if (e.key === 'Enter') {
            sendCSMessage();
        }
    }

    function sendCSMessage() {
        const input = document.getElementById('csInputText');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        const container = document.getElementById('supportMessagesContainer');

        // Append User bubble
        const userDiv = document.createElement('div');
        userDiv.className = 'd-flex justify-content-end';
        userDiv.innerHTML = `
            <div class="p-3 rounded-4 bg-primary text-white" style="max-width: 80%; border-bottom-right-radius: 2px;">
                <p class="mb-0 small">${text}</p>
                <span class="text-white-50 d-block text-end mt-1" style="font-size: 0.65rem;">Anda</span>
            </div>
        `;
        container.appendChild(userDiv);
        container.scrollTop = container.scrollHeight;

        // Fetch CS Auto reply from backend
        fetch("{{ route('buyer.support.chat') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            setTimeout(() => {
                const csDiv = document.createElement('div');
                csDiv.className = 'd-flex justify-content-start';
                csDiv.innerHTML = `
                    <div class="p-3 rounded-4 bg-light text-dark border" style="max-width: 80%; border-bottom-left-radius: 2px;">
                        <p class="mb-0 small">${data.reply}</p>
                        <span class="text-muted d-block text-end mt-1" style="font-size: 0.65rem;">CS Agent</span>
                    </div>
                `;
                container.appendChild(csDiv);
                container.scrollTop = container.scrollHeight;
            }, 600);
        });
    }
</script>
@endpush
@endsection
