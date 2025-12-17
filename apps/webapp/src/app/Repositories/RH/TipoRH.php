<?php

namespace App\Repositories\RH;

class TipoRH 
{
    public static function obtenerColumnas(&$query, string $columnas = '') 
    {
        $mapa = [
            'id'                 => 'ctp.tipo_proyecto_id',
            'nombre'             => 'ctp.nombre',
            'status'             => 'ctp.status',
            'registroFecha'      => 'ctp.registro_fecha',
            'registroAutorId'    => 'ctp.registro_autor_id',
            'actualizacionFecha' => 'ctp.actualizacion_fecha',
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

        if (!empty($filtros['status'])) {
            $query->where('ctp.status', $filtros['status']);
        }
    }

    public static function obtenerOrden(&$query, array $orden) 
    {
        $columna = $orden['columna'] ?? 'ctp.tipo_proyecto_id';
        $direccion = $orden['direccion'] ?? 'asc';

        $query->orderBy($columna, $direccion);
    }
}