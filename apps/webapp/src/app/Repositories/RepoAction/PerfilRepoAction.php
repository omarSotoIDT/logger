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
}
