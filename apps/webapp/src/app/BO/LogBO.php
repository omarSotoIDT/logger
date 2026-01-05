<?php

namespace App\BO;

use Illuminate\Support\Facades\Auth;

class LogBO
{
    public static function armarInsertAgregarLog($proyectoId, $item)
    {
        return [
            'proyecto_id' => $proyectoId,
            'nombre'      => $item['nombre'],
            'path'        => $item['path'],
            'log_fecha'   => self::extraerFechaDesdeNombre($item['nombre']),

            'registro_fecha'    => now(),
            'registro_autor_id' => Auth::id(),
        ];
    }

    public static function armarUpdateActualizarLog() {
        return [
            'ultima_sincronizacion' => now(),
            'actualizacion_fecha' => now(),
            'actualizacion_autor_id' => Auth::id(),
        ];
    }

    public static function extraerFechaDesdeNombre($nombre)
    {
        if (preg_match('/(\d{4}-\d{2}-\d{2})/', $nombre, $m)) {
            return $m[1];
        }

        return null;
    }

    public static function armarInsertAgregarDetalle(array $item): array
    {
        return [
            'log_id'           => $item['log_id'],
            'codigo_excepcion' => (string)($item['codigo_excepcion'] ?? '0'),
            'codigo_interno'   => (string)$item['codigo_interno'],
            'codigo_interno_mensaje' => $item['codigo_interno_mensaje'] ?? null,
            'mensaje'          => (string)($item['mensaje'] ?? ''),
            'nivel'            => (string)$item['nivel'],
            'fecha_hora_log'   => (string)$item['fecha_hora_log'],

            'archivo'          => $item['archivo'] ?? null,
            'linea'            => $item['linea'] ?? null,
            'stacktrace'       => $item['stacktrace'] ?? null,

            'registro_fecha'    => now(),
            'registro_autor_id' => Auth::id(),
        ];
    }

    public static function parsearContenido(string $contenido, int $logId): array
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
                        'codigo_interno_mensaje' => null,
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

                    if (preg_match('/^(.*)\[(.+)\]\s*$/', $val, $mm)) {
                        $mensaje = trim($mm[1]);
                        $codigo = trim($mm[2]);
                        $curr['codigo_interno'] = $codigo !== '' ? $codigo : null;
                        $curr['codigo_interno_mensaje'] = $mensaje !== '' ? $mensaje : null;
                    } else {
                        $curr['codigo_interno'] = $val !== '' ? $val : null;
                    }
                    continue;
                }

                if (str_starts_with($texto, 'CodigoInternoMensaje:')) {
                    $val = trim(substr($texto, strlen('CodigoInternoMensaje:')));
                    $curr['codigo_interno_mensaje'] = $val !== '' ? $val : null;
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

            if ($curr && str_starts_with($linea, 'CodigoInterno:')) {
                $val = trim(substr($linea, strlen('CodigoInterno:')));
                if (preg_match('/^(.*)\[(.+)\]\s*$/', $val, $mm)) {
                    $mensaje = trim($mm[1]);
                    $codigo = trim($mm[2]);
                    $curr['codigo_interno'] = $codigo !== '' ? $codigo : null;
                    $curr['codigo_interno_mensaje'] = $mensaje !== '' ? $mensaje : null;
                } else {
                    $curr['codigo_interno'] = $val !== '' ? $val : null;
                }
                $modoMultilinea = null;
            }
        }

        $finalizar();

        return $items;
    }
}
