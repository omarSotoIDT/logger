<?php

namespace App\Utils;

class UtilsChart
{
    public static function resolverFechaDesde($rango)
    {
        $rango = strtolower((string)$rango);

        switch ($rango) {
            case '24h':
                return now()->subHours(24);
            case '1w':
                return now()->subWeek();
            case '1m':
                return now()->subMonth();
            case '3m':
                return now()->subMonths(3);
            case '1y':
                return now()->subYear();
            default:
                return null;
        }
    }

    public static function mapearTotalesPorHora(iterable $rows): array
    {
        $mapa = [];

        foreach ($rows as $row) {
            $hora  = (int)($row->hora ?? -1);
            $total = (int)($row->total ?? 0);

            if ($hora >= 0 && $hora <= 23) {
                $mapa[$hora] = $total;
            }
        }

        return $mapa;
    }

    public static function generarDataset24Horas(array $mapaHoraTotales): array
    {
        $labels = [];
        $data   = [];

        for ($i = 0; $i < 24; $i++) {
            $labels[] = str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':00';
            $data[]   = $mapaHoraTotales[$i] ?? 0;
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }
}