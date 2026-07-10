@extends('layouts.app')
@section('title', 'Daftar Tiket')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-pink-400">
            <i class="fas fa-ticket mr-2"></i>Daftar Tiket
        </h1>
        <p class="text-gray-400 mt-1">Kelola semua kategori tiket konser</p>
    </div>
    <a href="{{ route('tiket.create') }}"
       class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Tiket Baru
    </a>
</div>

<div class="bg-gray-900 border border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800">
            <tr class="text-gray-300">
                <th class="text-left px-4 py-3">No</th>
                <th class="text-left px-4 py-3">Konser</th>
                <th class="text-left px-4 py-3">Kategori</th>
                <th class="text-left px-4 py-3">Harga</th>
                <th class="text-left px-4 py-3">Kuota</th>
                <th class="text-left px-4 py-3">Terjual</th>
                <th class="text-left px-4 py-3">Sisa</th>
                <th class="text-left px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tikets as $tiket)
            <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                <td class="px-4 py-3 font-medium">{{ $tiket->konser->nama_konser }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 bg-pink-900 text-pink-300 rounded text-xs font-bold">
                        {{ $tiket->kategori }}
                    </span>
                </td>
                <td class="px-4 py-3 text-green-400">Rp {{ number_format($tiket->harga) }}</td>
                <td class="px-4 py-3">{{ number_format($tiket->kuota) }}</td>
                <td class="px-4 py-3">{{ number_format($tiket->terjual) }}</td>
                <td class="px-4 py-3">
                    <span class="{{ $tiket->sisa <= 0 ? 'text-red-400' : 'text-green-400' }}">
                        {{ number_format($tiket->sisa) }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('tiket.edit', $tiket) }}"
                       class="bg-yellow-700 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('tiket.destroy', $tiket) }}" method="POST"
                          onsubmit="return confirm('Hapus tiket ini?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-700 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                    Belum ada data tiket. <a href="{{ route('tiket.create') }}" class="text-pink-400">Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection