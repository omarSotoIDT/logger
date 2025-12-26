<?php

namespace App\Repositories\RH;

class LogDetalleRH
{
    public static function obtenerColumnas(&$query, $columnas)
    {
        $mapa = [
            'id'                => 'ld.log_detalle_id AS logDetalleId',
            'logId'             => 'ld.log_id AS logId',

            'codigoExcepcion'   => 'ld.codigo_excepcion AS codigoExcepcion',
            'codigoInterno'     => 'ld.codigo_interno AS codigoInterno',
            'mensaje'           => 'ld.mensaje AS mensaje',
            'nivel'             => 'ld.nivel AS nivel',
            'fechaHoraLog'      => 'ld.fecha_hora_log AS fechaHoraLog',

            'archivo'           => 'ld.archivo AS archivo',
            'linea'             => 'ld.linea AS linea',
            'stacktrace'        => 'ld.stacktrace AS stacktrace',

            'registroFecha'     => 'ld.registro_fecha AS registroFecha',
            'registroAutorId'   => 'ld.registro_autor_id AS registroAutorId',
            'actualizacionFecha'=> 'ld.actualizacion_fecha AS actualizacionFecha',
            'actualizacionAutorId' => 'ld.actualizacion_autor_id AS actualizacionAutorId',

            'proyectoId'        => 'l.proyecto_id AS proyectoId',
        ];

        if (empty($columnas)) {
            $columnas = implode(',', array_keys($mapa));
        }

        $solicitadas = array_map('trim', explode(',', $columnas));

        $query->select();

        foreach ($solicitadas as $col) {
            if (isset($mapa[$col])) {
                $query->addSelect($mapa[$col]);
            }
        }
    }

    public static function obtenerFiltros(&$query, $filtros)
    {
        if (empty($filtros)) return;

        if (!empty($filtros['proyectoId'])) {
            $query->where('l.proyecto_id', $filtros['proyectoId']);
        }

        if (!empty($filtros['logId'])) {
            $query->where('ld.log_id', $filtros['logId']);
        }

        if (!empty($filtros['codigoInterno'])) {
            $query->where('ld.codigo_interno', $filtros['codigoInterno']);
        }

        if (!empty($filtros['nivel'])) {
            $nivel = $filtros['nivel'];

            if (is_array($nivel)) {
                $query->whereIn('ld.nivel', $nivel);
            } else {
                $query->where('ld.nivel', $nivel);
            }
        }

        if (!empty($filtros['search'])) {
            $s = $filtros['search'];
            $query->where('ld.mensaje', 'LIKE', "%{$s}%");
        }
    }

    public static function obtenerOrden(&$query, $orden)
    {
        $ordersDisponibles = [
            'fecha_hora_log_asc'   => ['ld.fecha_hora_log', 'asc'],
            'fecha_hora_log_desc'  => ['ld.fecha_hora_log', 'desc'],

            'nivel_asc'            => ['ld.nivel', 'asc'],
            'nivel_desc'           => ['ld.nivel', 'desc'],

            'codigo_interno_asc'   => ['ld.codigo_interno', 'asc'],
            'codigo_interno_desc'  => ['ld.codigo_interno', 'desc'],

            'registro_fecha_asc'   => ['ld.registro_fecha', 'asc'],
            'registro_fecha_desc'  => ['ld.registro_fecha', 'desc'],
        ];

        $key = $ordersDisponibles[$orden] ?? $ordersDisponibles['fecha_hora_log_desc'];

        $query->orderBy($key[0], $key[1]);
    }
}
