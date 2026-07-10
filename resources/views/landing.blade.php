<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embud Creative - Platform Tiket Konser</title>
    <meta name="description" content="Beli tiket konser musik resmi dengan mudah, cepat, dan aman di Embud Creative. E-ticket terenkripsi dengan QR Code unik.">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎸</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(219, 39, 119, 0.3); }
            50% { box-shadow: 0 0 40px rgba(219, 39, 119, 0.6); }
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        @keyframes marquee2 {
            0% { transform: translateX(100%); }
            100% { transform: translateX(0%); }
        }
 
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delay { animation: float 6s ease-in-out infinite; animation-delay: 1s; }
        .animate-float-delay2 { animation: float 6s ease-in-out infinite; animation-delay: 2s; }
 
        .fade-in-up { opacity: 0; animation: fadeInUp 0.8s ease forwards; }
        .fade-in { opacity: 0; animation: fadeIn 1s ease forwards; }
 
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
 
        .pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .bounce-slow { animation: bounce-slow 3s ease-in-out infinite; }
 
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
        }
 
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s ease;
        }
        .btn-shine:hover::before {
            left: 100%;
        }
 
        .animate-marquee { animation: marquee 25s linear infinite; }
        .animate-marquee2 { animation: marquee2 25s linear infinite; }
        .marquee-wrapper:hover .animate-marquee,
        .marquee-wrapper:hover .animate-marquee2 {
            animation-play-state: paused;
        }
 
        @media (max-width: 768px) {
            .animate-float, .animate-float-delay, .animate-float-delay2 {
                animation-duration: 8s;
            }
        }
    </style>
