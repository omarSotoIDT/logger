<?php

namespace App\Http\Middleware;

use App\const\PermisoConsts;
use App\Services\PermisoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class permisosMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $mensajes = PermisoConsts::MENSAJES;

        $mapaPermisos = PermisoConsts::RUTAS;

        $ruta = $request->route()?->getName();

        if (!$ruta || !isset($mapaPermisos[$ruta])) {
            return $next($request);
        }

        $codigo = $mapaPermisos[$ruta];

        $usuarioId = Auth::id();

        $tienePermiso = PermisoService::validarPermisos($usuarioId, $codigo);

        if(!$tienePermiso){
            return back()->with('error', $mensajes[$codigo]);
        }

        return $next($request);
    }
}
