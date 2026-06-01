<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIsRestricted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_restricted) {
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'الحساب قيد المراجعة أو موقوف بواسطة الإدارة.'], 403);
            }

            return redirect()->route('login')->with('error', 'الحساب قيد المراجعة أو موقوف بواسطة الإدارة.');
        }

        return $next($request);
    }
}
