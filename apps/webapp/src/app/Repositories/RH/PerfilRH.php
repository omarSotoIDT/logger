<?php

namespace App\Repositories\RH;

class PerfilRH
{
    public static function obtenerColumnas(&$query, $columnas)
    {
        $mapa = [
            'id' => 'pf.perfil_id',
            'clave' => 'pf.clave',
            'titulo' => 'pf.titulo',
            'descripcion' => 'pf.descripcion',
            'status' => 'pf.status'
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
            $query->where('pf.titulo', 'LIKE', "%{$filtros['search']}%");
        }

        if(!empty($filtros['status'])) {
            $status = $filtros['status'];

            if (is_array($status)) {
                $query->whereIn('pf.status', $status);
            } else {
                $query->where('pf.status', $status);
            }
        }
    }

    public static function obtenerOrden(&$query, $orden)
    {
        $ordersDisponibles = [
            'titulo_asc' => ['pf.titulo', 'asc'],
            'titulo_desc' => ['pf.titulo', 'desc'],

            'usuario_id_asc' => ['up.usuario_id', 'asc'],
            'usuario_id_desc' => ['up.usuario_id', 'desc'],

            'clave_asc' => ['pf.clave', 'asc'],
            'clave_desc' => ['pf.clave', 'desc'],

            'status_asc' => ['pf.status', 'asc'],
            'status_desc' => ['pf.status', 'desc'],

            'registro_fecha_asc' => ['pf.registro_fecha', 'asc'],
            'registro_fecha_desc' => ['pf.registro_fecha', 'desc'],
        ];

        $key = $ordersDisponibles[$orden] ?? $ordersDisponibles['registro_fecha_asc'];

        $query->orderBy($key[0], $key[1]);
    }
}