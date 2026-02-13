<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Servicio;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::where('is_active', true)->orderBy('created_at', 'desc')->get();

        // NUEVO: Traemos los servicios para el modal de agendar cita
        $servicios = Servicio::all();
        return view('pacientes.index', compact('pacientes', 'servicios'));
    }

    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|unique:usuarios_sistema,email',
        ]);

        try {
            DB::beginTransaction();


            // 1. Crear Usuario
            $user = User::create([
                'id_clinica' => 1,
                'nombre_completo' => $request->nombre . ' ' . $request->apellido_paterno,
                'email' => $request->email,
                'password' => Hash::make('dental123'), // <--- CAMBIADO DE password_hash A password
                'rol' => 'paciente',
                'is_active' => true
            ]);

            // 2. Crear Paciente (AHORA CON TODOS LOS DATOS)
            Paciente::create([
                'id_usuario' => $user->id_usuario,
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo ?? 'O',
                'telefono' => $request->telefono,
                'correo_electronico' => $request->email,

                // --- CAMPOS NUEVOS AGREGADOS ---
                'tipo_sangre' => $request->tipo_sangre,
                'peso' => $request->peso,
                'ocupacion' => $request->ocupacion,
                'enfermedades_cronicas' => $request->enfermedades_cronicas,
                'alergias' => $request->alergias,
            ]);

            // 3. Generar Token
            $tokenStr = 'PAC-' . strtoupper(Str::random(6));
            Token::create([
                'id_usuario' => $user->id_usuario,
                'token' => $tokenStr,
                'tipo_token' => 'acceso_app',
                'estado' => 'activo',
                'fecha_creacion' => now(),
                'fecha_expiracion' => now()->addYear()
            ]);

            DB::commit();

            return redirect()->back()->with('success', "Paciente guardado correctamente. TOKEN: $tokenStr");

        } catch (\Exception $e) {
            DB::rollBack();
            // Esto es vital: nos dirá el error exacto si falla
            return redirect()->back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }
}