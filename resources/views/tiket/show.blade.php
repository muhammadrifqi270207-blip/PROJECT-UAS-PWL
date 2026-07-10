@extends('layouts.app')
@section('title', 'Detail Konser')

@section('content')
<div class="mb-6">
    <a href="{{ route('konser.index') }}" class="text-gray-400 hover:text-white text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
    <h1 class="text-3xl font-bold text-pink-400 mt-2">
        <i class="fas fa-info-circle mr-2"></i>Detail Konser
    </h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- KOLOM KIRI: Info Konser --}}
    <div class="lg:col-span-1">
        <div class="bg-gray-900 border border-gray-700 rounded-xl p-6">

            {{-- Poster --}}
            @if($konser->poster)
                <img src="{{ asset('storage/' . $konser->poster) }}"
                     class="w-full h-48 object-cover rounded-lg mb-4 border border-gray-700">
            @else
                <div class="w-full h-48 bg-gray-800 rounded-lg mb-4 flex items-center justify-center border border-gray-700">
                    <i class="fas fa-music text-gray-600 text-4xl"></i>
                </div>
            @endif

            <h2 class="text-xl font-bold text-white">{{ $konser->nama_konser }}</h2>
            <p class="text-pink-400 font-medium mt-1">{{ $konser->artis }}</p>

            <div class="mt-4 space-y-2 text-sm text-gray-400">
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt w-4"></i>
                    <span>{{ $konser->venue }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar w-4"></i>
                    <span>{{ \Carbon\Carbon::parse($konser->tanggal)->format('d F Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock w-4"></i>
                    <span>{{ $konser->jam }} WIB</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-circle w-4"></i>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold
                        {{ $konser->status === 'aktif' ? 'bg-green-900 text-green-400' :
                          ($konser->status === 'selesai' ? 'bg-gray-700 text-gray-300' : 'bg-red-900 text-red-400') }}">
                        {{ ucfirst($konser->status) }}
                    </span>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('konser.edit', $konser) }}"
                   class="flex-1 text-center bg-yellow-700 hover:bg-yellow-600 text-white py-2 rounded-lg text-sm transition">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('konser.destroy', $konser) }}" method="POST"
                      onsubmit="return confirm('Hapus konser ini?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button class="w-full bg-red-700 hover:bg-red-600 text-white py-2 rounded-lg text-sm transition">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Daftar Tiket --}}
    <div class="lg:col-span-2">
        <div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-pink-400">
                    <i class="fas fa-ticket mr-2"></i>Tiket Tersedia
                </h3>
                <a href="{{ route('tiket.create') }}"
                   class="bg-pink-600 hover:bg-pink-700 text-white px-3 py-1 rounded-lg text-sm transition">
                    <i class="fas fa-plus mr-1"></i> Tambah Tiket
                </a>
            </div>

            @forelse($konser->tikets as $tiket)
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="bg-pink-900 text-pink-300 px-3 py-1 rounded-full text-sm font-bold">
                            {{ $tiket->kategori }}
                        </span>
                        <p class="text-green-400 font-bold mt-2 text-lg">
                            Rp {{ number_format($tiket->harga) }}
                        </p>
                    </div>
                    <div class="text-right text-sm text-gray-400">
                        <p>Kuota: <span class="text-white font-medium">{{ number_format($tiket->kuota) }}</span></p>
                        <p>Terjual: <span class="text-yellow-400 font-medium">{{ number_format($tiket->terjual) }}</span></p>
                        <p>Sisa:
                            <span class="{{ $tiket->sisa <= 0 ? 'text-red-400' : 'text-green-400' }} font-medium">
                                {{ number_format($tiket->sisa) }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mt-3">
                    @php $persen = $tiket->kuota > 0 ? ($tiket->terjual / $tiket->kuota) * 100 : 0; @endphp
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Penjualan</span>
                        <span>{{ number_format($persen, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all
                            {{ $persen >= 100 ? 'bg-red-500' : ($persen >= 75 ? 'bg-yellow-500' : 'bg-green-500') }}"
                             style="width: {{ min($persen, 100) }}%"></div>
                    </div>
                </div>

                <div class="mt-3 flex gap-2 justify-end">
                    <a href="{{ route('tiket.edit', $tiket) }}"
                       class="bg-yellow-700 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('tiket.destroy', $tiket) }}" method="POST"
                          onsubmit="return confirm('Hapus tiket ini?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-700 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-ticket text-4xl mb-3 block"></i>
                Belum ada tiket untuk konser ini.
                <a href="{{ route('tiket.create') }}" class="text-pink-400 block mt-2">+ Tambah tiket sekarang</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection