<?php

namespace App\Http\Controllers;

class UsuarioController
{
    public function index() {
        return view('usuarios.index');
    }
}