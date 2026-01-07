<?php

namespace App\Services;

use App\BO\ProyectoBO;
use App\Repositories\RepoAction\ProyectoRepoAction;
use App\Repositories\RepoData\ProyectoRepoData;
use Exception;

class ProyectoService
{
    public static function listarProyectos($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '') {
        return ProyectoRepoData::listar($filtros, $columnas, $limit, $offset, $orden);
    }

    public static function obtenerProyecto($id, $columnas = '')
    {
        return ProyectoRepoData::obtener($id, $columnas);
    }

    public static function agregarProyecto($data)
    {
        $insert = ProyectoBO::armarInsertAgregarProyecto($data);
        return ProyectoRepoAction::agregar($insert);
    }

    public static function actualizarProyecto($id, $data)
    {
        $update = ProyectoBO::armarUpdateActualizarProyecto($data);
        return ProyectoRepoAction::actualizar($id, $update);
    }

    public static function eliminarProyecto($id)
    {
        if (ProyectoRepoData::tieneSincronizacion($id)) {
            throw new Exception('El proyecto no puede ser eliminado porque contiene sincronizaciones.');
        }

        $update = ProyectoBO::armarUpdateEliminarProyecto();
        return ProyectoRepoAction::actualizar($id, $update);
    }

    public static function actualizarUltimaSincronizacion($id)
    {
        $update = ProyectoBO::armarUpdateUltimaSincronizacion();
        return ProyectoRepoAction::actualizar($id, $update);
    }
}
