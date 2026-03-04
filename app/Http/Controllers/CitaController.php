<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index()
    {
        $idClinica = Auth::user()->id_clinica;
        $citas = Cita::with(['paciente', 'servicio'])
            ->where('id_clinica', $idClinica)
            ->get();
        if (request()->wantsJson()) {
            return response()->json($citas);
        }
        return view('citas.index', compact('citas'));
    }

    /**
     * Almacena una nueva cita en la base de datos.
     *
     * Valida los datos recibidos, busca el precio del servicio y crea el registro de la cita.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_paciente' => 'required|exists:pacientes,id_paciente',
            'id_servicio' => 'required|exists:catalogo_servicios,id_servicio',
            'fecha' => 'required|date_format:Y-m-d',
            'hora' => 'required|date_format:H:i',
        ]);

        try {
            $user = Auth::user();
            $idClinica = $user->id_clinica;

            // Combinar fecha + hora en un solo datetime
            $fechaHora = Carbon::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);

            // Buscar el servicio para obtener el precio y nombre
            $servicio = Servicio::findOrFail($request->id_servicio);

            // Buscar el primer doctor activo de la clínica via join con usuarios_sistema
            $idDoctor = DB::table('doctores')
                ->join('usuarios_sistema', 'doctores.id_usuario', '=', 'usuarios_sistema.id_usuario')
                ->where('usuarios_sistema.id_clinica', $idClinica)
                ->where('usuarios_sistema.is_active', true)
                ->value('doctores.id_doctor') ?? 1;

            // ── Verificar duplicado exacto ────────────────────────────────
            // Si ya existe una cita pendiente para este paciente en la misma fecha y hora (mismo minuto),
            // rechazar la solicitud para evitar duplicados por clicks múltiples.
            $duplicado = Cita::where('id_paciente', $request->id_paciente)
                ->where('id_clinica', $idClinica)
                ->where('estado_cita', 'pendiente')
                ->where('fecha_hora_inicio', $fechaHora)
                ->exists();

            if ($duplicado) {
                return redirect()->route('pacientes.index')
                    ->with('error', 'Ya existe una cita pendiente para este paciente en la misma fecha y hora.')
                    ->withInput();
            }

            Cita::create([
                'id_clinica' => $idClinica,
                'id_paciente' => $request->id_paciente,
                'id_doctor' => $idDoctor,
                'id_servicio' => $request->id_servicio,
                'fecha_hora_inicio' => $fechaHora,
                'fecha_hora_fin' => $fechaHora->copy()->addHour(),
                'estado_cita' => 'pendiente',
                'motivo' => $servicio->nombre_servicio,
                'costo_estimado' => $servicio->precio_base,
            ]);

            return redirect()->route('pacientes.index')->with('success', '¡Cita agendada correctamente para el ' . $fechaHora->format('d/m/Y \a \l\a\s H:i') . '!');

        } catch (\Exception $e) {
            return redirect()->route('pacientes.index')->with('error', 'Error al agendar: ' . $e->getMessage())->withInput();
        }
    }
}
