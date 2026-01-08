<?php

namespace App\Repositories\RH;

class PermisoRH
{
    public static function obtenerColumnas(&$query, $columnas)
    {
        $mapa = [
            'codigo' => 'p.codigo',
            'titulo' => 'p.titulo',
            'descripcion' => 'p.descripcion',
            'seccion' => 'p.seccion',
            'orden' => 'p.orden'
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

    public static function obtenerFiltros(&$query, $filtros)
    {
        if (!empty($filtros['search'])) {
            $query->where('p.titulo', 'LIKE', "%{$filtros['search']}%");
        }

        if (!empty($filtros['orden'])) {
            $orden = $filtros['orden'];

            if (is_array($orden)) {
                $query->whereIn('p.orden', $orden);
            } else {
                $query->where('p.orden', $orden);
            }
        }
    }

    public static function obtenerOrden(&$query, $orden)
    {
        $ordersDisponibles = [

            'permiso_id_asc' => ['permiso_id', 'asc'],
            'permiso_id_desc' => ['permiso_id', 'desc'],

            'titulo_asc' => ['titulo', 'asc'],
            'titulo_desc' => ['titulo', 'desc'],

            'seccion_asc' => ['seccion', 'asc'],
            'seccion_desc' => ['seccion', 'desc'],

            'orden_asc' => ['orden', 'asc'],
            'orden_desc' => ['orden', 'desc'],
        ];

        $defaultKey = 'orden_asc';

        if (empty($orden)) {
            $ordenes = [$defaultKey];
        } elseif (is_array($orden)) {
            $ordenes = $orden;
        } else {
            $ordenes = [$orden];
        }

        foreach ($ordenes as $orden) {
            [$columnas, $direccion] = $ordersDisponibles[$orden] ?? $ordersDisponibles[$defaultKey];
            $query->orderBy($columnas, $direccion);
        }
    }
}
