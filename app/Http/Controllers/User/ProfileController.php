<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog; // <-- 1. IMPORT MODEL LOG AKTIVITAS DI SINI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('user.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        // Cek apakah user juga sekalian upload foto baru
        $gantiFoto = $request->hasFile('foto_profil');

        if ($gantiFoto) {
            // Hapus foto lama
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $user->foto_profil = $request->file('foto_profil')->store('profil', 'public');
        }

        $user->save();

        // <-- 2. POSISI LOG PERTAMA: Catat setelah profil sukses disimpan
        $pesanLog = $gantiFoto 
            ? "Memperbarui informasi profil dan mengganti foto akun" 
            : "Memperbarui informasi nama/email akun";
            
        ActivityLog::catat($user->id, $pesanLog);

        return back()->with('success', 'Profil berhasil diperbarui! ✅');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // <-- 3. POSISI LOG KEDUA: Catat setelah keamanan password berhasil diubah
        ActivityLog::catat($user->id, "Berhasil mengubah password akun");

        return back()->with('success', 'Password berhasil diubah! 🔒');
    }
}