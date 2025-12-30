<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\LogRH;
use Illuminate\Support\Facades\DB;

class LogRepoData
{
    public static function listarLogs($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('logs AS l');
        $query->select();

        LogRH::obtenerColumnasLog($query, $columnas);
        LogRH::obtenerFiltrosLog($query, $filtros);
        LogRH::obtenerOrdenLog($query, $orden);

        if (!empty($offset)) {
            $query->offset($offset);
        }
        if (!empty($limit)) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function obtenerLog($id, $columnas = '')
    {
        $query = DB::table('logs AS l');
        LogRH::obtenerColumnasLog($query, $columnas);

        return $query->where('l.log_id', $id)->first();
    }

    public static function listarLogsDetalle($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('logs_detalle AS ld')
            ->leftJoin('logs AS l', 'l.log_id', '=', 'ld.log_id');
        $query->select();

        LogRH::obtenerColumnasLogDetalle($query, $columnas);
        LogRH::obtenerFiltrosLogDetalle($query, $filtros);
        LogRH::obtenerOrdenLogDetalle($query, $orden);

        if (!empty($offset)) {
            $query->offset($offset);
        } 
        if (!empty($limit)) {
            $query->limit($limit);
        } 

        return $query->get();
    }

}
