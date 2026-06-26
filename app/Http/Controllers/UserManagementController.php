<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->withCount('orders')
            ->latest()
            ->get();

        $totalUser    = $users->count();
        $totalOrder   = Order::whereHas('user', function($q) {
            $q->where('role', 'user');
        })->count();

        return view('user-management.index', compact('users', 'totalUser', 'totalOrder'));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak bisa hapus akun admin!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}