<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\ProyectoRH;
use Illuminate\Support\Facades\DB;

class ProyectoRepoData
{
    public static function listar($filtros, $columnas, $limit, $offset, $orden)
    {
        $query = DB::table('proyectos AS p')
            ->leftJoin('cat_tipos_proyecto AS ctp', 'ctp.tipo_proyecto_id', '=', 'p.tipo_proyecto_id');

        ProyectoRH::obtenerColumnas($query, $columnas);
        ProyectoRH::obtenerFiltros($query, $filtros);
        ProyectoRH::obtenerOrden($query, $orden);

        if (!empty($offset)) {
            $query->offset($offset);
        }

        if (!empty($limit)) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function listarProyectosPorUsuario($columnas = '', $filtros = [], $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('rel_usuarios_proyectos AS rup')->select('rup.usuario_id')
            ->join('proyectos AS p', 'p.proyecto_id', '=', 'rup.proyecto_id');

        ProyectoRH::obtenerColumnas($query, $columnas);
        ProyectoRH::obtenerFiltros($query, $filtros);
        ProyectoRH::obtenerOrden($query, $orden);

        if (!empty($offset)) {
            $query->offset($offset);
        }

        if (!empty($limit)) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function obtener($id, $filtros, $columnas)
    {
        $query = DB::table('proyectos AS p')
            ->leftJoin('cat_tipos_proyecto AS ctp', 'ctp.tipo_proyecto_id', '=', 'p.tipo_proyecto_id');

        ProyectoRH::obtenerColumnas($query, $columnas);

        return $query
            ->where('p.proyecto_id', $id)
            ->first();
    }

    public static function tieneSincronizacion($id)
    {
        return DB::table('logs AS l')
            ->where('l.proyecto_id', $id)
            ->whereNotNull('l.ultima_sincronizacion')
            ->exists();
    }
}
