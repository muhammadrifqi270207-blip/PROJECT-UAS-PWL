@extends('layouts.app')
@section('title', 'Tambah Konser')

@section('content')
<div class="mb-6">
    <a href="{{ route('konser.index') }}" class="text-gray-400 hover:text-white text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
    <h1 class="text-3xl font-bold text-purple-400 mt-2">
        <i class="fas fa-plus-circle mr-2"></i>Tambah Konser Baru
    </h1>
</div>

<div class="bg-gray-900 border border-gray-700 rounded-xl p-6 max-w-2xl">
    <form action="{{ route('konser.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-gray-300 text-sm block mb-1">Nama Konser</label>
                <input type="text" name="nama_konser" value="{{ old('nama_konser') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none"
                       placeholder="e.g. Synchronize Festival 2026">
                @error('nama_konser')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Artis / Penampil</label>
                <input type="text" name="artis" value="{{ old('artis') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none"
                       placeholder="e.g. Coldplay, Dewa 19">
                @error('artis')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- GENRE --}}
           <div>
                <label class="text-gray-300 text-sm block mb-1">Genre Musik</label>
                <select name="genre"
                    class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none">
                    <option value="">-- Pilih Genre --</option>
                    <option value="Pop" {{ old('genre') == 'Pop' ? 'selected' : '' }}>🎵 Pop</option>
                    <option value="Pop Punk" {{ old('genre') == 'Pop Punk' ? 'selected' : '' }}>🎸 Pop Punk</option>
                    <option value="Rock" {{ old('genre') == 'Rock' ? 'selected' : '' }}>🤘 Rock</option>
                    <option value="Dangdut" {{ old('genre') == 'Dangdut' ? 'selected' : '' }}>🥁 Dangdut</option>
                    <option value="Campuran" {{ old('genre') == 'Campuran' ? 'selected' : '' }}>🎪 Campuran</option>
                </select>
                @error('genre')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="text-gray-300 text-sm block mb-1">Venue</label>
                <input type="text" name="venue" value="{{ old('venue') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none"
                       placeholder="e.g. Gelora Bung Karno, Jakarta">
                @error('venue')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none">
                @error('tanggal')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Jam Mulai</label>
                <input type="time" name="jam" value="{{ old('jam') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none">
                @error('jam')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Status</label>
                <select name="status"
                        class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none">
                    <option value="aktif">Aktif</option>
                    <option value="selesai">Selesai</option>
                    <option value="batal">Batal</option>
                </select>
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-1">Poster (opsional)</label>
                <input type="file" name="poster" accept="image/*"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none">
                @error('poster')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-gray-300 text-sm block mb-1">Deskripsi Konser (opsional)</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none"
                          placeholder="Ceritakan tentang konser ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-gray-300 text-sm block mb-1">
                    Google Maps Embed URL (opsional)
                    <span class="text-gray-500 text-xs ml-1">— dari Google Maps → Share → Embed a map → copy src="..."</span>
                </label>
                <input type="text" name="maps_url" value="{{ old('maps_url') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-purple-500 focus:outline-none"
                       placeholder="https://www.google.com/maps/embed?pb=...">
                @error('maps_url')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>Simpan Konser
            </button>
            <a href="{{ route('konser.index') }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection