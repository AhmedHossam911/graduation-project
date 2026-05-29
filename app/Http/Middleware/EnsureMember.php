<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureMember
{
    /**
     * Intercept the incoming request to ensure the authenticated user holds the 'Member' role.
     * Redirects staff (Admins/Employees) to their respective dashboards.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role) {
            $roleName = strtolower(Auth::user()->role->name);
            
            if ($roleName === 'member') {
                return $next($request);
            }
            
            // If the user is staff, redirect them to the staff dashboard
            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard'); // Employee dashboard
        }

        abort(403, 'غير مصرح لك بالوصول لهذه الصفحة.');
    }
}
