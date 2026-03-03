<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateClinicaRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Clinica;
use App\Models\User;
use App\Models\Doctor;

class ConfiguracionController extends Controller
{
    /**
     * Muestra la vista de configuración con datos del usuario autenticado y su clínica.
     */
    public function index()
    {
        $user = Auth::user();
        $clinica = Clinica::find($user->id_clinica);
        abort_if(!$clinica, 403, 'No tienes una clínica asignada.');

        // Datos del doctor principal de la clínica
        $doctorUser = User::where('id_clinica', $user->id_clinica)
            ->where('rol', 'doctor')
            ->first();

        $doctorPerfil = $doctorUser
            ? Doctor::where('id_usuario', $doctorUser->id_usuario)->first()
            : null;

        // Lista de recepcionistas de la misma clínica
        $recepcionistas = User::where('id_clinica', $user->id_clinica)
            ->where('rol', 'recepcionista')
            ->get();

        return view('configuracion.index', compact(
            'user',
            'clinica',
            'doctorUser',
            'doctorPerfil',
            'recepcionistas'
        ));
    }

    /**
     * Actualiza la información de la clínica.
     * Usa UpdateClinicaRequest para todas las validaciones.
     */
    public function updateClinica(UpdateClinicaRequest $request)
    {
        $clinica = Clinica::find(Auth::user()->id_clinica);
        abort_if(!$clinica, 403, 'No tienes permiso para modificar esta clínica.');

        $clinica->update($request->validated());

        return back()->with('success', 'Datos de la clínica actualizados correctamente.');
    }

    /**
     * Actualiza la información de un usuario (doctor o recepcionista).
     * La autorización de tenant está garantizada por UpdateUsuarioRequest::authorize().
     */
    public function updateUsuario(UpdateUsuarioRequest $request)
    {
        $usuario = User::findOrFail($request->id_usuario);

        $data = [
            'nombre_completo' => $request->nombre_completo,
            'email' => $request->email,
        ];

        // Actualizar contraseña solo si se proporcionó una nueva
        if ($request->filled('password')) {
            $data['password'] = $request->password;  // El cast 'hashed' del modelo User lo hashea automáticamente
        }

        $usuario->update($data);

        // Si es doctor, actualizar datos profesionales
        if ($usuario->rol === 'doctor') {
            Doctor::updateOrCreate(
                ['id_usuario' => $usuario->id_usuario],
                [
                    'cedula_profesional' => $request->cedula_profesional,
                    'horario_default' => $request->horario_default,
                ]
            );
        }

        return back()->with('success', 'Perfil de usuario actualizado correctamente.');
    }

    /**
     * Crea una nueva cuenta de recepcionista para la clínica del usuario autenticado.
     */
    public function storeRecepcionista(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios_sistema,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'id_clinica' => Auth::user()->id_clinica,
            'nombre_completo' => $request->nombre_completo,
            'email' => $request->email,
            'password' => $request->password,  // El cast 'hashed' del modelo User lo hashea automáticamente
            'rol' => 'recepcionista',
            'is_active' => true,
        ]);

        return back()->with('success', 'Recepcionista agregada correctamente.');
    }
}
