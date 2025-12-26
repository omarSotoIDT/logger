<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Exception;

class UtilsRequest
{
    private static function normalizarUrlListado(string $url): string
    {
        $url = trim($url);
        $url = rtrim($url, '/');

        if (preg_match('#/logs$#', $url)) {
            return $url;
        }

        return $url . '/logs';
    }

    public static function hacerPeticionGet($urlEndpoint, $apiKey = null)
    {
        try {
            $url = self::normalizarUrlListado($urlEndpoint);

            $http = Http::timeout(15)->acceptJson();

            if (!empty($apiKey)) {
                $http = $http->withHeaders([
                    'X-API-KEY' => $apiKey,
                ]);
            }
            /** @var Response $resp */
            $resp = $http->get($url);

            if (!$resp->successful()) {
                $body = mb_substr((string)$resp->body(), 0, 400);
                throw new Exception("Error al consultar endpoint de logs. HTTP {$resp->status()} | URL: {$url} | Body: {$body}");
            }

            $json = $resp->json();

            if (!is_array($json)) {
                $body = mb_substr((string)$resp->body(), 0, 400);
                throw new Exception("Respuesta NO es JSON. URL: {$url} | Body: {$body}");
            }

            if (!array_key_exists('codigo', $json)) {
                $body = mb_substr((string)$resp->body(), 0, 400);
                throw new Exception("JSON sin 'codigo'. URL: {$url} | Body: {$body}");
            }

            if ((int)$json['codigo'] !== 200) {
                $msg = $json['mensaje'] ?? 'Error al listar logs remoto';
                throw new Exception("Endpoint respondió con error: {$msg} | URL: {$url}");
            }

            return $json['datos'] ?? [];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function obtenerContenidoLog($urlEndpoint, $nombreArchivo, $apiKey = null)
    {
        try {
            $url = trim($urlEndpoint);
            $url = rtrim($url, '/');

            if (!preg_match('#/logs$#', $url)) {
                $url .= '/logs';
            }

            $url .= '/' . rawurlencode($nombreArchivo);

            $http = Http::timeout(30)->acceptJson();

            if (!empty($apiKey)) {
                $http = $http->withHeaders(['X-API-KEY' => $apiKey]);
            }

            /** @var Response $resp */
            $resp = $http->get($url);

            if (!$resp->successful()) {
                $body = mb_substr((string)$resp->body(), 0, 400);
                throw new \Exception("Error al obtener log. HTTP {$resp->status()} | URL: {$url} | Body: {$body}");
            }

            $json = $resp->json();
            if (!is_array($json) || !isset($json['codigo'])) {
                $body = mb_substr((string)$resp->body(), 0, 400);
                throw new \Exception("Respuesta inválida del endpoint de log. URL: {$url} | Body: {$body}");
            }

            if ((int)$json['codigo'] !== 200) {
                $msg = $json['mensaje'] ?? 'Error al obtener log remoto';
                throw new \Exception($msg);
            }

            return $json['datos']['contenido'] ?? '';
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
