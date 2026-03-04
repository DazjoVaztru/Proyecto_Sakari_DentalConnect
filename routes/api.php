<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PacienteAppController;

// ==========================================
// API PÚBLICA / APLICACIÓN MÓVIL DEL PACIENTE
// ==========================================

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/user', function (Request $request) {
        return $request->user(); });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Endpoints Ligeros para el Paciente
    Route::get('/perfil', [PacienteAppController::class, 'perfil']);
    Route::get('/citas-proximas', [PacienteAppController::class, 'citasProximas']);
    Route::get('/citas-pasadas', [PacienteAppController::class, 'citasPasadas']);
    Route::get('/horarios-disponibles', [PacienteAppController::class, 'horariosDisponibles']);
    Route::get('/estado-cuenta', [PacienteAppController::class, 'estadoCuenta']);
    Route::get('/clinicas-doctores', [PacienteAppController::class, 'clinicasYDoctores']);
    Route::get('/publicidad', [PacienteAppController::class, 'publicidad']);
});