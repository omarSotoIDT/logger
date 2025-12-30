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

        if(!empty($offset)) {
            $query->offset($offset);
        }

        if(!empty($limit)) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public static function obtener($id, $columnas)
    {
        $query = DB::table('proyectos AS p')
            ->leftJoin('cat_tipos_proyecto AS ctp', 'ctp.tipo_proyecto_id', '=', 'p.tipo_proyecto_id');
        
        ProyectoRH::obtenerColumnas($query, $columnas);

        return $query
            ->where('p.proyecto_id', $id)
            ->first();
    }
}