<?php

namespace App\Coordinators;

use App\const\StatusConsts;
use App\Services\PerfilService;
use App\Services\PermisoService;
use Illuminate\Support\Facades\DB;

class PerfilCoordinator
{
    public static function listar()
    {
        $permisos = PermisoService::listar('', 'seccion_asc');

        $perfiles = PerfilService::listarPerfil('', ['status' => StatusConsts::ACTIVO], null, null, null, 5);

        return [$permisos, $perfiles];
    }

    public static function agregar($datos)
    {
        DB::transaction(function () use ($datos) {
            $perfilId = PerfilService::agregar($datos);

            foreach ($datos['permisos'] ?? [] as $permisoId) {
                PerfilService::agregarRelacion($perfilId, $permisoId);
            }
        }, attempts: 2);
    }
}
