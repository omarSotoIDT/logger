<?php

namespace App\Http\Controllers;

use App\const\StatusConsts;
use App\Coordinators\ProyectoCoordinator;
use App\Services\ProyectoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProyectoController extends Controller
{
    public function gestor() {
        try {
            [$proyectos, $tipos, $timezones] = ProyectoCoordinator::listarProyectos();
            return view('proyectos.index', compact('proyectos', 'tipos', 'timezones'));
        } catch(Exception $e) {
            return back()->with('error', 'Error al listar los proyectos');
        }
    }

    public function obtener() {
        
    }

    public function dashboard() {
        try {
            $proyectos = ProyectoService::listarProyectos([
                'status' => [StatusConsts::ACTIVO, StatusConsts::INACTIVO]
            ]);
            return view('dashboard.index', compact('proyectos'));
        } catch(Exception $e) {
            return back()->with('errors', 'Hubo un problema al recuperar el dashboard');
        }
    }

    public function crear(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'nombre'         => 'required|max:120',
                'tipoProyectoId' => 'required|integer',
                'urlEndpoint'    => 'required|max:255',
                'apiKey'         => 'required|max:120',
                'timezone'       => 'required|max:60',
                'status'         => 'required|in:ACTIVO,INACTIVO',
            ]);

            if($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();

            ProyectoService::agregarProyecto($data);

            return back()->with('success', 'Proyecto creado correctamente');
        } catch(Exception $e) {

        }
    }

    public function actualizar(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'nombre'         => 'required|max:120',
                'tipoProyectoId' => 'required|integer',
                'urlEndpoint'    => 'required|max:255',
                'apiKey'         => 'required|max:120',
                'timezone'       => 'required|max:60',
                'status'         => 'required|in:ACTIVO,INACTIVO',
            ]);

            if($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();

            ProyectoService::actualizarProyecto($id, $data);

            return back()->with('success', 'Proyecto actualizado correctamente');
        } catch(Exception $e) {
            return back()->with('error', 'Error al actualizar el proyecto');
        }
    }

    public function eliminar($id)
    {
        try {
            ProyectoService::eliminarProyecto($id);

            return back()->with('success', 'Proyecto eliminado correctamente');
        } catch (Exception $e) {
            return back()->with('error', 'Error al eliminar el proyecto');
        }
    }
}
