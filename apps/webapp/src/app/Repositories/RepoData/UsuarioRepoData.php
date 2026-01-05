<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\UsuarioRH;
use Illuminate\Support\Facades\DB;

class UsuarioRepoData{

    public static function listar($columnas, $filtros, $limit, $offset, $orden){
        $query = DB::table('sys_usuarios');

        UsuarioRH::obtenerColumnas($columnas, $query);
        UsuarioRH::obtenerFiltro($filtros, $query);
        UsuarioRH::obtenerOrden($orden,$query);

        if(!empty($limit)){
            $query->limit($limit);
        }

        if(!empty($offset)){
            $query->offset($offset);
        }
        
        return $query->paginate(5);
    }
}