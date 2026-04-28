<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsLogin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika BELUM login, redirect ke halaman login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Jika SUDAH login, lanjut ke halaman yang diminta
        return $next($request);
    }
}

