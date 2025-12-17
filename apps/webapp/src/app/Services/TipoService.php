<?php

namespace App\Services;

use App\BO\TipoBO;
use App\Repositories\RepoAction\TipoRepoAction;
use App\Repositories\RepoData\TipoRepoData;

class TipoService 
{
    public static function listarTipos($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = [])
    {
        return TipoRepoData::listar($filtros, $columnas, $limit, $offset, $orden);
    }

    public static function agregarTipo($data) 
    {
        $preparado = TipoBO::armarInsertAgregatTipo($data);
        return TipoRepoAction::agregar($preparado);
    }

    public static function actualizarTipo($id, $data) {
        $preparado = TipoBO::armarUpdateActualizarTipo($data);
        return TipoRepoAction::actualizar($id, $preparado);
    }

    public static function eliminarTipo($id)
    {
        $preparado = TipoBO::armarUpdateEliminarTipo($id);
        return TipoRepoAction::actualizar($id, $preparado);
    }
}