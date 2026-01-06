<?php

namespace App\Http\Controllers;

use App\const\StatusConsts;
use App\Coordinators\ProyectoCoordinator;
use App\Services\ProyectoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProyectoController extends Controller
{
    public function gestor() {
        try {
            [$proyectos, $tipos, $timezones] = ProyectoCoordinator::listarProyectos();
            return view('proyectos.index', compact('proyectos', 'tipos', 'timezones'));
        } catch(Throwable $e) {
            return back()->with('error', 'Error al listar los proyectos');
        }
    }

    public function obtener($id)
    {
        try {
            [$proyecto, $diasDisponibles, $syncWarning] = ProyectoCoordinator::obtenerDetalle($id);

            if (!empty($syncWarning)) {
                session()->flash('warning', $syncWarning);
            }

            return view('dashboard.detalles', compact('proyecto', 'diasDisponibles'));

        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function sincronizarDetalles(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'log_id'         => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();

            $insertados = ProyectoCoordinator::sincronizarDetalles(
                $data['log_id']
            );

            if(empty($insertados)) {
                return back()->with('success', 'EL archivo ya está al día con los últimos cambios');
            }

            return back()->with('success', "Sincronización completa. Insertados: {$insertados}");
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    
    public function dashboard() {
        try {
            $proyectos = ProyectoService::listarProyectos([
                'status' => [StatusConsts::ACTIVO, StatusConsts::INACTIVO]
            ]);
            return view('dashboard.index', compact('proyectos'));
        } catch(Throwable $e) {
            return back()->with('error', 'Hubo un problema al recuperar el dashboard');
        }
    }

    public function verDetalles(Request $request, $id) {
        try {
            $filtros = [
                'nivel' => $request->input('nivel'),
                'search' => $request->input('search'),
                'archivo' => $request->input('archivo'),
                'codigoInterno' => $request->input('codigoInterno'),
            ];

            [$logs, $proyecto] = ProyectoCoordinator::obtenerDetallesLogs($id, $filtros);
            return view('dashboard.logDetalle', compact('logs', 'proyecto'));
        } catch(Throwable $e) {
            return back()->with('error', 'Hubo un problema al recuperar los los del día');
        }
    }

    public function verAnalisis(Request $request, $id) {
        try {
            $rango = $request->input('rango', 'all');
            $data = ProyectoCoordinator::obtenerAnalisisLogs($id, $rango);
            return view('dashboard.analisis', $data);
        } catch(Throwable $e) {
            return back()->with('error', 'Hubo un problema al recuperar el análisis del proyecto');
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
        } catch(Throwable $e) {
            return back()->with('error', "Error al crear el proyecto {$e}");
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
        } catch(Throwable $e) {
            return back()->with('error', 'Error al actualizar el proyecto');
        }
    }

    public function eliminar($id)
    {
        try {
            ProyectoService::eliminarProyecto($id);

            return back()->with('success', 'Proyecto eliminado correctamente');
        } catch (Throwable $e) {
            return back()->with('error', 'Error al eliminar el proyecto');
        }
    }
}
