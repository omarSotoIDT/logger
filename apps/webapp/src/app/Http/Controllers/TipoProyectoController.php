<?php

namespace App\Http\Controllers;

use App\const\StatusConsts;
use App\Services\TipoProyectoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TipoProyectoController extends Controller
{
    public function gestor() {
        try {
            $tipos = TipoProyectoService::listarTipos(['status' => StatusConsts::ACTIVO]);

            return view('tipos.index', compact('tipos'));
        } catch(Throwable $e) {
            return back()->with('error', 'Error al listar los tipos de categorías');
        }
    }

    public function crear(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|max:80',
            ]);

            if($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();

            TipoProyectoService::agregarTipo($data);

            return back()->with('success', 'Tipo creado correctamente');

            
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function actualizar(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|max:80',
            ]);

            if($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();

            TipoProyectoService::actualizarTipo($id, $data);

            return back()->with('success', 'Tipo de proyecto actualizado correctamente');

        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function eliminar($id) {
        try {
            TipoProyectoService::eliminarTipo($id);

            return back()->with('success', 'Tipo de proyecto eliminado correctamente');
        } catch(Throwable $e) {
            return back()->with('error', 'Error al eliminar el tipo de proyecto');
        }
    }
}
