<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsAuthorOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $author = $request->route('author');


        if (!$author || auth()->user()->id !== $author->user_id) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
