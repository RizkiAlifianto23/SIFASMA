<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->status != 1) {
            auth()->logout();
            return redirect('/login')->withErrors(['email' => 'Akun Anda tidak aktif.']);
        }

        return $next($request);
    }
}
