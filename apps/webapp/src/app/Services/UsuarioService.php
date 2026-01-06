<?php

namespace App\Services;

use App\BO\UsuariosBO;
use App\const\StatusConsts;
use App\Repositories\RepoAction\UsuarioRepoAction;
use App\Repositories\RepoData\UsuarioRepoData;

class UsuarioService
{

    public static function listarUsuarios($columna = [], $filtros = ['status' => StatusConsts::ACTIVO], $limite = null, $offset = null, $orden = null, $paginar = 5)
    {
        return UsuarioRepoData::listar($columna, $filtros, $limite, $offset, $orden, $paginar);
    }

    public static function agregarUsuario($datos)
    {
        $insertarUsuario = UsuariosBO::agregar($datos);

        return UsuarioRepoAction::agregar($insertarUsuario);
    }

    public static function agregarRelacionProyecto($usuarioId, $proyectoId)
    {
        $insertarRelacion = UsuariosBO::agregarRelacionProyecto($usuarioId, $proyectoId);
        UsuarioRepoAction::agregarRelacionProyecto($insertarRelacion);
    }

    public static function editarUsuario($datos, $id)
    {

        $actualizarDatos = UsuariosBO::editar($datos);

        return UsuarioRepoAction::editar($actualizarDatos, $id);
    }

    public static function nuevaRelacionProyecto($usuarioId, $proyectos)
    {
        UsuarioRepoAction::eliminarRelacionProyecto($usuarioId);

        foreach ($proyectos as $proyectoId) {
            $datos = UsuariosBO::agregarRelacionProyecto($usuarioId, $proyectoId);
            UsuarioRepoAction::agregarRelacionProyecto($datos);
        }
    }

    public static function eliminarUsuario($id)
    {
        $eliminarUsuario = UsuariosBO::eliminar();

        return UsuarioRepoAction::editar($eliminarUsuario, $id);
    }
}
