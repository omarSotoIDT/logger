<?php

namespace App\Services;

use App\BO\LogBO;
use App\Repositories\RepoAction\LogRepoAction;
use App\Repositories\RepoData\LogRepoData;

class LogService
{
    public static function listarLogs($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '')
    {
        return LogRepoData::listarLogs($filtros, $columnas, $limit, $offset, $orden);
    }


    public static function obtenerLog($id, $filtros = [], $columnas = '')
    {
        return LogRepoData::obtenerLog($id, $filtros, $columnas);
    }


    public static function insertarNuevosLogsDeProyecto($proyectoId, $logsRemotos)
    {
        $logsExistentes = LogRepoData::listarLogs(
            ['proyectoId' => $proyectoId],
            'nombre',
            null,
            null,
            'nombre_asc'    
        )->all();

        $existentesSet = array_fill_keys(
            array_map(fn($e) => $e->nombre, $logsExistentes),   
            true
        );


        $rows = [];
        foreach ($logsRemotos as $logRemoto) {
            if (empty($logRemoto['nombre']) || empty($logRemoto['path'])) {
                continue;
            } 
            if (isset($existentesSet[$logRemoto['nombre']])) {
                continue;
            }

            $row = LogBO::armarInsertAgregarLog($proyectoId, $logRemoto);

            if (empty($row['log_fecha'])) {
                continue;
            } 

            $rows[] = $row;
        }

        LogRepoAction::agregarMasivoLogs($rows);
        return count($rows);
    }

    public static function insertarLogsDetalleProyecto($proyectoId, array $logsDetalleRemotos)
    {
        $logsExistentes = LogRepoData::listarLogsDetalle(
            ['proyectoId' => $proyectoId],
            'ld.codigo_interno, ld.fecha_hora_log'
        )->all();

        $existentesSet = [];
        foreach ($logsExistentes as $logExistente) {
            if (empty($logExistente->codigo_interno) || empty($logExistente->fecha_hora_log)) {
                continue;
            } 
            $existentesSet[$logExistente->codigo_interno . '|' . (string)$logExistente->fecha_hora_log] = true;
        }

        $rows = [];

        foreach ($logsDetalleRemotos as $logDetalleRemotos) {
            $codigoInterno = $logDetalleRemotos['codigo_interno'] ?? null;
            $fechaHoraLog  = $logDetalleRemotos['fecha_hora_log'] ?? null;

            if (empty($codigoInterno) || empty($fechaHoraLog)) {
                continue;
            } 

            $key = $codigoInterno . '|' . $fechaHoraLog;

            if (isset($existentesSet[$key])) {
                continue;
            } 

            $row = LogBO::armarInsertAgregarDetalle($logDetalleRemotos);

            $rows[] = $row;
            $existentesSet[$key] = true;
        }

        if (empty($rows)) {
            return 0;
        } 

        LogRepoAction::agregarMasivoLogsDetalle($rows);
        return count($rows);
    }

    public static function parsearContenido($contenido, $logId) {
        return LogBO::parsearContenido($contenido, $logId);
    }

    public static function actualizarLog($id, $data) {
        $update = LogBO::armarUpdateActualizarLog($data);
        LogRepoAction::actualizarLog($id, $update);
    }
}
