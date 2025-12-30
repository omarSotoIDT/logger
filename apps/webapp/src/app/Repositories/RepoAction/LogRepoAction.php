<?php

namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\DB;

class LogRepoAction
{
    public static function agregarLog($data)
    {
        return DB::table('logs')->insertGetId($data);
    }

    public static function agregarMasivoLogs($rows)
    {
        return DB::table('logs')->insert($rows);
    }

    public static function actualizarLog($id, $data)
    {
        DB::table('logs')
            ->where('log_id', $id)
            ->update($data);
    }
    
    public static function agregarLogDetalle($data) {
        return DB::table('logs_detalle')->insertGetId($data);
    }

    public static function agregarMasivoLogsDetalle($rows) {
        return DB::table('logs_detalle')->insert($rows);
    }
}
