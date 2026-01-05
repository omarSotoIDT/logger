<?php

namespace App\Repositories\RH;

class LogRH
{
    public static function obtenerColumnasLog(&$query, $columnas)
    {
        $mapa = [
            'id'            => 'l.log_id',
            'proyectoId'    => 'l.proyecto_id',
            'nombre'        => 'l.nombre',
            'path'          => 'l.path',
            'logFecha'      => 'l.log_fecha',
            'registroFecha' => 'l.registro_fecha',
            'ultimaSincronizacion' => 'l.ultima_sincronizacion',
        ];

        if (empty($columnas)) {
            $columnas = implode(',', array_keys($mapa));
        }

        $solicitadas = array_map('trim', explode(',', $columnas));

        foreach ($solicitadas as $col) {
            if (isset($mapa[$col])) {
                $query->addSelect($mapa[$col]);
            }
        }
    }

    public static function obtenerFiltrosLog(&$query, $filtros)
    {
        if (!empty($filtros['proyectoId'])) {
            $query->where('l.proyecto_id', $filtros['proyectoId']);
        }

        if (!empty($filtros['nombre'])) {
            $query->where('l.nombre', $filtros['nombre']);
        }

        if (array_key_exists('logFechaNotNull', $filtros) && $filtros['logFechaNotNull'] === true) {
            $query->whereNotNull('l.log_fecha');
        }
    }

    public static function obtenerOrdenLog(&$query, $orden)
    {
        $orders = [
            'log_fecha_desc' => ['l.log_fecha', 'desc'],
            'log_fecha_asc'  => ['l.log_fecha', 'asc'],
            'nombre_asc'     => ['l.nombre', 'asc'],
        ];

        $key = $orders[$orden] ?? $orders['log_fecha_desc'];
        $query->orderBy($key[0], $key[1]);
    }

    public static function obtenerColumnasLogDetalle(&$query, $columnas)
    {
        $mapa = [
            'id'                => 'ld.log_detalle_id',
            'logId'             => 'ld.log_id',

            'codigoExcepcion'   => 'ld.codigo_excepcion',
            'codigoInterno'     => 'ld.codigo_interno',
            'mensaje'           => 'ld.mensaje',
            'nivel'             => 'ld.nivel',
            'fechaHoraLog'      => 'ld.fecha_hora_log',

            'archivo'           => 'ld.archivo',
            'linea'             => 'ld.linea',
            'stacktrace'        => 'ld.stacktrace',

            'registroFecha'     => 'ld.registro_fecha',
            'registroAutorId'   => 'ld.registro_autor_id',
            'actualizacionFecha'=> 'ld.actualizacion_fecha',
            'actualizacionAutorId' => 'ld.actualizacion_autor_id',

            'proyectoId'        => 'l.proyecto_id',
        ];

        if (empty($columnas)) {
            $columnas = implode(',', array_keys($mapa));
        }

        $solicitadas = array_map('trim', explode(',', $columnas));

        foreach ($solicitadas as $col) {
            if (isset($mapa[$col])) {
                $query->addSelect($mapa[$col]);
            }
        }
    }

    public static function obtenerFiltrosLogDetalle(&$query, $filtros)
    {
        if (empty($filtros)) {
            return;
        }

        if (!empty($filtros['proyectoId'])) {
            $query->where('l.proyecto_id', $filtros['proyectoId']);
        }

        if (!empty($filtros['logId'])) {
            $query->where('l.log_id', $filtros['logId']);
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
            $search = $filtros['search'];
            $query->where('ld.mensaje', 'LIKE', "%{$search}%");
        }

        if (!empty($filtros['archivo'])) {
            $archivo = $filtros['archivo'];
            $query->where('ld.archivo', 'LIKE', "%{$archivo}%");
        }
    }

    public static function obtenerOrdenLogDetalle(&$query, $orden)
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
