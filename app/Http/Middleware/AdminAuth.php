<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $isAuthenticated = (bool) $request->session()->get('admin_authenticated');

        if (! $isAuthenticated && $request->hasCookie('admin_auth_token')) {
            $adminPassword = (string) (config('app.admin_password') ?: env('ADMIN_PASSWORD') ?: 'zizoaura2025!');
            $cookieToken = (string) $request->cookie('admin_auth_token');
            if (! empty($cookieToken) && Hash::check($adminPassword, $cookieToken)) {
                $isAuthenticated = true;
                $request->session()->put('admin_authenticated', true);
            }
        }

        if (! $isAuthenticated) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Authentification administrateur requise.'], 401);
            }
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
