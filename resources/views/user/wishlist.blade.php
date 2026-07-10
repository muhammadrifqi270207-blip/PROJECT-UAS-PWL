@extends('layouts.user')
@section('title', 'Wishlist Saya')

@section('content')
<h1 class="text-3xl font-bold text-pink-400 mb-6">
    <i class="fas fa-heart mr-2"></i>Wishlist Saya
</h1>

@forelse($konsers as $konser)
@php
    $totalKuota   = $konser->tikets->sum('kuota');
    $totalTerjual = $konser->tikets->sum('terjual');
    $sisa         = $totalKuota - $totalTerjual;
    $persen       = $totalKuota > 0 ? round(($totalTerjual / $totalKuota) * 100) : 0;
@endphp
<div class="bg-gray-900 border border-gray-700 rounded-2xl overflow-hidden mb-6 hover:border-pink-700 transition">
    <div class="flex flex-col md:flex-row">
        <div class="md:w-48 h-48 flex-shrink-0">
            @if($konser->poster)
                <img src="{{ asset('storage/' . $konser->poster) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-pink-900 to-gray-800 flex items-center justify-center">
                    <i class="fas fa-music text-pink-400 text-4xl"></i>
                </div>
            @endif
        </div>

        <div class="p-6 flex-1 flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $konser->nama_konser }}</h2>
                        <p class="text-pink-400 font-medium">{{ $konser->artis }}</p>
                    </div>
                    
                    {{-- Form HTML Murni untuk Hapus Cepat dari Wishlist --}}
                    <form action="{{ route('user.wishlist.toggle', $konser->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-400 hover:text-red-300 transition text-xl" title="Hapus dari Wishlist">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>

                <div class="mt-3 flex flex-wrap gap-4 text-sm text-gray-400">
                    <span><i class="fas fa-map-marker-alt mr-1 text-pink-500"></i>{{ $konser->venue }}</span>
                    <span><i class="fas fa-calendar mr-1 text-pink-500"></i>{{ \Carbon\Carbon::parse($konser->tanggal)->format('d M Y') }}</span>
                    <span><i class="fas fa-clock mr-1 text-pink-500"></i>{{ $konser->jam ?? '19:00' }} WIB</span>
                </div>

                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($konser->tikets as $tiket)
                    <span class="bg-gray-800 border border-gray-600 text-gray-300 text-xs px-3 py-1 rounded-full">
                        {{ $tiket->kategori }} — Rp {{ number_format($tiket->harga) }}
                    </span>
                    @endforeach
                </div>

                {{-- Progress bar --}}
                @if($totalKuota > 0)
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                        <span><i class="fas fa-ticket mr-1"></i>Tiket Terjual</span>
                        <span class="{{ $persen >= 80 ? 'text-red-400' : ($persen >= 50 ? 'text-yellow-400' : 'text-green-400') }} font-bold">
                            {{ $persen }}% ({{ $sisa }} sisa)
                        </span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $persen >= 80 ? 'bg-red-500' : ($persen >= 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                             style="width: {{ $persen }}%"></div>
                    </div>
                </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('user.konser.show', $konser) }}"
                   class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-xl font-bold transition">
                    <i class="fas fa-ticket mr-2"></i>Lihat & Beli Tiket
                </a>
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-20 text-gray-500">
    <i class="fas fa-heart text-6xl mb-4 block text-gray-700"></i>
    <p class="text-xl">Belum ada konser di wishlist kamu</p>
    <a href="{{ route('user.home') }}" class="text-pink-400 mt-3 inline-block hover:underline">
        Cari konser sekarang →
    </a>
</div>
@endforelse
@endsection