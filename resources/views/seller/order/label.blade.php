@php
    $lang = session('preferred_language', 'id');
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Pengiriman - {{ $order->kode_transaksi }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: white;
            color: black;
            padding: 20px;
            margin: 0;
        }
        .label-container {
            border: 2px solid black;
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 10px;
            background-color: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px dashed black;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .logo {
            font-weight: 800;
            font-size: 20px;
        }
        .courier-box {
            border: 2px solid black;
            padding: 5px 15px;
            font-weight: 800;
            font-size: 18px;
            text-transform: uppercase;
        }
        .address-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            border-bottom: 2px dashed black;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .address-box {
            font-size: 14px;
            line-height: 1.4;
        }
        .address-title {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            color: #555;
            margin-bottom: 3px;
        }
        .barcode-section {
            text-align: center;
            border-bottom: 2px dashed black;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .barcode-line {
            display: inline-block;
            background-color: black;
            height: 50px;
            width: 3px;
            margin: 0 1px;
        }
        .barcode-text {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 5px;
            margin-top: 5px;
        }
        .item-details {
            font-size: 13px;
            line-height: 1.4;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .no-print-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4F46E5;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            max-width: 200px;
            text-align: center;
        }
        @media print {
            .no-print-btn {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .label-container {
                border: 2px solid black !important;
                box-shadow: none !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="label-container">
        <!-- Label Header -->
        <div class="header">
            <div class="logo">Thrift<span>In</span></div>
            <div class="courier-box">{{ $order->ekspedisi ?? 'PICKUP' }}</div>
        </div>

        <!-- Simulated Barcode -->
        <div class="barcode-section">
            <div class="barcode">
                @for($i = 0; $i < 40; $i++)
                    <span class="barcode-line" style="width: {{ rand(1, 4) }}px; margin-right: {{ rand(1, 2) }}px;"></span>
                @endfor
            </div>
            <div class="barcode-text">{{ $order->kode_transaksi }}</div>
        </div>

        <!-- Address details -->
        <div class="address-section">
            <div class="address-box">
                <div class="address-title">Penerima (To):</div>
                <strong>{{ $order->nama_pembeli }}</strong><br>
                {{ $order->alamat }}<br>
                No. HP: {{ $order->no_hp ?? '-' }}
            </div>
            
            <div class="address-box">
                <div class="address-title">Pengirim (From):</div>
                <strong>{{ $penitip->nama }}</strong><br>
                {{ $penitip->alamat }}<br>
                No. HP: {{ $penitip->no_hp }}
            </div>
        </div>

        <!-- Items Table details -->
        <div class="item-details">
            <div class="address-title">Daftar Barang (Item List):</div>
            <div class="item-row" style="font-weight: 600; border-bottom: 1px solid black; padding-bottom: 3px; margin-bottom: 5px;">
                <span>Nama Barang</span>
                <span>Berat</span>
            </div>
            <div class="item-row">
                <span>{{ $order->barang->nama_barang ?? 'Barang Dihapus' }} (Qty: 1)</span>
                <span>{{ $order->barang->berat ?? 500 }} gr</span>
            </div>
            <div class="item-row" style="margin-top: 10px; font-size: 11px; color: #555;">
                <span>Catatan: Jangan dibanting, barang preloved vintage.</span>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <button class="no-print-btn" onclick="window.print()">Cetak Label / Print</button>

</body>
</html>
