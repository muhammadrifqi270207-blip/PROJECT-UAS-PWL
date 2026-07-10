@extends('layouts.user')
@section('title', 'Edit Profil')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-pink-400 mb-6">
        <i class="fas fa-user-edit mr-2"></i>Edit Profil
    </h1>

    {{-- FOTO & INFO PROFIL --}}
    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-6 mb-6">
        <div class="flex items-center gap-6 mb-6">
            <div class="relative">
                @if(auth()->user()->foto_profil)
                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}"
                     class="w-24 h-24 rounded-full object-cover border-4 border-pink-600">
                @else
                <div class="w-24 h-24 bg-pink-700 rounded-full flex items-center justify-center text-3xl font-bold border-4 border-pink-600">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                @endif
            </div>
            <div>
                <p class="text-white text-xl font-bold">{{ auth()->user()->name }}</p>
                <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
                <span class="bg-pink-900 text-pink-400 text-xs px-3 py-1 rounded-full font-bold mt-2 inline-block">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        {{-- Form Edit Profil --}}
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="text-gray-300 text-sm block mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-pink-500 focus:outline-none">
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="text-gray-300 text-sm block mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-pink-500 focus:outline-none">
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label class="text-gray-300 text-sm block mb-2">Foto Profil</label>
                <input type="file" name="foto_profil" accept="image/*"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-pink-500 focus:outline-none"
                       onchange="previewFoto(this)">
                <img id="preview-foto" src="" class="mt-3 w-20 h-20 rounded-full object-cover hidden border-4 border-pink-600">
                @error('foto_profil')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="w-full bg-pink-600 hover:bg-pink-700 text-white py-3 rounded-xl font-bold transition">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Form Ganti Password --}}
    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-6">
        <h2 class="text-xl font-bold text-pink-400 mb-4">
            <i class="fas fa-lock mr-2"></i>Ganti Password
        </h2>

        <form action="{{ route('user.profile.password') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="text-gray-300 text-sm block mb-2">Password Lama</label>
                <div class="relative">
                    <input type="password" name="password_lama" id="pass_lama"
                           class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-pink-500 focus:outline-none pr-12">
                    <button type="button" onclick="togglePass('pass_lama', 'eye_lama')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i id="eye_lama" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="text-gray-300 text-sm block mb-2">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="pass_baru"
                           class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-pink-500 focus:outline-none pr-12">
                    <button type="button" onclick="togglePass('pass_baru', 'eye_baru')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i id="eye_baru" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <label class="text-gray-300 text-sm block mb-2">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="pass_konfirm"
                           class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-pink-500 focus:outline-none pr-12">
                    <button type="button" onclick="togglePass('pass_konfirm', 'eye_konfirm')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i id="eye_konfirm" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-red-700 hover:bg-red-600 text-white py-3 rounded-xl font-bold transition">
                <i class="fas fa-key mr-2"></i>Ganti Password
            </button>
        </form>
    </div>
</div>

<script>
function previewFoto(input) {
    const preview = document.getElementById('preview-foto');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
@endsection