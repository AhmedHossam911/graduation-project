<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Intercept the incoming request to ensure the authenticated user holds the 'Admin' role.
     * Redirects regular members to their profile and other staff to the dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role) {
            $roleName = strtolower(auth()->user()->role->name);
            if ($roleName === 'admin') {
                return $next($request);
            }
            
            if ($roleName === 'member') {
                return redirect()->route('profile.index');
            }
            
            return redirect()->route('dashboard');
        }

        abort(403, 'غير مصرح لك بالوصول لهذه الصفحة.');
    }
}
