<?php

namespace App\BO;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuariosBO{
    public static function agregar($datos){
        return [ 'usuario' => $datos['usuario'],
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

        if(!empty($datos['password'])){
            $actualizar['password'] = Hash::make($datos['password']);
        }

        return $actualizar;
    }

    public static function eliminar($status){
        return [
            'status' => $status,

            'actualizacion_fecha' => now(),
            'actualizacion_autor_id' => Auth::id()
        ];
    }
}