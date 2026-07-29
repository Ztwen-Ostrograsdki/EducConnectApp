<?php

namespace App\Http\Middleware;

use App\Models\Classe;
use App\Models\Subject;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureThatClasseIsActiveOrNotLockedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $classe_slug = $request->route('classe_slug');

        $subject_slug = $request->route('subject_slug');

        if(!($classe_slug && $subject_slug)){

            return abort(403);
        }

        $classe = Classe::firstWhere('slug', $classe_slug);

        $subject = Subject::firstWhere('slug', $subject_slug);

        if(!($classe && $subject)){

            return abort(403);
        }

        if(!($classe->is_active && !$classe->is_locked)){

            return abort(403);
        }

        /**@var \App\Models\User */
            $user = auth('tenant')->user();

        if(!$user->teacher || !$user->teacher->canAccessIntoClasse($classe->id)){

            return abort(403);
        }

        return $next($request);
    }
}
