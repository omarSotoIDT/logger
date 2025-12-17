<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('sys_usuarios')->insert([
            'usuario'                     => 'admin',
            'password'                    => Hash::make('password'),
            'nombre_corto'                => 'Administrador',
            'email'                       => 'admin@logger.local',
            'telefono'                    => null,

            'ultimo_acceso_fecha'         => null,

            'status'                      => 'ACTIVO',

            'registro_fecha'              => now(),
            'registro_autor_id'           => 1,
            'actualizacion_fecha'         => null,
            'actualizacion_autor_id'      => null,
        ]);

        $tipos = [
            'Laravel',
            'Python',
            'Node.JS',
            'Django'
        ];

        foreach ($tipos as $nombre) {
            DB::table('cat_tipos_proyecto')->insert([
                'nombre'                  => $nombre,
                'status'                  => 'ACTIVO',
                'registro_fecha'          => now(),
                'registro_autor_id'       => null,
                'actualizacion_fecha'     => null,
                'actualizacion_autor_id'  => null,
            ]);
        }
    
    }
}
