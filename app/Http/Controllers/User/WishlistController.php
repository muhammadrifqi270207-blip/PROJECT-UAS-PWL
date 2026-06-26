<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Konser;
use App\Models\ActivityLog; // Import log aktivitas biar tercatat
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Tampilan halaman daftar wishlist milik user
    public function index()
    {
        $wishlists = Wishlist::with('konser')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.wishlist', compact('wishlists'));
    }

    // Logika pasang & lepas wishlist (Sistem Toggle)
    public function toggle(Konser $konser)
    {
        $userId = auth()->id();
        
        // Cek apakah konser ini sudah ada di wishlist user
        $wishlist = Wishlist::where('user_id', $userId)
                            ->where('konser_id', $konser->id)
                            ->first();

        if ($wishlist) {
            // JIKA SUDAH ADA: Hapus dari wishlist (Unwishlist)
            $wishlist->delete();

            // Catat ke log aktivitas akun
            ActivityLog::catat($userId, "Menghapus konser '" . $konser->nama_konser . "' dari wishlist");

            return back()->with('success', 'Konser dihapus dari wishlist! 💔');
        } else {
            // JIKA BELUM ADA: Tambahkan ke wishlist
            Wishlist::create([
                'user_id' => $userId,
                'konser_id' => $konser->id
            ]);

            // Catat ke log aktivitas akun
            ActivityLog::catat($userId, "Menambahkan konser '" . $konser->nama_konser . "' ke wishlist");

            return back()->with('success', 'Konser berhasil ditambah ke wishlist! 💖');
        }
    }
}