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
         // Vérifier que c'est bien le super admin
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Auth::check() || !$user->isSuperAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}