@extends('layouts.user')
@section('title', 'Beranda')

@section('content')

{{-- HERO SECTION DENGAN LAMPU ULAR MUTER FULL KELILING --}}
<div class="relative rounded-3xl p-[3px] mb-10 overflow-hidden bg-gray-950 isolate">
    <style>
        @keyframes ularMuter {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        .efek-ular-neon {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: conic-gradient(from 0deg, transparent 30%, #ec4899 50%, transparent 70%);
            animation: ularMuter 4s linear infinite;
            z-index: -1;
        }
    </style>

    <div class="efek-ular-neon pointer-events-none"></div>

    <div class="relative bg-gradient-to-b from-gray-900 to-black rounded-[21px] py-16 px-4 text-center h-full w-full z-10">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-72 h-72 bg-pink-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-2xl mx-auto space-y-4">
            <span class="inline-flex items-center gap-1.5 bg-pink-950 text-pink-400 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-pink-900/60 shadow-sm">
                🚀 Platform Tiket Konser No. 1
            </span>

            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight h-[60px] md:h-[70px] flex items-center justify-center">
                <span x-data="{ 
                    text: '', 
                    fullText: 'Temukan Konser Favoritmu!', 
                    currentIndex: 0,
                    init() {
                        let timer = setInterval(() => {
                            if (this.currentIndex < this.fullText.length) {
                                this.text += this.fullText[this.currentIndex];
                                this.currentIndex++;
                            } else {
                                clearInterval(timer);
                                setTimeout(() => {
                                    this.text = '';
                                    this.currentIndex = 0;
                                    this.init();
                                }, 3000);
                            }
                        }, 100);
                    }
                }" x-text="text" class="text-transparent bg-clip-text bg-gradient-to-r from-white via-pink-400 to-pink-500 border-r-2 border-pink-400 pr-1 animate-pulse"></span>
            </h1>

            <p class="text-gray-400 text-sm md:text-base max-w-md mx-auto leading-relaxed animate-bounce [animation-duration:3s]">
                Beli tiket konser musik original dengan mudah, cepat, dan aman tanpa ribet.
            </p>
            <p class="text-pink-400 text-sm md:text-base font-bold mono max-w-md mx-auto leading-relaxed animate-bounce [animation-duration:2s]">
                CREATED BY : Rifqi , Dino , Arika
            </p>
        </div>
    </div>
</div>

@php
    $genreIcon = [
        'Pop' => '🎵', 'Pop Punk' => '🎸', 'Rock' => '🤘',
        'Dangdut' => '🥁', 'Campuran' => '🎪'
    ];
@endphp

{{-- SEARCH & FILTER BAR --}}
<div class="bg-gray-900 border border-pink-500/50 rounded-2xl p-5 mb-8 shadow-[0_0_15px_rgba(219,39,119,0.2)] hover:shadow-[0_0_25px_rgba(219,39,119,0.4)] transition-all duration-300">
    <form method="GET" action="{{ route('user.home') }}" class="flex flex-col md:flex-row gap-4">
        @if(request('genre'))
            <input type="hidden" name="genre" value="{{ request('genre') }}">
        @endif

        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari konser atau artis..."
                   class="w-full bg-gray-800 border border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white placeholder-gray-500 focus:border-pink-500 focus:outline-none">
        </div>
        <div class="md:w-48">
            <select name="harga" class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-gray-300 focus:border-pink-500 focus:outline-none">
                <option value="">Semua Harga</option>
                <option value="0-100000"       {{ request('harga') == '0-100000'       ? 'selected' : '' }}>Di bawah Rp 100.000</option>
                <option value="100000-300000"  {{ request('harga') == '100000-300000'  ? 'selected' : '' }}>Rp 100.000 - 300.000</option>
                <option value="300000-500000"  {{ request('harga') == '300000-500000'  ? 'selected' : '' }}>Rp 300.000 - 500.000</option>
                <option value="500000-99999999"{{ request('harga') == '500000-99999999'? 'selected' : '' }}>Di atas Rp 500.000</option>
            </select>
        </div>
        <div class="md:w-48">
            <select name="tanggal" class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-gray-300 focus:border-pink-500 focus:outline-none">
                <option value="">Semua Tanggal</option>
                <option value="bulan_ini" {{ request('tanggal') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="3_bulan"   {{ request('tanggal') == '3_bulan'   ? 'selected' : '' }}>3 Bulan ke Depan</option>
                <option value="tahun_ini" {{ request('tanggal') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </div>
        <button type="submit"
                class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-lg shadow-pink-600/10">
            <i class="fas fa-filter text-xs"></i> Filter
        </button>
        @if(request('search') || request('harga') || request('tanggal') || request('genre'))
        <a href="{{ route('user.home') }}"
           class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 text-center">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
    </form>
</div>

{{-- FILTER LOG LABEL STATS --}}
@if(request('search') || request('harga') || request('tanggal') || request('genre'))
<p class="text-gray-400 text-sm mb-6 bg-gray-900/40 border border-gray-800 inline-block px-4 py-2 rounded-xl">
    Menampilkan <span class="text-pink-400 font-bold">{{ $konsers->count() }}</span> konser hasil pencarian:
    @if(request('search'))
        kata kunci "<span class="text-white font-semibold">{{ request('search') }}</span>"
    @endif
    @if(request('genre'))
        • genre <span class="text-pink-400 font-bold">{{ request('genre') }}</span>
    @endif
    @if(request('harga'))
        • budget terfilter
    @endif
    @if(request('tanggal'))
        • rentang waktu terfilter
    @endif
</p>
@endif

{{-- PEMBUNGKUS ALPINE.JS UNTUK LOGIKA SKELETON LOADING --}}
<div x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 600)">

    <div x-show="isLoading" class="space-y-6">
        @for ($i = 0; $i < 2; $i++)
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden p-6 animate-pulse flex flex-col md:flex-row gap-6">
            <div class="bg-gray-800 w-full md:w-48 h-48 rounded-xl flex-shrink-0"></div>
            <div class="flex-1 space-y-4 py-2">
                <div class="flex justify-between items-start">
                    <div class="space-y-2 w-1/2">
                        <div class="h-6 bg-gray-800 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-800 rounded w-1/2"></div>
                    </div>
                    <div class="h-6 bg-gray-800 rounded w-20"></div>
                </div>
                <div class="h-4 bg-gray-800 rounded w-1/3 mt-3"></div>
                <div class="h-10 bg-gray-800 rounded-xl w-full mt-4"></div>
            </div>
        </div>
        @endfor
    </div>

    <div x-show="!isLoading" x-cloak>

        @forelse($konsers as $konser)
        @php
            $totalKuota   = $konser->tikets->sum('kuota');
            $totalTerjual = $konser->tikets->sum('terjual');
            $totalSisa    = $totalKuota - $totalTerjual;
            $persen       = $totalKuota > 0 ? round(($totalTerjual / $totalKuota) * 100) : 0;
            $tanggalKonser = \Carbon\Carbon::parse($konser->tanggal . ' ' . $konser->jam);
            $sudahLewat    = $tanggalKonser->isPast();
        @endphp

        <div class="bg-gray-900 border {{ $konser->id === $terlaris_id && $konsers->count() > 1 ? 'border-yellow-500/50 shadow-lg shadow-yellow-950/10' : 'border-gray-800' }} rounded-2xl overflow-hidden mb-6 hover:border-pink-500/50 transition-all duration-300">
            <div class="flex flex-col md:flex-row items-stretch">

                {{-- POSTER --}}
                <div class="w-full md:w-44 md:h-56 flex-shrink-0 relative bg-gray-950 flex items-center justify-center overflow-hidden">
                    @if($konser->poster)
                        <img src="{{ asset('storage/' . $konser->poster) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-pink-950/40 to-gray-900 flex items-center justify-center min-h-[180px] md:min-h-0">
                            <i class="fas fa-music text-pink-500 text-2xl"></i>
                        </div>
                    @endif

                    @if($konser->id === $terlaris_id && $konsers->count() > 1)
                    <div class="absolute top-2 left-2">
                        <span class="bg-gradient-to-r from-yellow-500 to-amber-600 text-black text-[9px] font-black px-2 py-0.5 rounded shadow uppercase tracking-wider">
                            🔥 Terlaris
                        </span>
                    </div>
                    @endif

                    @if(!$sudahLewat)
                    <div class="absolute bottom-2 right-2">
                        <span class="bg-pink-950/80 text-pink-300 text-[9px] font-bold px-2 py-0.5 rounded border border-pink-800/60">
                            <i class="fas fa-circle text-[6px] mr-1 text-green-400"></i> Akan Datang
                        </span>
                    </div>
                    @endif
                </div>

                {{-- INFO SINGKAT --}}
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                            <div class="space-y-0.5">
                                <h2 class="text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-white via-pink-400 to-pink-200 tracking-tight">
                                    {{ $konser->nama_konser }}
                                </h2>
                                <p class="text-pink-400 font-semibold text-xs flex items-center gap-1.5">
                                    <i class="fas fa-microphone text-[10px]"></i> {{ $konser->artis }}
                                </p>
                            </div>

                            <div class="flex gap-1.5 flex-wrap items-center">
                                @if($konser->genre)
                                    <span class="bg-pink-950/60 text-pink-300 text-[10px] uppercase font-bold px-2 py-0.5 rounded border border-pink-900/50">
                                        {{ $genreIcon[$konser->genre] ?? '🎸' }} {{ $konser->genre }}
                                    </span>
                                @endif
                                <span class="bg-green-950/60 text-green-400 text-[10px] uppercase font-bold px-2 py-0.5 rounded border border-green-900/50">
                                    Active
                                </span>
                            </div>
                        </div>

                        {{-- Hanya tampil harga termurah sebagai cuplikan --}}
                        @if($konser->tikets->count() > 0)
                        <div class="mt-3 flex items-center gap-2 text-xs">
                            <span class="text-gray-500">Mulai dari</span>
                            <span class="text-pink-400 font-bold text-sm">
                                Rp {{ number_format($konser->tikets->min('harga'), 0, ',', '.') }}
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Tombol Beli Tiket --}}
                    <div class="flex-shrink-0 w-full lg:w-auto">
                        <a href="{{ route('user.konser.show', $konser) }}"
                           class="inline-flex items-center justify-center gap-2 bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-xl font-bold text-xs sm:text-sm transition shadow-md shadow-pink-950/30 w-full sm:w-auto">
                            <i class="fas fa-ticket-alt"></i> Lihat & Beli Tiket
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 text-gray-600 bg-gray-900/30 border border-gray-800 rounded-2xl">
            <i class="fas fa-search-minus text-5xl mb-4 block text-gray-700"></i>
            <p class="text-lg font-medium text-gray-400">Wah, konser yang kamu cari tidak ditemukan</p>
            <p class="text-gray-500 text-sm mt-1">Coba gunakan kata kunci lain atau bersihkan filter kamu.</p>
            <a href="{{ route('user.home') }}" class="text-pink-400 mt-4 inline-block hover:underline text-sm font-semibold">
                <i class="fas fa-sync-alt mr-1"></i> Lihat Semua Konser Tersedia
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- FOOTER --}}
<footer class="mt-16 bg-gray-950 border-t border-pink-900/40 rounded-t-3xl pt-12 pb-6 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">

        <div class="space-y-4">
            <h3 class="text-xl font-bold text-white tracking-tight">
                Embud <span class="text-pink-400">Creative</span> 🎸
            </h3>
            <p class="text-gray-500 text-xs leading-relaxed max-w-sm">
                Platform penyedia tiket konser musik original, aman, terpercaya, dan anti ribet. Nikmati pengalaman menonton musisi favoritmu tanpa cemas.
            </p>
        </div>

        <div class="space-y-3">
            <h4 class="text-sm font-bold text-gray-300 uppercase tracking-wider">Tentang Kami</h4>
            <ul class="space-y-2 text-xs">
                <li>
                    <a href="https://www.instagram.com/embud_mbg?igsh=MW04aTJkZWRhYTB2dg==" 
                       target="_blank" 
                       class="text-gray-400 hover:text-pink-400 transition flex items-center gap-2">
                        <i class="fab fa-instagram text-base"></i> Instagram @embud_mbg
                    </a>
                </li>
                <li>
                    <span class="text-gray-600">Kebijakan Privasi (Coming Soon)</span>
                </li>
            </ul>
        </div>

        <div class="space-y-3">
            <h4 class="text-sm font-bold text-gray-300 uppercase tracking-wider">Contact Us</h4>
            <p class="text-gray-500 text-xs">Punya kendala pemesanan atau pertanyaan seputar tiket?</p>
            <div class="pt-1">
                <a href="https://wa.me/6283133703775?text=halo%20min%20bud%20masih%20ada%20tiket%20yg%20VIP%20gak" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-md shadow-emerald-950/20">
                    <i class="fab fa-whatsapp text-sm"></i> Chat Min Bud
                </a>
            </div>
        </div>

    </div>

    <div class="border-t border-gray-900 pt-6 text-center">
        <p class="text-[11px] text-gray-600">
            &copy; 2026 Embud Creative. All rights reserved. Built for professional concert experience.
        </p>
    </div>
</footer>

@endsection