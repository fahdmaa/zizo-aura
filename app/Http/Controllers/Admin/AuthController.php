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

        $isHash = password_get_info($adminPassword)['algo'] !== null;
        $isValid = $isHash
            ? (Hash::check($inputPassword, $adminPassword) || Hash::check(trim($inputPassword), $adminPassword))
            : (hash_equals($adminPassword, $inputPassword) || hash_equals(trim($adminPassword), trim($inputPassword)));

        if ($isValid) {
            $request->session()->regenerate();
            $request->session()->put('admin_authenticated', true);
            $request->session()->save();

            return redirect()->intended('/admin');
        }

        return back()->withErrors(['password' => 'Mot de passe administrateur incorrect.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
