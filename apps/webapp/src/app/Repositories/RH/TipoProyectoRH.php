<?php

namespace App\Repositories\RH;

class TipoProyectoRH 
{
    public static function obtenerColumnas(&$query, string $columnas = '') 
    {
        $mapa = [
            'id'                   => 'ctp.tipo_proyecto_id',
            'nombre'               => 'ctp.nombre',
            'status'               => 'ctp.status',
            'registroFecha'        => 'ctp.registro_fecha',
            'registroAutorId'      => 'ctp.registro_autor_id',
            'actualizacionFecha'   => 'ctp.actualizacion_fecha',
            'actualizacionAutorId' => 'ctp.actualizacion_autor_id',
        ];

        if(empty($columnas)) {
            $columnas = implode(',', array_keys($mapa));
        }

        $solicitadas = array_map('trim', explode(',', $columnas));

        $query->select();

        foreach($solicitadas as $col) {
            if(isset($mapa[$col])) {
                $query->addSelect($mapa[$col]);
            }
        }

    }

    public static function obtenerFiltros(&$query, array $filtros)
    {
        if (!empty($filtros['search'])) {
            $query->where('ctp.nombre', 'LIKE', '%' . $filtros['search'] . '%');
        }

        if (!empty($filtros['nombre'])) {
            $query->where('ctp.nombre', $filtros['nombre']);
        }

        if (!empty($filtros['excluirId'])) {
            $query->where('ctp.tipo_proyecto_id', '!=', $filtros['excluirId']);
        }

        if (!empty($filtros['status'])) {
            $status = $filtros['status'];

            if (is_array($status)) {
                $query->whereIn('ctp.status', $status);
            } else {
                $query->where('ctp.status', $status);
            }
        }

    }

    public static function obtenerOrden(&$query, string $orden = '')
    {
        $ordersDisponibles = [
            'tipo_proyecto_asc'   => ['ctp.tipo_proyecto_id', 'asc'],
            'tipo_proyecto_desc'  => ['ctp.tipo_proyecto_id', 'desc'],

            'nombre_asc'          => ['ctp.nombre', 'asc'],
            'nombre_desc'         => ['ctp.nombre', 'desc'],

            'status_asc'          => ['ctp.status', 'asc'],
            'status_desc'         => ['ctp.status', 'desc'],

            'registro_fecha_asc'  => ['ctp.registro_fecha', 'asc'],
            'registro_fecha_desc' => ['ctp.registro_fecha', 'desc'],
        ];

        $defaultKey = 'tipo_proyecto_asc';

        $key = !empty($orden) ? $orden : $defaultKey;

        if (!isset($ordersDisponibles[$key])) {
            $key = $defaultKey;
        }

        [$columna, $direccion] = $ordersDisponibles[$key];

        $query->orderBy($columna, $direccion);
    }
}
