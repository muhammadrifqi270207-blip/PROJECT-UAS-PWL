<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Konser;
use App\Models\ActivityLog; // <-- 1. IMPORT MODEL LOG AKTIVITAS DI SINI JIRR
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Konser::with('tikets')->where('status', 'aktif');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_konser', 'like', '%' . $request->search . '%')
                  ->orWhere('artis', 'like', '%' . $request->search . '%')
                  ->orWhere('venue', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->harga) {
            [$min, $max] = explode('-', $request->harga);
            $query->whereHas('tikets', function($q) use ($min, $max) {
                $q->whereBetween('harga', [$min, $max]);
            });
        }

        if ($request->tanggal) {
            match($request->tanggal) {
                'bulan_ini' => $query->whereMonth('tanggal', now()->month),
                '3_bulan'   => $query->whereBetween('tanggal', [now(), now()->addMonths(3)]),
                'tahun_ini' => $query->whereYear('tanggal', now()->year),
                default     => null
            };
        }

        if ($request->genre) {
            $query->where('genre', $request->genre);
        }

        $konsers = $query->latest()->get();

        $terlaris_id = $konsers->sortByDesc(function($k) {
            return $k->tikets->sum('terjual');
        })->first()?->id;

        return view('user.home', compact('konsers', 'terlaris_id'));
    }

    public function show(Konser $konser)
    {
        DB::table('notification_reads')->updateOrInsert([
            'user_id'   => auth()->id(),
            'konser_id' => $konser->id,
        ]);

        $konser->load('tikets');

        $reviews = \App\Models\Review::with('user')
            ->where('konser_id', $konser->id)
            ->latest()
            ->get();

        $ratingRata = $reviews->avg('rating');

        $sudahBeli = \App\Models\Order::where('user_id', auth()->id())
            ->whereHas('items.tiket', function($q) use ($konser) {
                $q->where('konser_id', $konser->id);
            })->exists();

        $sudahReview = \App\Models\Review::where('user_id', auth()->id())
            ->where('konser_id', $konser->id)
            ->exists();

        return view('user.konser-detail', compact('konser', 'reviews', 'ratingRata', 'sudahBeli', 'sudahReview'));
    }

    public function storeReview(Request $request, Konser $konser)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        $sudahBeli = \App\Models\Order::where('user_id', auth()->id())
            ->whereHas('items.tiket', function($q) use ($konser) {
                $q->where('konser_id', $konser->id);
            })->exists();

        if (!$sudahBeli) {
            return back()->with('error', 'Kamu hanya bisa review konser yang sudah dibeli!');
        }

        $sudahReview = \App\Models\Review::where('user_id', auth()->id())
            ->where('konser_id', $konser->id)
            ->exists();

        if ($sudahReview) {
            return back()->with('error', 'Kamu sudah pernah memberikan review untuk konser ini!');
        }

        \App\Models\Review::create([
            'user_id'   => auth()->id(),
            'konser_id' => $konser->id,
            'rating'    => $request->rating,
            'komentar'  => $request->komentar,
        ]);

        // BONUS LOG: Review konser juga dicatatkan ke log aktivitas biar makin keren
        ActivityLog::catat(auth()->id(), "Memberikan review bintang {$request->rating} pada konser '" . $konser->nama_konser . "'");

        return back()->with('success', 'Review berhasil ditambahkan! ⭐');
    }

    public function markRead(Konser $konser)
    {
        DB::table('notification_reads')->updateOrInsert([
            'user_id'   => auth()->id(),
            'konser_id' => $konser->id,
        ]);

        return response()->json(['success' => true]);
    }

    public function toggleWishlist(Konser $konser)
    {
        $userId = auth()->id();

        $exists = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('konser_id', $konser->id)
            ->exists();

        if ($exists) {
            DB::table('wishlists')
                ->where('user_id', $userId)
                ->where('konser_id', $konser->id)
                ->delete();
                
            $status = 'removed';

            // 👇 2. SUNTIK LOG: Saat konser dihapus dari wishlist via AJAX
            ActivityLog::catat($userId, "Menghapus konser '" . $konser->nama_konser . "' dari wishlist");
        } else {
            DB::table('wishlists')->insert([
                'user_id'    => $userId,
                'konser_id'  => $konser->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $status = 'added';

            // 👇 3. SUNTIK LOG: Saat konser ditambah ke wishlist via AJAX
            ActivityLog::catat($userId, "Menambahkan konser '" . $konser->nama_konser . "' ke wishlist");
        }

        return response()->json(['status' => $status]);
    }

    public function wishlist()
    {
        $wishlistIds = DB::table('wishlists')
            ->where('user_id', auth()->id())
            ->pluck('konser_id')
            ->toArray();

        $konsers = Konser::with('tikets')
            ->whereIn('id', $wishlistIds)
            ->get();

        return view('user.wishlist', compact('konsers'));
    }

    public function chatbot(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $pesan   = strtolower($request->message);
        $konsers = Konser::with('tikets')->where('status', 'aktif')->get();
        $reply   = '';

        $genres = ['pop', 'rock', 'jazz', 'electronic', 'hip-hop', 'r&b', 'indie', 'metal', 'dangdut', 'campuran'];
        $genreMatch = null;
        foreach ($genres as $g) {
            if (str_contains($pesan, $g)) {
                $genreMatch = $g;
                break;
            }
        }

        $bulanMap = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
        ];
        $bulanMatch = null;
        foreach ($bulanMap as $nama => $nomor) {
            if (str_contains($pesan, $nama)) {
                $bulanMatch = $nomor;
                break;
            }
        }

        $hasil = $konsers->filter(function($k) use ($genreMatch, $bulanMatch, $pesan) {
            $cocok = true;
            if ($genreMatch) {
                $cocok = $cocok && strtolower($k->genre) === $genreMatch;
            }
            if ($bulanMatch) {
                $cocok = $cocok && \Carbon\Carbon::parse($k->tanggal)->month == $bulanMatch;
            }
            if (!$genreMatch && !$bulanMatch) {
                $cocok = str_contains(strtolower($k->nama_konser), $pesan) ||
                         str_contains(strtolower($k->artis), $pesan);
            }
            return $cocok;
        });

        if (str_contains($pesan, 'halo') || str_contains($pesan, 'hai') || str_contains($pesan, 'hello')) {
            $reply = "Halo! Saya KonserBot 🎵 Saya bisa bantu kamu cari konser berdasarkan genre, bulan, atau nama artis. Coba tanya: *konser indie bulan juli* atau *konser Sheila On 7*!";

        } elseif (str_contains($pesan, 'semua') || str_contains($pesan, 'list') || str_contains($pesan, 'daftar')) {
            if ($konsers->isEmpty()) {
                $reply = "Maaf, saat ini belum ada konser yang tersedia 😔";
            } else {
                $reply = "🎵 Berikut semua konser yang tersedia:\n\n";
                foreach ($konsers as $k) {
                    $reply .= "• *{$k->nama_konser}* - {$k->artis}\n  📅 " . \Carbon\Carbon::parse($k->tanggal)->format('d M Y') . " | 📍 {$k->venue}\n\n";
                }
            }

        } elseif ($hasil->isNotEmpty()) {
            $reply = "🎵 Saya temukan " . $hasil->count() . " konser";
            if ($genreMatch) $reply .= " genre *" . ucfirst($genreMatch) . "*";
            if ($bulanMatch) $reply .= " bulan *" . array_search($bulanMatch, $bulanMap) . "*";
            $reply .= ":\n\n";

            foreach ($hasil as $k) {
                $tikets = $k->tikets->map(function($t) {
                    $sisa = $t->kuota - $t->terjual;
                    return "{$t->kategori}: Rp " . number_format($t->harga) . " (sisa {$sisa})";
                })->join(', ');

                $reply .= "🎤 *{$k->nama_konser}*\n";
                $reply .= "   Artis: {$k->artis}\n";
                $reply .= "   📅 " . \Carbon\Carbon::parse($k->tanggal)->format('d M Y') . " | {$k->jam} WIB\n";
                $reply .= "   📍 {$k->venue}\n";
                $reply .= "   🎟️ {$tikets}\n\n";
            }

        } else {
            $reply = "Maaf, saya tidak menemukan konser yang sesuai 😔\n\nCoba tanya:\n• *konser indie*\n• *konser bulan juli*\n• *konser Sheila On 7*\n• *daftar semua konser*";
        }

        return response()->json(['reply' => $reply]);
    }
}