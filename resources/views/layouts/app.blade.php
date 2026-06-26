<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎵 Embud Creative - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-gray-900 border-b border-blue-900 px-4 py-3 sticky top-0 z-50"
         x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">

                {{-- LOGO --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xl">🎵</span>
                    <span class="text-lg font-bold text-blue-400">Embud Creative</span>
                </a>

                {{-- DESKTOP MENU --}}
                <div class="hidden md:flex items-center gap-5">
                    <a href="{{ route('dashboard') }}"
                       class="text-sm hover:text-blue-400 transition {{ request()->routeIs('dashboard') ? 'text-blue-400 border-b-2 border-blue-400 pb-1' : 'text-gray-300' }}">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('konser.index') }}"
                       class="text-sm hover:text-blue-400 transition {{ request()->routeIs('konser.*') ? 'text-blue-400 border-b-2 border-blue-400 pb-1' : 'text-gray-300' }}">
                        <i class="fas fa-music mr-1"></i> Daftar Konser
                    </a>
                    <a href="{{ route('tiket.index') }}"
                       class="text-sm hover:text-blue-400 transition {{ request()->routeIs('tiket.*') ? 'text-blue-400 border-b-2 border-blue-400 pb-1' : 'text-gray-300' }}">
                        <i class="fas fa-ticket mr-1"></i> Daftar Tiket
                    </a>
                    <a href="{{ route('users.index') }}"
                       class="text-sm hover:text-blue-400 transition {{ request()->routeIs('users.*') ? 'text-blue-400 border-b-2 border-blue-400 pb-1' : 'text-gray-300' }}">
                        <i class="fas fa-users mr-1"></i> Manajemen User
                    </a>
                </div>

                {{-- DESKTOP PROFIL --}}
                <div class="hidden md:block relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 text-gray-400 text-sm hover:text-blue-400 transition">
                        <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center font-bold text-xs text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden lg:block">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-xl shadow-lg z-50">
                        <div class="px-4 py-3 border-b border-gray-700">
                            <p class="text-white text-sm font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-gray-400 text-xs">{{ Auth::user()->email }}</p>
                            <span class="bg-blue-900 text-blue-300 text-xs px-2 py-0.5 rounded-full mt-1 inline-block">Admin</span>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="p-1">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700 rounded-lg flex items-center gap-2">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- MOBILE: Kanan --}}
                <div class="flex md:hidden items-center gap-3">
                    <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center font-bold text-xs text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <button @click="mobileMenu = !mobileMenu" class="text-gray-300 hover:text-white">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenu"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenu"></i>
                    </button>
                </div>
            </div>

            {{-- MOBILE MENU --}}
            <div x-show="mobileMenu" @click.away="mobileMenu = false"
                 class="md:hidden mt-3 pb-3 border-t border-gray-700 space-y-1 pt-3">

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'bg-blue-900 text-blue-400' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-home w-5"></i> Dashboard
                </a>
                <a href="{{ route('konser.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('konser.*') ? 'bg-blue-900 text-blue-400' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-music w-5"></i> Daftar Konser
                </a>
                <a href="{{ route('tiket.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('tiket.*') ? 'bg-blue-900 text-blue-400' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-ticket w-5"></i> Daftar Tiket
                </a>
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('users.*') ? 'bg-blue-900 text-blue-400' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-users w-5"></i> Manajemen User
                </a>

                <div class="border-t border-gray-700 pt-3 mt-2">
                    <div class="px-3 py-2">
                        <p class="text-white text-sm font-semibold">{{ Auth::user()->name }}</p>
                        <p class="text-gray-400 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="px-3">
                        @csrf
                        <button type="submit"
                                class="w-full text-left py-2 text-sm text-red-400 flex items-center gap-2">
                            <i class="fas fa-sign-out-alt w-5"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- CONTENT --}}
    <main class="max-w-7xl mx-auto px-4 py-6">
        @if(session('success'))
        <div class="mb-4 bg-green-900 border border-green-500 text-green-300 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-red-900 border border-red-500 text-red-300 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

</body>
</html>