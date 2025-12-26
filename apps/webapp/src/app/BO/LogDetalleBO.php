<?php

namespace App\BO;

use Illuminate\Support\Facades\Auth;

class LogDetalleBO
{
    public static function armarInsertAgregarDetalle(array $item): array
    {
        return [
            'log_id'           => $item['log_id'],
            'codigo_excepcion' => (string)($item['codigo_excepcion'] ?? '0'),
            'codigo_interno'   => (string)$item['codigo_interno'],
            'mensaje'          => (string)($item['mensaje'] ?? ''),
            'nivel'            => (string)$item['nivel'],
            'fecha_hora_log'   => (string)$item['fecha_hora_log'],

            'archivo'          => $item['archivo'] ?? null,
            'linea'            => $item['linea'] ?? null,
            'stacktrace'       => $item['stacktrace'] ?? null,

            'registro_fecha'    => now(),
            'registro_autor_id' => Auth::id(),
            'actualizacion_fecha'    => null,
            'actualizacion_autor_id' => null,
        ];
    }
}
