<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Embud Creative</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-[#090d16] text-white min-h-screen flex flex-col items-center justify-center font-sans px-4 relative py-12">

    <!-- Tombol Kembali Ke Landing Page (Kiri Atas untuk Desktop/Fleksibel untuk Mobile) -->
    <div class="w-full max-w-md mx-auto mb-2 text-left">
        <a href="/" class="inline-flex items-center gap-2 text-xs text-gray-400 hover:text-pink-400 font-semibold transition group bg-[#111827]/40 border border-white/5 px-4 py-2 rounded-xl backdrop-blur-md">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> 
            Kembali ke Beranda
        </a>
    </div>

    <!-- Container Utama Form -->
    <div class="w-full max-w-md mx-auto flex flex-col items-center">
        
        <!-- Logo & Header -->
        <div class="mb-2 text-center">
            <span class="text-4xl inline-block animate-bounce mb-2">🎸</span>
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-pink-600 tracking-tight">
                Embud Creative
            </h1>
            <p class="text-gray-400 text-xs mt-1">Buat akun baru kamu</p>
        </div>

        <!-- Box Form Card -->
        <div class="w-full bg-[#111827]/60 border border-white/5 rounded-2xl p-8 shadow-2xl backdrop-blur-xl mt-6">
            
            <!-- Alert Error Validasi Laravel -->
            @if($errors->any())
            <div class="bg-red-950/80 border border-red-800 text-red-400 px-4 py-3 rounded-xl mb-5 text-xs flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-sm"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form action="/register" method="POST" class="space-y-5">
                @csrf
                
                <!-- Input Nama Lengkap -->
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-user text-pink-400"></i> Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap Kamu" 
                           class="w-full bg-[#1f2937]/50 border border-white/10 rounded-xl py-3 px-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" required />
                </div>

                <!-- Input Email -->
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-envelope text-pink-400"></i> Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kamu.com" 
                           class="w-full bg-[#1f2937]/50 border border-white/10 rounded-xl py-3 px-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" required />
                </div>
                
                <!-- Input Password -->
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-lock text-pink-400"></i> Password
                    </label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" 
                           class="w-full bg-[#1f2937]/50 border border-white/10 rounded-xl py-3 px-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" required />
                </div>

                <!-- Input Konfirmasi Password -->
                <div>
                    <label class="block text-gray-400 text-xs font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-pink-400"></i> Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password kamu" 
                           class="w-full bg-[#1f2937]/50 border border-white/10 rounded-xl py-3 px-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" required />
                </div>
                
                <!-- Tombol Submit Daftar -->
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3.5 rounded-xl text-sm transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg shadow-pink-600/20 mt-2">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <!-- Link Pindah ke Login -->
            <div class="text-center mt-6">
                <p class="text-gray-400 text-xs">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-pink-400 hover:text-pink-300 font-bold hover:underline ml-1">
                        Masuk di sini!
                    </a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>