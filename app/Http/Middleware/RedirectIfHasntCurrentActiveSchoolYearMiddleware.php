<?php

namespace App\Http\Middleware;

use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfHasntCurrentActiveSchoolYearMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('tenant')) {

            return redirect('login');
        }

        /**@var \App\Models\User */
        $user = auth('tenant')->user();

        if(!$user || !$user->hasRole('directeur')){

            return abort(403);
            
        }

        $currentActiveSchoolYear = SchoolYear::current()->first();

        if(!$currentActiveSchoolYear){

            return to_route('tenant.schoolyears.portal');
        }

        return $next($request);
    }
}
