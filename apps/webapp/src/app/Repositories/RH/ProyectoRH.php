<?php

namespace App\Repositories\RH;

class ProyectoRH
{
    public static function obtenerColumnas(&$query, $columnas)
    {
        $mapa = [
            'id' => 'p.proyecto_id AS proyectoId',
            'nombre' => 'p.nombre AS nombre',
            'tipoProyectoId' => 'p.tipo_proyecto_id AS tipoId',
            'tipoProyectoNobre' => 'ctp.nombre AS tipoNombre',
            'urlEndpoint' => 'p.url_endpoint AS url',
            'apiKey' => 'p.api_key AS api',
            'timezone' => 'p.timezone AS timezone',
            'status' => 'p.status AS status'
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

    public static function obtenerFiltros(&$query, $filtros)
    {
        if(!empty($filtros['search'])) {
            $query->where('p.nombre', 'LIKE', "%{$filtros['search']}%");
        }

        if(!empty($filtros['status'])) {
            $status = $filtros['status'];

            if (is_array($status)) {
                $query->whereIn('p.status', $status);
            } else {
                $query->where('p.status', $status);
            }
        }
    }

    public static function obtenerOrden(&$query, $orden)
    {
        $ordersDisponibles = [
            'nombre_asc'          => ['p.nombre', 'asc'],
            'nombre_desc'         => ['p.nombre', 'desc'],

            'status_asc'          => ['p.status', 'asc'],
            'status_desc'         => ['p.status', 'desc'],

            'registro_fecha_asc'  => ['p.registro_fecha', 'asc'],
            'registro_fecha_desc' => ['p.registro_fecha', 'desc'],
        ];

        $key = $ordersDisponibles[$orden] ?? $ordersDisponibles['registro_fecha_asc'];

        $query->orderBy($key[0], $key[1]);
    }
}