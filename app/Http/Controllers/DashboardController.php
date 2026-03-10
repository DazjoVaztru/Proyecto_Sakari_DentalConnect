<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\IngresoCaja;
use App\Models\Inventario;
use App\Models\Notificacion;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard con métricas reales de la clínica.
     */
    public function index()
    {
        $user = Auth::user();
        $idClinica = $user->id_clinica;
        $hoy = Carbon::today();
        $ahora = Carbon::now();

        // 1. Citas Futuras
        $citasFuturas = Cita::with(['paciente', 'servicio'])
            ->where('id_clinica', $idClinica)
            ->where('fecha_hora_inicio', '>=', $ahora)
            ->where('estado_cita', 'pendiente')
            ->orderBy('fecha_hora_inicio', 'asc')
            ->get();

        // 2. Citas Vencidas
        $citasVencidas = Cita::with(['paciente', 'servicio'])
            ->where('id_clinica', $idClinica)
            ->where('fecha_hora_inicio', '<', $ahora)
            ->where('estado_cita', 'pendiente')
            ->orderBy('fecha_hora_inicio', 'desc')
            ->get();

        // 3. ¡LA MAGIA! Rescatar citas Completadas pero que tienen DEUDA
        $citasConDeuda = Cita::with(['paciente', 'servicio'])
            ->where('id_clinica', $idClinica)
            ->where('estado_cita', 'completada')
            ->whereRaw('costo_estimado > (SELECT COALESCE(SUM(monto), 0) FROM ingresos_caja WHERE id_cita = citas.id_cita)')
            ->orderBy('fecha_hora_inicio', 'desc')
            ->get();

        // Unimos en el Dashboard: Primero las Deudas, luego Futuras, luego Vencidas.
        $proximasCitas = $citasConDeuda->concat($citasFuturas)->concat($citasVencidas)->take(20);

        // --- Citas de hoy ---
        $citasHoyCount = Cita::where('id_clinica', $idClinica)
            ->whereDate('fecha_hora_inicio', $hoy)
            ->where('estado_cita', 'pendiente')
            ->count();

        // --- Total de pacientes activos ---
        $totalPacientes = Paciente::whereHas('usuario', function ($q) use ($idClinica) {
            $q->where('id_clinica', $idClinica);
        })->where('is_active', true)->count();

        // --- Ingresos del mes actual ---
        $ingresosMes = IngresoCaja::where('id_clinica', $idClinica)
            ->whereMonth('fecha_ingreso', $hoy->month)
            ->whereYear('fecha_ingreso', $hoy->year)
            ->sum('monto');

        // --- Ítems de inventario con stock bajo (< 5 unidades) ---
        $itemsBajoStock = Inventario::where('id_clinica', $idClinica)
            ->where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // --- Notificaciones no leídas del usuario actual ---
        $notificacionesPendientes = Notificacion::where('id_usuario', $user->id_usuario)
            ->where('estado', 'pendiente')
            ->count();

        // --- Catálogo de servicios (para el odontograma) ---
        $servicios = Servicio::where('id_clinica', $idClinica)->orderBy('nombre_servicio')->get();

        return view('dashboard', compact(
            'proximasCitas',
            'citasHoyCount',
            'totalPacientes',
            'ingresosMes',
            'itemsBajoStock',
            'notificacionesPendientes',
            'servicios'
        ));
    }

    // --- FUNCIÓN 1: Datos del Modal de Detalle  ---
    /**
     * Obtiene los datos detallados de una cita para el modal.
     */
    public function obtenerDatosModal($idCita)
    {
        $cita = Cita::with(['paciente', 'servicio', 'ingresos'])->findOrFail($idCita);

        $p = $cita->paciente;
        $costoTotal = floatval($cita->costo_estimado ?? 0);

        // ¡NUEVA MAGIA! Si el costo es 0, jalamos el precio del catálogo automáticamente
        if ($costoTotal == 0 && $cita->servicio) {
            $costoTotal = floatval($cita->servicio->precio_base);
        }

        $totalPagado = $cita->ingresos ? $cita->ingresos->sum('monto') : 0;
        $saldo = max(0, $costoTotal - $totalPagado);

        // ── Datos Paciente ────────────────────────────────────────────────
        $pacienteData = null;
        if ($p) {
            $edad = $p->fecha_nacimiento ? Carbon::parse($p->fecha_nacimiento)->age : null;

            $sexoMap = ['M' => 'Masculino', 'F' => 'Femenino', 'O' => 'Otro'];

            $pacienteData = [
                // Keys exactos que el JS del dashboard lee (no cambiar):
                'id_paciente' => $p->id_paciente,
                'nombres' => $p->nombre,
                'paterno' => $p->apellido_paterno,
                'materno' => $p->apellido_materno,
                'edad' => $edad ? $edad . ' años' : 'N/A',
                'edad_numero' => $edad,
                'sexo' => $sexoMap[$p->sexo] ?? $p->sexo ?? 'N/A',
                'telefono' => $p->telefono,
                'tipo_sangre' => $p->tipo_sangre,
                'peso' => $p->peso ? $p->peso . ' kg' : 'N/A',
                'alergias' => $p->alergias ?? 'Ninguna registrada',
                'enfermedades' => $p->enfermedades_cronicas ?? 'Ninguna registrada',
            ];
        }

        // ── Fila Tabla ────────────────────────────────────────────────────
        // El JS lee data.fila_tabla.dia / .hora / .seguimiento / .abono
        $filaTabla = [
            'dia' => Carbon::parse($cita->fecha_hora_inicio)->format('d/m/Y'),
            'hora' => Carbon::parse($cita->fecha_hora_inicio)->format('h:i A')
                . ' – ' . Carbon::parse($cita->fecha_hora_fin)->format('h:i A'),
            'seguimiento' => $cita->motivo ?? ($cita->servicio?->nombre_servicio ?? 'Consulta'),
            'abono' => number_format($totalPagado, 2),
        ];

        // ── Finanzas ──────────────────────────────────────────────────────
        // El JS usa data.finanzas.total y data.finanzas.restante como strings con coma
        $finanzas = [
            'total' => number_format($costoTotal, 2),
            'pagado' => number_format($totalPagado, 2),
            'restante' => number_format($saldo, 2),
        ];

        // ── Fecha para calendario ─────────────────────────────────────────
        $fechaCita = [
            'mes' => (int) Carbon::parse($cita->fecha_hora_inicio)->format('m'),
            'anio' => (int) Carbon::parse($cita->fecha_hora_inicio)->format('Y'),
        ];

        // ── Historial odontograma ─────────────────────────────────────────
        $odontograma = \App\Models\Odontograma::where('id_paciente', $p->id_paciente ?? -1)
            ->orderBy('id_odontograma', 'desc')
            ->get();

        // ── Historial completo de citas del paciente ──────────────────────
        $hoy = Carbon::today();
        $todasLasCitas = Cita::with(['ingresos', 'servicio'])
            ->where('id_paciente', $p?->id_paciente)
            ->orderBy('fecha_hora_inicio', 'desc')
            ->get()
            ->map(function ($c) use ($idCita, $hoy) {
                // Abono: mostrar lo abonado en cada cita sin importar la fecha
                $abonadoEnCita = $c->ingresos ? $c->ingresos->sum('monto') : 0;

                // Estado: solo "Completada" o "Pendiente"
                $estadoBadge = match ($c->estado_cita) {
                    'completada' => 'Completada',
                    'pendiente' => 'Pendiente',
                    default => 'Pendiente',
                };
                return [
                    'id' => $c->id_cita,
                    'dia' => Carbon::parse($c->fecha_hora_inicio)->format('d/m/Y'),
                    'hora' => Carbon::parse($c->fecha_hora_inicio)->format('h:i A')
                        . ' – ' . Carbon::parse($c->fecha_hora_fin)->format('h:i A'),
                    'servicio' => $c->servicio?->nombre_servicio ?? 'Consulta General',
                    'seguimiento' => preg_replace('/^Seguimiento añadido:\s*/i', '', $c->motivo ?? ($c->servicio?->nombre_servicio ?? 'Consulta')),
                    'abono' => number_format($abonadoEnCita, 2),
                    'estado' => $estadoBadge,
                    'es_actual' => $c->id_cita == $idCita,
                ];
            });

        return response()->json([
            'success' => true,
            'paciente' => $pacienteData,
            'fila_tabla' => $filaTabla,
            'finanzas' => $finanzas,
            'fecha_cita' => $fechaCita,
            'odontograma' => $odontograma,
            'ingresos' => $cita->ingresos,
            'historial_citas' => $todasLasCitas,
        ]);
    }

    // --- FUNCIÓN 2: Actualizar estado de cita ---
    /**
     * Actualiza el estado o notas de una cita desde el modal del dashboard.
     */
    public function actualizarCita(Request $request, $idCita)
    {
        $cita = Cita::findOrFail($idCita);

        $validated = $request->validate([
            'estado_cita' => 'nullable|in:pendiente,confirmada,cancelada,completada',
            'costo_estimado' => 'nullable|numeric|min:0',
            'nueva_fecha' => 'nullable|date',
            'nueva_hora' => 'nullable|date_format:H:i',
            'notas_seguimiento' => 'nullable|string|max:1000',
            'monto_abono' => 'nullable|numeric|min:0'
        ]);

        // 1. Estados y costo bases
        if ($request->filled('estado_cita'))
            $cita->estado_cita = $request->estado_cita;
        if ($request->filled('costo_estimado'))
            $cita->costo_estimado = $request->costo_estimado;

        // 2. ¿Reprogramar o Agendar Siguiente Cita? (ANTI-VIAJE EN EL TIEMPO)
        $nuevaCitaGenerada = false;
        if ($request->filled('nueva_fecha')) {
            $fecha = $request->nueva_fecha;
            $hora = $request->filled('nueva_hora') ? $request->nueva_hora : Carbon::parse($cita->fecha_hora_inicio)->format('H:i');
            $nuevaFechaHora = $fecha . ' ' . $hora;

            // Regla de Oro: Si la cita es de hoy o del pasado, NO sobrescribimos su fecha. CREAMOS la siguiente cita.
            if (Carbon::parse($cita->fecha_hora_inicio)->isPast() || Carbon::parse($cita->fecha_hora_inicio)->isToday() || $cita->estado_cita === 'completada') {

                Cita::create([
                    'id_clinica' => $cita->id_clinica,
                    'id_paciente' => $cita->id_paciente,
                    'id_doctor' => $cita->id_doctor,
                    'id_servicio' => $cita->id_servicio,
                    'fecha_hora_inicio' => $nuevaFechaHora,
                    'fecha_hora_fin' => Carbon::parse($nuevaFechaHora)->addMinutes(30),
                    'estado_cita' => 'pendiente',
                    'motivo' => 'Seguimiento programado',
                    'costo_estimado' => 0
                ]);
                $nuevaCitaGenerada = true;

            } else {
                // Si la cita original era para el mes que viene, sí la estamos reprogramando.
                $cita->fecha_hora_inicio = $nuevaFechaHora;
                $cita->fecha_hora_fin = Carbon::parse($nuevaFechaHora)->addMinutes(30);
            }
        }

        $cita->save();

        // 3. Seguimiento Médico 
        if ($request->filled('notas_seguimiento')) {
            \App\Models\SeguimientoClinico::create([
                'id_cita' => $cita->id_cita,
                'observaciones' => $request->notas_seguimiento
            ]);
            // Guardamos el texto tal cual sin prefijo para que aparezca limpio en la tabla
            $cita->motivo = $request->notas_seguimiento;
            $cita->save();
        }

        // 4. Pago / Ingresos Caja
        if ($request->filled('monto_abono') && $request->monto_abono > 0) {
            \App\Models\IngresoCaja::create([
                'id_clinica' => Auth::user()->id_clinica ?? 1,
                'id_cita' => $cita->id_cita,
                'monto' => $request->monto_abono,
                'fecha_ingreso' => now(),
                'metodo_pago' => 'efectivo',
                'descripcion' => 'Abono en cita: ' . $cita->motivo
            ]);

            // Refrescar la relación para que los cálculos usen datos actualizados
            $cita->load('ingresos');
        }

        // 5. Cálculos para responder al front-end 
        $costoTotal = floatval($cita->costo_estimado ?? 0);
        $totalPagado = $cita->ingresos ? $cita->ingresos->sum('monto') : 0;
        $saldo = max(0, $costoTotal - $totalPagado);

        return response()->json([
            'success' => true,
            'message' => 'Cita y pagos actualizados correctamente.',
            'data' => [
                'costo_total' => '$' . number_format($costoTotal, 2),
                'restante' => '$' . number_format($saldo, 2),
                'abono_fila' => '$' . number_format($request->filled('monto_abono') ? $request->monto_abono : 0, 2),
                'nueva_fecha' => Carbon::parse($cita->fecha_hora_inicio)->format('d/m/Y'),
                'nueva_hora' => Carbon::parse($cita->fecha_hora_inicio)->format('h:i A'),
                'seguimiento' => $cita->motivo ?? ($cita->servicio->nombre_servicio ?? 'Consulta')
            ]
        ]);
    }

    // --- FUNCIÓN 3: Marcar cita como completada (AJAX) ---
    public function completarCita($idCita)
    {
        $cita = Cita::findOrFail($idCita);
        $cita->estado_cita = 'completada';
        $cita->save();

        return response()->json([
            'success' => true,
            'message' => 'Cita marcada como completada.'
        ]);
    }

    // --- FUNCIÓN 4: Disponibilidad del mes para el calendario ---
    /**
     * Retorna los días del mes con citas agendadas para colorear el calendario.
     * 
     * REGLAS:
     * - Verde (libre): Sin citas o con citas pero horas disponibles
     * - Amarillo (ocupado): Algunas citas pero aún hay horas libres
     * - Rojo (lleno): Todas las horas disponibles del día están ocupadas (8 horas = 16 slots de 30 min)
     * - Gris: Días pasados (no pueden agendar)
     */
    public function obtenerDisponibilidadMes(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->month);
        $anio = $request->input('anio', Carbon::now()->year);

        $diasDelMes = Carbon::createFromDate($anio, $mes, 1)->daysInMonth;

        // Horas operativas: 8 horas (08:00 - 17:00) = 16 slots de 30 minutos
        $slotsDisponiblesPorDia = 16;

        $eventos = [];

        for ($i = 1; $i <= $diasDelMes; $i++) {
            $fecha = Carbon::createFromDate($anio, $mes, $i);

            // Contar citas del día (solo no canceladas)
            $citasDelDia = Cita::where('id_clinica', Auth::user()->id_clinica)
                ->whereDate('fecha_hora_inicio', $fecha)
                ->where('estado_cita', '!=', 'cancelada')
                ->get();

            $totalCitas = $citasDelDia->count();

            // Estado del día basado en disponibilidad de slots
            $estado = 'verde'; // Por defecto libre
            $clickable = true;

            if ($totalCitas > 0 && $totalCitas < $slotsDisponiblesPorDia) {
                // Está ocupado pero aún hay horas disponibles
                $estado = 'amarillo';
                $clickable = true;
            } elseif ($totalCitas >= $slotsDisponiblesPorDia) {
                // Todas las horas están ocupadas
                $estado = 'rojo';
                $clickable = false;
            }

            // Días pasados en gris y no clickeables
            $fechaFinal = $fecha->copy()->endOfDay();
            if ($fechaFinal->isPast() && !$fecha->isToday()) {
                $estado = 'gris';
                $clickable = false;
            }

            // Información sobre horas ocupadas y disponibles
            $horasOcupadas = ceil($totalCitas / 2); // 2 slots = 1 hora
            $horasDisponibles = max(0, 8 - $horasOcupadas);

            $eventos[$i] = [
                'estado' => $estado,
                'clickable' => $clickable,
                'total_citas' => $totalCitas,
                'horas_ocupadas' => $horasOcupadas,
                'horas_disponibles' => $horasDisponibles,
                'slots_ocupados' => $totalCitas,
                'slots_totales' => $slotsDisponiblesPorDia
            ];
        }

        // El frontend espera directamente el mapa de días
        return response()->json($eventos);
    }
}