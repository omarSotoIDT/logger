<?php

namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\DB;

class LogDetalleRepoAction
{
    public static function agregar($data) {
        return DB::table('logs_detalle')->insertGetId($data);
    }

    public static function agregarMasivo($rows) {
        return DB::table('logs_detalle')->insert($rows);
    }
}