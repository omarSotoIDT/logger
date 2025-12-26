<?php

namespace App\Services;

use App\BO\LogBO;
use App\Repositories\RepoAction\LogRepoAction;
use App\Repositories\RepoData\LogRepoData;

class LogService
{
    public static function listarLogs($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '')
    {
        return LogRepoData::listar($filtros, $columnas, $limit, $offset, $orden);
    }


    public static function obtenerLog($id, $filtros = [], $columnas = '')
    {
        return LogRepoData::obtener($id, $filtros, $columnas);
    }


    public static function insertarNuevosLogsDeProyecto($proyectoId, $logsRemotos)
    {
        $existentes = LogRepoData::listar(
            ['proyectoId' => $proyectoId],
            'nombre',
            null,
            null,
            'nombre_asc'    
        )->all();

        // array map
        $existentesSet = array_fill_keys(
            array_map(fn($e) => $e->nombre, $existentes),
            true
        );


        $rows = [];
        foreach ($logsRemotos as $item) {
            if (empty($item['nombre']) || empty($item['path'])) continue;

            if (isset($existentesSet[$item['nombre']])) continue;

            $row = LogBO::armarInsertAgregarLog($proyectoId, $item);

            if (empty($row['log_fecha'])) continue;

            $rows[] = $row;
        }

        LogRepoAction::agregarMasivo($rows);
        return count($rows);
    }

    public static function actualizarLog($id, $data) {
        $update = LogBO::armarUpdateActualizarLog($data);
        return LogRepoAction::actualizar($id, $update);
    }
}
