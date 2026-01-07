<?php

namespace App\Services;

use App\const\StatusConsts;
use App\Repositories\RepoData\PerfilRepoData;

class PerfilService
{
    public static function listarPerfil($columna = '', $filtros = [], $limite = null, $offset = null, $orden = null, $paginar = null)
    {
        return PerfilRepoData::listar($columna, $filtros, $limite, $offset, $orden, $paginar);
    }

    public static function listarPerfilesPorUsuarios($columna = '', $filtros = [], $limite = null, $offset = null, $orden = '')
    {
        return PerfilRepoData::listarPerfilesPorUsuario($columna, $filtros, $limite, $offset, $orden);
    }
}
