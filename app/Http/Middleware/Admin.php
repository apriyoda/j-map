<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && (auth()->user()->role === 'admin' || in_array(auth()->user()->email, ['admin@jmap.com', 'your@email.com']))) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Akses ditolak. Halaman ini hanya dapat diakses oleh Administrator.');
    }
}
