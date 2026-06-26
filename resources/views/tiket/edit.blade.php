@extends('layouts.app')
@section('title', 'Edit Tiket')

@section('content')
<div class="mb-6">
    <a href="{{ route('tiket.index') }}" class="text-gray-400 hover:text-white text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
    <h1 class="text-3xl font-bold text-yellow-400 mt-2">
        <i class="fas fa-edit mr-2"></i>Edit Tiket
    </h1>
</div>

<div class="bg-gray-900 border border-gray-700 rounded-xl p-6 max-w-xl">
    <form action="{{ route('tiket.update', $tiket) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label class="text-gray-300 text-sm block mb-1">Konser</label>
                <select name="konser_id"
                        class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-yellow-500 focus:outline-none">
                    @foreach($konsers as $konser)
                    <option value="{{ $konser->id }}"
                        {{ $tiket->konser_id == $konser->id ? 'selected' : '' }}>
                        {{ $konser->nama_konser }} - {{ $konser->artis }}
                    </option>
                    @endforeach
                </select>
                @error('konser_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Kategori Tiket</label>
                <input type="text" name="kategori" value="{{ old('kategori', $tiket->kategori) }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-yellow-500 focus:outline-none">
                @error('kategori')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $tiket->harga) }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-yellow-500 focus:outline-none">
                @error('harga')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Kuota Tiket</label>
                <input type="number" name="kuota" value="{{ old('kuota', $tiket->kuota) }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-yellow-500 focus:outline-none">
                @error('kuota')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Field terjual hanya ada di edit, tidak di create --}}
            <div>
                <label class="text-gray-300 text-sm block mb-1">Jumlah Terjual</label>
                <input type="number" name="terjual" value="{{ old('terjual', $tiket->terjual) }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-yellow-500 focus:outline-none">
                <p class="text-gray-500 text-xs mt-1">Update manual jika ada penjualan baru</p>
                @error('terjual')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Update Tiket
            </button>
            <a href="{{ route('tiket.index') }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">Batal</a>
        </div>
    </form>
</div>
@endsection