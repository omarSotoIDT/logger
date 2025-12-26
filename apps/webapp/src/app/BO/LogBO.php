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
}
