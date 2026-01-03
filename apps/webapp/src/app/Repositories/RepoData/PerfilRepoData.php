<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\PerfilRH;
use Illuminate\Support\Facades\DB;

class PerfilRepoData{
    public static function listarPerfilesPorUsuario($columnas, $filtros, $limit, $offset, $orden){
        $query = DB::table('rel_usuarios_perfiles AS up')
            ->join('sys_perfiles AS pf', 'pf.perfil_id', '=', 'up.perfil_id')
            ->select('pf.*');
    
        PerfilRH::obtenerColumnas($query, $columnas);
        PerfilRH::obtenerFiltros($query, $filtros);
        PerfilRH::obtenerOrden($query,$orden);
    
        if(!empty($limit)){
            $query->limit($limit);
        }

        if(!empty($offset)){
            $query->offset($offset);
        }
    
        return $query->get();
    }
}