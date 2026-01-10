<?php

namespace App\Repositories\RepoData;

use App\Repositories\RH\PermisoRH;
use Illuminate\Support\Facades\DB;

class PermisoRepoData
{

    public static function listar($columnas = '', $filtros = [], $limit = null, $offset = null, $orden = '')
    {
        $query = DB::table('sys_permisos as p')->select('p.permiso_id');

        PermisoRH::obtenerColumnas($query, $columnas);
        PermisoRH::obtenerFiltros($query, $filtros);
        PermisoRH::obtenerOrden($query, $orden);

        if (!empty($limit)) {
            $query->limit($limit);
        }

        if (!empty($offset)) {
            $query->offset($offset);
        }

        return $query->get();
    }

    public static function listarPermisosPorPerfil($columnas = '', $filtros = [])
    {
        $query = DB::table('rel_perfiles_permisos as rp')->select('rp.perfil_id', 'rp.permiso_id')
            ->join('sys_permisos as p', 'p.permiso_id', '=', 'rp.permiso_id');

        PermisoRH::obtenerColumnas($query, $columnas);
        PermisoRH::obtenerFiltros($query, $filtros);

        return $query->get();
    }

    public static function validarPermisos($usuarioId)
    {
        return DB::table('sys_permisos as p')->select('p.codigo')
            ->join('rel_perfiles_permisos as rp', 'rp.permiso_id', '=', 'p.permiso_id')
            ->join('rel_usuarios_perfiles as up', 'up.perfil_id', '=', 'rp.perfil_id')
            ->where('up.usuario_id', '=', $usuarioId)->where('up.status', '=', 'ACTIVO')
            ->pluck('codigo');
    }
}
