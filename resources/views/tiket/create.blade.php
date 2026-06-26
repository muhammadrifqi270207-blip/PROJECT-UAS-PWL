@extends('layouts.app')
@section('title', 'Tambah Tiket')

@section('content')
<div class="mb-6">
    <a href="{{ route('tiket.index') }}" class="text-gray-400 hover:text-white text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
    <h1 class="text-3xl font-bold text-purple-400 mt-2">
        <i class="fas fa-plus-circle mr-2"></i>Tambah Tiket Baru
    </h1>
</div>

<div class="bg-gray-900 border border-gray-700 rounded-xl p-6 max-w-xl">
    <form action="{{ route('tiket.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="text-gray-300 text-sm block mb-1">Konser</label>
                <select name="konser_id"
                        class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none">
                    <option value="">-- Pilih Konser --</option>
                    @foreach($konsers as $konser)
                    <option value="{{ $konser->id }}" {{ old('konser_id') == $konser->id ? 'selected' : '' }}>
                        {{ $konser->nama_konser }} - {{ $konser->artis }}
                    </option>
                    @endforeach
                </select>
                @error('konser_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Kategori Tiket</label>
                <input type="text" name="kategori" value="{{ old('kategori') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none"
                       placeholder="e.g. VIP, VVIP, Festival, Tribune">
                @error('kategori')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none"
                       placeholder="e.g. 500000">
                @error('harga')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Kuota Tiket</label>
                <input type="number" name="kuota" value="{{ old('kuota') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none"
                       placeholder="e.g. 1000">
                @error('kuota')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Simpan Tiket
            </button>
            <a href="{{ route('tiket.index') }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">Batal</a>
        </div>
    </form>
</div>
@endsection