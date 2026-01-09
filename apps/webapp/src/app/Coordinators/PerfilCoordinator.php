<?php

namespace App\Coordinators;

use App\const\StatusConsts;
use App\Services\PerfilService;
use App\Services\PermisoService;
use Illuminate\Support\Facades\DB;

class PerfilCoordinator
{
    public static function listar()
    {
        $permisos = PermisoService::listar('', 'seccion_desc');

        $perfiles = PerfilService::listarPerfil('', ['status' => StatusConsts::ACTIVO], null, null, null, 5);
        
        $permisosPorPerfil = PermisoService::listarPermisosPorPerfil()->groupBy('perfil_id');

        foreach($perfiles as $perfil){
            $perfil->permisos = $permisosPorPerfil[$perfil->perfil_id] ?? collect();
        }

        return [$permisos, $perfiles];
    }

    public static function agregar($datos)
    {
        DB::transaction(function () use ($datos) {
            $perfilId = PerfilService::agregar($datos);

            foreach ($datos['permisos'] ?? [] as $permisoId) {
                PerfilService::agregarRelacion($perfilId, $permisoId);
            }
        }, attempts: 2);
    }

    public static function editar($datos, $perfilId)
    {
        DB::transaction(function () use ($datos, $perfilId) {
            PerfilService::editar($datos, $perfilId);

            if (array_key_exists('permisos', $datos)) {
                PerfilService::editarRelacion($perfilId);

                foreach ($datos['permisos'] as $permisoId) {
                    PerfilService::agregarRelacion($perfilId, $permisoId);
                }
            }
        },  attempts: 2);
    }
}
