<?php

namespace App\Repositories\RH;

class UsuarioRH
{
    public static function obtenerColumnas($columnas, &$query){

        $camposUsuarios = [
            'usuariosId' => 'usuario_id',
            'usuario' => 'usuario',
            'nombreCorto' => 'nombre_corto',
            'email' => 'email',
            'telefono' => 'telefono',
            'ultimoAccesoFecha' => 'ultimo_acceso_fecha',
            'status' => 'status',
            'registroFecha' => 'registro_fecha',
            'registroAutorId' => 'registro_autor_id',
            'actualizacionFecha' => 'actualizacion_fecha',
            'actualizacionAutorId' => 'actualizacion_autor_id'
        ];

        if(empty($columnas)){
            foreach($camposUsuarios as $value){
                $query->addSelect($value);
            }
        }else{
            foreach($camposUsuarios as $value){
                if(isset($camposUsuarios[$value])){
                    $query->addSelect($camposUsuarios[$value]);
                }
            }
        }
    }

    public static function obtenerFiltro($filtro, &$query)
    {
        $filtros_usuarios = [
            'usuario' => 'usuario',
            'status' => 'status'
        ];

        foreach($filtro as $key => $value){
            if(!empty($filtros_usuarios[$key])){
                if(is_array($value)){
                    $query->whereIn($key, $value);
                }else{
                    $query->where($key, $value);
                }
            }
        }
    }

    public static function obtenerOrden($orden, &$query){

        $ordersDisponibles = [

            'usuario_id_asc' => ['usuario_id', 'asc'],
            'usuario_id_desc' => ['usuario_id', 'desc'],

            'usuario_asc' => ['usuario', 'asc'],
            'usuario_desc' => ['usuario', 'desc'],

            'status_asc' => ['status', 'asc'],
            'status_desc' => ['status', 'desc'],

            'registro_fecha_asc' => ['registro_fecha', 'asc'],
            'registro_fecha_desc' => ['registro_fecha', 'desc'],
        ];

        $defaultKey = 'usuario_id_asc';

        $key = !empty($orden) ? $orden : $defaultKey;

        if (!isset($ordersDisponibles[$key])) {
            $key = $defaultKey;
        }

        [$columna, $direccion] = $ordersDisponibles[$key];

        $query->orderBy($columna, $direccion);
    }
}