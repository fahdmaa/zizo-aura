<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->session()->get('admin_authenticated')) {
            return redirect()->to('/admin');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $adminPassword = (string) (config('app.admin_password') ?: env('ADMIN_PASSWORD') ?: 'zizoaura2025!');
        $inputPassword = (string) $request->input('password');

        $isHash = !empty($adminPassword) && (str_starts_with($adminPassword, '$2y$') || str_starts_with($adminPassword, '$2a$') || str_starts_with($adminPassword, '$argon2'));
        $isValid = false;
        if ($isHash) {
            try {
                $isValid = password_verify($inputPassword, $adminPassword) || password_verify(trim($inputPassword), $adminPassword);
            } catch (\Throwable $e) {
                $isValid = false;
            }
        } else {
            $isValid = hash_equals($adminPassword, $inputPassword) || hash_equals(trim($adminPassword), trim($inputPassword));
        }

        if ($isValid) {
            $request->session()->regenerate();
            $request->session()->put('admin_authenticated', true);
            $request->session()->save();

            $appKey = (string) (config('app.key') ?: env('APP_KEY') ?: 'base64:zizoaura');
            $token = hash_hmac('sha256', 'admin_auth_' . $adminPassword, $appKey);
            $authCookie = cookie('admin_auth_token', $token, 240, '/', null, null, true, false, 'lax');

            return redirect()->to('/admin')->withCookie($authCookie);
        }

        return back()->withErrors(['password' => 'Mot de passe administrateur incorrect.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->withoutCookie('admin_auth_token');
    }
}
