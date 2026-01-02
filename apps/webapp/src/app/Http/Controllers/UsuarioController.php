<?php

namespace App\Http\Controllers;

use App\const\StatusConsts;
use App\Coordinators\UsuarioCoordinator;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioController
{
    public function gestor() {
        try{
            [$usuarios, $proyectos] = UsuarioCoordinator::listarProyectosyUsuarios();

            return view('usuarios.index', compact('usuarios', 'proyectos'));
        }catch(Exception $e){
            return back()->with('error', "Error al listar los usuarios");
        }
    }

    public function agregar(Request $request)
    {
        try{
            $validar = Validator::make($request->all(), [
                'usuario' => 'required',
                'email' => 'required',
                'nombreCorto' => 'required',
                'password' => 'required|min:8',
            ]);

            if($validar->fails()) {
                return back()->withErrors($validar)->withInput();
            }

            $data = $validar->validated();

            UsuarioService::agregarUsuario($data);

            return back()->with('success', 'El usuario se creo correctamente');

        }catch(Exception $e){
            return back()->with('error', 'No se agrego el usuario correctamente');
        }
    }

    public function actualizar(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'usuario' => 'required',
                'email' => 'required',
                'nombreCorto' => 'required',
                'password' => 'nullable|min:8'
            ]);

            if($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();

            UsuarioService::editarUsuario($data, $id);

            return back()->with('success', 'Usuario actualizado correctamente');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar el usuario');
        }
    }

    public function eliminar($id) {
        try {
            UsuarioService::eliminarUsuario($id);

            return back()->with('success', 'Usuario eliminado correctamente');
        } catch(Exception $e) {
            return back()->with('error', 'Error al eliminar el Usuario');
        }
    }
}