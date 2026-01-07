<?php

namespace App\Services;

use App\BO\UsuariosBO;
use App\const\StatusConsts;
use App\Repositories\RepoAction\UsuarioRepoAction;
use App\Repositories\RepoData\UsuarioRepoData;

class UsuarioService
{

    public static function listarUsuarios($columna = '', $filtros = [], $limite = null, $offset = null, $orden = null, $paginar = null)
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

    public static function eliminarRelacionesProyecto($usuarioId, $proyectos)
    {
        $datos = UsuariosBO::eliminarRelacionProyecto();
        UsuarioRepoAction::eliminarRelacionProyecto($usuarioId, $proyectos, $datos);
    }

    public static function actualizarRelacionProyecto($usuarioId, $proyectos)
    {
        $proyectos = $proyectos ?? [];

        self::eliminarRelacionesProyecto($usuarioId, $proyectos);

        foreach ($proyectos as $proyectoId) {
            $existe = UsuarioRepoData::proyectoActivo(['usuario_id' => $usuarioId, 'proyecto_id' => $proyectoId, 'status' => StatusConsts::ACTIVO]);

            if (!$existe) {
                $datos = UsuariosBO::agregarRelacionProyecto($usuarioId, $proyectoId);
                UsuarioRepoAction::agregarRelacionProyecto($datos);
            }
        }
    }

    public static function eliminarUsuario($id)
    {
        $eliminarUsuario = UsuariosBO::eliminar();

        return UsuarioRepoAction::editar($eliminarUsuario, $id);
    }
}
