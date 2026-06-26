@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-purple-400">
            <i class="fas fa-users mr-2"></i>Manajemen User
        </h1>
        <p class="text-gray-400 mt-1">Kelola semua user yang terdaftar</p>
    </div>
</div>

{{-- STATS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-gray-900 border border-purple-800 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total User</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $totalUser }}</p>
            </div>
            <div class="bg-purple-900 p-3 rounded-lg">
                <i class="fas fa-users text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-green-800 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Transaksi</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $totalOrder }}</p>
            </div>
            <div class="bg-green-900 p-3 rounded-lg">
                <i class="fas fa-shopping-cart text-green-400 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- TABEL USER --}}
<div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
    <h2 class="text-xl font-bold text-purple-400 mb-4">
        <i class="fas fa-list mr-2"></i>Daftar User
    </h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-gray-400 border-b border-gray-700">
                <th class="text-left py-2">No</th>
                <th class="text-left py-2">Nama</th>
                <th class="text-left py-2">Email</th>
                <th class="text-left py-2">Total Order</th>
                <th class="text-left py-2">Terdaftar</th>
                <th class="text-left py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $i => $user)
            <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                <td class="py-3 text-gray-400">{{ $i + 1 }}</td>
                <td class="py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-700 rounded-full flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-white">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="py-3 text-gray-400">{{ $user->email }}</td>
                <td class="py-3">
                    <span class="bg-purple-900 text-purple-300 text-xs px-2 py-1 rounded-full font-bold">
                        {{ $user->orders_count }} order
                    </span>
                </td>
                <td class="py-3 text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                <td class="py-3">
                    <button onclick="confirmHapus('{{ $user->id }}', '{{ $user->name }}')"
                            class="bg-red-900 hover:bg-red-800 text-red-400 text-xs px-3 py-1 rounded-lg font-bold transition">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-4 text-center text-gray-500">Belum ada user terdaftar</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL HAPUS --}}
<div id="modalHapus"
     class="fixed inset-0 bg-black bg-opacity-70 z-50 items-center justify-center px-4"
     style="display:none;">
    <div class="bg-gray-900 border border-red-700 rounded-2xl p-6 max-w-md w-full shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-times text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white">Hapus User?</h3>
            <p class="text-gray-400 text-sm mt-1">User: <span class="text-red-400 font-bold" id="modal-nama"></span></p>
            <p class="text-gray-500 text-xs mt-2">Semua data order user ini juga akan terhapus!</p>
        </div>
        <div class="flex gap-3">
            <button onclick="closeModal()"
                    class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-xl font-bold transition">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <form id="hapusForm" method="POST" class="flex-1">
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
    function confirmHapus(userId, nama) {
        document.getElementById('modal-nama').textContent = nama;
        document.getElementById('hapusForm').action = '/users/' + userId;
        document.getElementById('modalHapus').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalHapus').style.display = 'none';
    }

    document.getElementById('modalHapus').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endsection