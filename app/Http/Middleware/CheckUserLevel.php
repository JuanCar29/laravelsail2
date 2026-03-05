<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$levels): Response
    {
        if (Auth::check() && in_array(Auth::user()->nivel, $levels) && Auth::user()->activo) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}
