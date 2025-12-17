<?php

namespace App\Http\Controllers;

use App\Services\TipoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TipoController extends Controller
{
    public function index() {
        try {
            $tipos = TipoService::listarTipos();

            return view('tipos.index', compact('tipos'));
        } catch(\Exception $e) {
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

            TipoService::agregarTipo($data);

            return back()->with('success', 'Tipo creado correctamente');

            
        } catch(\Exception $e) {
            return back()->with('error', 'Error al crear el nuevo tipo de proyecto');
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

            TipoService::actualizarTipo($id, $data);

            return back()->with('success', 'Tipo de proyecto actualizado correctamente');

        } catch(\Exception $e) {
            return back()->with('error', 'Error al actualizar el tipo de proyecto');
        }
    }

    public function eliminar($id) {
        try {
            TipoService::eliminarTipo($id);

            return back()->with('success', 'Tipo de proyecto eliminado correctamente');
        } catch(\Exception $e) {
            return back()->with('error', 'Error al eliminar el tipo de proyecto');
        }
    }
}
