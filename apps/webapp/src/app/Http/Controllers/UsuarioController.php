<?php

namespace App\Http\Controllers;

use App\const\StatusConsts;
use App\Coordinators\UsuarioCoordinator;
use App\Services\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UsuarioController
{
    public function gestor()
    {
        try {
            [$usuarios, $proyectos, $perfiles] = UsuarioCoordinator::listarProyectosyUsuarios();

            return view('usuarios.index', compact('usuarios', 'proyectos', 'perfiles'));
        } catch (Throwable $e) {
            return back()->with('error', 'Error al listar los usuarios');
        }
    }

    public function agregar(Request $request)
    {
        try {
            $validar = Validator::make($request->all(), [
                'usuario' => 'required',
                'email' => 'required',
                'nombreCorto' => 'required',
                'password' => 'required|min:8',

                'proyectos' => 'nullable|array',
                'proyectos.*' => 'integer'
            ]);

            if ($validar->fails()) {
                return back()->withErrors($validar)->withInput();
            }

            $data = $validar->validated();

            UsuarioCoordinator::agregarUsuarioConProyecto($data);

            return back()->with('success', 'El usuario se creo correctamente');
        } catch (Throwable $e) {
            return back()->with('error', 'No se agrego el usuario correctamente');
        }
    }

    public function actualizar(Request $request, $usuarioId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'usuario' => 'required',
                'email' => 'required',
                'nombreCorto' => 'required',
                'password' => 'nullable|min:8',

                'proyectos' => 'nullable|array',
                'proyectos.*' => 'integer'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();

            UsuarioCoordinator::actualizarUsuarioConProyecto($data, $usuarioId);

            return back()->with('success', 'Usuario actualizado correctamente');
        } catch (Throwable $e) {
            return back()->with('error', 'Error al actualizar el usuario');
        }
    }

    public function eliminar($id)
    {
        try {
            UsuarioService::eliminarUsuario($id);

            return back()->with('success', 'Usuario eliminado correctamente');
        } catch (Throwable $e) {
            return back()->with('error', 'Error al eliminar el Usuario');
        }
    }
}
