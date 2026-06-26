@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- HEADER --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-purple-400">
            <i class="fas fa-chart-line mr-2"></i>Dashboard
        </h1>
        <p class="text-gray-400 mt-1">Selamat datang di sistem manajemen tiket konser</p>
    </div>
    <a href="{{ route('orders.export') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl font-bold transition flex items-center gap-2">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
</div>

{{-- STATS CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gray-900 border border-purple-800 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Konser</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $totalKonser }}</p>
            </div>
            <div class="bg-purple-900 p-3 rounded-lg">
                <i class="fas fa-music text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-blue-800 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Konser Aktif</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $konserAktif }}</p>
            </div>
            <div class="bg-blue-900 p-3 rounded-lg">
                <i class="fas fa-calendar-check text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-green-800 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Tiket Terjual</p>
                <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalTerjual) }}</p>
            </div>
            <div class="bg-green-900 p-3 rounded-lg">
                <i class="fas fa-ticket text-green-400 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-yellow-800 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Pendapatan</p>
                <p class="text-2xl font-bold text-white mt-1">Rp {{ number_format($totalPendapatan) }}</p>
            </div>
            <div class="bg-yellow-900 p-3 rounded-lg">
                <i class="fas fa-money-bill text-yellow-400 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
        <h2 class="text-lg font-bold text-purple-400 mb-4">
            <i class="fas fa-chart-bar mr-2"></i>Pendapatan 6 Bulan Terakhir
        </h2>
        <canvas id="grafikBulanan" height="200"></canvas>
    </div>

    <div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
        <h2 class="text-lg font-bold text-purple-400 mb-4">
            <i class="fas fa-chart-pie mr-2"></i>Tiket Terjual per Konser
        </h2>
        <canvas id="grafikTiket" height="200"></canvas>
    </div>
</div>

{{-- PROGRESS PENJUALAN --}}
<div class="bg-gray-900 border border-gray-700 rounded-xl p-6 mb-8">
    <h2 class="text-lg font-bold text-purple-400 mb-6">
        <i class="fas fa-chart-line mr-2"></i>Progress Penjualan Tiket per Konser
    </h2>
    @foreach($grafikKonser as $g)
    <div class="mb-4">
        <div class="flex justify-between text-sm mb-1">
            <span class="text-white font-medium">{{ $g['nama'] }}</span>
            <span class="text-gray-400">{{ $g['terjual'] }} / {{ $g['kuota'] }} tiket
                <span class="{{ $g['persen'] >= 80 ? 'text-red-400' : ($g['persen'] >= 50 ? 'text-yellow-400' : 'text-green-400') }} font-bold ml-2">
                    {{ $g['persen'] }}%
                </span>
            </span>
        </div>
        <div class="w-full bg-gray-700 rounded-full h-3">
            <div class="h-3 rounded-full transition-all duration-500
                {{ $g['persen'] >= 80 ? 'bg-red-500' : ($g['persen'] >= 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                 style="width: {{ $g['persen'] }}%">
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- TABEL KONSER TERBARU --}}
<div class="bg-gray-900 border border-gray-700 rounded-xl p-6">
    <h2 class="text-xl font-bold text-purple-400 mb-4">
        <i class="fas fa-list mr-2"></i>Konser Terbaru
    </h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-gray-400 border-b border-gray-700">
                <th class="text-left py-2">Nama Konser</th>
                <th class="text-left py-2">Artis</th>
                <th class="text-left py-2">Tanggal</th>
                <th class="text-left py-2">Status</th>
                <th class="text-left py-2">Tiket Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($konserTerbaru as $k)
            <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                <td class="py-3 font-medium">{{ $k->nama_konser }}</td>
                <td class="py-3 text-gray-400">{{ $k->artis }}</td>
                <td class="py-3 text-gray-400">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                <td class="py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-bold
                        {{ $k->status === 'aktif' ? 'bg-green-900 text-green-400' :
                          ($k->status === 'selesai' ? 'bg-gray-700 text-gray-300' : 'bg-red-900 text-red-400') }}">
                        {{ ucfirst($k->status) }}
                    </span>
                </td>
                <td class="py-3">{{ $k->total_terjual }} / {{ $k->total_tiket }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-4 text-center text-gray-500">Belum ada data konser</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bulananLabels = @json($grafikBulanan->pluck('bulan'));
    const bulananData   = @json($grafikBulanan->pluck('pendapatan'));
    const konserLabels  = @json($grafikKonser->pluck('nama'));
    const konserTerjual = @json($grafikKonser->pluck('terjual'));

    new Chart(document.getElementById('grafikBulanan'), {
        type: 'bar',
        data: {
            labels: bulananLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: bulananData,
                backgroundColor: 'rgba(139, 92, 246, 0.7)',
                borderColor: 'rgba(139, 92, 246, 1)',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#9ca3af' } },
                tooltip: {
                    callbacks: {
                        label: (ctx) => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } },
                y: {
                    ticks: {
                        color: '#9ca3af',
                        callback: (val) => 'Rp ' + val.toLocaleString('id-ID')
                    },
                    grid: { color: '#374151' }
                }
            }
        }
    });

    new Chart(document.getElementById('grafikTiket'), {
        type: 'doughnut',
        data: {
            labels: konserLabels,
            datasets: [{
                data: konserTerjual,
                backgroundColor: [
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                ],
                borderColor: '#1f2937',
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#9ca3af', padding: 15 }
                }
            }
        }
    });
</script>

@endsection