@extends('layouts.user')
@section('title', 'Beranda')

@section('content')

{{-- HERO SECTION DENGAN LAMPU ULAR MUTER FULL KELILING --}}
<div class="relative rounded-3xl p-[3px] mb-10 overflow-hidden bg-gray-950 isolate">
    
    <!-- SUNTIKAN CSS: Mengatur Animasi Ular Muter Presisi Segala Sisi -->
    <style>
        @keyframes ularMuter {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        .efek-ular-neon {
            position: absolute;
            top: 50%;
            left: 50%;
            /* Ukuran dilebarin banget (300%) biar gak ada sisi yang kepotong pas muter */
            width: 300%;
            height: 300%;
            background: conic-gradient(from 0deg, transparent 30%, #3b82f6 50%, transparent 70%);
            animation: ularMuter 4s linear infinite;
            z-index: -1;
        }
    </style>

    <!-- Lampu Ular yang Muter -->
    <div class="efek-ular-neon pointer-events-none"></div>
    
    <!-- Konten Utama di Dalam Kotak -->
    <div class="relative bg-gradient-to-b from-gray-900 to-black rounded-[21px] py-16 px-4 text-center h-full w-full z-10">
        <!-- Ornamen Cahaya Halus di Latar Belakang Dalam -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-72 h-72 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-2xl mx-auto space-y-4">
            <span class="inline-flex items-center gap-1.5 bg-blue-950 text-blue-400 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-blue-900/60 shadow-sm">
                🚀 Platform Tiket Konser No. 1
            </span>
            
            <!-- Tulisan yang Gerak Mengetik -->
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
                }" x-text="text" class="text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-400 to-blue-500 border-r-2 border-blue-400 pr-1 animate-pulse"></span>
            </h1>
            
            <!-- Teks Sub-judul yang Bergerak Mengambang Halus -->
            <p class="text-gray-400 text-sm md:text-base max-w-md mx-auto leading-relaxed animate-bounce [animation-duration:3s]">
                Beli tiket konser musik original dengan mudah, cepat, dan aman tanpa ribet.
            </p>
        </div>
    </div>
</div>

{{-- FILTER GENRE (TOMBOL) --}}
@php
    $genres = ['Pop', 'Pop Punk', 'Rock', 'Dangdut', 'Campuran'];
    $genreIcon = [
        'Pop' => '🎵', 'Pop Punk' => '🎸', 'Rock' => '🤘',
        'Dangdut' => '🥁', 'Campuran' => '🎪'
    ];
@endphp
<div class="flex flex-wrap gap-2 mb-6">
    {{-- Tombol Semua Genre: Membawa filter search, harga, tanggal yang sudah ada, tapi mengosongkan genre --}}
    <a href="{{ route('user.home', array_merge(request()->only(['search', 'harga', 'tanggal']), ['genre' => ''])) }}"
       class="px-4 py-2 rounded-full text-xs font-bold transition {{ !request('genre') ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
        🎪 Semua Genre
    </a>
    @foreach($genres as $g)
    {{-- Tombol Genre Spesifik: Menjaga filter search, harga, tanggal yang aktif, lalu menimpa/mengisi value genre --}}
    <a href="{{ route('user.home', array_merge(request()->only(['search', 'harga', 'tanggal']), ['genre' => $g])) }}"
       class="px-4 py-2 rounded-full text-xs font-bold transition {{ request('genre') == $g ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
        {{ $genreIcon[$g] ?? '🎵' }} {{ $g }}
    </a>
    @endforeach
</div>

{{-- SEARCH & FILTER BAR --}}
<div class="bg-gray-900 border border-blue-500/50 rounded-2xl p-5 mb-8 shadow-[0_0_15px_rgba(37,99,235,0.2)] hover:shadow-[0_0_25px_rgba(37,99,235,0.4)] transition-all duration-300">
    <form method="GET" action="{{ route('user.home') }}" class="flex flex-col md:flex-row gap-4">
        {{-- SUNTIKAN MODIFIKASI: Menyimpan state genre yang aktif saat form search/filter di-submit --}}
        @if(request('genre'))
            <input type="hidden" name="genre" value="{{ request('genre') }}">
        @endif

        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari konser atau artis..."
                   class="w-full bg-gray-800 border border-gray-600 rounded-xl pl-10 pr-4 py-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none">
        </div>
        <div class="md:w-48">
            <select name="harga" class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-gray-300 focus:border-blue-500 focus:outline-none">
                <option value="">Semua Harga</option>
                <option value="0-100000"       {{ request('harga') == '0-100000'       ? 'selected' : '' }}>Di bawah Rp 100.000</option>
                <option value="100000-300000"  {{ request('harga') == '100000-300000'  ? 'selected' : '' }}>Rp 100.000 - 300.000</option>
                <option value="300000-500000"  {{ request('harga') == '300000-500000'  ? 'selected' : '' }}>Rp 300.000 - 500.000</option>
                <option value="500000-99999999"{{ request('harga') == '500000-99999999'? 'selected' : '' }}>Di atas Rp 500.000</option>
            </select>
        </div>
        <div class="md:w-48">
            <select name="tanggal" class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-gray-300 focus:border-blue-500 focus:outline-none">
                <option value="">Semua Tanggal</option>
                <option value="bulan_ini" {{ request('tanggal') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="3_bulan"   {{ request('tanggal') == '3_bulan'   ? 'selected' : '' }}>3 Bulan ke Depan</option>
                <option value="tahun_ini" {{ request('tanggal') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </div>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
            <i class="fas fa-filter"></i> Filter
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
    Menampilkan <span class="text-blue-400 font-bold">{{ $konsers->count() }}</span> konser hasil pencarian:
    @if(request('search'))
        kata kunci "<span class="text-white font-semibold">{{ request('search') }}</span>"
    @endif
    @if(request('genre'))
        • genre <span class="text-blue-400 font-bold">{{ request('genre') }}</span>
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

    {{-- ELEMEN SKELETON (Hanya muncul saat isLoading true) --}}
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

    {{-- DAFTAR KONSER ASLI (Otomatis muncul setelah selesai loading) --}}
    <div x-show="!isLoading" x-cloak>
        {{-- KONSER LIST --}}
        @forelse($konsers as $konser)
        @php
            $totalKuota   = $konser->tikets->sum('kuota');
            $totalTerjual = $konser->tikets->sum('terjual');
            $totalSisa    = $totalKuota - $totalTerjual;
            $persen       = $totalKuota > 0 ? round(($totalTerjual / $totalKuota) * 100) : 0;
        @endphp
        <div class="bg-gray-900 border {{ $konser->id === $terlaris_id && $konsers->count() > 1 ? 'border-yellow-600/70 shadow-lg shadow-yellow-950/20' : 'border-gray-800' }} rounded-2xl overflow-hidden mb-6 hover:border-blue-600 transition duration-300">
            <div class="flex flex-col md:flex-row">

                {{-- Poster --}}
                <div class="md:w-48 h-48 flex-shrink-0 relative">
                    @if($konser->poster)
                        <img src="{{ asset('storage/' . $konser->poster) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-900/50 to-gray-800 flex items-center justify-center">
                            <i class="fas fa-music text-blue-400 text-4xl"></i>
                        </div>
                    @endif
                    @if($konser->id === $terlaris_id && $konsers->count() > 1)
                    <div class="absolute top-2 left-2">
                        <span class="bg-gradient-to-r from-yellow-500 to-amber-600 text-black text-[10px] font-extrabold px-2 py-1 rounded-md shadow uppercase tracking-wider">
                            🔥 Terlaris
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-4" >
                            <div>
                                <!-- Nama Konser Efek Kedap-Kedip Smooth + Gradasi Biru Keren -->
                                <h2 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-400 to-blue-200 tracking-tight animate-pulse [animation-duration:2s]">
                                    {{ $konser->nama_konser }}
                                </h2>
                                <p class="text-blue-400 font-medium text-sm mt-0.5">{{ $konser->artis }}</p>
                            </div>
                            <div class="flex gap-1.5 flex-wrap justify-end flex-shrink-0">
                                @if($konser->id === $terlaris_id && $konsers->count() > 1)
                                <span class="bg-yellow-950/50 text-yellow-400 text-[10px] uppercase font-bold px-2.5 py-1 rounded-md border border-yellow-800/60">
                                    🔥 Terlaris
                                </span>
                                @endif
                                @if($konser->genre)
                                <span class="bg-blue-950/50 text-blue-300 text-[10px] uppercase font-bold px-2.5 py-1 rounded-md border border-blue-800/60">
                                    {{ $genreIcon[$konser->genre] ?? '🎵' }} {{ $konser->genre }}
                                </span>
                                @endif
                                <span class="bg-green-950/50 text-green-400 text-[10px] uppercase font-bold px-2.5 py-1 rounded-md border border-green-800/60">
                                    Aktif
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-gray-400">
                            <span><i class="fas fa-map-marker-alt mr-1.5 text-blue-500 w-3.5"></i>{{ $konser->venue }}</span>
                            <span><i class="fas fa-calendar mr-1.5 text-blue-500 w-3.5"></i>{{ \Carbon\Carbon::parse($konser->tanggal)->format('d M Y') }}</span>
                            <span><i class="fas fa-clock mr-1.5 text-blue-500 w-3.5"></i>{{ $konser->jam }} WIB</span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach($konser->tikets as $tiket)
                            <span class="bg-gray-950 border border-gray-800 text-gray-400 text-[11px] px-3 py-1 rounded-lg">
                                <span class="text-gray-200 font-medium">{{ $tiket->kategori }}</span> — Rp {{ number_format($tiket->harga) }}
                            </span>
                            @endforeach
                        </div>

                        {{-- PROGRESS BAR STOK --}}
                        @if($totalKuota > 0)
                        <div class="mt-4 max-w-xl">
                            <div class="flex justify-between text-xs text-gray-400 mb-1">
                                <span><i class="fas fa-ticket mr-1.5 text-gray-500"></i>Tiket Terjual</span>
                                <span>
                                    <span class="{{ $persen >= 80 ? 'text-red-400' : ($persen >= 50 ? 'text-yellow-400' : 'text-green-400') }} font-bold">
                                        {{ $persen }}%
                                    </span>
                                    ({{ $totalSisa }} sisa)
                                </span>
                            </div>
                            <div class="w-full bg-gray-950 rounded-full h-1.5 border border-gray-800">
                                <div class="h-1.5 rounded-full transition-all duration-500
                                    {{ $persen >= 80 ? 'bg-gradient-to-r from-red-500 to-pink-600' : ($persen >= 50 ? 'bg-gradient-to-r from-yellow-500 to-amber-500' : 'bg-gradient-to-r from-green-500 to-emerald-500') }}"
                                     style="width: {{ $persen }}%">
                                </div>
                            </div>
                            @if($persen >= 80)
                            <p class="text-red-400 text-[11px] mt-1 font-bold animate-pulse">
                                <i class="fas fa-fire mr-1"></i>Hampir habis! Segera amankan slot sebelum kehabisan!
                            </p>
                            @endif
                        </div>
                        @endif

                        {{-- COUNTDOWN TIMER --}}
                        @php
                            $tanggalKonser = \Carbon\Carbon::parse($konser->tanggal . ' ' . $konser->jam);
                            $sudahLewat = $tanggalKonser->isPast();
                        @endphp

                        @if(!$sudahLewat)
                        <div class="mt-4 bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 inline-block">
                            <p class="text-gray-500 text-[10px] uppercase font-bold tracking-wider mb-1.5"><i class="fas fa-hourglass-half mr-1 text-blue-500"></i>Mulai dalam:</p>
                            <div class="flex gap-3 items-center" id="countdown-{{ $konser->id }}">
                                <div class="text-center">
                                    <span class="text-lg font-extrabold text-blue-400" id="days-{{ $konser->id }}">00</span>
                                    <p class="text-[9px] text-gray-500 uppercase font-medium">Hari</p>
                                </div>
                                <span class="text-blue-600 text-lg font-bold -mt-2">:</span>
                                <div class="text-center">
                                    <span class="text-lg font-extrabold text-blue-400" id="hours-{{ $konser->id }}">00</span>
                                    <p class="text-[9px] text-gray-500 uppercase font-medium">Jam</p>
                                </div>
                                <span class="text-blue-600 text-lg font-bold -mt-2">:</span>
                                <div class="text-center">
                                    <span class="text-lg font-extrabold text-blue-400" id="minutes-{{ $konser->id }}">00</span>
                                    <p class="text-[9px] text-gray-500 uppercase font-medium">Menit</p>
                                </div>
                                <span class="text-blue-600 text-lg font-bold -mt-2">:</span>
                                <div class="text-center">
                                    <span class="text-lg font-extrabold text-blue-400" id="seconds-{{ $konser->id }}">00</span>
                                    <p class="text-[9px] text-gray-500 uppercase font-medium">Detik</p>
                                </div>
                            </div>
                        </div>
                        <script>
                            (function() {
                                const target = new Date("{{ $tanggalKonser->toIso8601String() }}").getTime();
                                const id = {{ $konser->id }};
                                function update() {
                                    const now = new Date().getTime();
                                    const diff = target - now;
                                    if (diff <= 0) {
                                        document.getElementById('countdown-' + id).innerHTML =
                                            '<span class="text-emerald-400 font-bold text-xs"><i class="fas fa-play-circle mr-1"></i>🎵 Konser sedang berlangsung saat ini!</span>';
                                        return;
                                    }
                                    const days    = Math.floor(diff / (1000 * 60 * 60 * 24));
                                    const hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                    document.getElementById('days-'    + id).textContent = String(days).padStart(2, '0');
                                    document.getElementById('hours-'   + id).textContent = String(hours).padStart(2, '0');
                                    document.getElementById('minutes-' + id).textContent = String(minutes).padStart(2, '0');
                                    document.getElementById('seconds-' + id).textContent = String(seconds).padStart(2, '0');
                                }
                                update();
                                setInterval(update, 1000);
                            })();
                        </script>
                        @else
                        <div class="mt-4">
                            <span class="bg-gray-950 border border-gray-800 text-gray-500 text-xs px-3 py-1.5 rounded-xl">
                                <i class="fas fa-check-circle mr-1 text-gray-600"></i>Konser telah selesai
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('user.konser.show', $konser) }}"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition shadow-md shadow-blue-950/30">
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
            <a href="{{ route('user.home') }}" class="text-blue-400 mt-4 inline-block hover:underline text-sm font-semibold">
                <i class="fas fa-sync-alt mr-1"></i> Lihat Semua Konser Tersedia
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- FOOTER SECTION SEPERTI WEB PROFESIONAL --}}
<footer class="mt-16 bg-gray-950 border-t border-blue-900/40 rounded-t-3xl pt-12 pb-6 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        
        {{-- Kolom 1: Logo & Deskripsi --}}
        <div class="space-y-4">
            <h3 class="text-xl font-bold text-white tracking-tight">
                Embud <span class="text-blue-400">Creative</span> 🎸
            </h3>
            <p class="text-gray-500 text-xs leading-relaxed max-w-sm">
                Platform penyedia tiket konser musik original, aman, terpercaya, dan anti ribet. Nikmati pengalaman menonton musisi favoritmu tanpa cemas.
            </p>
        </div>

        {{-- Kolom 2: Tentang Kami --}}
        <div class="space-y-3">
            <h4 class="text-sm font-bold text-gray-300 uppercase tracking-wider">Tentang Kami</h4>
            <ul class="space-y-2 text-xs">
                <li>
                    <a href="https://www.instagram.com/embud_mbg?igsh=MW04aTJkZWRhYTB2dg==" 
                       target="_blank" 
                       class="text-gray-400 hover:text-blue-400 transition flex items-center gap-2">
                        <i class="fab fa-instagram text-base"></i> Instagram @embud_mbg
                    </a>
                </li>
                <li>
                    <span class="text-gray-600">Kebijakan Privasi (Coming Soon)</span>
                </li>
            </ul>
        </div>

        {{-- Kolom 3: Hubungi Kami --}}
        <div class="space-y-3">
            <h4 class="text-sm font-bold text-gray-300 uppercase tracking-wider">Contact Us</h4>
            <p class="text-gray-500 text-xs">Punya kendala pemesanan atau pertanyaan seputar tiket?</p>
            <div class="pt-1">
                <!-- Tombol WhatsApp dengan Pesan Otomatis -->
                <a href="https://wa.me/6283133703775?text=halo%20min%20bud%20masih%20ada%20tiket%20yg%20VIP%20gak" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-md shadow-emerald-950/20">
                    <i class="fab fa-whatsapp text-sm"></i> Chat Min Bud
                </a>
            </div>
        </div>

    </div>

    {{-- Garis Pembatas Bawah & Hak Cipta --}}
    <div class="border-t border-gray-900 pt-6 text-center">
        <p class="text-[11px] text-gray-600">
            &copy; 2026 Embud Creative. All rights reserved. Built for professional concert experience.
        </p>
    </div>
</footer>

@endsection