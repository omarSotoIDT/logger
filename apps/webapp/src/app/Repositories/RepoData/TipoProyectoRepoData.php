<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\TipoProyectoRH;
use Illuminate\Support\Facades\DB;

class TipoProyectoRepoData 
{
    public static function listar($filtros, $columnas, $limit, $offset, $orden) {
        $query = DB::table('cat_tipos_proyecto AS ctp')
            ->select('ctp.tipo_proyecto_id');;

        TipoProyectoRH::obtenerColumnas($query, $columnas);
        TipoProyectoRH::obtenerFiltros($query, $filtros);
        TipoProyectoRH::obtenerOrden($query, $orden);

        if(!empty($offset)) {
            $query->offset($offset);
        }
        if(!empty($limit)) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
}