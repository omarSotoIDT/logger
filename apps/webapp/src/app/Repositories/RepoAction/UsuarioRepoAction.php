<?php
namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\DB;

class UsuarioRepoAction
{
    public static function agregar($datos)
    {
        DB::table('sys_usuarios')->insert($datos);
    }

    public static function editar($datos, $id){
        DB::table('sys_usuarios')->where('usuario_id', $id)->update($datos);
    }
}