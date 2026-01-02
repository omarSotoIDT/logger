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
                'password' => 'required',
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

    public function eliminar($id) {
        try {
            UsuarioService::eliminarUsuario($id);

            return back()->with('success', 'Usuario eliminado correctamente');
        } catch(Exception $e) {
            return back()->with('error', 'Error al eliminar el Usuario');
        }
    }
}