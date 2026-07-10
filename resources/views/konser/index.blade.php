@extends('layouts.app')
@section('title', 'Daftar Konser')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold text-pink-400">
            <i class="fas fa-music mr-2"></i>Daftar Konser
        </h1>
        <p class="text-gray-400 mt-1">Kelola semua data konser di sini</p>
    </div>
    <a href="{{ route('konser.create') }}"
       class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Konser Baru
    </a>
</div>

<div class="bg-gray-900 border border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800">
            <tr class="text-gray-300">
                <th class="text-left px-4 py-3">No</th>
                <th class="text-left px-4 py-3">Nama Konser</th>
                <th class="text-left px-4 py-3">Poster</th>
                <th class="text-left px-4 py-3">Artis</th>
                <th class="text-left px-4 py-3">Venue</th>
                <th class="text-left px-4 py-3">Tanggal</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
           @forelse($konsers as $konser)
            <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                <td class="px-4 py-3 font-medium">{{ $konser->nama_konser }}</td>
                
                <td class="px-4 py-3">
                    @if($konser->poster)
                        <img src="{{ asset($konser->poster) }}" alt="Poster" class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-700 flex items-center justify-center rounded-lg">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                </td>

                <td class="px-4 py-3 text-gray-300">{{ $konser->artis }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $konser->venue }}</td>
                <td class="px-4 py-3 text-gray-400">
                    {{ \Carbon\Carbon::parse($konser->tanggal)->format('d M Y') }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-bold
                        {{ $konser->status === 'aktif' ? 'bg-green-900 text-green-400' :
                          ($konser->status === 'selesai' ? 'bg-gray-700 text-gray-300' : 'bg-red-900 text-red-400') }}">
                        {{ ucfirst($konser->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('konser.show', $konser) }}"
                       class="bg-pink-700 hover:bg-pink-600 text-white px-2 py-1 rounded text-xs">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('konser.edit', $konser) }}"
                       class="bg-yellow-700 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('konser.destroy', $konser) }}" method="POST"
                          onsubmit="return confirm('Hapus konser ini?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-700 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    Belum ada data konser. <a href="{{ route('konser.create') }}" class="text-pink-400">Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection