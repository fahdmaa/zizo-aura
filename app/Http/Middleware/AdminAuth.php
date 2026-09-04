<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $isAuthenticated = (bool) $request->session()->get('admin_authenticated');

        if (! $isAuthenticated) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Authentification administrateur requise.'], 401);
            }
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
