<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'usuario'  => 'required|string|max:20',
                'password' => 'required|string|max:30',
            ]);

            $success = AuthService::logearUsuario($credentials);

            if (!$success) {
                return back()
                    ->with('error', 'Credenciales incorrectas o sin acceso')
                    ->withInput();
            }

            $request->session()->regenerate();

            return redirect()->route('proyecto.dashboard')
                ->with('success', 'Inicio de sesión exitoso');

        } catch (Exception $e) {
            return back()->with('error', 'Ocurrió un error inesperado.');
        }
    }

    public function logout(Request $request)
    {
        AuthService::logoutUsuario();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