</head>
<body class="bg-gray-950 text-white min-h-screen overflow-x-hidden">
 
    {{-- 1. NAVBAR --}}
    <nav class="px-4 sm:px-6 py-4 sm:py-5 flex items-center justify-between max-w-6xl mx-auto fade-in">
        <div class="flex items-center gap-2">
            <span class="text-xl sm:text-2xl bounce-slow inline-block">🎸</span>
            <span class="text-lg sm:text-xl font-bold text-pink-400">Embud Creative</span>
        </div>
        <a href="{{ route('login') }}"
           class="btn-shine bg-pink-600 hover:bg-pink-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl font-bold text-sm sm:text-base transition-all hover:scale-105">
            <i class="fas fa-sign-in-alt mr-1 sm:mr-2"></i>Masuk
        </a>
    </nav>
 
    {{-- 2. HERO SECTION --}}
    <div class="relative overflow-hidden">
        <div class="absolute top-10 left-1/4 w-48 sm:w-72 h-48 sm:h-72 bg-pink-600/20 rounded-full blur-3xl pointer-events-none animate-float"></div>
        <div class="absolute top-40 right-1/4 w-40 sm:w-60 h-40 sm:h-60 bg-pink-500/10 rounded-full blur-3xl pointer-events-none animate-float-delay"></div>
        <div class="absolute bottom-0 left-1/3 w-56 sm:w-80 h-56 sm:h-80 bg-pink-700/10 rounded-full blur-3xl pointer-events-none animate-float-delay2"></div>
 
        <div class="absolute top-24 left-6 sm:left-10 text-pink-500/20 text-3xl sm:text-4xl animate-float hidden md:block">
            <i class="fas fa-music"></i>
        </div>
        <div class="absolute top-60 right-10 sm:right-16 text-pink-400/20 text-4xl sm:text-5xl animate-float-delay hidden md:block">
            <i class="fas fa-guitar"></i>
        </div>
        <div class="absolute bottom-20 left-12 sm:left-20 text-pink-600/20 text-2xl sm:text-3xl animate-float-delay2 hidden md:block">
            <i class="fas fa-headphones"></i>
        </div>
 
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-12 sm:py-20 text-center">
            <span class="fade-in-up inline-flex items-center gap-1.5 bg-pink-950 text-pink-400 text-[10px] sm:text-xs font-bold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full uppercase tracking-wider border border-pink-900/60 mb-4 sm:mb-6">
                🚀 Platform Tiket Konser No. 1
            </span>
 
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight">
                Temukan & Beli Tiket <br class="md:hidden">
                <span id="typing-text" class="text-pink-500 border-r-4 border-pink-500 pr-1 animate-pulse"></span>
            </h1>
 
            <p class="fade-in-up delay-200 text-gray-400 text-sm sm:text-base md:text-lg max-w-xs sm:max-w-2xl mx-auto mb-8 sm:mb-10">
                Beli tiket konser musik original dengan mudah, cepat, dan aman tanpa ribet.
            </p>
 
            <div class="fade-in-up delay-300 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4 sm:px-0">
                <a href="{{ route('register') }}"
                   class="btn-shine pulse-glow bg-pink-600 hover:bg-pink-700 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold transition-all hover:scale-105 text-base sm:text-lg w-full sm:w-auto">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </a>
                <a href="{{ route('login') }}"
                   class="btn-shine bg-gray-800 hover:bg-gray-700 border border-gray-700 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold transition-all hover:scale-105 text-base sm:text-lg w-full sm:w-auto">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Akun
                </a>
            </div>
        </div>
    </div>
 
   {{-- SECTION KONSER POPULER --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 fade-in-up">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-white">
                    Event <span class="text-pink-400">Terbaru Pekan Ini</span>
                </h2>
                <p class="text-gray-500 text-xs sm:text-sm mt-1">Jangan sampai kehabisan, beli tiketnya sekarang juga!</p>
            </div>
        </div>
 
       <!-- Grid Kartu Konser Real-time -->
       <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
 
           @forelse($konsers as $konser)
               <div class="bg-gray-900 border {{ $konser->id === $terlaris_id ? 'border-yellow-500/50' : 'border-gray-800' }} rounded-2xl overflow-hidden card-hover flex flex-col justify-between">
                   <div class="relative bg-gray-800 h-48 flex items-center justify-center text-gray-600">
                       @if($konser->poster)
                           <img src="{{ asset('storage/' . $konser->poster) }}" alt="Poster konser {{ $konser->nama_konser }}" loading="lazy" class="w-full h-full object-cover">
                        @else
                           <i class="fas fa-images text-4xl"></i>
                        @endif
 
                        @if($konser->id === $terlaris_id)
                        <span class="absolute top-3 left-3 bg-gradient-to-r from-yellow-500 to-amber-600 text-black text-[10px] font-black px-2.5 py-1 rounded-full uppercase">
                            🔥 Terlaris
                        </span>
                        @else
                        <span class="absolute top-3 left-3 bg-pink-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                            Aktif
                        </span>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-white font-bold text-lg mb-1 truncate">{{ $konser->nama_konser }}</h3>
                        <p class="text-gray-400 text-xs mb-2 truncate"><i class="fas fa-microphone mr-1 text-pink-400"></i> {{ $konser->artis }}</p>
                        <p class="text-pink-400 text-xs font-semibold mb-3">
                            <i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($konser->tanggal)->translatedFormat('d F Y') }}
                        </p>
 
                        <div class="flex items-center justify-between pt-3 border-t border-gray-800 mt-auto">
                            <div>
                                <p class="text-gray-500 text-[10px] uppercase font-bold tracking-wider">Mulai Dari</p>
                                <p class="text-white font-extrabold text-sm">
                                    Rp {{ number_format($konser->tikets->min('harga'), 0, ',', '.') }}
                                </p>
                            </div>
                            <a href="{{ route('login') }}" class="bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                                Beli Tiket
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-12 bg-gray-900/50 border border-gray-800 rounded-2xl">
                    <i class="fas fa-calendar-times text-gray-600 text-4xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Belum ada konser aktif saat ini. Cek kembali nanti!</p>
                </div>
            @endforelse
 
        </div>
 
    {{-- 3. SECTION TEKS BERJALAN (MARQUEE) --}}
    <div class="w-full bg-pink-600 text-white py-3.5 overflow-hidden shadow-md my-6 marquee-wrapper">
        <div class="relative flex max-w-full overflow-hidden">
            <div class="animate-marquee whitespace-nowrap flex items-center gap-12 text-sm font-semibold tracking-wide">
                <span>🔥PROMO TERBATAS: Diskon Tiket 10% untuk pengguna baru dengan kode: <span class="promo-code bg-white text-pink-600 px-2 py-0.5 rounded text-xs font-bold cursor-pointer" title="Klik untuk salin kode">EMBUDGANTENG</span></span>
                <span>⚡INFO: Dapatkan Tiket Konser Dengan Harga Terjangkau Hanya Disini!</span>
                <span>🔒JAMINAN: 100% Tiket Resmi & Aman terenkripsi dengan E-Ticket unik.</span>
            </div>
 
            <div class="absolute top-0 animate-marquee2 whitespace-nowrap flex items-center gap-12 text-sm font-semibold tracking-wide" aria-hidden="true">
                <span>🔥PROMO TERBATAS: Diskon Tiket 10% untuk pengguna baru dengan kode: <span class="bg-white text-pink-600 px-2 py-0.5 rounded text-xs font-bold">EMBUDGANTENG</span></span>
                <span>⚡INFO: Dapatkan Tiket Konser Dengan Harga Terjangkau Hanya Disini!</span>
                <span>🔒JAMINAN: 100% Tiket Resmi & Aman terenkripsi dengan E-Ticket unik.</span>
            </div>         
        </div>
    </div>
 
    {{-- 4. FITUR UNGGULAN --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
        <h2 class="fade-in-up text-2xl sm:text-3xl font-bold text-center text-white mb-8 sm:mb-12">
            Kenapa Pilih <span class="text-pink-400">Embud Creative?</span>
        </h2>
 
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            <div class="card-hover fade-in-up delay-100 bg-gray-900 border border-gray-800 rounded-2xl p-5 sm:p-6 hover:border-pink-600/50">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-pink-950 rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                    <i class="fas fa-bolt text-pink-400 text-lg sm:text-xl"></i>
                </div>
                <h3 class="text-white font-bold text-base sm:text-lg mb-2">Cepat & Mudah</h3>
                <p class="text-gray-400 text-xs sm:text-sm">Beli tiket konser hanya dalam beberapa klik tanpa proses yang rumit.</p>
            </div>
 
            <div class="card-hover fade-in-up delay-200 bg-gray-900 border border-gray-800 rounded-2xl p-5 sm:p-6 hover:border-pink-600/50">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-pink-950 rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                    <i class="fas fa-shield-alt text-pink-400 text-lg sm:text-xl"></i>
                </div>
                <h3 class="text-white font-bold text-base sm:text-lg mb-2">Aman & Terpercaya</h3>
                <p class="text-gray-400 text-xs sm:text-sm">Setiap tiket dilengkapi QR Code unik sebagai bukti pembelian sah.</p>
            </div>
 
            <div class="card-hover fade-in-up delay-300 bg-gray-900 border border-gray-800 rounded-2xl p-5 sm:p-6 hover:border-pink-600/50 sm:col-span-2 md:col-span-1">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-pink-950 rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                    <i class="fas fa-robot text-pink-400 text-lg sm:text-xl"></i>
                </div>
                <h3 class="text-white font-bold text-base sm:text-lg mb-2">Asisten AI</h3>
                <p class="text-gray-400 text-xs sm:text-sm">KonserBot siap bantu kamu cari konser yang cocok dengan preferensimu.</p>
            </div>
        </div>
    </div>
 
    {{-- 5. SECTION FAQ SINGKAT --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 sm:py-16 fade-in-up">
        <h2 class="text-2xl sm:text-3xl font-bold text-center text-white mb-8">
            Pertanyaan <span class="text-pink-400">Sering Diajukan</span>
        </h2>
 
        <div class="space-y-4">
            <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl card-hover">
                <h3 class="text-base sm:text-lg font-bold text-pink-400 mb-2 flex items-center gap-2">
                    <i class="fas fa-question-circle"></i> Apakah tiket yang dijual di sini resmi?
                </h3>
                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                    100% resmi! Embud Creative bekerja sama langsung dengan promotor konser dan artis terkait untuk memastikan seluruh e-tiket yang kamu beli sah dan aman terenkripsi.
                </p>
            </div>
 
            <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl card-hover">
                <h3 class="text-base sm:text-lg font-bold text-pink-400 mb-2 flex items-center gap-2">
                    <i class="fas fa-question-circle"></i> Bagaimana cara saya menerima e-ticket?
                </h3>
                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                    Setelah pembayaran kamu berhasil diverifikasi, e-ticket yang dilengkapi dengan QR Code unik otomatis muncul di halaman "Pesanan Saya" dan bisa langsung kamu gunakan untuk masuk ke venue.
                </p>
            </div>
 
            <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl card-hover">
                <h3 class="text-base sm:text-lg font-bold text-pink-400 mb-2 flex items-center gap-2">
                    <i class="fas fa-question-circle"></i> Metode pembayaran apa saja yang didukung?
                </h3>
                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                    Kami mendukung berbagai metode pembayaran instan mulai dari e-wallet (GoPay, OVO, Dana), Virtual Account bank ternama, hingga transfer bank manual untuk memudahkan transaksi kamu.
                </p>
            </div>
        </div>
    </div>
 
    {{-- SECTION PARTNER & SOSMED (TRUST BADGE) --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 border-t border-gray-900 fade-in">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
            
            <!-- Kiri: Metode Pembayaran -->
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-3">Metode Pembayaran Didukung</p>
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 text-gray-400 text-lg sm:text-xl">
                    <span class="bg-gray-900 border border-gray-800 px-3 py-1.5 rounded-xl font-bold tracking-tight text-sm hover:text-pink-400 transition">GoPay</span>
                    <span class="bg-gray-900 border border-gray-800 px-3 py-1.5 rounded-xl font-bold tracking-tight text-sm hover:text-pink-400 transition">OVO</span>
                    <span class="bg-gray-900 border border-gray-800 px-3 py-1.5 rounded-xl font-bold tracking-tight text-sm hover:text-pink-400 transition">Dana</span>
                    <span class="bg-gray-900 border border-gray-800 px-3 py-1.5 rounded-xl text-sm font-semibold hover:text-pink-400 transition"><i class="fas fa-university mr-1"></i> Virtual Account</span>
                </div>
            </div>
 
            <!-- Kanan: Sosial Media -->
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-3 text-center md:text-right">Ikuti Kami</p>
                <div class="flex justify-center md:justify-end gap-3">
                    <!-- Link Instagram -->
                    <a href="https://www.instagram.com/embud_mbg?igsh=MW04aTJkZWRhYTB2dg==" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gray-900 border border-gray-800 hover:border-pink-500 hover:text-pink-400 rounded-xl flex items-center justify-center transition-all hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <!-- Link TikTok -->
                    <a href="https://www.tiktok.com/@kiww270207?_r=1&_t=ZS-97b38GETOTZ" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gray-900 border border-gray-800 hover:border-pink-500 hover:text-pink-400 rounded-xl flex items-center justify-center transition-all hover:scale-110">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <!-- Link X / Twitter -->
                    <a href="#" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gray-900 border border-gray-800 hover:border-pink-500 hover:text-pink-400 rounded-xl flex items-center justify-center transition-all hover:scale-110">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
 
    {{-- 6. FOOTER --}}
    <footer class="border-t border-gray-800 py-6 sm:py-8 text-center fade-in px-4">
        <p class="text-gray-500 text-xs sm:text-sm">
            &copy; 2026 Embud Creative. All rights reserved.
        </p>
    </footer>
 
    {{-- JAVASCRIPT LOGIC --}}
    <script>
        // Intersection Observer untuk Animasi Scroll Fade-In
        const observerOptions = { threshold: 0.1 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);
 
        document.querySelectorAll('.fade-in-up, .fade-in').forEach(el => {
            observer.observe(el);
        });
 
        // Script Animasi Mengetik (Typing Effect)
        const txtElement = document.getElementById('typing-text');
        const words = ['Konser Favoritmu.', 'Festival Musik Hits.', 'Event Seru Terdekat.'];
        let txtIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
 
        function typeEffect() {
            const currentWord = words[txtIndex];
            
            if (isDeleting) {
                txtElement.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                txtElement.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }
 
            let typeSpeed = isDeleting ? 50 : 100;
 
            if (!isDeleting && charIndex === currentWord.length) {
                typeSpeed = 2000; 
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                txtIndex = (txtIndex + 1) % words.length; 
                typeSpeed = 500; 
            }
 
            setTimeout(typeEffect, typeSpeed);
        }
 
        document.addEventListener('DOMContentLoaded', () => {
            typeEffect();
        });
 
        // Salin kode promo ke clipboard saat diklik
        document.querySelectorAll('.promo-code').forEach(el => {
            el.addEventListener('click', () => {
                navigator.clipboard.writeText(el.textContent.trim()).then(() => {
                    const original = el.textContent;
                    el.textContent = 'Tersalin!';
                    setTimeout(() => { el.textContent = original; }, 1500);
                });
            });
        });
    </script>
</body>
</html>
 