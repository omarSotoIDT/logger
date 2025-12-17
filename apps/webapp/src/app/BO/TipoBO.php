<?php

namespace App\BO;

use Illuminate\Support\Facades\Auth;

use function Symfony\Component\Clock\now;

class TipoBO
{
    public static function armarInsertAgregatTipo($data)
    {
        return [
            'nombre'            => $data['nombre'],
            'registro_fecha'    => now(),
            'registro_autor_id' => Auth::id()
        ];
    }

    public static function armarUpdateActualizarTipo($data)
    {
        return [
            'nombre'                 => $data['nombre'],
            'actualizacion_fecha'    => now(),
            'actualizacion_autor_id' => Auth::id()
        ];
    }

    public static function armarUpdateEliminarTipo()
    {
        return [
            'status'                 => 'ELIMINADO',
            'actualizacion_fecha'    => now(),
            'actualizacion_autor_id' => Auth::id()
        ];
    }
}