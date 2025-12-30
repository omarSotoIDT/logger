<?php

namespace App\Coordinators;

use App\const\StatusConsts;
use App\Services\LogService;
use App\Services\ProyectoService;
use App\Services\TipoProyectoService;
use App\Utils\UtilsRequest;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\DB;

class ProyectoCoordinator
{
    public static function listarProyectos() {
        $proyectos = ProyectoService::listarProyectos([
            'status' => [StatusConsts::ACTIVO, StatusConsts::INACTIVO]
        ]);
        $tipos     = TipoProyectoService::listarTipos([
            'status' => StatusConsts::ACTIVO,
        ]);
        $timezones = DateTimeZone::listIdentifiers();

        return [$proyectos, $tipos, $timezones];
    }

    public static function obtenerDetalle($proyectoId)
    {
        $proyecto = ProyectoService::obtenerProyecto($proyectoId);

        $logsRemotos = [];
        $syncWarning = null;

        try {
            $urlListado = UtilsRequest::armarUrlLogs($proyecto->url_endpoint);
            $json = UtilsRequest::hacerPeticionGet($urlListado, $proyecto->api_key, 15);
            $logsRemotos = $json['datos'] ?? [];
        } catch (Exception $e) {
            $syncWarning = 'No se pudo sincronizar con el endpoint remoto. Mostrando información local.';
            $logsRemotos = [];
        }

        return DB::transaction(function () use ($proyecto, $logsRemotos, $syncWarning) {

            if (!empty($logsRemotos)) {
                LogService::insertarNuevosLogsDeProyecto($proyecto->proyecto_id, $logsRemotos);
            }

            $diasDisponibles = LogService::listarLogs([
                'proyectoId' => $proyecto->proyecto_id
            ]);

            return [$proyecto, $diasDisponibles, $syncWarning];

        }, 5);
    }


    public static function sincronizarDetalles($logId)
    {
        $log = LogService::obtenerLog($logId);

        $proyecto = ProyectoService::obtenerProyecto($log->proyecto_id);

        $urlContenido = UtilsRequest::armarUrlContenidoLog($proyecto->url_endpoint, $log->nombre);

        $json = UtilsRequest::hacerPeticionGet($urlContenido, $proyecto->api_key, 30);
        $contenido = $json['datos']['contenido'] ?? '';

        if (empty($logId)) {
            throw new Exception("No existe el log '{$log->nombre}' en BD para el proyecto {$proyecto->proyecto_id}");
        }
        $items = LogService::parsearContenido($contenido, $logId);

        return DB::transaction(function () use ($proyecto, $items) {
            return LogService::insertarLogsDetalleProyecto($proyecto->proyecto_id, $items);
        }, 5);
    }
    
}
