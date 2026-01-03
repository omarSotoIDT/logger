<?php

namespace App\Coordinators;

use App\const\StatusConsts;
use App\Services\PerfilService;
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

        $proyectoPorUsuario = ProyectoService::listarProyectosPorUsuario('p.nombre,p.proyecto_id,rup.usuario_id,p.status',
        ['p.status' => StatusConsts::ACTIVO],null,null,'usuario_id_asc')->groupBy('usuario_id');

        $perfilPorUsuario = PerfilService::listarPerfilesPorUsuarios('up.usuario_id,pf.perfil_id,pf.nombre',
        ['pf.status' => StatusConsts::ACTIVO], null, null, 'usuario_id_asc')->groupBy('usuario_id');

        foreach ($usuarios as $usuario) {
            $usuario->proyectos = $proyectoPorUsuario[$usuario->usuario_id] ?? collect();
            $usuario->perfiles  = $perfilPorUsuario[$usuario->usuario_id] ?? collect();
        }

        return [$usuarios, $proyectos];
    }
}