<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Jika belum login → ke login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Jika admin mencoba akses halaman user → ke dashboard admin
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}