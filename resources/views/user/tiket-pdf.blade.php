<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #fff; color: #333; margin: 0; padding: 20px; }
        .ticket { border: 3px solid #7c3aed; border-radius: 12px; padding: 30px; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px dashed #7c3aed; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #7c3aed; font-size: 28px; margin: 0; }
        .header p { color: #666; margin: 5px 0; }
        .kode { text-align: center; background: #f3f0ff; border-radius: 8px; padding: 15px; margin: 20px 0; }
        .kode h2 { color: #7c3aed; font-size: 32px; font-weight: bold; margin: 0; letter-spacing: 4px; }
        .kode p { color: #666; font-size: 12px; margin: 5px 0 0; }
        .info { margin: 20px 0; }
        .info table { width: 100%; border-collapse: collapse; }
        .info table td { padding: 8px 0; border-bottom: 1px solid #eee; font-size: 14px; }
        .info table td:first-child { color: #666; width: 40%; }
        .info table td:last-child { font-weight: bold; color: #333; }
        .total { text-align: right; background: #f3f0ff; border-radius: 8px; padding: 15px; margin-top: 20px; }
        .total p { margin: 0; color: #666; font-size: 14px; }
        .total h3 { margin: 5px 0 0; color: #7c3aed; font-size: 24px; }
        .footer { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 2px dashed #7c3aed; }
        .footer p { color: #999; font-size: 12px; margin: 3px 0; }
        .status { display: inline-block; background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
<div class="ticket">
    <div class="header">
        <h1>Embud Creative</h1>
        <p>E-Ticket Konser</p>
        <span class="status">✓ {{ strtoupper($order->status) }}</span>
    </div>

    <div class="kode">
        <p style="margin-bottom:10px; color:#666; font-size:13px;">Tunjukkan QR Code ini saat masuk venue</p>
        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(180)->generate($order->kode_order)) }}" 
         style="width:180px; height:180px;">
        <p style="margin-top:8px; color:#7c3aed; font-size:13px; font-weight:bold;">{{ $order->kode_order }}</p>
    </div>

    @foreach($order->items as $item)
    <div class="info">
        <table>
            <tr>
                <td>Nama Konser</td>
                <td>{{ $item->tiket->konser->nama_konser }}</td>
            </tr>
            <tr>
                <td>Artis</td>
                <td>{{ $item->tiket->konser->artis }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>{{ \Carbon\Carbon::parse($item->tiket->konser->tanggal)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Jam</td>
                <td>{{ $item->tiket->konser->jam }} WIB</td>
            </tr>
            <tr>
                <td>Venue</td>
                <td>{{ $item->tiket->konser->venue }}</td>
            </tr>
            <tr>
                <td>Kategori Tiket</td>
                <td>{{ $item->tiket->kategori }}</td>
            </tr>
            <tr>
                <td>Jumlah Tiket</td>
                <td>{{ $item->jumlah }} tiket</td>
            </tr>
            <tr>
                <td>Nama Pemesan</td>
                <td>{{ $order->nama_pemesan }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $order->email_pemesan }}</td>
            </tr>
        </table>
    </div>

    <div class="total">
        <p>Total Pembayaran</p>
        <h3>Rp {{ number_format($item->subtotal) }}</h3>
    </div>
    @endforeach

    <div class="footer">
        <p>Tiket ini diterbitkan oleh Embud Creative</p>
        <p>Tanggal pembelian: {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
        <p>© {{ date('Y') }} Embud Creative - All Rights Reserved</p>
    </div>
</div>
</body>
</html>