<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\TipoRH;
use Illuminate\Support\Facades\DB;

class TipoRepoData 
{
    public static function listar($filtros, $columnas, $limit, $offset, $orden) {
        $query = DB::table('cat_tipos_proyecto AS ctp');

        TipoRH::obtenerColumnas($query, $columnas);
        TipoRH::obtenerFiltros($query, $filtros);
        TipoRH::obtenerOrden($query, $orden);

        if(!empty($offset)) {
            $query->offset($offset);
        }
        
        if(!empty($limit)) {
            $query->limit($limit);
        }
        
        return $query->where('ctp.status', 'ACTIVO')->get();
    }
}