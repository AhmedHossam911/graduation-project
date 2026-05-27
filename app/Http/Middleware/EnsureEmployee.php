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
        if (auth()->check() && auth()->user()->role) {
            $roleName = strtolower(auth()->user()->role->name);
            
            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            if ($roleName === 'member') {
                return redirect()->route('profile.index');
            }
            
            return $next($request);
        }

        abort(403, 'غير مصرح لك بالوصول لهذه الصفحة.');
    }
}
