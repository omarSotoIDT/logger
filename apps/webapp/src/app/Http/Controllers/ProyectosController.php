<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProyectosController extends Controller
{
    public function dashboardProyectos() {
        return view('proyectos.dashboard');
    }
}
