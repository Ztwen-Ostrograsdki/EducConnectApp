<?php

namespace App\Http\Middleware;

use App\Models\Classe;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureThatTeacherIsPrincipalOfClasseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $classe_slug = $request->route('classe_slug');

        if(!($classe_slug)){

            return abort(404);
        }

        $classe = Classe::firstWhere('slug', $classe_slug);

        if(!($classe)){

            return abort(404);
        }

        /**@var \App\Models\User */
        $user = auth('tenant')->user();

        $teacher = $user->teacher;


        if(!$teacher || !$teacher->canAccessIntoClasse($classe->id)){

            return abort(403);
        }

        $classePrincipal = $classe->principal;

        if(!$classePrincipal || $teacher->id !== $classePrincipal->id){

            return abort(403);
        }

        return $next($request);
    }
}
