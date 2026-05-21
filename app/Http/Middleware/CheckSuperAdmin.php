<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur est connecté via le guard central.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! Auth::guard('central')->check()) {
            return redirect()->route('central.login');
        }

        return $next($request);
    }
}
