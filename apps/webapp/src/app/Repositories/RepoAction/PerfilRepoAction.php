<?php

namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\DB;

class PerfilRepoAction
{
    public static function agregar($datos)
    {
        return DB::table('sys_perfiles')->insertGetId($datos);
    }

    public static function agregarRelacion($datos)
    {
        DB::table('rel_perfiles_permisos')->insert($datos);
    }

    public static function editar($datos, $perfilId)
    {
        DB::table('sys_perfiles')
            ->where('perfil_id', $perfilId)
            ->update($datos);
    }

    public static function eliminarRelaciones($perfilId)
    {
        DB::table('rel_perfiles_permisos')->where('perfil_id', $perfilId)->delete();
    }
}
