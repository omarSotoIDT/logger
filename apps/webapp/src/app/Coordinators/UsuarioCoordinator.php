<?php

namespace App\Coordinators;

use App\const\StatusConsts;
use App\Services\ProyectoService;
use App\Services\UsuarioService;

class UsuarioCoordinator
{
    public static function listarProyectosyUsuarios()
    {
        $usuarios = UsuarioService::listarUsuarios([],['status' => StatusConsts::ACTIVO], null, null, null);

        $proyectos = ProyectoService::listarProyectos([
            'status' => [StatusConsts::ACTIVO]
        ]);

        return [$usuarios, $proyectos];
    }
}