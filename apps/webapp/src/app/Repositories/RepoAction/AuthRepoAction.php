<?php

namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\DB;

class AuthRepoAction
{
    public static function actualizarUltimoAcceso(int $usuarioId): int
    {
        return DB::table('sys_usuarios')
            ->where('usuario_id', $usuarioId)
            ->update([
                'ultimo_acceso_fecha' => now(),
            ]);
    }
}
