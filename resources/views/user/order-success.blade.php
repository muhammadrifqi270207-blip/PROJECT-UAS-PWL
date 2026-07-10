@extends('layouts.user')
@section('title', 'Pemesanan Berhasil')

@section('content')
<div class="max-w-2xl mx-auto text-center py-12">

    {{-- ICON SUKSES --}}
    <div class="w-24 h-24 bg-green-900 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-check text-green-400 text-5xl"></i>
    </div>

    <h1 class="text-3xl font-bold text-white mb-2">Pemesanan Berhasil! 🎉</h1>
    <p class="text-gray-400 mb-8">Tiketmu sudah dikonfirmasi, selamat menikmati konsernya!</p>

    {{-- DETAIL ORDER --}}
    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-6 text-left mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-pink-400">Detail Pesanan</h2>
            <span class="bg-green-900 text-green-400 text-xs px-3 py-1 rounded-full font-bold">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Kode Order</span>
                <span class="text-pink-400 font-extrabold tracking-wider">{{ $order->kode_order }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Nama Pemesan</span>
                <span class="text-white">{{ $order->nama_pemesan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Email</span>
                <span class="text-white">{{ $order->email_pemesan }}</span>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-4 pt-4">
            @foreach($order->items as $item)
            <div class="flex justify-between items-center mb-3">
                <div>
                    <p class="font-bold text-white">{{ $item->tiket->konser->nama_konser }}</p>
                    <p class="text-gray-400 text-xs">{{ $item->tiket->kategori }} · {{ $item->jumlah }}x tiket</p>
                    <p class="text-gray-500 text-xs">
                        {{ \Carbon\Carbon::parse($item->tiket->konser->tanggal)->format('d M Y') }} · {{ $item->tiket->konser->venue }}
                    </p>
                </div>
                <p class="text-green-400 font-bold">Rp {{ number_format($item->subtotal) }}</p>
            </div>
            @endforeach
        </div>

        <div class="border-t border-gray-700 mt-4 pt-4 flex justify-between">
            <span class="font-bold text-white">Total Bayar</span>
            <span class="text-2xl font-extrabold text-green-400">Rp {{ number_format($order->total_harga) }}</span>
        </div>

        {{-- QR CODE --}}
        <div class="border-t border-gray-700 mt-4 pt-4 text-center">
            <p class="text-gray-400 text-sm mb-3">Tunjukkan QR Code ini saat masuk venue</p>
            <div class="inline-block bg-white p-4 rounded-xl">
                {!! QrCode::size(180)->generate($order->kode_order) !!}
            </div>
            <p class="text-pink-400 font-bold tracking-widest mt-2">{{ $order->kode_order }}</p>
        </div>
    </div>

    {{-- TOMBOL --}}
    <div class="flex gap-4 justify-center">
        <a href="{{ route('user.orders.my') }}"
           class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-xl font-bold transition">
            <i class="fas fa-ticket mr-2"></i>Lihat Tiket Saya
        </a>
        <a href="{{ route('user.home') }}"
           class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold transition">
            <i class="fas fa-home mr-2"></i>Kembali ke Beranda
        </a>
    </div>

</div>
@endsection