<?php

namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\DB;

class LogRepoAction
{
    public static function agregar($data)
    {
        return DB::table('logs')->insertGetId($data);
    }

    public static function agregarMasivo($rows)
    {
        return DB::table('logs')->insert($rows);
    }

    public static function actualizar($id, $data)
    {
        return DB::table('logs')
            ->where('log_id', $id)
            ->update($data);
    }

}
