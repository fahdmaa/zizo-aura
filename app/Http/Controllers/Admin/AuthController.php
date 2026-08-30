<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $adminPassword = config('app.admin_password', env('ADMIN_PASSWORD'));

        $isHash = is_string($adminPassword) && password_get_info($adminPassword)['algo'] !== null;
        // Existing deployments may still have a plaintext value. Keep them
        // operational while .env.example documents the hash-only setting.
        $isValid = $isHash
            ? Hash::check($request->password, $adminPassword)
            : is_string($adminPassword) && hash_equals($adminPassword, $request->password);

        if ($isValid) {
            $request->session()->put('admin_authenticated', true);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['password' => 'Mot de passe incorrect.']);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
