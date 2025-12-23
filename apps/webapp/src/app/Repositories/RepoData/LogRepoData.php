<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\LogRH;
use Illuminate\Support\Facades\DB;

class LogRepoData
{
    public static function listar($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('logs AS l');

        LogRH::obtenerColumnas($query, $columnas);
        LogRH::obtenerFiltros($query, $filtros);
        LogRH::obtenerOrden($query, $orden);

        if (!empty($offset)) $query->offset($offset);
        if (!empty($limit))  $query->limit($limit);

        return $query->get();
    }
}
