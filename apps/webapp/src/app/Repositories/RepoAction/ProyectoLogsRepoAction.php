<?php

namespace App\Repositories\RepoAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProyectoLogsRepoAction
{
    public static function upsertLogs(array $rows)
    {
        return DB::table('logs')->upsert(
            $rows,
            ['proyecto_id', 'nombre'],
            ['path', 'log_fecha']
        );
    }

    public static function reemplazarDetallesYMarcarSync($logId, array $detalles)
    {
        return DB::transaction(function () use ($logId, $detalles) {
            DB::table('logs_detalle')
                ->where('log_id', $logId)
                ->delete();

            if (!empty($detalles)) {
                DB::table('logs_detalle')->insert($detalles);
            }

            DB::table('logs')
                ->where('log_id', $logId)
                ->update([
                    'actualizacion_fecha' => now(),
                    'actualizacion_autor_id' => Auth::id(),
                ]);

            return true;
        });
    }
}
