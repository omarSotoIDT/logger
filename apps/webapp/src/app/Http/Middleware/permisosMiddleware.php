<?php

namespace App\Http\Middleware;

use App\Repositories\RepoData\PermisoRepoData;
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
        $mensajes = [

            /* Dashboard */ 
            'ANALYSIS_VIEW' => 'No tienes permiso para visualizar graficas', 
            'DETAILS_VIEW' => 'No tienes permiso para visualizar detalles', 
            'SYNC_LOGS' => 'No tienes permiso para sincronizar Logs', 
            'LOGS_VIEW' => 'No tienes permisos para ver Logs',  

            /* Proyectos */ 
            'PROJECTS_VIEW' => 'No tienes permiso para ver visualizar proyectos', 
            'PROJECTS_CREATE' => 'No tienes permiso para crear proyectos.', 
            'PROJECTS_EDIT' => 'No puedes editar proyectos.', 
            'PROJECTS_DELETE' => 'No puedes eliminar proyectos.',

             /* Tipos */ 
            'TYPES_VIEW' => 'No tienes permiso para ver visualizar tipos', 
            'TYPES_CREATE' => 'No tienes permiso para crear tipos.', 
            'TYPES_EDIT' => 'No puedes editar tipos.', 

             /* Usuarios */  
            'USERS_VIEW' => 'No tienes permiso para ver visualizar usuarios', 
            'USERS_CREATE' => 'No tienes permiso para crear usuarios.',
            'USERS_EDIT' => 'No puedes editar usuarios.',
            'USERS_DELETE' => 'No puedes eliminar usuarios.',
             
            /* Perfiles */ 
            'ROLES_VIEW' => 'No tienes permiso para ver visualizar perfiles', 
            'ROLES_CREATE' => 'No tienes permiso para crear perfiles.', 
            'ROLES_EDIT' => 'No puedes editar perfiles.',
            'ROLES_DELETE' => 'No puedes eliminar perfiles.',
        ];

        $mapaPermisos = [
            'proyectos.analisis' => 'ANALYSIS_VIEW',
            'proyectos.sync' => 'SYNC_LOGS',
            'proyectos.detalles' => 'LOGS_VIEW',
    
            'proyectos.index' => 'PROJECTS_VIEW',
            'proyectos.crear' => 'PROJECTS_CREATE',
            'proyectos.actualizar' => 'PROJECTS_EDIT',
            'proyectos.eliminar' => 'PROJECTS_DELETE',
    
            'tipos.index' => 'TYPES_VIEW',
            'tipos.crear' => 'TYPES_CREATE',
            'tipos.actualizar' => 'TYPES_EDIT',
    
            'usuarios.gestor' => 'USERS_VIEW',
            'usuarios.crear' => 'USERS_CREATE',
            'usuarios.actualizar' => 'USERS_EDIT',
            'usuarios.eliminar' => 'USERS_DELETE',
    
            'perfiles.gestor' => 'ROLES_VIEW',
            'perfiles.crear' => 'ROLES_CREATE',
            'perfiles.actualizar' => 'ROLES_EDIT',
            'perfiles.eliminar' => 'ROLES_DELETE',
        ];

        $ruta = $request->route()?->getName();

        if (!$ruta || !isset($mapaPermisos[$ruta])) {
            return $next($request);
        }

        $codigo = $mapaPermisos[$ruta];

        $usuarioId = Auth::id();

        $permisosUsuario = PermisoRepoData::validarPermisos($usuarioId);

        if(!$permisosUsuario->contains($codigo)){
            return back()->with('error', $mensajes[$codigo]);
        }

        return $next($request);
    }
}
