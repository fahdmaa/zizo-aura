<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $isAuthenticated = $request->session()->get('admin_authenticated')
            || $request->cookie('admin_logged_in') === '1';

        if (! $isAuthenticated) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Authentification administrateur requise.'], 401);
            }
            return redirect()->route('admin.login');
        }

        // Keep session synchronized
        if (! $request->session()->get('admin_authenticated')) {
            $request->session()->put('admin_authenticated', true);
        }

        return $next($request);
    }
}
