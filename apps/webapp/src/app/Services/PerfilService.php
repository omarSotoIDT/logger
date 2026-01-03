<?php

namespace App\Services;

use App\Repositories\RepoData\PerfilRepoData;

class PerfilService{

    public static function listarPerfilesPorUsuarios($columna, $filtros, $limite, $offset, $orden){
        return PerfilRepoData::listarPerfilesPorUsuario($columna, $filtros, $limite, $offset, $orden);
    }
}