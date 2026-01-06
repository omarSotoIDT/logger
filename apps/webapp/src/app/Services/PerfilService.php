<?php

namespace App\Services;

use App\const\StatusConsts;
use App\Repositories\RepoData\PerfilRepoData;

class PerfilService
{
    public static function listarPerfilesPorUsuarios(
        $columna =  'up.usuario_id,pf.perfil_id,pf.nombre',
        $filtros = ['pf.status' => StatusConsts::ACTIVO],
        $limite = null,
        $offset = null,
        $orden = ''
    ) {
        return PerfilRepoData::listarPerfilesPorUsuario($columna, $filtros, $limite, $offset, $orden);
    }
}
