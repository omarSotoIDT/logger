<?php

namespace App\Services;

use App\BO\UsuariosBO;
use App\const\StatusConsts;
use App\Repositories\RepoAction\UsuarioRepoAction;
use App\Repositories\RepoData\UsuarioRepoData;

class UsuarioService{

    public static function listarUsuarios($columna, $filtros, $limite, $offset, $orden){
        return UsuarioRepoData::listar($columna, $filtros, $limite, $offset, $orden);
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

    public static function editarUsuario($datos, $id){

        $actualizarDatos = UsuariosBO::editar($datos);

        return UsuarioRepoAction::editar($actualizarDatos, $id);
    }

    public static function nuevaRelacionProyecto($usuarioId, $proyectos)
    {
        UsuarioRepoAction::eliminarRelacionProyecto($usuarioId);

        foreach($proyectos as $proyectoId){
            $datos = UsuariosBO::agregarRelacionProyecto($usuarioId, $proyectoId);
            UsuarioRepoAction::agregarRelacionProyecto($datos);
        }
    }

    public static function eliminarUsuario($id)
    {
        $status = StatusConsts::ELIMINADO;

        $eliminarUsuario = UsuariosBO::eliminar($status);

        return UsuarioRepoAction::editar($eliminarUsuario, $id);
    }
}