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
}
