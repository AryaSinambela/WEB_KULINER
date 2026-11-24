<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        if (!$request->user()->is_aktif) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['email' => 'Akun Anda sedang dinonaktifkan / diblokir oleh Administrator.']);
        }

        return $next($request);
    }
}