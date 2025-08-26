<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        ;
        if (auth()->check()) {
            if (auth()->user()->role == 'Super admin') {
                return $next($request);
            } else {
                // Redirect non-admin users to app
                return redirect('/app');
            }
        }

        return redirect('/');

    }
}
