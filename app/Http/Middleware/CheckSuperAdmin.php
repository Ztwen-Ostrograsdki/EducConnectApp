<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {

        if (!Auth::guard('central')->check()) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}