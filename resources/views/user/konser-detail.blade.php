@extends('layouts.user')
@section('title', $konser->nama_konser)

@section('content')
<div class="mb-6">
    <a href="{{ route('user.home') }}" class="text-gray-400 hover:text-white text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Info Konser --}}
    <div class="lg:col-span-1">
        <div class="bg-gray-900 border border-gray-700 rounded-2xl overflow-hidden sticky top-24">
            @if($konser->poster)
                <img src="{{ asset('storage/' . $konser->poster) }}" class="w-full h-56 object-cover">
            @else
                <div class="w-full h-56 bg-gradient-to-br from-pink-900 to-gray-800 flex items-center justify-center">
                    <i class="fas fa-music text-pink-400 text-5xl"></i>
                </div>
            @endif
            <div class="p-5">
                <h1 class="text-2xl font-bold text-white">{{ $konser->nama_konser }}</h1>
                <p class="text-pink-400 font-semibold mt-1">{{ $konser->artis }}</p>
                <div class="mt-4 space-y-2 text-sm text-gray-400">
                    <p><i class="fas fa-map-marker-alt w-5 text-pink-500"></i> {{ $konser->venue }}</p>
                    <p><i class="fas fa-calendar w-5 text-pink-500"></i> {{ \Carbon\Carbon::parse($konser->tanggal)->format('d F Y') }}</p>
                    <p><i class="fas fa-clock w-5 text-pink-500"></i> {{ $konser->jam }} WIB</p>
                </div>

                {{-- PROGRESS BAR KUOTA --}}
                @php
                    $totalKuota   = $konser->tikets->sum('kuota');
                    $totalTerjual = $konser->tikets->sum('terjual');
                    $totalSisa    = $totalKuota - $totalTerjual;
                    $persen       = $totalKuota > 0 ? round(($totalTerjual / $totalKuota) * 100) : 0;
                @endphp
                @if($totalKuota > 0)
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                        <span><i class="fas fa-ticket-alt mr-1"></i>Status Kuota</span>
                        <span class="{{ $persen >= 80 ? 'text-red-400' : ($persen >= 50 ? 'text-yellow-400' : 'text-green-400') }} font-bold">
                            {{ $persen }}% ({{ $totalSisa }} sisa)
                        </span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $persen >= 80 ? 'bg-red-500' : ($persen >= 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                             style="width: {{ $persen }}%"></div>
                    </div>
                </div>
                @endif

                {{-- COUNTDOWN TIMER --}}
                @php
                    $tanggalKonser = \Carbon\Carbon::parse($konser->tanggal . ' ' . $konser->jam);
                    $sudahLewat = $tanggalKonser->isPast();
                @endphp
                @if(!$sudahLewat)
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <p class="text-gray-500 text-xs mb-2"><i class="fas fa-hourglass-half mr-1"></i>Konser dimulai dalam:</p>
                    <div class="flex gap-3" id="countdown-{{ $konser->id }}">
                        <div class="text-center">
                            <span class="text-xl font-extrabold text-pink-400" id="days-{{ $konser->id }}">00</span>
                            <p class="text-gray-500 text-xs">Hari</p>
                        </div>
                        <span class="text-pink-400 text-xl font-bold">:</span>
                        <div class="text-center">
                            <span class="text-xl font-extrabold text-pink-400" id="hours-{{ $konser->id }}">00</span>
                            <p class="text-gray-500 text-xs">Jam</p>
                        </div>
                        <span class="text-pink-400 text-xl font-bold">:</span>
                        <div class="text-center">
                            <span class="text-xl font-extrabold text-pink-400" id="minutes-{{ $konser->id }}">00</span>
                            <p class="text-gray-500 text-xs">Menit</p>
                        </div>
                        <span class="text-pink-400 text-xl font-bold">:</span>
                        <div class="text-center">
                            <span class="text-xl font-extrabold text-pink-400" id="seconds-{{ $konser->id }}">00</span>
                            <p class="text-gray-500 text-xs">Detik</p>
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
                                    '<span class="text-green-400 font-bold text-sm">🎵 Konser sedang berlangsung!</span>';
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
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <span class="bg-gray-800 text-gray-500 text-xs px-3 py-2 rounded-xl inline-block">
                        <i class="fas fa-check-circle mr-1"></i>Konser telah selesai
                    </span>
                </div>
                @endif

                {{-- WISHLIST BUTTON --}}
                @php
                    $isWishlisted = \Illuminate\Support\Facades\DB::table('wishlists')
                        ->where('user_id', auth()->id())
                        ->where('konser_id', $konser->id)
                        ->exists();
                @endphp
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <button id="wishlist-btn" onclick="toggleWishlist({{ $konser->id }})"
                        class="flex items-center gap-2 text-sm font-bold transition {{ $isWishlisted ? 'text-red-400' : 'text-gray-400 hover:text-red-400' }}">
                        <i class="fas fa-heart text-lg"></i>
                        <span id="wishlist-text">{{ $isWishlisted ? 'Tersimpan di Wishlist' : 'Tambah ke Wishlist' }}</span>
                    </button>
                </div>

                {{-- SHARE --}}
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <p class="text-gray-500 text-xs mb-2">Bagikan konser ini:</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="https://wa.me/?text={{ urlencode('Yuk nonton ' . $konser->nama_konser . ' - ' . $konser->artis . ' pada ' . \Carbon\Carbon::parse($konser->tanggal)->format('d M Y') . ' di ' . $konser->venue . '! Beli tiket di: ' . url()->current()) }}"
                           target="_blank"
                           class="flex items-center gap-2 bg-green-800 hover:bg-green-700 text-white text-xs px-3 py-2 rounded-lg transition">
                            <i class="fab fa-whatsapp text-lg"></i> WhatsApp
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode('Yuk nonton ' . $konser->nama_konser . ' - ' . $konser->artis . '! 🎸 ' . url()->current()) }}"
                           target="_blank"
                           class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white text-xs px-3 py-2 rounded-lg transition border border-gray-600">
                            <i class="fab fa-x-twitter text-lg"></i> Twitter/X
                        </a>
                        <button onclick="copyLink()"
                                class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white text-xs px-3 py-2 rounded-lg transition border border-gray-600">
                            <i class="fas fa-link text-sm"></i> <span id="copy-text">Salin Link</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Konten Kanan --}}
    <div class="lg:col-span-2 space-y-6">

        @if($konser->deskripsi)
        <div class="bg-gray-900 border border-gray-700 rounded-2xl p-6">
            <h2 class="text-xl font-bold text-pink-400 mb-3">
                <i class="fas fa-info-circle mr-2"></i>Tentang Konser
            </h2>
            <p class="text-gray-300 leading-relaxed">{{ $konser->deskripsi }}</p>
        </div>
        @endif

        <div>
            <h2 class="text-2xl font-bold text-pink-400 mb-5">
                <i class="fas fa-ticket mr-2"></i>Pilih Kategori Tiket
            </h2>

            @forelse($konser->tikets as $tiket)
            @php $sisa = $tiket->kuota - $tiket->terjual; @endphp
            <div class="bg-gray-900 border {{ $sisa <= 0 ? 'border-gray-700 opacity-60' : 'border-gray-700 hover:border-pink-600' }} rounded-2xl p-6 mb-4 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $tiket->kategori }}</h3>
                        <p class="text-3xl font-extrabold text-green-400 mt-1">
                            Rp {{ number_format($tiket->harga) }}
                        </p>
                        <p class="text-gray-500 text-sm mt-1">
                            Sisa: <span class="{{ $sisa <= 0 ? 'text-red-400' : 'text-green-400' }} font-bold">
                                {{ $sisa }} tiket
                            </span>
                        </p>
                    </div>

                    @if($sisa > 0)
                    <button onclick="showModal('{{ $tiket->id }}', '{{ $tiket->kategori }}', '{{ number_format($tiket->harga) }}', '{{ $konser->nama_konser }}')"
                            class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-xl font-bold transition">
                        <i class="fas fa-shopping-cart mr-2"></i>Beli
                    </button>
                    @else
                    <span class="bg-gray-700 text-gray-500 px-6 py-3 rounded-xl font-bold cursor-not-allowed">
                        Habis
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-gray-500">Belum ada tiket tersedia</div>
            @endforelse
        </div>

        @if($konser->maps_url)
        <div class="bg-gray-900 border border-gray-700 rounded-2xl p-6">
            <h2 class="text-xl font-bold text-pink-400 mb-4">
                <i class="fas fa-map-marker-alt mr-2"></i>Lokasi Venue
            </h2>
            <div class="bg-gray-800 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <p class="text-white font-semibold">{{ $konser->venue }}</p>
                    <p class="text-gray-400 text-sm">Klik tombol untuk lihat lokasi di Google Maps</p>
                </div>
                <a href="{{ $konser->maps_url }}" target="_blank"
                   class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-3 rounded-xl font-bold transition flex items-center gap-2 flex-shrink-0">
                    <i class="fas fa-map-marked-alt"></i> Buka Maps
                </a>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- RATING & REVIEW --}}
