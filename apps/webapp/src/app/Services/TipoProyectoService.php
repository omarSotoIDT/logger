<?php

namespace App\Services;

use App\BO\TipoProyectoBO;
use App\Repositories\RepoAction\TipoProyectoRepoAction;
use App\Repositories\RepoData\TipoProyectoRepoData;
use Exception;

class TipoProyectoService
{
    public static function listarTipos($filtros = [], $columnas = '', $limit = null, $offset = null, $orden = '')
    {
        return TipoProyectoRepoData::listar($filtros, $columnas, $limit, $offset, $orden);
    }

    private static function validarNombreUnico(string $nombre, ?int $excluirId = null): void
    {
        $filtros = [
            'nombre' => $nombre,
        ];

        if (!empty($excluirId)) {
            $filtros['excluirId'] = $excluirId;
        }

        $existente = self::listarTipos($filtros,'id', 1);

        if ($existente->isNotEmpty()) {
            throw new Exception('Ya existe un tipo de proyecto con ese nombre.');
        }
    }

    public static function agregarTipo($data)
    {
        self::validarNombreUnico($data['nombre']);

        $insert = TipoProyectoBO::armarInsertAgregarTipo($data);
        return TipoProyectoRepoAction::agregar($insert);
    }

    public static function actualizarTipo($id, $data)
    {
        self::validarNombreUnico($data['nombre'], (int)$id);

        $update = TipoProyectoBO::armarUpdateActualizarTipo($data);
        return TipoProyectoRepoAction::actualizar($id, $update);
    }

    public static function eliminarTipo($id)
    {
        $update = TipoProyectoBO::armarUpdateEliminarTipo($id);
        return TipoProyectoRepoAction::actualizar($id, $update);
    }
}