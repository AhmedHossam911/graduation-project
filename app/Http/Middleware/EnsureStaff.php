<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureStaff
{
    /**
     * Intercept the incoming request to ensure the authenticated user is a Staff member (Admin or Employee).
     * Redirects members to their member portal.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role) {
            $roleName = strtolower(Auth::user()->role->name);
            
            if (in_array($roleName, ['admin', 'employee'])) {
                return $next($request);
            }
            
            // If the user is a member, redirect them to their member portal dashboard
            return redirect()->route('member.dashboard');
        }

        abort(403, 'غير مصرح لك بالوصول لهذه الصفحة.');
    }
}
