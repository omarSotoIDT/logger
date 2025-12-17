<?php

namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\DB;

class TipoRepoAction 
{
    public static function agregar($data)
    {
        return DB::table('cat_tipos_proyecto')->insertGetId($data);
    }

    public static function actualizar($id, $data)
    {
        return DB::table('cat_tipos_proyecto')
            ->where('tipo_proyecto_id', $id)
            ->update($data);
    }
}