<div class="bg-gray-900 border border-gray-700 rounded-2xl p-6">
    <h2 class="text-xl font-bold text-pink-400 mb-4">
        <i class="fas fa-star mr-2"></i>Rating & Review
    </h2>

    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-700">
        <div class="text-center">
            <p class="text-5xl font-extrabold text-yellow-400">{{ number_format($ratingRata, 1) }}</p>
            <div class="flex gap-1 mt-1 justify-center">
                @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star text-sm {{ $i <= round($ratingRata) ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                @endfor
            </div>
            <p class="text-gray-500 text-xs mt-1">{{ $reviews->count() }} review</p>
        </div>
    </div>

    @if($sudahBeli && !$sudahReview)
    <div class="mb-6 bg-gray-800 rounded-xl p-4">
        <h3 class="text-white font-bold mb-3">Tulis Review Kamu</h3>
        <form action="{{ route('user.review.store', $konser) }}" method="POST">
            @csrf
            <div class="flex gap-2 mb-3" id="star-rating">
                @for($i = 1; $i <= 5; $i++)
                <button type="button" onclick="setRating({{ $i }})"
                        class="text-2xl text-gray-600 hover:text-yellow-400 transition star-btn"
                        data-value="{{ $i }}">
                    <i class="fas fa-star"></i>
                </button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating-input" value="0">

            <textarea name="komentar" rows="3" placeholder="Ceritakan pengalaman kamu di konser ini..."
                      class="w-full bg-gray-700 border border-gray-600 rounded-xl px-4 py-3 text-white text-sm focus:border-pink-500 focus:outline-none mb-3"></textarea>

            <button type="submit"
                    class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-xl font-bold transition text-sm">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Review
            </button>
        </form>
    </div>
    @elseif(!$sudahBeli)
    <div class="mb-6 bg-gray-800 rounded-xl p-4 text-center text-gray-500 text-sm">
        <i class="fas fa-lock mr-2"></i>Beli tiket dulu untuk bisa review konser ini
    </div>
    @elseif($sudahReview)
    <div class="mb-6 bg-green-900 border border-green-700 rounded-xl p-4 text-center text-green-400 text-sm">
        <i class="fas fa-check-circle mr-2"></i>Kamu sudah memberikan review untuk konser ini
    </div>
    @endif

    @forelse($reviews as $review)
    <div class="border-b border-gray-800 pb-4 mb-4">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-pink-700 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0">
                {{ strtoupper(substr($review->user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <p class="text-white font-semibold text-sm">{{ $review->user->name }}</p>
                    <p class="text-gray-500 text-xs">{{ $review->created_at->format('d M Y') }}</p>
                </div>
                <div class="flex gap-1 mt-1">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                    @endfor
                </div>
                @if($review->komentar)
                <p class="text-gray-300 text-sm mt-2">{{ $review->komentar }}</p>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-6 text-gray-500 text-sm">
        <i class="fas fa-star text-3xl mb-2 block text-gray-700"></i>
        Belum ada review untuk konser ini
    </div>
    @endforelse
</div>

{{-- MODAL KONFIRMASI --}}
<div id="modalKonfirmasi"
     class="fixed inset-0 bg-black bg-opacity-70 z-50 items-center justify-center px-4"
     style="display:none;">
    <div class="bg-gray-900 border border-pink-700 rounded-2xl p-6 max-w-md w-full shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-pink-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-ticket text-pink-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white">Konfirmasi Pembelian</h3>
            <p class="text-gray-400 text-sm mt-1">Yakin ingin melanjutkan pembelian tiket ini?</p>
        </div>

        <div class="bg-gray-800 rounded-xl p-4 mb-6 space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Konser</span>
                <span class="text-white font-semibold" id="modal-konser"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Kategori</span>
                <span class="text-pink-400 font-bold" id="modal-kategori"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Harga</span>
                <span class="text-green-400 font-bold" id="modal-harga"></span>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="hideModal()"
                    class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-xl font-bold transition">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <a id="modal-link" href="#"
               class="flex-1 bg-pink-600 hover:bg-pink-700 text-white py-3 rounded-xl font-bold transition text-center">
                <i class="fas fa-check mr-2"></i>Lanjut Beli
            </a>
        </div>
    </div>
</div>

<script>
    function showModal(tiketId, kategori, harga, konser) {
        document.getElementById('modal-konser').textContent = konser;
        document.getElementById('modal-kategori').textContent = kategori;
        document.getElementById('modal-harga').textContent = 'Rp ' + harga;
        document.getElementById('modal-link').href = '{{ route("user.checkout.index", "") }}?tiket_id=' + tiketId;
        document.getElementById('modalKonfirmasi').style.display = 'flex';
    }

    function hideModal() {
        document.getElementById('modalKonfirmasi').style.display = 'none';
    }

    document.getElementById('modalKonfirmasi').addEventListener('click', function(e) {
        if (e.target === this) hideModal();
    });

    function copyLink() {
        navigator.clipboard.writeText(window.location.href);
        document.getElementById('copy-text').textContent = 'Tersalin!';
        setTimeout(() => {
            document.getElementById('copy-text').textContent = 'Salin Link';
        }, 2000);
    }

    function toggleWishlist(konserID) {
        fetch(`/home/wishlist/${konserID}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const btn  = document.getElementById('wishlist-btn');
            const text = document.getElementById('wishlist-text');
            if (data.status === 'added') {
                btn.classList.remove('text-gray-400');
                btn.classList.add('text-red-400');
                text.textContent = 'Tersimpan di Wishlist';
            } else {
                btn.classList.remove('text-red-400');
                btn.classList.add('text-gray-400');
                text.textContent = 'Tambah ke Wishlist';
            }
        });
    }

    function setRating(value) {
        document.getElementById('rating-input').value = value;
        document.querySelectorAll('.star-btn').forEach(btn => {
            const v = parseInt(btn.dataset.value);
            btn.querySelector('i').style.color = v <= value ? '#facc15' : '#4b5563';
        });
    }
</script>

@endsection