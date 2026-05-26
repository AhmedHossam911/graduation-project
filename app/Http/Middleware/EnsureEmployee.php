<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role && strtolower(auth()->user()->role->name) === 'member') {
            return redirect()->route('profile.index'); // Redirect members to their profile or member dashboard
        }

        return $next($request);
    }
}
