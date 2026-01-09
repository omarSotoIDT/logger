<?php

namespace App\Http\Controllers;

use App\Coordinators\PerfilCoordinator;
use App\Services\PerfilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PerfilController
{
    public function gestor()
    {
        try {
            [$permisos, $perfiles] = PerfilCoordinator::listar();

            return view('perfiles.index', compact('permisos', 'perfiles'));
        } catch (Throwable $e) {
            return back()->with('error', 'Error al listar los perfiles');
        }
    }

    public static function agregar(Request $request)
    {
        try {
            $validar = Validator::make($request->all(), [
                'titulo' => 'required',
                'clave' => 'required',
                'descripcion' => 'required',

                'permisos' => 'nullable|array',
                'permisos.*' => 'integer'
            ]);

            if ($validar->fails()) {
                return back()->withErrors($validar)->withInput();
            }

            $data = $validar->validated();

            PerfilCoordinator::agregar($data);

            return back()->with('success', 'El perfil se creo correctamente');
        } catch (Throwable $e) {
            return back()->with('error', 'No se agrego el perfil correctamente');
        }
    }
}
