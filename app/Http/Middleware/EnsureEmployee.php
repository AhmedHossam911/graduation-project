<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployee
{
    /**
     * Intercept the incoming request to verify that the user is not a regular member.
     * Administrators and employees are permitted to proceed.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role) {
            $roleName = strtolower(auth()->user()->role->name);
            
            if ($roleName === 'member') {
                return redirect()->route('profile.index');
            }
            
            // Since members are redirected above, allow all other administrative roles (Admin, Employee, Auditor) to proceed.
            return $next($request);
        }

        abort(403, 'غير مصرح لك بالوصول لهذه الصفحة.');
    }
}
