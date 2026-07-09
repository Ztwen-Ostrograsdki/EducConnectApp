<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogoutUserWhenInactivityIsTakeLongTimeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = auth('tenant')->check();

        if (!$auth) {

            return redirect()->route('login');
        }

        $lastActivity = session('last_activity');

        if($lastActivity && now()->diffInMinutes($lastActivity) > config('session.lifetime')){

            Auth::guard('tenant')->logout();

            session()->invalidate();

            session()->regenerate();

            return redirect()->route('login');
        }

        session('last_activity', now());

        return $next($request);
    }
}
