<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sys_permisos')->insert([

            /* Dashboard */

            [
                'codigo' => 'ANALYSIS_VIEW',
                'titulo' => 'Ver graficas',
                'descripcion' => "Permite ver graficas del sistema",
                'seccion' => 'Dashboard',
                'orden' => 1.00,
            ],
            [
                'codigo' => 'SYNC_LOGS',
                'titulo' => 'Sincronizar logs',
                'descripcion' => "Permite sincronizar logs del sistema",
                'seccion' => 'Dashboard',
                'orden' => 2.00,
            ],
            [
                'codigo' => 'LOGS_VIEW',
                'titulo' => 'Ver logs',
                'descripcion' => "Permite sincronizar logs del sistema",
                'seccion' => 'Dashboard',
                'orden' => 3.00,
            ],

            /* Proyectos */

            [
                'codigo' => 'PROJECTS_VIEW',
                'titulo' => 'Ver proyectos',
                'descripcion' => "Permite ver los proyectos del sistema",
                'seccion' => 'Proyectos',
                'orden' => 1.00,
            ],
            [
                'codigo' => 'PROJECTS_CREATE',
                'titulo' => 'Crear proyectos',
                'descripcion' => "Permite crear proyectos del sistema",
                'seccion' => 'Proyectos',
                'orden' => 2.00,
            ],
            [
                'codigo' => 'PROJECTS_EDIT',
                'titulo' => 'Editar proyectos',
                'descripcion' => "Permite editar los proyectos del sistema",
                'seccion' => 'Proyectos',
                'orden' => 3.00,
            ],
            [
                'codigo' => 'PROJECTS_DELETE',
                'titulo' => 'Eliminar proyectos',
                'descripcion' => "Permite eliminar los proyectos del sistema",
                'seccion' => 'Proyectos',
                'orden' => 4.00,
            ],

            /* Tipos */

            [
                'codigo' => 'TYPES_VIEW',
                'titulo' => 'Ver tipos',
                'descripcion' => "Permite ver los tipos del sistema",
                'seccion' => 'Tipos',
                'orden' => 1.00,
            ],
            [
                'codigo' => 'TYPES_CREATE',
                'titulo' => 'Crear tipos',
                'descripcion' => "Permite crear los tipos del sistema",
                'seccion' => 'Tipos',
                'orden' => 2.00,
            ],
            [
                'codigo' => 'TYPES_EDIT',
                'titulo' => 'Editar tipos',
                'descripcion' => "Permite editar los tipos del sistema",
                'seccion' => 'Tipos',
                'orden' => 3.00,
            ],

            /* Usuarios */

            [
                'codigo' => 'USERS_VIEW',
                'titulo' => 'Ver usuarios',
                'descripcion' => 'Permite ver los usuarios del sistema',
                'seccion' => 'Usuarios',
                'orden' => 1.00,
            ],
            [
                'codigo' => 'USERS_CREATE',
                'titulo' => 'Crear usuarios',
                'descripcion' => 'Permite crear usuarios en el sistema',
                'seccion' => 'Usuarios',
                'orden' => 2.00,
            ],
            [
                'codigo' => 'USERS_EDIT',
                'titulo' => 'Editar usuarios',
                'descripcion' => 'Permite editar usuarios del sistema',
                'seccion' => 'Usuarios',
                'orden' => 3.00,
            ],
            [
                'codigo' => 'USERS_DELETE',
                'titulo' => 'Eliminar usuarios',
                'descripcion' => 'Permite eliminar usuarios del sistema',
                'seccion' => 'Usuarios',
                'orden' => 4.00,
            ],

            /* Perfiles */

            [
                'codigo' => 'ROLES_VIEW',
                'titulo' => 'Ver perfiles',
                'descripcion' => 'Permite ver los perfiles del sistema',
                'seccion' => 'Perfiles',
                'orden' => 1.00,
            ],
            [
                'codigo' => 'ROLES_CREATE',
                'titulo' => 'Crear perfiles',
                'descripcion' => 'Permite crear perfiles en el sistema',
                'seccion' => 'Perfiles',
                'orden' => 2.00,
            ],
            [
                'codigo' => 'ROLES_EDIT',
                'titulo' => 'Editar perfiles',
                'descripcion' => 'Permite editar perfiles del sistema',
                'seccion' => 'Perfiles',
                'orden' => 3.00,
            ],
            [
                'codigo' => 'ROLES_DELETE',
                'titulo' => 'Eliminar perfiles',
                'descripcion' => 'Permite eliminar perfiles del sistema',
                'seccion' => 'Perfiles',
                'orden' => 4.00,
            ]

        ]);
    }
}
