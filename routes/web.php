<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\PublicidadController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CitaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas Públicas (Login/Registro)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirigir la raíz al dashboard o login según corresponda
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas Privadas (Requieren Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pacientes
    Route::resource('pacientes', PacienteController::class);
    // Ruta adicional para POST en pacientes (si se usa manualmente en el form)
    // Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store'); 

    // Tratamientos
    Route::resource('tratamientos', TratamientoController::class)->except(['create', 'edit', 'show']);
    Route::post('/citas/{id}/actualizar', [DashboardController::class, 'actualizarCita'])->name('citas.actualizar');

    // Publicidad

    Route::resource('publicidad', App\Http\Controllers\PublicidadController::class)->only(['index', 'store', 'destroy']);
    // Configuración
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/clinica', [ConfiguracionController::class, 'updateClinica'])->name('configuracion.updateClinica');
    Route::post('/configuracion/usuario', [ConfiguracionController::class, 'updateUsuario'])->name('configuracion.updateUsuario');
    Route::post('/configuracion/recepcionista', [ConfiguracionController::class, 'storeRecepcionista'])->name('configuracion.storeRecepcionista');

    // API Interna
    Route::get('/api/citas/{id}/modal-detalles', [DashboardController::class, 'obtenerDatosModal'])->name('api.cita.detalles');
    Route::get('/api/calendario/disponibilidad', [DashboardController::class, 'obtenerDisponibilidadMes'])->name('api.calendario');

    // Citas
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
});