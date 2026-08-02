<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureThatTutorIsActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         /**@var \App\Models\User */
        $user = auth('tenant')->user();

        if (!$user) return redirect()->route('login');

        if($user->blocked){

            $user->logout();

            session()->invalidate();

            session()->regenerate();

            return redirect()->route('login');
        }

        if(!$user->tutor) return abort('403');

        if(!$user->tutor->is_active) return abort('403');
        
        return $next($request);
    }
}
