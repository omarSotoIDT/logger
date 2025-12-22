<?php

namespace App\Coordinators;

use App\const\StatusConsts;
use App\Services\ProyectoService;
use App\Services\TipoProyectoService;
use DateTimeZone;

class ProyectoCoordinator
{
    public static function listarProyectos() {
        $proyectos = ProyectoService::listarProyectos([
            'status' => [StatusConsts::ACTIVO, StatusConsts::INACTIVO]
        ]);
        $tipos     = TipoProyectoService::listarTipos([
            'status' => StatusConsts::ACTIVO,
        ]);
        $timezones = \DateTimeZone::listIdentifiers();

        return [$proyectos, $tipos, $timezones];
    }
}
