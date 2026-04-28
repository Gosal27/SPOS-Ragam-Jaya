<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        .header h2 { margin: 0; font-size: 22px; font-weight: bold; }
        .sub-header { font-size: 13px; margin-bottom: 10px; }
        table { width:100%; border-collapse: collapse; margin-top: 8px; }
        table th, table td { border:1px solid #000; padding:6px; vertical-align: middle; }
        table th { text-align:center; font-weight:700; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .meta { margin-bottom: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>RAGAM JAYA</h2>
        <div class="sub-header">
            ACCESORIS COMPUTER, OFFICE & BANKING SUPPLIES<br>
            081278999339 / 08123203607
        </div>
    </div>

    <div class="meta">
        <table style="border:0;">
            <tr style="border:0;">
                <td style="border:0; width:120px;">Tanggal</td>
                <td style="border:0; width:10px;">:</td>
                <td style="border:0;">
                    {{ \Carbon\Carbon::parse($sale->created_at)->format('d F Y H:i') }}
                </td>
            </tr>
            <tr style="border:0;">
                <td style="border:0;">No. Faktur</td>
                <td style="border:0;">:</td>
                <td style="border:0;">{{ $sale->no_faktur ?? 'RJ/' . $sale->idSales }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px;">NO</th>
                <th>NAMA BARANG</th>
                <th style="width:80px;">BANYAK</th>
                <th style="width:80px;">SATUAN</th>
                <th style="width:120px;">HARGA</th>
                <th style="width:130px;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($details as $d)
                <tr>
                    <td class="right">{{ $i++ }}</td>
                    {{-- Sesuaikan properti nama produk jika relasi berbeda --}}
                    <td>{{ $d->produk->nama ?? $d->produk->nama_barang ?? '-' }}</td>
                    <td class="right">{{ $d->quantity ?? $d->jumlah ?? $d->qty }}</td>
                    <td>{{ $d->produk->satuan ?? '-' }}</td>
                    <td class="right">Rp{{ number_format($d->subtotal / ($d->quantity ?: 1), 0, ',', '.') }}</td>
                    <td class="right">Rp{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="right bold">TOTAL</td>
                <td class="right bold">Rp{{ number_format($sale->subtotal ?? $sale->total ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top:18px; font-size:13px;">
        Pembayaran:<br>
        AC : 032 - 296 - 8922<br>
        A/N : Miniek Poernamawati
    </div>

    <script>
        // delay kecil agar browser selesai render sebelum print (mengurangi chance diblok)
        setTimeout(function(){ window.print(); }, 300);
        window.onafterprint = function(){ setTimeout(() => window.close(), 300); };
    </script>
</body>
</html>
