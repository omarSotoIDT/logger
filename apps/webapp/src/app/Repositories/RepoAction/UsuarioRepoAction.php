<?php

namespace App\Repositories\RepoAction;

use App\const\StatusConsts;
use Illuminate\Support\Facades\DB;

class UsuarioRepoAction
{
    public static function agregar($datos)
    {
        return DB::table('sys_usuarios')->insertGetId($datos);
    }

    public static function editar($datos, $id)
    {
        DB::table('sys_usuarios')->where('usuario_id', $id)->update($datos);
    }

    public static function agregarRelacionProyecto($datos)
    {
        DB::table('rel_usuarios_proyectos')->insert($datos);
    }

    public static function eliminarRelacionProyecto($usuarioId, $proyectos, $datos)
    {
        DB::table('rel_usuarios_proyectos')
            ->where('usuario_id', $usuarioId)
            ->whereNotIn('proyecto_id', $proyectos)
            ->where('status', StatusConsts::ACTIVO)
            ->update($datos);
    }

    public static function agregarRelacionPerfil($datos)
    {
        DB::table('rel_usuarios_perfiles')->insert($datos);
    }

    public static function eliminarRelacionPerfil($usuarioId, $perfiles, $datos)
    {
        DB::table('rel_usuarios_perfiles')
            ->where('usuario_id', $usuarioId)
            ->whereNotIn('perfil_id', $perfiles)
            ->where('status', StatusConsts::ACTIVO)
            ->update($datos);
    }
}
