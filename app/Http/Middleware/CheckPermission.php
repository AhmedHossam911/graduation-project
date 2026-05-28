<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Intercept the incoming request to verify that the user has the required permission(s).
     * Supports checking multiple permissions separated by a pipe (|).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->is_restricted) {
            abort(403, 'حسابك موقوف، يرجى التواصل مع الإدارة.');
        }

        $permissions = explode('|', $permission);

        foreach ($permissions as $p) {
            if ($user->hasPermission($p)) {
                return $next($request);
            }
        }

        abort(403, 'عذراً، لا تمتلك الصلاحية الكافية للوصول إلى هذه الصفحة.');
    }
}
