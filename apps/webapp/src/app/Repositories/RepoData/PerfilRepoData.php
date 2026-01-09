<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\PerfilRH;
use Illuminate\Support\Facades\DB;

class PerfilRepoData
{

    public static function listar($columnas = '', $filtros = [], $limit = null, $offset = null, $orden = '', $paginar = null)
    {
        $query = DB::table('sys_perfiles AS pf')->select('pf.perfil_id', DB::raw('COUNT(pp.permiso_id) as total_permisos'))
            ->leftJoin('rel_perfiles_permisos AS pp', 'pp.perfil_id', '=', 'pf.perfil_id')
            ->groupBy('pf.perfil_id');

        PerfilRH::obtenerColumnas($query, $columnas);
        PerfilRH::obtenerFiltros($query, $filtros);
        PerfilRH::obtenerOrden($query, $orden);

        if (!empty($limit)) {
            $query->limit($limit);
        }

        if (!empty($offset)) {
            $query->offset($offset);
        }

        return isset($paginar) ? $query->paginate($paginar) : $query->get();
    }

    public static function listarPerfilesPorUsuario($columnas = '', $filtros = [], $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('rel_usuarios_perfiles AS up')->select('pf.perfil_id')
            ->join('sys_perfiles AS pf', 'pf.perfil_id', '=', 'up.perfil_id');

        PerfilRH::obtenerColumnas($query, $columnas);
        PerfilRH::obtenerFiltros($query, $filtros);
        PerfilRH::obtenerOrden($query, $orden);

        if (!empty($limit)) {
            $query->limit($limit);
        }

        if (!empty($offset)) {
            $query->offset($offset);
        }

        return $query->get();
    }
}
