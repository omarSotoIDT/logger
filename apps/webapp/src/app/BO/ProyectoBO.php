<?php

namespace App\BO;

use App\const\StatusConsts;
use Illuminate\Support\Facades\Auth;

class ProyectoBO 
{
    public static function armarInsertAgregarProyecto($data)
    {
        return [
            'nombre' => $data['nombre'],
            'tipo_proyecto_id' => $data['tipoProyectoId'],
            'url_endpoint' => $data['urlEndpoint'],
            'api_key'=> $data['apiKey'],
            'timezone' => $data['timezone'],
            'status' => $data['status'],

            'registro_fecha' => now(),
            'registro_autor_id' => Auth::id(),
        ];
    }

    public static function armarUpdateActualizarProyecto($data)
    {
        return [
            'nombre' => $data['nombre'],
            'tipo_proyecto_id' => $data['tipoProyectoId'],
            'url_endpoint' => $data['urlEndpoint'],
            'api_key' => $data['apiKey'],
            'timezone' => $data['timezone'],
            'status' => $data['status'],

            'actualizacion_fecha' => now(),
            'actualizacion_autor_id' => Auth::id(),
        ];
    }

    public static function armarUpdateEliminarProyecto()
    {
        return [
            'status' => StatusConsts::ELIMINADO,
            'actualizacion_fecha' => now(),
            'actualizacion_autor_id' => Auth::id(),
        ];
    }
}