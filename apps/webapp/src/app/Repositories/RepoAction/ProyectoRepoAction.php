<?php

namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\DB;

class ProyectoRepoAction
{
    public static function agregar($data) 
    {
        return DB::table('proyectos')->insertGetId($data);
    }

    public static function actualizar($id, $data) 
    {
        return DB::table('proyectos')
            ->where('proyecto_id', $id)
            ->update($data);
    }
}