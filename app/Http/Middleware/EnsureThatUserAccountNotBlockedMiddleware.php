<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureThatUserAccountNotBlockedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('tenant')->check()) {

            /**@var \App\Models\User */
            $user = auth('tenant')->user();

            if($user && !$user->blocked){

                return $next($request);
            }

            return abort('403');
        }
        return redirect()->route('login');
    }
}
