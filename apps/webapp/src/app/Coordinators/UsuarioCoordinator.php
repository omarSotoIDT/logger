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
        $usuarios = UsuarioService::listarUsuarios();

        $proyectos = ProyectoService::listarProyectos([
            'status' => [StatusConsts::ACTIVO]
        ]);

        $proyectoPorUsuario = ProyectoService::listarProyectosPorUsuario()->groupBy('usuario_id');

        $perfilPorUsuario = PerfilService::listarPerfilesPorUsuarios()->groupBy('usuario_id');

        foreach ($usuarios as $usuario) {
            $usuario->proyectos = $proyectoPorUsuario[$usuario->usuario_id] ?? collect();
            $usuario->perfiles  = $perfilPorUsuario[$usuario->usuario_id] ?? collect();
        }

        return [$usuarios, $proyectos];
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

            UsuarioService::nuevaRelacionProyecto($usuarioId, $data['proyectos'] ?? []);
        }, attempts: 2);
    }
}
