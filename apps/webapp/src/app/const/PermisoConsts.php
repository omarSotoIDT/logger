<?php

namespace App\const;

class PermisoConsts
{
    public const MENSAJES = [
        /* Dashboard */
        'ANALYSIS_VIEW' => 'No tienes permiso para visualizar graficas',
        'DETAILS_VIEW' => 'No tienes permiso para visualizar detalles',
        'SYNC_LOGS' => 'No tienes permiso para sincronizar Logs',
        'LOGS_VIEW' => 'No tienes permisos para ver Logs',

        /* Proyectos */
        'PROJECTS_VIEW' => 'No tienes permiso para ver visualizar proyectos',
        'PROJECTS_CREATE' => 'No tienes permiso para crear proyectos.',
        'PROJECTS_EDIT' => 'No puedes editar proyectos.',
        'PROJECTS_DELETE' => 'No puedes eliminar proyectos.',

        /* Tipos */
        'TYPES_VIEW' => 'No tienes permiso para ver visualizar tipos',
        'TYPES_CREATE' => 'No tienes permiso para crear tipos.',
        'TYPES_EDIT' => 'No puedes editar tipos.',

        /* Usuarios */
        'USERS_VIEW' => 'No tienes permiso para ver visualizar usuarios',
        'USERS_CREATE' => 'No tienes permiso para crear usuarios.',
        'USERS_EDIT' => 'No puedes editar usuarios.',
        'USERS_DELETE' => 'No puedes eliminar usuarios.',

        /* Perfiles */
        'ROLES_VIEW' => 'No tienes permiso para ver visualizar perfiles',
        'ROLES_CREATE' => 'No tienes permiso para crear perfiles.',
        'ROLES_EDIT' => 'No puedes editar perfiles.',
        'ROLES_DELETE' => 'No puedes eliminar perfiles.',
    ];

    public const RUTAS = [

        'proyectos.analisis' => 'ANALYSIS_VIEW',
        'proyectos.sync' => 'SYNC_LOGS',
        'proyectos.detalles' => 'LOGS_VIEW',

        'proyectos.index' => 'PROJECTS_VIEW',
        'proyectos.crear' => 'PROJECTS_CREATE',
        'proyectos.actualizar' => 'PROJECTS_EDIT',
        'proyectos.eliminar' => 'PROJECTS_DELETE',

        'tipos.index' => 'TYPES_VIEW',
        'tipos.crear' => 'TYPES_CREATE',
        'tipos.actualizar' => 'TYPES_EDIT',

        'usuarios.gestor' => 'USERS_VIEW',
        'usuarios.crear' => 'USERS_CREATE',
        'usuarios.actualizar' => 'USERS_EDIT',
        'usuarios.eliminar' => 'USERS_DELETE',

        'perfiles.gestor' => 'ROLES_VIEW',
        'perfiles.crear' => 'ROLES_CREATE',
        'perfiles.actualizar' => 'ROLES_EDIT',
        'perfiles.eliminar' => 'ROLES_DELETE',
    ];
}
