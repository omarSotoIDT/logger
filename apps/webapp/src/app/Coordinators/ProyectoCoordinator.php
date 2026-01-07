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
            $remotosPorNombre = [];
            if (empty($syncWarning)) {
                foreach ($logsRemotos as $logRemoto) {
                    $nombre = $logRemoto['nombre'] ?? null;
                    if (!empty($nombre)) {
                        $remotosPorNombre[$nombre] = true;
                    }
                }
            }

            if (!empty($logsRemotos)) {
                LogService::insertarNuevosLogsDeProyecto($proyecto->proyecto_id, $logsRemotos);
            }

            $diasDisponibles = LogService::listarLogs([
                'proyectoId' => $proyecto->proyecto_id
            ]);

            if (empty($syncWarning)) {
                foreach ($diasDisponibles as $diaDisponible) {
                    $diaDisponible->disponible_remoto = isset($remotosPorNombre[$diaDisponible->nombre]);
                }
            }

            return [$proyecto, $diasDisponibles, $syncWarning];

        }, 5);
    }


    public static function sincronizarDetalles($logId)
    {
        $log = LogService::obtenerLog($logId);
        if(empty($log)) {
            throw new Exception('No se pudo hallar el log que se busca sincronizar');
        }

        $proyecto = ProyectoService::obtenerProyecto($log->proyecto_id);

        $urlContenido = UtilsRequest::armarUrlContenidoLog($proyecto->url_endpoint, $log->nombre);

        try {
            $json = UtilsRequest::hacerPeticionGet($urlContenido, $proyecto->api_key, 30);
        } catch (Exception $e) {
            if ($e->getCode() === 404) {
                throw new Exception('No se pudo encontrar el archivo en el remoto para sincronizar.');
            }
            throw $e;
        }
        $contenido = $json['datos']['contenido'] ?? '';

        $items = LogService::parsearContenido($contenido, $logId);

        return DB::transaction(function () use ($proyecto, $items, $log) {
            LogService::actualizarLog($log->log_id);
            ProyectoService::actualizarUltimaSincronizacion($proyecto->proyecto_id);
            return LogService::insertarLogsDetalleProyecto($proyecto->proyecto_id, $items);
        }, 5);
    }

    public static function obtenerDetallesLogs($id, $filtros = []) 
    {
        $log = LogService::obtenerLog($id);
        if(empty($log)) {
            throw new Exception('No se pudo hallar el log que se busca sincronizar');
        }

        $filtros = array_merge(['logId' => $id], $filtros);
        $logDetalles = LogService::listarLogsDetalle($filtros);
        $proyecto = ProyectoService::obtenerProyecto($log->proyecto_id);
        
        return [$logDetalles, $proyecto];

    }

    public static function obtenerAnalisisLogs($id, $rango = 'all')
    {
        $proyecto = ProyectoService::obtenerProyecto($id);
        if (empty($proyecto)) {
            throw new Exception('No se pudo hallar el proyecto que se busca analizar');
        }

        $analisis = LogService::obtenerAnalisisLogsRango($id, $rango);
        return array_merge(['proyecto' => $proyecto], $analisis);
    }
    
}
