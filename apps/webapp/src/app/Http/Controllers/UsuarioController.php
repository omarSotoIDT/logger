<?php

namespace App\Http\Controllers;

use App\const\StatusConsts;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioController
{
    public function gestor() {
        try{
            $usuarios = UsuarioService::listarUsuarios([],['status' => StatusConsts::ACTIVO], null, null, '');

            return view('usuarios.index', compact('usuarios'));
        }catch(Exception $e){
            return back()->with('error', "Error al listar los usuarios");
        }
    }

    public function agregar(Request $request)
    {
        try{
            $validar = Validator::make($request->all(), [

            ]);
        }catch(Exception $e){
            return back()->with('error', 'No se agrego el usuario correctamente');
        }
    }
}