<?php

namespace App\Services;

use App\Repositories\RepoAction\AuthRepoAction;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public static function logearUsuario(array $data): bool
    {
        $exito = Auth::attempt([
            'usuario'           => $data['usuario'],
            'password'          => $data['password'],
            'status'            => 'ACTIVO',
        ]);

        if (!$exito) return false;

        $userId = Auth::id();
        if ($userId) {
            AuthRepoAction::actualizarUltimoAcceso($userId);
        }

        return true;
    }

    public static function logoutUsuario(): void
    {
        Auth::logout();
    }

    public static function esSuperUsuario(): bool
    {
        $u = Auth::user();
        return $u && ((int)$u->super_usuario === 1);
    }
}
