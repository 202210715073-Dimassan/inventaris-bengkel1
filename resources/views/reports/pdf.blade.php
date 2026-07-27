<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Restock Mo Gerzz</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 20px; font-weight: bold; }
        .subtitle { color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Restock (Kritis) - Mo Gerzz</div>
        <div class="subtitle">Tanggal Cetak: {{ date('d M Y, H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok Saat Ini</th>
                <th>Batas ROP</th>
                <th>Lead Time (Hari)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($urgentProducts as $product)
            <tr>
                <td>{{ $product->code ?? ('#PRD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT)) }}</td>
                <td>{{ $product->name }}</td>
                <td style="color: red; font-weight: bold;">{{ $product->stock }} {{ $product->unit }}</td>
                <td>{{ round($product->rop_value) }} {{ $product->unit }}</td>
                <td>{{ $product->lead_time }} Hari</td>
            </tr>
            @endforeach
            @if($urgentProducts->count() == 0)
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada barang yang membutuhkan restock saat ini.</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
