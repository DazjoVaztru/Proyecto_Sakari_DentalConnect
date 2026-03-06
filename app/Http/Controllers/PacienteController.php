<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Token;
use App\Models\Servicio;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Muestra la lista de pacientes activos (vista independiente).
     * Esta ruta sigue disponible para acceso directo por URL.
     */
    public function index()
    {
        $idClinica = Auth::user()->id_clinica;

        $pacientes = Paciente::whereHas('usuario', function ($query) use ($idClinica) {
            $query->where('id_clinica', $idClinica);
        })
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $servicios = Servicio::where('id_clinica', $idClinica)->orderBy('nombre_servicio')->get();
        return view('pacientes.index', compact('pacientes', 'servicios'));
    }

    /**
     * Almacena un nuevo paciente, su contacto de emergencia y su token de acceso.
     *
     * Flujo transaccional:
     *   1. Insertar en contacto_emergencia (si se proporcionaron datos).
     *   2. Crear usuario en usuarios_sistema (rol: paciente).
     *   3. Crear perfil en pacientes con los campos de texto libre para salud.
     *   4. Generar token de acceso para la app móvil.
     *
     * Tablas afectadas:
     *   contacto_emergencia: nombre | apellido_paterno | apellido_materno | numero_telefono
     *   usuarios_sistema:    id_clinica | nombre_completo | rol | email | password | is_active
     *   pacientes:           id_usuario | id_contacto_emergencia | nombre | apellido_paterno |
     *                        apellido_materno | telefono | correo_electronico | fecha_nacimiento |
     *                        sexo | tipo_sangre | peso | ocupacion | enfermedades_cronicas |
     *                        alergias | is_active
     *   tokens:              id_usuario | token | tipo_token | fecha_creacion | fecha_expiracion | estado
     */
    public function store(StorePacienteRequest $request)
    {
        // id_clinica siempre del usuario autenticado — nunca hardcodeado
        $idClinica = Auth::user()->id_clinica;

        try {
            DB::beginTransaction();

            // ── 1. Contacto de Emergencia (sólo si se proporcionó teléfono o nombre) ──
            $idContactoEmergencia = null;

            $hayContacto = $request->filled('emergencia_nombre')
                || $request->filled('emergencia_telefono');

            if ($hayContacto) {
                $idContactoEmergencia = DB::table('contacto_emergencia')->insertGetId([
                    'nombre' => $request->input('emergencia_nombre', ''),
                    'apellido_paterno' => $request->input('emergencia_apellido_paterno', ''),
                    'apellido_materno' => $request->input('emergencia_apellido_materno', ''),
                    'numero_telefono' => $request->input('emergencia_telefono', ''),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ── 2. Crear el usuario del sistema ──────────────────────────────────────
            $nombreCompleto = trim(
                $request->nombre . ' ' .
                $request->apellido_paterno . ' ' .
                ($request->apellido_materno ?? '')
            );

            $user = User::create([
                'id_clinica' => $idClinica,
                'nombre_completo' => $nombreCompleto,
                'email' => $request->email,
                'password' => 'dental123',   // El cast 'hashed' del modelo User lo hashea automáticamente
                'rol' => 'paciente',
                'is_active' => true,
            ]);

            // ── 3. Crear el perfil del paciente ───────────────────────────────────────
            $paciente = Paciente::create([
                'id_usuario' => $user->id_usuario,
                'id_contacto_emergencia' => $idContactoEmergencia,
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo ?? 'O',
                'telefono' => $request->telefono,
                'correo_electronico' => $request->email,
                'tipo_sangre' => $request->tipo_sangre,
                'peso' => $request->peso,
                'direccion' => $request->direccion,
                'ocupacion' => $request->ocupacion,
                // Texto libre — NO se usan tablas pivot
                'enfermedades_cronicas' => $request->enfermedades_cronicas,
                'alergias' => $request->alergias,
                'is_active' => true,
            ]);

            // ── 4. Generar token de acceso para la app móvil ──────────────────────────
            $tokenStr = 'PAC-' . strtoupper(Str::random(6));
            Token::create([
                'id_usuario' => $user->id_usuario,
                'token' => $tokenStr,
                'tipo_token' => 'acceso_app',
                'estado' => 'activo',
                'fecha_creacion' => now(),
                'fecha_expiracion' => now()->addYear(),
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', "Paciente \"{$paciente->nombre} {$paciente->apellido_paterno}\" registrado correctamente. TOKEN de acceso: {$tokenStr}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al guardar el paciente: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Actualiza los datos de un paciente existente.
     */
    public function update(Request $request, $id)
    {
        $idClinica = Auth::user()->id_clinica;
        $paciente = Paciente::where('id_paciente', $id)
            ->whereHas('usuario', function ($q) use ($idClinica) {
                $q->where('id_clinica', $idClinica);
            })->firstOrFail();

        $request->validate([
            'telefono' => 'required|string|max:20',
            'peso' => 'nullable|integer|min:0|max:500',
            'direccion' => 'nullable|string|max:100',
            'email' => 'required|email|max:100',
            'emergencia_nombre' => 'nullable|string|max:100',
            'emergencia_apellido_paterno' => 'nullable|string|max:100',
            'emergencia_apellido_materno' => 'nullable|string|max:100',
            'emergencia_telefono' => 'nullable|string|max:15',
        ]);

        try {
            DB::beginTransaction();

            $paciente->update([
                'telefono' => $request->telefono,
                'peso' => $request->peso,
                'direccion' => $request->direccion,
                'ocupacion' => $request->ocupacion,
                'enfermedades_cronicas' => $request->enfermedades_cronicas,
                'alergias' => $request->alergias,
            ]);

            // Actualizar contacto de emergencia si se proporcionó información
            if ($request->filled('emergencia_nombre') || $request->filled('emergencia_telefono')) {
                if ($paciente->id_contacto_emergencia) {
                    DB::table('contacto_emergencia')->where('id_contacto_emergencia', $paciente->id_contacto_emergencia)
                        ->update([
                            'nombre' => $request->input('emergencia_nombre', ''),
                            'apellido_paterno' => $request->input('emergencia_apellido_paterno', ''),
                            'apellido_materno' => $request->input('emergencia_apellido_materno', ''),
                            'numero_telefono' => $request->input('emergencia_telefono', ''),
                            'updated_at' => now(),
                        ]);
                } else {
                    $idCe = DB::table('contacto_emergencia')->insertGetId([
                        'nombre' => $request->input('emergencia_nombre', ''),
                        'apellido_paterno' => $request->input('emergencia_apellido_paterno', ''),
                        'apellido_materno' => $request->input('emergencia_apellido_materno', ''),
                        'numero_telefono' => $request->input('emergencia_telefono', ''),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $paciente->update(['id_contacto_emergencia' => $idCe]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Datos del paciente actualizados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar paciente: ' . $e->getMessage());
        }
    }

    /**
     * Eliminación de pacientes deshabilitada por cumplimiento normativo.
     *
     * NOM-004-SSA3-2012: Los expedientes clínicos deben conservarse un mínimo
     * de 5 años tras la última consulta.
     */
    public function destroy($id)
    {
        return redirect()->back()->with(
            'error',
            'Acción no permitida. La NOM-004-SSA3-2012 prohíbe la eliminación de expedientes clínicos. ' .
            'Los registros deben conservarse un mínimo de 5 años.'
        );
    }
}