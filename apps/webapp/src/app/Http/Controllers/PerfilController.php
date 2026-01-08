<?php

namespace App\Http\Controllers;

use App\Coordinators\PerfilCoordinator;
use App\Services\PerfilService;
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
}
