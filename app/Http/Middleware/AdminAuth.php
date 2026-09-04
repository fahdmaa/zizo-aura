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
            $appKey = (string) (config('app.key') ?: env('APP_KEY') ?: 'base64:zizoaura');
            $expectedToken = hash_hmac('sha256', 'admin_auth_' . $adminPassword, $appKey);

            if (! empty($cookieToken) && hash_equals($expectedToken, $cookieToken)) {
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
