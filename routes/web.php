<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\PublicidadController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Api\OdontogramaController;
use App\Http\Controllers\Api\PacienteHistorialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * Rutas Públicas de Autenticación.
 *
 * Manejan el inicio de sesión, registro y cierre de sesión.
 * No requieren autenticación previa.
 */
// Rutas Públicas (Login/Registro)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de Recuperación de Contraseña
Route::get('/olvide-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/recuperar-password', function () {
    return view('auth.reset-password');
})->name('password.reset');

// Redirigir la raíz al dashboard o login según corresponda
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/**
 * Rutas Privadas / Protegidas.
 *
 * Requieren que el usuario esté autenticado (middleware 'auth').
 * Incluyen el dashboard, gestión de pacientes, tratamientos, configuración y APIs internas.
 */
// Rutas Privadas (Requieren Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pacientes — solo index (lista) y store (crear desde modal del dashboard)
    Route::resource('pacientes', PacienteController::class)->only(['index', 'store']);

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

    /**
     * API Interna para consumo AJAX.
     *
     * Rutas que devuelven JSON para poblar modales y calendarios sin recargar la página.
     */
    // API Interna
    Route::get('/api/citas/{id}/modal-detalles', [DashboardController::class, 'obtenerDatosModal'])->name('api.cita.detalles');
    Route::post('/api/citas/{id}/completar', [DashboardController::class, 'completarCita'])->name('api.cita.completar');
    Route::get('/api/calendario/disponibilidad', [DashboardController::class, 'obtenerDisponibilidadMes'])->name('api.calendario');

    // API de Odontograma (CRUD completo)
    Route::get('/api/odontograma/paciente/{id_paciente}', [OdontogramaController::class, 'index']);
    Route::post('/api/odontograma', [OdontogramaController::class, 'store']);
    Route::patch('/api/odontograma/{id_odontograma}', [OdontogramaController::class, 'update']);
    Route::delete('/api/odontograma/{id_odontograma}', [OdontogramaController::class, 'destroy']);

    // APIs de Historial Clínico del Paciente
    Route::get('/api/pacientes/{idPaciente}/signos-vitales', [PacienteHistorialController::class, 'signosVitales']);
    Route::get('/api/pacientes/{idPaciente}/evoluciones', [PacienteHistorialController::class, 'evoluciones']);
    Route::post('/api/pacientes/{idPaciente}/evoluciones', [PacienteHistorialController::class, 'storeEvolucion']);

    // Citas
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');

});