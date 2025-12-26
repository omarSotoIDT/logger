<?php

namespace App\Services;

use App\BO\LogDetalleBO;
use App\Repositories\RepoAction\LogDetalleRepoAction;
use App\Repositories\RepoData\LogDetalleRepoData;

class LogDetalleService
{
    public static function insertarLogsDetalleProyecto($proyectoId, array $logsDetalleRemotos)
    {
        $existentes = LogDetalleRepoData::listar(
            ['proyectoId' => $proyectoId],
            'ld.codigo_interno, ld.fecha_hora_log'
        )->all();

        $existentesSet = [];
        foreach ($existentes as $e) {
            if (empty($e->codigo_interno) || empty($e->fecha_hora_log)) continue;
            $existentesSet[$e->codigo_interno . '|' . (string)$e->fecha_hora_log] = true;
        }

        $rows = [];

        foreach ($logsDetalleRemotos as $item) {
            $codigoInterno = $item['codigo_interno'] ?? null;
            $fechaHoraLog  = $item['fecha_hora_log'] ?? null;

            if (empty($codigoInterno) || empty($fechaHoraLog)) continue;

            $key = $codigoInterno . '|' . $fechaHoraLog;
            if (isset($existentesSet[$key])) continue;

            $row = LogDetalleBO::armarInsertAgregarDetalle($item);

            if (
                empty($row['log_id']) ||
                $row['codigo_excepcion'] === null ||
                empty($row['codigo_interno']) ||
                empty($row['mensaje']) ||
                empty($row['nivel']) ||
                empty($row['fecha_hora_log']) ||
                empty($row['registro_fecha']) ||
                empty($row['registro_autor_id'])
            ) {
                continue;
            }

            $rows[] = $row;
            $existentesSet[$key] = true;
        }

        if (empty($rows)) return 0;

        LogDetalleRepoAction::agregarMasivo($rows);
        return count($rows);
    }
}
