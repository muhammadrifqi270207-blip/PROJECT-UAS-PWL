@extends('layouts.user')
@section('title', 'Detail Pembayaran')

@section('content')
<div class="mb-6">
    <a href="{{ route('user.home') }}" class="text-gray-400 hover:text-white text-sm transition">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
    </a>
</div>

<div class="max-w-3xl mx-auto space-y-6">

    {{-- 1. NOTIFIKASI DAN COUNTDOWN TIMER --}}
    @if($order->status === 'pending')
        @php
            $waktuBatas = \Carbon\Carbon::parse($order->created_01)->addMinutes(15);
            // KUNCI PERBAIKAN: Ditambahkan intval() agar hasilnya bulat tanpa desimal panjang
            $sisaDetik  = max(0, intval(now()->diffInSeconds($waktuBatas, false)));
        @endphp

        <div class="bg-yellow-950/40 border border-yellow-600/50 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div class="flex-1">
                <p class="text-yellow-400 font-bold text-sm flex items-center justify-center sm:justify-start gap-1.5">
                    <i class="fas fa-clock animate-spin-slow"></i> Selesaikan Pembayaran Anda!
                </p>
                <p class="text-gray-400 text-xs mt-0.5">Tiket otomatis dibatalkan jika waktu hitung mundur habis.</p>
            </div>
            <!-- Box Timer yang Lebih Compact & Proporsional -->
            <div class="bg-gray-950 border border-yellow-500/60 text-yellow-400 px-4 py-2 rounded-xl font-mono font-black text-xl tracking-widest w-32 text-center shadow-md shadow-black/50 shrink-0" id="countdown-box">
                 15:00
            </div>
        </div>

        <script>
            let sisaDetik = {{ $sisaDetik }};
            const countdownBox = document.getElementById('countdown-box');

            const timer = setInterval(() => {
                if (sisaDetik <= 0) {
                    clearInterval(timer);
                    countdownBox.innerHTML = "WAKTU HABIS";
                    window.location.reload();
                } else {
                    sisaDetik--;
                    let menit = Math.floor(sisaDetik / 60);
                    let detik = sisaDetik % 60;

                    menit = menit < 10 ? '0' + menit : menit;
                    detik = detik < 10 ? '0' + detik : detik;

                    countdownBox.innerHTML = `${menit}:${detik}`;
                }
            }, 1000);
        </script>
    @endif

    {{-- 2. KONDISI JIKA ORDER EXPIRED --}}
    @if($order->status === 'expired')
        <div class="bg-red-950/40 border border-red-500/50 rounded-2xl p-8 text-center shadow-lg">
            <i class="fas fa-calendar-times text-red-500 text-5xl mb-3 animate-bounce"></i>
            <h2 class="text-white font-bold text-lg">Transaksi Ini Telah Kadaluarsa</h2>
            <p class="text-gray-400 text-sm mt-1 max-w-md mx-auto">Batas waktu pembayaran 15 menit sudah habis. Kuota tiket telah dikembalikan otomatis ke dalam sistem antrean.</p>
            <a href="{{ route('user.home') }}" class="mt-5 inline-block bg-pink-600 hover:bg-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition">
                Cari Tiket Lain
            </a>
        </div>
    @endif

    {{-- 3. DETAIL INVOICE PESANAN --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="border-b border-gray-800 pb-4 flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Order</span>
                <h3 class="text-white font-mono font-bold text-lg">{{ $order->kode_order }}</h3>
            </div>
            <div>
                @if($order->status === 'pending')
                    <span class="bg-yellow-950 text-yellow-400 border border-yellow-900/60 text-xs font-bold px-3 py-1 rounded-full uppercase">Menunggu Pembayaran</span>
                @elseif($order->status === 'paid')
                    <span class="bg-green-950 text-green-400 border border-green-900/60 text-xs font-bold px-3 py-1 rounded-full uppercase">Lunas</span>
                @else
                    <span class="bg-red-950 text-red-400 border border-red-900/60 text-xs font-bold px-3 py-1 rounded-full uppercase">Hangus</span>
                @endif
            </div>
        </div>

        <div class="space-y-3">
            <h4 class="text-sm font-bold text-pink-400 uppercase tracking-wide">Rincian Tiket</h4>
            @foreach($order->items as $item)
                <div class="flex justify-between items-center bg-gray-950/50 p-4 rounded-xl border border-gray-800/60">
                    <div>
                        <p class="text-white font-bold text-sm">{{ $item->tiket->konser->nama_konser }}</p>
                        <p class="text-gray-400 text-xs mt-0.5">Kategori: <span class="text-pink-400 font-medium">{{ $item->tiket->kategori }}</span></p>
                        <p class="text-gray-500 text-xs">{{ $item->jumlah }}x @ Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-white font-black text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-2 flex justify-between items-center text-white">
            <span class="text-sm text-gray-400 font-medium">Total yang Harus Dibayar:</span>
            <span class="text-xl font-black text-pink-400">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>

   {{-- 4. TOMBOL SIMULASI AKSI MANUAL (KHUSUS DEMO PRESENTASI) --}}
    @if($order->status === 'pending')
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl space-y-4">
            <p class="text-sm text-gray-400 font-medium text-center mb-2">Simulasi Aksi (Demo Presentasi):</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- Tombol Lunas --}}
                <form action="{{ route('user.checkout.bayar', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-check-circle text-base"></i> Konfirmasi Bayar Lunas
                    </button>
                </form>

                {{-- Tombol Batal Manual --}}
                <form action="{{ route('user.checkout.batal', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                    @csrf
                    <button type="submit" class="w-full bg-red-600/20 hover:bg-red-600 border border-red-500/40 text-red-400 hover:text-white font-bold py-3 px-4 rounded-xl transition shadow-md flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-times-circle text-base"></i> Batalkan Pesanan
                    </button>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection