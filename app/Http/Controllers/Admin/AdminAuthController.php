<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 1) Busca o admin pelo e-mail
        $admin = Admin::where('email', $credentials['email'])->first();

        // 2) Se não achou ou senha não confere, erro
        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        // 3) Loga no guard admin e regenera sessão
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.orders.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
