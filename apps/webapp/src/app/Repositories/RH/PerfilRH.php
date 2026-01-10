<?php

namespace App\Repositories\RH;

use Illuminate\Support\Facades\DB;

class PerfilRH
{
    public static function obtenerColumnas(&$query, $columnas)
    {
        $mapa = [
            'id' => 'pf.perfil_id',
            'clave' => 'pf.clave',
            'titulo' => 'pf.titulo',
            'descripcion' => 'pf.descripcion',
            'status' => 'pf.status',

            'total_permisos' => DB::raw('COUNT(pp.permiso_id) AS total_permisos'),
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
            $query->where('pf.titulo', 'LIKE', "%{$filtros['search']}%");
        }

        if (!empty($filtros['statusPerfiles'])) {
            $status = $filtros['statusPerfiles'];

            if (is_array($status)) {
                $query->whereIn('pf.status', $status);
            } else {
                $query->where('pf.status', $status);
            }
        }

        if (!empty($filtros['statusUsuarioPerfil'])) {
            $status = $filtros['statusUsuarioPerfil'];

            if (is_array($status)) {
                $query->whereIn('up.status', $status);
            } else {
                $query->where('up.status', $status);
            }
        }
    }

    public static function obtenerOrden(&$query, $orden)
    {
        $ordersDisponibles = [

            'perfil_id_asc' => ['pf.perfil_id', 'asc'],
            'perfil_id_desc' => ['pf.perfil_id', 'desc'],

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

        $defaultKey = 'perfil_id_asc';

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
