<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\PermisoRH;
use Illuminate\Support\Facades\DB;

class PermisoRepoData
{

    public static function listar($columnas = '', $filtros = [], $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('sys_permisos as p')->select('p.permiso_id');

        PermisoRH::obtenerColumnas($query, $columnas);
        PermisoRH::obtenerFiltros($query, $filtros);
        PermisoRH::obtenerOrden($query, $orden);

        if (!empty($limit)) {
            $query->limit($limit);
        }

        if (!empty($offset)) {
            $query->offset($offset);
        }

        // dd($query);

        return $query->get();
    }
}
