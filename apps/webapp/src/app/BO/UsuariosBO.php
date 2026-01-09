<?php

namespace App\BO;

use App\const\StatusConsts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuariosBO
{
    public static function agregar($datos)
    {
        return [
            'usuario' => $datos['usuario'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'nombre_corto' => $datos['nombreCorto'],

            'registro_fecha' => now(),
            'registro_autor_id' => Auth::id()
        ];
    }

    public static function editar($datos)
    {
        $actualizar = [
            'usuario' => $datos['usuario'],
            'email' => $datos['email'],
            'nombre_corto' => $datos['nombreCorto'],

            'actualizacion_fecha' => now(),
            'actualizacion_autor_id' => Auth::id()
        ];

        if (!empty($datos['password'])) {
            $actualizar['password'] = Hash::make($datos['password']);
        }

        return $actualizar;
    }

    public static function eliminar()
    {
        return [
            'status' => StatusConsts::ELIMINADO,

            'actualizacion_fecha' => now(),
            'actualizacion_autor_id' => Auth::id()
        ];
    }

    public static function agregarRelacionProyecto($usuarioId, $proyectoId)
    {
        return [
            'usuario_id' => $usuarioId,
            'proyecto_id' => $proyectoId,

            'registro_fecha' => now(),
            'registro_autor_id' => Auth::id()
        ];
    }

    public static function eliminarRelacionProyecto()
    {
        return [
            'status' => StatusConsts::ELIMINADO,

            'actualizacion_fecha' => now(),
            'actualizacion_autor_id' => Auth::id()
        ];
    }

    public static function agregarRelacionPerfiles($usuarioId, $perfilId)
    {
        return [
            'usuario_id' => $usuarioId,
            'perfil_id' => $perfilId,

            'registro_fecha' => now(),
            'registro_autor_id' => Auth::id()
        ];
    }
}
