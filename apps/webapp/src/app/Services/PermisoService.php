<?php

namespace App\Services;

use App\Repositories\RepoData\PermisoRepoData;

class PermisoService
{
    public static function listar($columna = '', $filtros = [], $limite = null, $offset = null, $orden = '')
    {
        return PermisoRepoData::listar($columna, $filtros, $limite, $offset, $orden);
    }
}
