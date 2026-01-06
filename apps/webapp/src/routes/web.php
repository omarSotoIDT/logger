<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\TipoProyectoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;


 /* Autenticación */

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.logger');


Route::middleware(['auth'])
    ->prefix('logger')
    ->group(function () {

        /* Dashboard */
        Route::controller(DashboardController::class)
            ->prefix('/')
            ->name('dashboard.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });

        /* Proyectos */
        Route::controller(ProyectoController::class)
            ->prefix('proyectos')
            ->name('proyectos.')
            ->group(function () {
                Route::get('/', 'gestor')->name('index');
                Route::post('/', 'crear')->name('crear');
                Route::patch('/{id}', 'actualizar')->name('actualizar');
                Route::delete('/{id}', 'eliminar')->name('eliminar');

                /* Dashboard */
                Route::get('/dashboard', 'dashboard')->name('dashboard');
                Route::get('/{id}/analisis', 'verAnalisis')->name('analisis');
                Route::get('/{id}/detalles', 'verDetalles')->name('detalles');
                Route::get('/{id}', 'obtener')->name('obtener');
                Route::post('/sync', 'sincronizarDetalles')->name('sync');
            });

        /* Tipos de Proyecto */
        Route::controller(TipoProyectoController::class)
            ->prefix('tipos-proyecto')
            ->name('tipos.')
            ->group(function () {
                Route::get('/', 'gestor')->name('index');
                Route::post('/', 'crear')->name('crear');
                Route::patch('/{id}', 'actualizar')->name('actualizar');
                Route::delete('/{id}', 'eliminar')->name('eliminar');
            });

        /* Usuarios */
        Route::controller(UsuarioController::class)
            ->prefix('usuarios')
            ->name('usuarios.')
            ->group(function () {
                Route::get('/', 'gestor')->name('gestor');
                Route::post('/', 'agregar')->name('crear');
                Route::patch('/{id}', 'actualizar')->name('actualizar');
                Route::delete('/{id}', 'eliminar')->name('eliminar');
            });

        /* Logout */
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
