<?php

namespace App\Coordinators;

use App\const\StatusConsts;
use App\Services\PerfilService;
use App\Services\PermisoService;

class PerfilCoordinator
{
    public static function listar()
    {
        $permisos = PermisoService::listar('','seccion_asc');

        $perfiles = PerfilService::listarPerfil('', ['status' => StatusConsts::ACTIVO], null, null, null, 5);

        return [$permisos, $perfiles];
    }
}
