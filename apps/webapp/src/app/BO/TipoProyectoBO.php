<?php

namespace App\BO;

use App\const\StatusConsts;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\Clock\now;

class TipoProyectoBO
{
    public static function armarInsertAgregarTipo($data)
    {
        return [
            'nombre'            => $data['nombre'],
            'registro_fecha'    => now(),
            'registro_autor_id' => Auth::id(),
            'status'            => StatusConsts::ACTIVO
        ];
    }

    public static function armarUpdateActualizarTipo($data)
    {
        return [
            'nombre'                 => $data['nombre'],
            'status'                 => $data['status'],
            'actualizacion_fecha'    => now(),
            'actualizacion_autor_id' => Auth::id()
        ];
    }

    public static function armarUpdateEliminarTipo()
    {
        return [
            'status'                 => StatusConsts::ELIMINADO,
            'actualizacion_fecha'    => now(),
            'actualizacion_autor_id' => Auth::id()
        ];
    }
}
