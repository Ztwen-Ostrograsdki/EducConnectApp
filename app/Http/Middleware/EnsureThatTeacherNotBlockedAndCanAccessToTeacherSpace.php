<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureThatTeacherNotBlockedAndCanAccessToTeacherSpace
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('tenant')->check()) {

            if(Auth::guard('tenant')->user()->hasRole('enseignant') && !auth('tenant')->user()->teacher->blocked){

                return $next($request);
            }

            return abort('403');
        }
        return redirect()->route('login');
        
        
    }
}
