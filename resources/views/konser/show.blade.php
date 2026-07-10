@extends('layouts.user')
@section('title', 'Detail Konser')

@section('content')
<div class="mb-6">
    <a href="{{ route('user.home') }}" class="text-gray-400 hover:text-white text-sm transition">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
    </a>
</div>

{{-- JIKA USER MEMILIKI TRANSAKSI PENDING YANG HAMPIR KADALUARSA --}}
@if(auth()->user() && $orderPending = auth()->user()->orders()->where('status', 'pending')->latest()->first())
    @php
        $waktuBatas = \Carbon\Carbon::parse($orderPending->created_at)->addMinutes(15);
        $sisaDetik  = max(0, now()->diffInSeconds($waktuBatas, false));
    @endphp

    @if($sisaDetik > 0)
        <div class="bg-yellow-950/40 border border-yellow-600/50 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left animate-pulse">
            <div>
                <p class="text-yellow-400 font-bold text-sm">⚠️ Kamu memiliki transaksi yang belum dibayar!</p>
                <p class="text-gray-400 text-xs mt-0.5">Selesaikan pembayaran untuk tiket <strong>{{ $orderPending->tiket->kategori ?? $orderPending->tiket->nama_tiket }}</strong> sebelum waktu habis.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-gray-900 border border-yellow-500 text-yellow-400 px-4 py-2 rounded-xl font-black text-lg tracking-wider" id="countdown-box">
                    15:00
                </div>
                <a href="{{ route('checkout.show', $orderPending->id) }}" class="bg-yellow-600 hover:bg-yellow-500 text-gray-950 text-xs font-bold px-4 py-2.5 rounded-xl transition">
                    Bayar Sekarang
                </a>
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
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- INFO KONSER (KIRI) --}}
    <div class="lg:col-span-1">
        <div class="bg-gray-900 border border-pink-900/60 rounded-2xl overflow-hidden shadow-lg">
            @if($konser->poster)
                <img src="{{ asset('storage/' . $konser->poster) }}" class="w-full h-64 object-cover">
            @else
                <div class="w-full h-64 bg-gradient-to-br from-pink-950 to-gray-900 flex items-center justify-center">
                    <i class="fas fa-music text-pink-500 text-5xl"></i>
                </div>
            @endif
            <div class="p-5 space-y-4">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">{{ $konser->nama_konser }}</h1>
                    <p class="text-pink-400 font-semibold mt-1 flex items-center gap-1.5">
                        <i class="fas fa-microphone text-xs"></i> {{ $konser->artis }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-pink-950 text-pink-400 border border-pink-900/60 uppercase">
                        {{ $konser->genre ?? 'General' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-950 text-green-400 border border-green-900/60 uppercase">
                        {{ $konser->status }}
                    </span>
                </div>

                <div class="border-t border-gray-800 pt-4 space-y-2.5 text-sm text-gray-400">
                    <p class="flex items-center gap-2"><i class="fas fa-map-marker-alt w-5 text-pink-500 text-center"></i> {{ $konser->venue }}</p>
                    <p class="flex items-center gap-2"><i class="fas fa-calendar w-5 text-pink-500 text-center"></i> {{ \Carbon\Carbon::parse($konser->tanggal)->format('d F Y') }}</p>
                    <p class="flex items-center gap-2"><i class="fas fa-clock w-5 text-pink-500 text-center"></i> {{ $konser->jam }} WIB</p>
                </div>

                @if($konser->maps_url)
                <a href="{{ $konser->maps_url }}" target="_blank"
                   class="mt-2 block text-center bg-pink-600 hover:bg-pink-700 text-white py-2.5 rounded-xl font-bold transition text-sm shadow-md shadow-pink-950/40">
                    <i class="fas fa-map-marked-alt mr-2"></i>Petunjuk Arah (Maps)
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- DESKRIPSI & FORM PEMBELIAN TIKET (KANAN) --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Deskripsi --}}
        @if($konser->deskripsi)
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-lg">
            <h2 class="text-lg font-bold text-pink-400 mb-3 flex items-center gap-2">
                <i class="fas fa-info-circle"></i> Tentang Konser
            </h2>
            <p class="text-gray-300 leading-relaxed text-sm">{{ $konser->deskripsi }}</p>
        </div>
        @endif

        {{-- Opsi Pilihan Kategori Tiket --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-lg">
            <h2 class="text-lg font-bold text-pink-400 mb-4 flex items-center gap-2">
                <i class="fas fa-ticket-alt"></i> Pilih Kategori Tiket
            </h2>

            <form action="{{ route('user.orders.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="konser_id" value="{{ $konser->id }}">

                <div class="grid grid-cols-1 gap-3">
                    @forelse($konser->tikets as $tiket)
                        @php 
                            $sisa = $tiket->kuota - $tiket->terjual; 
                            $namaKategori = $tiket->kategori ?? $tiket->nama_tiket;
                            $isVIP = Str::contains(Str::upper($namaKategori), 'VIP');
                        @endphp
                        
                        <label class="relative flex items-center justify-between bg-gray-800 border border-gray-700 rounded-2xl p-4 cursor-pointer hover:border-pink-500 transition-all has-[:checked]:border-pink-600 has-[:checked]:bg-pink-950/30 {{ $sisa <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="tiket_id" value="{{ $tiket->id }}" class="text-pink-600 focus:ring-pink-500 bg-gray-900 border-gray-700" {{ $sisa <= 0 ? 'disabled' : '' }} required>
                                
                                <div>
                                    <span class="block text-white font-bold text-sm">
                                        {{ $isVIP ? '👑' : '🎸' }} {{ $namaKategori }}
                                    </span>
                                    <span class="text-xs text-gray-400">Sisa Kuota: <span class="{{ $sisa < 10 ? 'text-yellow-400 font-semibold' : 'text-gray-300' }}">{{ $sisa }} tiket</span></span>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                @if($sisa <= 0)
                                    <span class="text-red-400 font-bold text-xs uppercase bg-red-950/50 border border-red-900/60 px-2.5 py-1 rounded-full">Habis</span>
                                @else
                                    <span class="text-pink-400 font-black text-base block">
                                        Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </label>
                    @empty
                        <div class="text-center py-8 text-gray-500 border border-dashed border-gray-800 rounded-2xl">
                            <i class="fas fa-ticket-alt text-3xl mb-2 block text-gray-700"></i>
                            <p class="text-sm">Maaf, tiket untuk konser ini belum tersedia.</p>
                        </div>
                    @endforelse
                </div>

                @if($konser->tikets->count() > 0)
                <div class="pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-gray-800 mt-4">
                    <div class="flex items-center gap-3">
                        <label class="text-gray-400 text-xs font-medium">Jumlah Tiket:</label>
                        <input type="number" name="jumlah" min="1" max="5" value="1" 
                               class="w-20 bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 text-white text-center text-sm focus:outline-none focus:border-pink-500">
                    </div>
                    
                    {{-- Tombol dinonaktifkan kalau user masih punya tunggakan transaksi --}}
                    @if(auth()->user() && auth()->user()->orders()->where('status', 'pending')->exists())
                        <button type="button" disabled class="bg-gray-700 text-gray-400 font-bold py-3 px-6 rounded-xl text-sm flex items-center justify-center gap-2 w-full sm:w-auto cursor-not-allowed">
                            <i class="fas fa-ban"></i> Selesaikan Transaksi Sebelumnya
                        </button>
                    @else
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md shadow-pink-950/50 text-sm flex items-center justify-center gap-2 w-full sm:w-auto">
                            <i class="fas fa-shopping-cart"></i> Pesan Tiket Sekarang
                        </button>
                    @endif
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection