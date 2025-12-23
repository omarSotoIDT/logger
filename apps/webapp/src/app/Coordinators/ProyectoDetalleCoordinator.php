<?php

namespace App\Coordinators;

use App\Services\LogService;
use App\Services\ProyectoService;
use App\Utils\UtilsRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class ProyectoDetalleCoordinator
{
    public static function obtenerDetalle($proyectoId)
    {
        $proyecto = ProyectoService::obtenerProyecto($proyectoId);

        $logsRemotos = UtilsRequest::hacerPeticionGet($proyecto->url, $proyecto->apikey);

        DB::beginTransaction();

        try {
            LogService::insertarNuevosLogsDeProyecto($proyecto->proyectoId, $logsRemotos);

            $diasDisponibles = LogService::listarLogs();

            DB::commit();

            return [$proyecto, $diasDisponibles];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [$proyecto, $diasDisponibles];
    }
}
