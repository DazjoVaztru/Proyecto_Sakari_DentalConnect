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

// Recuperación de contraseña
Route::get('/olvide-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/recuperar-password', function () {
    return view('auth.reset-password');
})->name('password.reset');

// Redirigir raíz
Route::get('/', function () {
    return redirect()->route('dashboard');
});


/**
 * Rutas Privadas / Protegidas.
 */
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pacientes
    Route::resource('pacientes', PacienteController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Tratamientos
    Route::resource('tratamientos', TratamientoController::class)
        ->parameters(['tratamientos' => 'id'])
        ->except(['create', 'edit', 'show']);

    // Actualizar cita
    Route::post('/citas/{id}/actualizar', [DashboardController::class, 'actualizarCita'])
        ->name('citas.actualizar');

    // Publicidad
    Route::resource('publicidad', PublicidadController::class)
        ->only(['index', 'store', 'destroy']);

    // Configuración
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/clinica', [ConfiguracionController::class, 'updateClinica'])->name('configuracion.updateClinica');
    Route::post('/configuracion/usuario', [ConfiguracionController::class, 'updateUsuario'])->name('configuracion.updateUsuario');
    Route::post('/configuracion/recepcionista', [ConfiguracionController::class, 'storeRecepcionista'])->name('configuracion.storeRecepcionista');
    Route::post('/configuracion/horarios', [ConfiguracionController::class, 'updateHorarios'])->name('configuracion.updateHorarios');
    Route::delete('/configuracion/recepcionista/{id}', [ConfiguracionController::class, 'destroyRecepcionista'])->name('configuracion.destroyRecepcionista');

    /**
     * APIs internas (AJAX)
     */

    // Citas
    Route::get('/api/citas/{id}/modal-detalles', [DashboardController::class, 'obtenerDatosModal'])->name('api.cita.detalles');
    Route::post('/api/citas/{id}/completar', [DashboardController::class, 'completarCita'])->name('api.cita.completar');

    // Calendario disponibilidad
    Route::get('/api/calendario/disponibilidad', [DashboardController::class, 'obtenerDisponibilidadMes'])->name('api.calendario');

    // 🔴 NUEVA API → HORAS OCUPADAS (IMPORTANTE PARA BLOQUEAR HORARIOS)

// Por esto:
Route::get('/api/citas/horas-ocupadas', [DashboardController::class, 'horasOcupadas'])
    ->name('api.citas.horas_ocupadas');


    /**
     * API Odontograma
     */
    Route::get('/api/odontograma/paciente/{id_paciente}', [OdontogramaController::class, 'index']);
    Route::post('/api/odontograma', [OdontogramaController::class, 'store']);
    Route::patch('/api/odontograma/{id_odontograma}', [OdontogramaController::class, 'update']);
    Route::delete('/api/odontograma/{id_odontograma}', [OdontogramaController::class, 'destroy']);

    /**
     * Historial clínico
     */
    Route::get('/api/pacientes/{idPaciente}/signos-vitales', [PacienteHistorialController::class, 'signosVitales']);
    Route::get('/api/pacientes/{idPaciente}/evoluciones', [PacienteHistorialController::class, 'evoluciones']);
    Route::post('/api/pacientes/{idPaciente}/evoluciones', [PacienteHistorialController::class, 'storeEvolucion']);
    Route::post('/api/pacientes/{idPaciente}/foto', [PacienteHistorialController::class, 'subirFotoProgreso']);
    Route::get('/api/pacientes/{idPaciente}/citas', [PacienteHistorialController::class, 'historialCitas']);

    /**
     * Crear cita
     */
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');

});


/**
 * Fallback para imágenes del storage (Railway fix)
 */
Route::get('/storage/{path}', function (string $path) {

    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return \Illuminate\Support\Facades\Storage::disk('public')->response($path);

})->where('path', '.*');