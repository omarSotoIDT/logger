<?php

namespace App\Repositories\RH;

class LogRH
{
    public static function obtenerColumnas(&$query, $columnas)
    {
        $mapa = [
            'id'         => 'l.log_id AS logId',
            'proyectoId' => 'l.proyecto_id AS proyectoId',
            'nombre'     => 'l.nombre AS nombre',
            'path'       => 'l.path AS path',
            'logFecha'   => 'l.log_fecha AS logFecha',
            'registroFecha' => 'l.registro_fecha AS registroFecha',
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

    public static function obtenerOrden(&$query, $orden)
    {
        $orders = [
            'log_fecha_desc' => ['l.log_fecha', 'desc'],
            'log_fecha_asc'  => ['l.log_fecha', 'asc'],
            'nombre_asc'     => ['l.nombre', 'asc'],
        ];

        $key = $orders[$orden] ?? $orders['log_fecha_desc'];
        $query->orderBy($key[0], $key[1]);
    }
}
