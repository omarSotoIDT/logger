<?php

namespace App\Coordinators;

use App\Repositories\RepoData\LogRepoData;
use App\Services\LogDetalleService;
use App\Services\ProyectoService;
use App\Utils\UtilsRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class ProyectoLogDetalleCoordinator
{
    public static function sincronizarDetalles($proyectoId, $nombreArchivo)
    {
        $proyecto = ProyectoService::obtenerProyecto($proyectoId);

        $contenido = UtilsRequest::obtenerContenidoLog(
            $proyecto->url,
            $nombreArchivo,
            $proyecto->apikey
        );

        $log = LogRepoData::listar(
            ['proyectoId' => $proyectoId, 'nombre' => $nombreArchivo],
            'log_id',
            1,
            0
        )->first();

        if (empty($log) || empty($log->log_id)) {
            throw new Exception("No existe el log '{$nombreArchivo}' en BD para el proyecto {$proyectoId}");
        }

        $logId = (int)$log->log_id;

        $items = self::parsearContenido($contenido, $logId);

        DB::beginTransaction();
        try {
            $insertados = LogDetalleService::insertarLogsDetalleProyecto($proyectoId, $items);
            DB::commit();
            return $insertados;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    private static function parsearContenido(string $contenido, int $logId): array
    {
        $contenido = str_replace(["\r\n", "\r"], "\n", $contenido);
        $lineas = explode("\n", $contenido);

        $items = [];
        $curr = null;
        $modoMultilinea = null; 

        $rePrefix = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+\w+\.(\w+):\s*(.*)$/';

        $finalizar = function () use (&$items, &$curr, &$modoMultilinea) {
            if (!$curr) return;

            if (isset($curr['mensaje']) && is_string($curr['mensaje'])) {
                $curr['mensaje'] = trim($curr['mensaje']);
            }
            if (isset($curr['stacktrace']) && is_string($curr['stacktrace'])) {
                $curr['stacktrace'] = trim($curr['stacktrace']);
            }

            if (!empty($curr['codigo_interno']) && !empty($curr['fecha_hora_log']) && !empty($curr['nivel'])) {
                $items[] = $curr;
            }

            $curr = null;
            $modoMultilinea = null;
        };

        foreach ($lineas as $linea) {
            $linea = rtrim($linea);

            if ($linea === '') {
                if ($curr && $modoMultilinea) {
                    $campo = $modoMultilinea;
                    $curr[$campo] = ($curr[$campo] ?? '') . "\n";
                }
                continue;
            }

            if (preg_match($rePrefix, $linea, $m)) {
                $fecha = $m[1];
                $nivel = strtoupper($m[2]);
                $texto = $m[3];

                if (str_starts_with(trim($texto), '============================================================================')) {
                    $finalizar();
                    continue;
                }

                if (str_starts_with($texto, 'CodigoInterno:') && $curr && !empty($curr['codigo_interno'])) {
                    $finalizar();
                }

                if (!$curr) {
                    $curr = [
                        'log_id'           => $logId,
                        'codigo_excepcion' => '0',
                        'codigo_interno'   => null,
                        'mensaje'          => null,
                        'nivel'            => $nivel,
                        'fecha_hora_log'   => $fecha,
                        'archivo'          => null,
                        'linea'            => null,
                        'stacktrace'       => null,
                    ];
                }

                $modoMultilinea = null;

                if (str_starts_with($texto, 'CodigoInterno:')) {
                    $val = trim(substr($texto, strlen('CodigoInterno:')));

                    if (preg_match('/\[(.+)\]\s*$/', $val, $mm)) {
                        $curr['codigo_interno'] = trim($mm[1]);
                    } else {
                        $curr['codigo_interno'] = $val;
                    }
                    continue;
                }

                if (str_starts_with($texto, 'Linea:')) {
                    $val = trim(substr($texto, strlen('Linea:')));
                    $curr['linea'] = is_numeric($val) ? (int)$val : null;
                    continue;
                }

                if (str_starts_with($texto, 'Archivo:')) {
                    $curr['archivo'] = trim(substr($texto, strlen('Archivo:')));
                    continue;
                }

                if (str_starts_with($texto, 'CodigoExcepcion:')) {
                    $curr['codigo_excepcion'] = trim(substr($texto, strlen('CodigoExcepcion:')));
                    continue;
                }

                if (str_starts_with($texto, 'Mensaje:')) {
                    $curr['mensaje'] = ltrim(substr($texto, strlen('Mensaje:')));
                    $modoMultilinea = 'mensaje';
                    continue;
                }

                if (str_starts_with($texto, 'Stacktrace:')) {
                    $curr['stacktrace'] = ltrim(substr($texto, strlen('Stacktrace:')));
                    $modoMultilinea = 'stacktrace';
                    continue;
                }

                continue;
            }

            if ($curr && $modoMultilinea) {
                $campo = $modoMultilinea;
                $curr[$campo] = ($curr[$campo] ?? '') . "\n" . $linea;
            }
        }

        $finalizar();

        return $items;
    }
}
