<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProyectosController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.logger');

Route::middleware(['auth'])->group(function () {

    Route::get('/logger', [ProyectosController::class, 'dashboardProyectos'])
        ->name('proyecto.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
