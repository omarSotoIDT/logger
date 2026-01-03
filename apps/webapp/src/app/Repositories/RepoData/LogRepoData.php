<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\LogRH;
use Illuminate\Support\Facades\DB;

class LogRepoData
{
    public static function listarLogs($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('logs AS l')->select();

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
            ->leftJoin('logs AS l', 'l.log_id', '=', 'ld.log_id')->select();

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

    public static function contarLogsDetalle($id)
    {
        return DB::table('logs_detalle AS ld')
            ->leftJoin('logs AS l', 'ld.log_id', '=', 'l.log_id')
            ->where('l.proyecto_id', $id)
            ->count();
    }

    public static function contarLogsDetalleNivel($id, $nivel)
    {
        return DB::table('logs_detalle AS ld')
            ->leftJoin('logs AS l', 'ld.log_id', '=', 'l.log_id')
            ->where('l.proyecto_id', $id)
            ->where('ld.nivel', $nivel)
            ->count();
    }

    public static function contarErroresPorHora($id, $desde = null)
    {
        $query = DB::table('logs_detalle AS ld')
            ->leftJoin('logs AS l', 'ld.log_id', '=', 'l.log_id')
            ->where('l.proyecto_id', $id)
            ->where('ld.nivel', 'ERROR');

        if (!empty($desde)) {
            $query->where('ld.fecha_hora_log', '>=', $desde);
        }

        return $query->selectRaw('HOUR(ld.fecha_hora_log) AS hora, COUNT(*) AS total')
            ->groupByRaw('HOUR(ld.fecha_hora_log)')
            ->orderBy('hora')
            ->get();
    }

    public static function contarDistribucionPorNivel($id)
    {
        return DB::table('logs_detalle AS ld')
            ->leftJoin('logs AS l', 'ld.log_id', '=', 'l.log_id')
            ->where('l.proyecto_id', $id)
            ->selectRaw("COALESCE(NULLIF(TRIM(ld.nivel), ''), 'OTROS') AS nivel, COUNT(*) AS total")
            ->groupByRaw("COALESCE(NULLIF(TRIM(ld.nivel), ''), 'OTROS')")
            ->orderByDesc('total')
            ->get();
    }


    public static function listarTopCodigosInternosMensaje($proyectoId, $limit = 5)
    {
        return DB::table('logs_detalle AS ld')
            ->leftJoin('logs AS l', 'l.log_id', '=', 'ld.log_id')
            ->where('l.proyecto_id', $proyectoId)
            ->whereNotNull('ld.codigo_interno_mensaje')
            ->where('ld.codigo_interno_mensaje', '<>', '')
            ->selectRaw('ld.codigo_interno_mensaje AS codigo_interno_mensaje, COUNT(*) AS total')
            ->groupBy('ld.codigo_interno_mensaje')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    public static function listarTopArchivosErrores($id, $limit = 5)
    {
        return DB::table('logs_detalle AS ld')
            ->leftJoin('logs AS l', 'l.log_id', '=', 'ld.log_id')
            ->where('l.proyecto_id', $id)
            ->where('ld.nivel', 'ERROR')
            ->whereNotNull('ld.archivo')
            ->where('ld.archivo', '<>', '')
            ->selectRaw('ld.archivo, COUNT(*) AS total')
            ->groupBy('ld.archivo')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    public static function listarUltimosLogs()
    {

    }

}
