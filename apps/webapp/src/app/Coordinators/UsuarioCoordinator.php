<?php

namespace App\Coordinators;

use App\const\StatusConsts;
use App\Services\PerfilService;
use App\Services\ProyectoService;
use App\Services\UsuarioService;
use Illuminate\Support\Facades\DB;

class UsuarioCoordinator
{
    public static function listarProyectosyUsuarios()
    {
        $usuarios = UsuarioService::listarUsuarios('', ['status' => StatusConsts::ACTIVO], null, null, null, 5);

        $proyectos = ProyectoService::listarProyectos([
            'status' => [StatusConsts::ACTIVO]
        ]);

        $perfiles = PerfilService::listarPerfil('', ['status' => StatusConsts::ACTIVO]);

        $proyectoPorUsuario = ProyectoService::listarProyectosPorUsuario(
            'nombre,id,status',
            ['rup.status' => StatusConsts::ACTIVO, 'p.status' => StatusConsts::ACTIVO]
        )->groupBy('usuario_id');

        $perfilPorUsuario = PerfilService::listarPerfilesPorUsuarios(
            'up.usuario_id,pf.perfil_id,pf.nombre',
            ['pf.status' => StatusConsts::ACTIVO]
        )->groupBy('usuario_id');

        foreach ($usuarios as $usuario) {
            $usuario->proyectos = $proyectoPorUsuario[$usuario->usuario_id] ?? collect();
            $usuario->perfiles  = $perfilPorUsuario[$usuario->usuario_id] ?? collect();
        }

        return [$usuarios, $proyectos, $perfiles];
    }

    public static function agregarUsuarioConProyecto($data)
    {
        DB::transaction(function () use ($data) {
            $usuarioId = UsuarioService::agregarUsuario($data);

            foreach ($data['proyectos'] ?? [] as $proyectoId) {
                UsuarioService::agregarRelacionProyecto($usuarioId, $proyectoId);
            }
        }, attempts: 2);
    }

    public static function actualizarUsuarioConProyecto($data, $usuarioId)
    {
        DB::transaction(function () use ($data, $usuarioId) {
            UsuarioService::editarUsuario($data, $usuarioId);

            UsuarioService::actualizarRelacionProyecto($usuarioId, $data['proyectos'] ?? []);
        }, attempts: 2);
    }
}
