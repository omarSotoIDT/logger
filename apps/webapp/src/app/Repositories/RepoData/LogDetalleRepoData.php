<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\LogDetalleRH;
use Illuminate\Support\Facades\DB;

class LogDetalleRepoData
{   
    public static function listar($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('logs_detalle AS ld')
            ->leftJoin('logs AS l', 'l.log_id', '=', 'ld.log_id');

        LogDetalleRH::obtenerColumnas($query, $columnas);
        LogDetalleRH::obtenerFiltros($query, $filtros);
        LogDetalleRH::obtenerOrden($query, $orden);

        if (!empty($offset)) $query->offset($offset);
        if (!empty($limit))  $query->limit($limit);

        return $query->get();
    }
}