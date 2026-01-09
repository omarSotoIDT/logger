<?php

namespace App\BO;

use Illuminate\Support\Facades\Auth;

class PerfilBO
{
    public static function agregar($datos)
    {
        return [
            'titulo' => $datos['titulo'],
            'clave' => $datos['clave'],
            'descripcion' => $datos['descripcion'],

            'registro_autor_id' => Auth::id(),
            'registro_fecha' => now()
        ];
    }

    public static function agregarRelacion($perfilId, $permisoId)
    {
        return [
            'perfil_id' => $perfilId,
            'permiso_id' => $permisoId,

            'registro_fecha' => now(),
            'registro_autor_id' => Auth::id()
        ];
    }
}
