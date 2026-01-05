<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Exception;

class UtilsRequest
{

    public static function armarUrlLogs(string $baseUrl): string
    {
        $baseUrl = trim(rtrim($baseUrl, '/'));
        return "{$baseUrl}/logs";
    }

    public static function armarUrlContenidoLog(string $baseUrl, string $nombreArchivo): string
    {
        return self::armarUrlLogs($baseUrl) . '/' . rawurlencode($nombreArchivo);
    }

    public static function hacerPeticionGet(string $url, ?string $apiKey = null, int $timeout = 15): array
    {
        $url = trim(rtrim($url, '/'));

        $http = Http::timeout($timeout)->acceptJson();

        if (!empty($apiKey)) {
            $http = $http->withHeaders(['X-API-KEY' => $apiKey]);
        }

        /** @var Response $resp */
        $resp = $http->get($url);

        if (!$resp->successful()) {
            $body = mb_substr((string)$resp->body(), 0, 400);
            throw new Exception("Error HTTP {$resp->status()} | URL: {$url} | Body: {$body}");
        }

        $json = $resp->json();

        if (!is_array($json)) {
            $body = mb_substr((string)$resp->body(), 0, 400);
            throw new Exception("Respuesta NO es JSON | URL: {$url} | Body: {$body}");
        }

        if (!array_key_exists('codigo', $json)) {
            $body = mb_substr((string)$resp->body(), 0, 400);
            throw new Exception("JSON sin 'codigo' | URL: {$url} | Body: {$body}");
        }

        if ((int)$json['codigo'] !== 200) {
            $msg = $json['mensaje'] ?? 'Error remoto';
            throw new Exception("Endpoint respondió con error: {$msg} | URL: {$url}");
        }

        return $json;
    }
}
