@extends('layouts.user')
@section('title', 'Tiket Saya')

@section('content')
<h1 class="text-3xl font-bold text-purple-400 mb-6">
    <i class="fas fa-ticket mr-2"></i>Tiket Saya
</h1>

@forelse($orders as $order)
@php
    $konserTanggal = $order->items->first()?->tiket?->konser?->tanggal;
    $konserJam = $order->items->first()?->tiket?->konser?->jam;
    $waktuKonser = $konserTanggal ? \Carbon\Carbon::parse($konserTanggal . ' ' . $konserJam) : null;
    $statusKonser = null;
    if ($waktuKonser) {
        if ($waktuKonser->isPast()) {
            $statusKonser = 'selesai';
        } elseif ($waktuKonser->diffInHours(now()) <= 24) {
            $statusKonser = 'segera';
        } else {
            $statusKonser = 'upcoming';
        }
    }
@endphp
<div class="bg-gray-900 border border-gray-700 rounded-2xl p-6 mb-4 hover:border-purple-700 transition">
    <div class="flex items-start justify-between mb-4">
        <div>
            <p class="text-gray-400 text-sm">Kode Order</p>
            <p class="text-xl font-extrabold text-purple-400 tracking-wider">{{ $order->kode_order }}</p>
            @if($order->nama_pemesan)
            <p class="text-gray-400 text-xs mt-1">Atas nama: <span class="text-white">{{ $order->nama_pemesan }}</span></p>
            @endif
        </div>
        <div class="flex items-center gap-2 flex-wrap justify-end">
            {{-- Status Tiket --}}
            <span class="px-3 py-1 rounded-full text-xs font-bold
                {{ $order->status === 'paid' ? 'bg-green-900 text-green-400' :
                  ($order->status === 'pending' ? 'bg-yellow-900 text-yellow-400' : 'bg-red-900 text-red-400') }}">
                {{ ucfirst($order->status) }}
            </span>
            {{-- Status Konser --}}
            @if($statusKonser === 'selesai')
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-700 text-gray-400">
                <i class="fas fa-check-circle mr-1"></i>Konser Selesai
            </span>
            @elseif($statusKonser === 'segera')
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-900 text-red-400 animate-pulse">
                <i class="fas fa-fire mr-1"></i>Segera Mulai!
            </span>
            @elseif($statusKonser === 'upcoming')
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-900 text-blue-400">
                <i class="fas fa-clock mr-1"></i>Akan Datang
            </span>
            @endif
            <button onclick="confirmDelete('{{ $order->id }}', '{{ $order->kode_order }}')"
                    class="bg-red-900 hover:bg-red-800 text-red-400 text-xs px-3 py-1 rounded-full font-bold transition">
                <i class="fas fa-trash mr-1"></i>Hapus
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        {{-- INFO TIKET --}}
        <div class="flex-1">
            @foreach($order->items as $item)
            <div class="flex gap-4 items-center mb-3">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-900 to-gray-800 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-music text-purple-400"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-white">{{ $item->tiket->konser->nama_konser }}</p>
                    <p class="text-gray-400 text-sm">{{ $item->tiket->konser->artis }}</p>
                    <p class="text-gray-500 text-xs mt-1">
                        <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($item->tiket->konser->tanggal)->format('d M Y') }}
                        &nbsp;|&nbsp;
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $item->tiket->konser->venue }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="bg-purple-900 text-purple-300 text-xs px-2 py-1 rounded font-bold block mb-1">
                        {{ $item->tiket->kategori }}
                    </span>
                    <p class="text-gray-400 text-xs">{{ $item->jumlah }}x tiket</p>
                    <p class="text-green-400 font-bold">Rp {{ number_format($item->subtotal) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- QR CODE --}}
        <div class="text-center flex-shrink-0">
            <p class="text-gray-500 text-xs mb-2">QR Tiket</p>
            <div class="inline-block bg-white p-3 rounded-xl">
                {!! QrCode::size(120)->generate($order->kode_order) !!}
            </div>
        </div>
    </div>

    {{-- FOOTER CARD --}}
    <div class="border-t border-gray-800 mt-4 pt-4 flex justify-between items-center">
        <p class="text-gray-500 text-xs">
            <i class="fas fa-clock mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB
        </p>
        <div class="flex items-center gap-3">
            <a href="{{ route('user.orders.download', $order) }}"
               class="bg-blue-700 hover:bg-blue-600 text-white text-xs px-4 py-2 rounded-lg font-bold transition">
                <i class="fas fa-download mr-1"></i>Download PDF
            </a>
            <p class="font-bold text-white">
                Total: <span class="text-green-400">Rp {{ number_format($order->total_harga) }}</span>
            </p>
        </div>
    </div>
</div>
@empty
<div class="text-center py-20 text-gray-500">
    <i class="fas fa-ticket text-6xl mb-4 block text-gray-700"></i>
    <p class="text-xl">Kamu belum punya tiket</p>
    <a href="{{ route('user.home') }}" class="text-purple-400 mt-3 inline-block hover:underline">
        Cari konser sekarang →
    </a>
</div>
@endforelse

{{-- MODAL HAPUS --}}
<div id="modalHapus"
     class="fixed inset-0 bg-black bg-opacity-70 z-50 items-center justify-center px-4"
     style="display:none;">
    <div class="bg-gray-900 border border-red-700 rounded-2xl p-6 max-w-md w-full shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white">Hapus Riwayat Tiket?</h3>
            <p class="text-gray-400 text-sm mt-1">Kode order: <span class="text-red-400 font-bold" id="modal-kode"></span></p>
            <p class="text-gray-500 text-xs mt-2">Tindakan ini tidak bisa dibatalkan!</p>
        </div>
        <div class="flex gap-3">
            <button onclick="closeDelete()"
                    class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-xl font-bold transition">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-700 hover:bg-red-600 text-white py-3 rounded-xl font-bold transition">
                    <i class="fas fa-trash mr-2"></i>Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(orderId, kodeOrder) {
        document.getElementById('modal-kode').textContent = kodeOrder;
        document.getElementById('deleteForm').action = '/home/orders/' + orderId;
        document.getElementById('modalHapus').style.display = 'flex';
    }

    function closeDelete() {
        document.getElementById('modalHapus').style.display = 'none';
    }

    document.getElementById('modalHapus').addEventListener('click', function(e) {
        if (e.target === this) closeDelete();
    });
</script>

@endsection