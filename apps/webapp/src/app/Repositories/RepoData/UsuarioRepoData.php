<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\UsuarioRH;
use Illuminate\Support\Facades\DB;

class UsuarioRepoData
{
    public static function listar($columnas = '', $filtros = [], $limit = null, $offset = null, $orden = '', $paginar = null)
    {
        $query = DB::table('sys_usuarios')->select('usuario_id');

        UsuarioRH::obtenerColumnas($columnas, $query);
        UsuarioRH::obtenerFiltro($filtros, $query);
        UsuarioRH::obtenerOrden($orden, $query);

        if (!empty($limit)) {
            $query->limit($limit);
        }

        if (!empty($offset)) {
            $query->offset($offset);
        }

        return isset($paginar) ? $query->paginate($paginar) : $query->get();
    }

    public static function proyectoActivo($filtros)
    {
        $query = DB::table('rel_usuarios_proyectos');

        UsuarioRH::obtenerFiltro($filtros, $query);

        return $query->exists();
    }

    public static function perfilesActivo($filtros)
    {
        $query = DB::table('rel_usuarios_perfiles');

        UsuarioRH::obtenerFiltro($filtros, $query);

        return $query->exists();
    }
}
