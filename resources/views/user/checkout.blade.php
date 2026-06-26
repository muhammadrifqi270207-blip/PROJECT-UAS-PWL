@extends('layouts.user')
@section('title', 'Checkout')

@section('content')
<div class="mb-6">
    <a href="{{ route('user.konser.show', $tiket->konser) }}" class="text-gray-400 hover:text-white text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
    <h1 class="text-3xl font-bold text-purple-400 mt-2">
        <i class="fas fa-shopping-cart mr-2"></i>Checkout Tiket
    </h1>
</div>

@php $sisa = $tiket->kuota - $tiket->terjual; @endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-4xl">

    {{-- FORM --}}
    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-6">
        <h2 class="text-lg font-bold text-white mb-5">Detail Pemesanan</h2>

        @if(session('error'))
        <div class="bg-red-900 border border-red-600 text-red-300 px-4 py-3 rounded-lg mb-4 text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        <form action="{{ route('user.checkout.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tiket_id" value="{{ $tiket->id }}">

            {{-- Nama Pemesan --}}
            <div class="mb-4">
                <label class="text-gray-300 text-sm block mb-2">Nama Pemesan</label>
                <input type="text" name="nama_pemesan"
                       value="{{ old('nama_pemesan', auth()->user()->name) }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-purple-500 focus:outline-none">
                @error('nama_pemesan')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="text-gray-300 text-sm block mb-2">Email</label>
                <input type="email" name="email_pemesan"
                       value="{{ old('email_pemesan', auth()->user()->email) }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-purple-500 focus:outline-none">
                @error('email_pemesan')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jumlah Tiket --}}
            <div class="mb-6">
                <label class="text-gray-300 text-sm block mb-2">Jumlah Tiket</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="changeQty(-1)"
                            class="bg-gray-700 hover:bg-gray-600 text-white w-10 h-10 rounded-lg font-bold text-xl transition">−</button>
                    <input type="number" name="jumlah" id="jumlah" value="1" min="1" max="{{ min(10, $sisa) }}"
                           class="w-20 text-center bg-gray-800 border border-gray-600 rounded-xl px-3 py-2 text-white text-xl font-bold focus:border-purple-500 focus:outline-none"
                           onchange="updateTotal()">
                    <button type="button" onclick="changeQty(1)"
                            class="bg-gray-700 hover:bg-gray-600 text-white w-10 h-10 rounded-lg font-bold text-xl transition">+</button>
                </div>
                <p class="text-gray-500 text-xs mt-1">Maks. {{ min(10, $sisa) }} tiket per transaksi</p>
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-4 rounded-xl font-bold text-lg transition">
                <i class="fas fa-lock mr-2"></i>Konfirmasi Pemesanan
            </button>
        </form>
    </div>

    {{-- RINGKASAN --}}
    <div>
        <div class="bg-gray-900 border border-gray-700 rounded-2xl p-6 sticky top-24">
            <h2 class="text-lg font-bold text-white mb-5">Ringkasan Pesanan</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Konser</span>
                    <span class="text-white font-medium">{{ $tiket->konser->nama_konser }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Artis</span>
                    <span class="text-white">{{ $tiket->konser->artis }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Tanggal</span>
                    <span class="text-white">{{ \Carbon\Carbon::parse($tiket->konser->tanggal)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Venue</span>
                    <span class="text-white">{{ $tiket->konser->venue }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Kategori</span>
                    <span class="bg-purple-900 text-purple-300 px-2 py-0.5 rounded font-bold">{{ $tiket->kategori }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Harga Satuan</span>
                    <span class="text-white">Rp {{ number_format($tiket->harga) }}</span>
                </div>
                <div class="border-t border-gray-700 pt-3 flex justify-between">
                    <span class="text-gray-400">Jumlah</span>
                    <span class="text-white font-bold" id="qty-display">1 tiket</span>
                </div>
                <div class="bg-gray-800 rounded-xl p-4 flex justify-between items-center">
                    <span class="text-gray-300 font-bold">Total Bayar</span>
                    <span class="text-2xl font-extrabold text-green-400" id="total-display">
                        Rp {{ number_format($tiket->harga) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const harga = {{ $tiket->harga }};
    const maxQty = {{ min(10, $sisa) }};

    function changeQty(delta) {
        const input = document.getElementById('jumlah');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > maxQty) val = maxQty;
        input.value = val;
        updateTotal();
    }

    function updateTotal() {
        const qty = parseInt(document.getElementById('jumlah').value) || 1;
        const total = harga * qty;
        document.getElementById('total-display').textContent =
            'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('qty-display').textContent = qty + ' tiket';
    }
</script>
@endsection