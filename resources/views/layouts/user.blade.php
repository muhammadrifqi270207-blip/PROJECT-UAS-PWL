<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎸 Embud Creative - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
            [x-cloak] { display: none !important; }
            .light-mode { background-color: #f3f4f6 !important; color: #111827 !important; }
            .light-mode nav { background-color: #ffffff !important; border-color: #e5e7eb !important; }
            .light-mode .bg-gray-900 { background-color: #ffffff !important; }
            .light-mode .bg-gray-800 { background-color: #f9fafb !important; }
            .light-mode .bg-gray-950 { background-color: #f3f4f6 !important; }
            .light-mode .bg-gray-700  { background-color: #e5e7eb !important; }
            .light-mode .border-gray-700 { border-color: #e5e7eb !important; }
            .light-mode .text-white   { color: #111827 !important; }
            .light-mode .text-gray-300 { color: #374151 !important; }
            .light-mode .text-gray-400 { color: #6b7280 !important; }
            .light-mode .text-gray-500 { color: #9ca3af !important; }
            .light-mode .text-pink-400 { color: #db2777 !important; }
            .light-mode .hover\:text-pink-400:hover { color: #be185d !important; }
            .light-mode .bg-pink-950 { background-color: #fce7f3 !important; color: #9d174d !important; }
            .light-mode .bg-pink-600 { background-color: #db2777 !important; }
            .light-mode .bg-pink-700 { background-color: #be185d !important; }
            .light-mode .border-pink-900 { border-color: #fbcfe8 !important; }
        </style>
</head>
<body x-data="{ mobileMenu: false }" class="bg-gray-950 text-white min-h-screen transition-colors duration-300">

@php
    $readIds = \Illuminate\Support\Facades\DB::table('notification_reads')
        ->where('user_id', auth()->id())
        ->pluck('konser_id')->toArray();

    $konserBaru = \App\Models\Konser::where('status', 'aktif')
        ->where('created_at', '>=', now()->subDays(7))
        ->whereNotIn('id', $readIds)->latest()->take(5)->get();

    $konserSegera = \App\Models\Konser::where('status', 'aktif')
        ->whereBetween('tanggal', [now()->toDateString(), now()->addDays(7)->toDateString()])
        ->whereNotIn('id', $readIds)->latest()->take(5)->get();

    $totalNotif = $konserBaru->count() + $konserSegera->count();

    $genreList = ['Pop', 'Pop Punk', 'Rock', 'Dangdut', 'Campuran'];
    $genreIcon = [
        'Pop' => '🎵', 'Pop Punk' => '🎸', 'Rock' => '🤘',
        'Dangdut' => '🥁', 'Campuran' => '🎪'
    ];
@endphp

{{-- NAVBAR --}}
<nav class="bg-gray-900 border-b border-pink-900 px-6 py-4 sticky top-0 z-50 transition-colors duration-300">

    <div class="max-w-6xl mx-auto flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('user.home') }}" class="flex items-center gap-2 flex-shrink-0">
            <span class="text-xl">🎸</span>
            <span class="text-pink-400 text-xl font-bold transition-colors">Embud Creative</span>
        </a>

        {{-- Desktop Menu --}}
        <div class="hidden md:flex items-center gap-5">
            <a href="{{ route('user.home') }}"
               class="text-sm hover:text-pink-400 transition {{ request()->routeIs('user.home') ? 'text-pink-400 font-semibold' : 'text-gray-300' }}">
                <i class="fas fa-home mr-1"></i> Beranda
            </a>

            {{-- GENRE DROPDOWN --}}
            <div class="relative" x-data="{ openGenre: false }">
                <button @click="openGenre = !openGenre"
                        class="text-sm hover:text-pink-400 transition flex items-center gap-1 {{ request('genre') ? 'text-pink-400 font-semibold' : 'text-gray-300' }}">
                    <i class="fas fa-music mr-1"></i> Genre
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="openGenre ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openGenre" @click.away="openGenre = false" x-cloak
                     class="absolute left-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-50 p-1">
                    <a href="{{ route('user.home', array_merge(request()->only(['search','harga','tanggal']), ['genre' => ''])) }}"
                       class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition {{ !request('genre') ? 'bg-pink-950 text-pink-400 font-bold' : 'text-gray-300 hover:bg-gray-700' }}">
                        🎪 Semua Genre
                    </a>
                    @foreach($genreList as $g)
                    <a href="{{ route('user.home', array_merge(request()->only(['search','harga','tanggal']), ['genre' => $g])) }}"
                       class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition {{ request('genre') == $g ? 'bg-pink-950 text-pink-400 font-bold' : 'text-gray-300 hover:bg-gray-700' }}">
                        {{ $genreIcon[$g] }} {{ $g }}
                    </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('user.orders.my') }}"
               class="text-sm hover:text-pink-400 transition {{ request()->routeIs('user.orders.my') ? 'text-pink-400 font-semibold' : 'text-gray-300' }}">
                <i class="fas fa-ticket mr-1"></i> Tiket Saya
            </a>
            <a href="{{ route('user.wishlist') }}"
               class="text-sm hover:text-pink-400 transition {{ request()->routeIs('user.wishlist') ? 'text-pink-400 font-semibold' : 'text-gray-300' }}">
                <i class="fas fa-heart mr-1"></i> Wishlist
            </a>

            {{-- Notifikasi Desktop --}}
            <div class="relative" x-data="{ openNotif: false }">
                <button @click="openNotif = !openNotif" class="relative text-gray-300 hover:text-pink-400 transition flex items-center">
                    <i class="fas fa-bell text-lg"></i>
                    @if($totalNotif > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                        {{ $totalNotif > 9 ? '9+' : $totalNotif }}
                    </span>
                    @endif
                </button>
                <div x-show="openNotif" @click.away="openNotif = false" x-cloak
                     class="absolute right-0 mt-2 w-72 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50">
                    <div class="px-4 py-3 border-b border-gray-700 flex justify-between items-center">
                        <p class="text-white font-bold text-sm">🔔 Notifikasi</p>
                        <span class="text-gray-500 text-xs">{{ $totalNotif }} baru</span>
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        @if($konserBaru->count() > 0)
                        <div class="px-4 py-2"><p class="text-pink-400 text-xs font-bold uppercase">🆕 Konser Baru</p></div>
                        @foreach($konserBaru as $k)
                        <a href="{{ route('user.konser.show', $k) }}"
                           class="flex gap-3 items-start px-4 py-3 hover:bg-gray-700 transition border-b border-gray-700">
                            <div class="w-8 h-8 bg-pink-950 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-music text-pink-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold">{{ $k->nama_konser }}</p>
                                <p class="text-gray-400 text-xs">{{ $k->artis }}</p>
                                <p class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</p>
                            </div>
                            <span class="ml-auto bg-pink-900 text-pink-300 text-xs px-2 py-0.5 rounded-full">Baru</span>
                        </a>
                        @endforeach
                        @endif

                        @if($konserSegera->count() > 0)
                        <div class="px-4 py-2"><p class="text-yellow-400 text-xs font-bold uppercase">⚡ Segera</p></div>
                        @foreach($konserSegera as $k)
                        <a href="{{ route('user.konser.show', $k) }}"
                           class="flex gap-3 items-start px-4 py-3 hover:bg-gray-700 transition border-b border-gray-700">
                            <div class="w-8 h-8 bg-yellow-900 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-fire text-yellow-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold">{{ $k->nama_konser }}</p>
                                <p class="text-gray-400 text-xs">{{ $k->artis }}</p>
                                <p class="text-yellow-500 text-xs">{{ \Carbon\Carbon::parse($k->tanggal)->diffForHumans() }}</p>
                            </div>
                            <span class="ml-auto bg-yellow-900 text-yellow-400 text-xs px-2 py-0.5 rounded-full">Segera</span>
                        </a>
                        @endforeach
                        @endif

                        @if($totalNotif === 0)
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-bell-slash text-3xl mb-2 block"></i>
                            <p class="text-sm">Tidak ada notifikasi baru</p>
                        </div>
                        @endif
                    </div>
                    <div class="px-4 py-3 border-t border-gray-700">
                        <a href="{{ route('user.home') }}" class="text-pink-400 text-xs hover:underline">Lihat semua konser →</a>
                    </div>
                </div>
            </div>

            <button onclick="toggleMode()" class="text-gray-300 hover:text-pink-400 transition">
                <i id="mode-icon" class="fas fa-sun text-lg"></i>
            </button>

            {{-- Profil Dropdown Desktop --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 text-gray-300 hover:text-white text-sm focus:outline-none">
                    @if(auth()->user()->foto_profil)
                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}"
                         class="w-8 h-8 rounded-full object-cover border-2 border-pink-500">
                    @else
                    <div class="w-8 h-8 bg-pink-700 rounded-full flex items-center justify-center font-bold text-white text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    @endif
                    <span class="hidden lg:block">{{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-50 p-1">
                    <div class="px-4 py-3 border-b border-gray-700 mb-1">
                        <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-gray-400 text-xs truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('user.profile.edit') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-user-edit text-pink-400"></i> Edit Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700 rounded-lg flex items-center gap-2 transition">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mobile Header Buttons --}}
        <div class="flex md:hidden items-center gap-4">
            <div class="relative" x-data="{ openNotif: false }">
                <button @click="openNotif = !openNotif" class="relative text-gray-300">
                    <i class="fas fa-bell text-lg"></i>
                    @if($totalNotif > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center">
                        {{ $totalNotif > 9 ? '9+' : $totalNotif }}
                    </span>
                    @endif
                </button>
                <div x-show="openNotif" @click.away="openNotif = false" x-cloak
                     class="absolute right-0 mt-2 w-64 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50">
                    <div class="px-4 py-3 border-b border-gray-700">
                        <p class="text-white font-bold text-sm">🔔 Notifikasi</p>
                    </div>
                    <div class="max-h-60 overflow-y-auto">
                        @forelse($konserBaru->merge($konserSegera) as $k)
                        <a href="{{ route('user.konser.show', $k) }}"
                           class="flex gap-3 items-start px-4 py-3 hover:bg-gray-700 transition border-b border-gray-700">
                            <div class="w-7 h-7 bg-pink-950 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-music text-pink-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold">{{ $k->nama_konser }}</p>
                                <p class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</p>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-6 text-gray-500 text-xs">Tidak ada notifikasi</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <button @click="mobileMenu = !mobileMenu" class="text-gray-300 hover:text-white focus:outline-none transition-colors">
                <i class="fas fa-bars text-xl" x-show="!mobileMenu"></i>
                <i class="fas fa-times text-xl" x-show="mobileMenu" x-cloak></i>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenu" x-transition @click.away="mobileMenu = false" x-cloak
         class="md:hidden mt-4 pt-3 border-t border-pink-900 space-y-1">
        <a href="{{ route('user.home') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.home') ? 'bg-pink-950 text-pink-400 font-bold' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-home w-5 text-center"></i> Beranda
        </a>

        {{-- GENRE MOBILE --}}
        <div x-data="{ openGenreMobile: false }">
            <button @click="openGenreMobile = !openGenreMobile"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-sm {{ request('genre') ? 'bg-pink-950 text-pink-400 font-bold' : 'text-gray-300 hover:bg-gray-800' }}">
                <span class="flex items-center gap-3"><i class="fas fa-music w-5 text-center"></i> Genre</span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="openGenreMobile ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="openGenreMobile" x-cloak class="ml-6 mt-1 space-y-1">
                <a href="{{ route('user.home', array_merge(request()->only(['search','harga','tanggal']), ['genre' => ''])) }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ !request('genre') ? 'text-pink-400 font-bold' : 'text-gray-400 hover:bg-gray-800' }}">
                    🎪 Semua Genre
                </a>
                @foreach($genreList as $g)
                <a href="{{ route('user.home', array_merge(request()->only(['search','harga','tanggal']), ['genre' => $g])) }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request('genre') == $g ? 'text-pink-400 font-bold' : 'text-gray-400 hover:bg-gray-800' }}">
                    {{ $genreIcon[$g] }} {{ $g }}
                </a>
                @endforeach
            </div>
        </div>

        <a href="{{ route('user.orders.my') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.orders.my') ? 'bg-pink-950 text-pink-400 font-bold' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-ticket w-5 text-center"></i> Tiket Saya
        </a>
        <a href="{{ route('user.wishlist') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.wishlist') ? 'bg-pink-950 text-pink-400 font-bold' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-heart w-5 text-center"></i> Wishlist
        </a>
        <a href="{{ route('user.profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('user.profile.edit') ? 'bg-pink-950 text-pink-400 font-bold' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-user-edit w-5 text-center"></i> Edit Profil
        </a>

        <div class="flex items-center justify-between px-3 py-3 bg-gray-950/40 rounded-lg my-2">
            <div class="truncate max-w-[200px]">
                <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-gray-400 text-xs truncate">{{ auth()->user()->email }}</p>
            </div>
            <button onclick="toggleMode()" class="text-gray-300 hover:text-pink-400 transition p-1">
                <i id="mode-icon-mobile" class="fas fa-sun text-lg"></i>
            </button>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="pt-1">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-400 hover:bg-red-950/30 rounded-lg flex items-center gap-3 transition">
                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
            </button>
        </form>
    </div>
</nav>

{{-- KONTEN --}}
<main class="max-w-6xl mx-auto px-4 py-6">
    @if(session('success'))
    <div class="mb-4 bg-green-900 border border-green-500 text-green-300 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-900 border border-red-500 text-red-300 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    @yield('content')
</main>

{{-- CHATBOT AI (PINK PREMIUM) --}}
<div id="chatbot" x-data="{ open: false }" class="fixed bottom-6 right-4 z-50">
    <button @click="open = !open"
            class="w-14 h-14 bg-pink-600 hover:bg-pink-700 rounded-full flex items-center justify-center shadow-lg shadow-pink-600/30 transition duration-300 focus:outline-none transform hover:scale-105">
        <i class="fas fa-robot text-white text-xl" x-show="!open"></i>
        <i class="fas fa-times text-white text-xl" x-show="open" x-cloak></i>
    </button>

    <div x-show="open" x-cloak
         class="absolute bottom-16 right-0 w-72 sm:w-80 bg-gray-900 border border-pink-600 rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-pink-600 px-4 py-3 flex items-center gap-3">
            <div class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center">
                <i class="fas fa-robot text-white text-sm"></i>
            </div>
            <div>
                <p class="text-white font-bold text-sm">KonserBot 🤖</p>
                <p class="text-pink-100 text-xs">Tanya apapun tentang konser!</p>
            </div>
        </div>
        <div id="chat-messages" class="h-64 overflow-y-auto p-4 space-y-3">
            <div class="flex gap-2">
                <div class="w-6 h-6 bg-pink-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                    <i class="fas fa-robot text-white text-xs"></i>
                </div>
                <div class="bg-gray-800 rounded-2xl rounded-tl-none px-3 py-2 max-w-xs">
                    <p class="text-white text-xs">Halo! Saya KonserBot 🎸 Tanya saya tentang konser, artis, tiket, atau rekomendasi konser!</p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 p-3 flex gap-2">
            <input type="text" id="chat-input"
                   placeholder="Tanya tentang konser..."
                   class="flex-1 bg-gray-800 border border-gray-600 rounded-xl px-3 py-2 text-white text-xs focus:border-pink-500 focus:outline-none"
                   onkeypress="if(event.key==='Enter') sendChat()">
            <button onclick="sendChat()"
                    class="bg-pink-600 hover:bg-pink-700 text-white px-3 py-2 rounded-xl transition">
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </div>
    </div>
</div>

<script>
    const savedMode = localStorage.getItem('mode') || 'light';

    function applyMode(mode) {
        if (mode === 'light') {
            document.body.classList.add('light-mode');
            ['mode-icon', 'mode-icon-mobile'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.className = 'fas fa-sun text-lg';
            });
        } else {
            document.body.classList.remove('light-mode');
            ['mode-icon', 'mode-icon-mobile'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.className = 'fas fa-moon text-lg';
            });
        }
    }

    applyMode(savedMode);

    function toggleMode() {
        const isCurrentlyLight = document.body.classList.contains('light-mode');
        const nextMode = isCurrentlyLight ? 'dark' : 'light';
        localStorage.setItem('mode', nextMode);
        applyMode(nextMode);
    }

    function botBubble(html) {
        return `<div class="flex gap-2">
            <div class="w-6 h-6 bg-pink-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-robot text-white text-xs"></i>
            </div>
            <div class="bg-gray-800 rounded-2xl rounded-tl-none px-3 py-2 max-w-xs">
                <p class="text-white text-xs leading-relaxed">${html}</p>
            </div>
        </div>`;
    }

    function formatReply(text) {
        return text.replace(/\*(.*?)\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
    }

    function sendChat() {
        const input    = document.getElementById('chat-input');
        const messages = document.getElementById('chat-messages');
        const message  = input.value.trim();
        if (!message) return;

        messages.innerHTML += `<div class="flex gap-2 justify-end">
            <div class="bg-pink-600 rounded-2xl rounded-tr-none px-3 py-2 max-w-xs">
                <p class="text-white text-xs">${message}</p>
            </div>
        </div>`;

        messages.innerHTML += `<div class="flex gap-2" id="loading-msg">
            <div class="w-6 h-6 bg-pink-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                <i class="fas fa-robot text-white text-xs"></i>
            </div>
            <div class="bg-gray-800 rounded-2xl rounded-tl-none px-3 py-2">
                <p class="text-gray-400 text-xs">Sedang mengetik...</p>
            </div>
        </div>`;

        messages.scrollTop = messages.scrollHeight;
        input.value = '';

        fetch('{{ route("user.chatbot") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ message })
        })
        .then(res => res.json())
        .then(data => {
            const loadEl = document.getElementById('loading-msg');
            if(loadEl) loadEl.remove();
            messages.innerHTML += botBubble(formatReply(data.reply));
            messages.scrollTop = messages.scrollHeight;
        })
        .catch(() => {
            const loadEl = document.getElementById('loading-msg');
            if(loadEl) loadEl.remove();
            messages.innerHTML += botBubble('Maaf, terjadi kesalahan. Coba lagi!');
            messages.scrollTop = messages.scrollHeight;
        });
    }
</script>

</body>
</html>