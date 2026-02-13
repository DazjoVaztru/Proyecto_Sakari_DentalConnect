<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $proximasCitas = Cita::with(['paciente', 'servicio'])
            ->where('fecha_hora_inicio', '>=', Carbon::now())
            ->where('estado_cita', 'pendiente')
            ->orderBy('fecha_hora_inicio', 'asc')
            ->take(10)
            ->get();

        // NOTA: Ya no enviamos datos para el calendario externo porque lo quitaremos.
        return view('dashboard', compact('proximasCitas'));
    }

    // --- FUNCIÓN 1: Datos del Modal de Detalle (Actualizada) ---
    public function obtenerDatosModal($idCita)
    {
        // Usamos findOrFail para que si no existe la cita, de error 404 controlado
        // Quitamos 'paciente.enfermedades' del with por ahora para evitar errores si no hay relación
        $cita = Cita::with(['paciente', 'servicio', 'ingresos'])->findOrFail($idCita);

        // Usamos costo_estimado si existe, si no el del servicio
        $costoTotal = $cita->costo_estimado ?? ($cita->servicio ? $cita->servicio->precio_base : 0);
        $totalAbonado = $cita->ingresos->sum('monto');
        $restante = max(0, $costoTotal - $totalAbonado);
        $fecha = Carbon::parse($cita->fecha_hora_inicio);

        // --- BLINDAJE DE DATOS NULOS ---
        // Si estos campos no existen en la BD, ponemos texto por defecto para que no falle el JSON
        $sangre = $cita->paciente->tipo_sangre ?? 'S/R'; // Sin Registro
        $peso = $cita->paciente->peso ?? '0';

        // Simulación de alergias si no hay tabla pivote aun
        $alergias = $cita->paciente->alergias_criticas ?? 'Ninguna conocida';
        $enfermedades = $cita->paciente->enfermedades_cronicas ?? 'Ninguna';

        return response()->json([
            'paciente' => [
                'nombres' => $cita->paciente->nombre,
                'paterno' => $cita->paciente->apellido_paterno,
                'materno' => $cita->paciente->apellido_materno ?? '',
                'edad' => $cita->paciente->fecha_nacimiento ? Carbon::parse($cita->paciente->fecha_nacimiento)->age . ' años' : 'N/A',
                'sexo' => $cita->paciente->sexo ?? 'N/A',
                'telefono' => $cita->paciente->telefono ?? 'Sin número',
                'tipo_sangre' => $sangre,
                'peso' => $peso . ' kg',
                'alergias' => $alergias,
                'enfermedades' => $enfermedades,
            ],
            'fila_tabla' => [
                'dia' => $fecha->translatedFormat('d M'),
                'hora' => $fecha->format('h:i A'),
                'seguimiento' => $cita->servicio ? $cita->servicio->nombre_servicio : 'Consulta General',
                'abono' => number_format($totalAbonado, 2),
            ],
            'finanzas' => [
                'total' => number_format($costoTotal, 2),
                'restante' => number_format($restante, 2)
            ],
            'fecha_cita' => [
                'mes' => $fecha->month,
                'anio' => $fecha->year,
                'dia' => $fecha->day
            ]
        ]);
    }
    // --- FUNCIÓN 2: Disponibilidad del Calendario (NUEVA) ---
    public function obtenerDisponibilidadMes(Request $request)
    {
        $mes = $request->query('mes', Carbon::now()->month);
        $anio = $request->query('anio', Carbon::now()->year);

        // 1. Contar citas por día en ese mes y año
        $citasPorDia = Cita::select(DB::raw('DAY(fecha_hora_inicio) as dia'), DB::raw('count(*) as total'))
            ->whereMonth('fecha_hora_inicio', $mes)
            ->whereYear('fecha_hora_inicio', $anio)
            ->groupBy('dia')
            ->pluck('total', 'dia')
            ->toArray();

        $disponibilidad = [];
        $diasEnMes = Carbon::createFromDate($anio, $mes, 1)->daysInMonth;
        $hoy = Carbon::today();

        // 2. Definir colores según ocupación
        // CRITERIO SIMPLE: 0 citas = Verde, 1-4 citas = Amarillo, 5+ citas = Rojo
        for ($dia = 1; $dia <= $diasEnMes; $dia++) {
            $fechaActual = Carbon::createFromDate($anio, $mes, $dia);
            $totalCitas = $citasPorDia[$dia] ?? 0;

            $estado = 'verde'; // Por defecto libre
            $clickable = true;

            if ($fechaActual->lt($hoy)) {
                $estado = 'pasado'; // Días pasados en gris
                $clickable = false;
            } elseif ($totalCitas >= 5) { // Límite arbitrario para "Lleno"
                $estado = 'rojo';
                $clickable = false; // No se puede agendar si está lleno
            } elseif ($totalCitas > 0) {
                $estado = 'amarillo';
                // Se puede agendar pero con precaución
            }

            $disponibilidad[$dia] = [
                'estado' => $estado,
                'clickable' => $clickable,
                'citas_count' => $totalCitas
            ];
        }

        return response()->json($disponibilidad);
    }

    // --- FUNCIÓN 3: Actualizar Cita (Pago, Seguimiento, Reprogramación) ---
    public function actualizarCita(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $cita = Cita::findOrFail($id);

            // 1. Reprogramación (Horario)
            if ($request->has('nueva_fecha') && $request->has('nueva_hora')) {
                // Combina fecha y hora
                $fechaHora = Carbon::parse($request->nueva_fecha . ' ' . $request->nueva_hora);
                $cita->fecha_hora_inicio = $fechaHora;
            }

            // 2. Seguimiento (Notas y Servicio)
            if ($request->filled('notas_seguimiento')) {
                // Buscar o crear seguimiento
                \App\Models\SeguimientoClinico::updateOrCreate(
                    ['id_cita' => $cita->id_cita],
                    [
                        'id_servicio' => $cita->id_servicio, // Mantiene el servicio original por ahora
                        'postratamiento' => 'no',
                        'observaciones' => $request->notas_seguimiento
                    ]
                );

                // IMPORTANTE: Si hay seguimiento, asumimos que la cita ocurrió
                if ($cita->estado_cita != 'completada') {
                    $cita->estado_cita = 'completada';
                }
            }

            // 3. Registrar Pago
            if ($request->filled('monto_abono') && $request->monto_abono > 0) {
                \App\Models\IngresoCaja::create([
                    'id_clinica' => 1, // Default por ahora
                    'id_cita' => $cita->id_cita,
                    'monto' => $request->monto_abono,
                    'metodo' => 'efectivo', // Podrías agregar un select en el modal
                    'descripcion' => 'Abono desde Dashboard',
                    'fecha_ingreso' => now()
                ]);
            }

            // Guardar cambios en la cita
            $cita->save();

            // 4. Recalcular Totales para respuesta JSON
            $totalAbonado = \App\Models\IngresoCaja::where('id_cita', $cita->id_cita)->sum('monto');
            $costoTotal = $cita->costo_estimado ?? ($cita->servicio ? $cita->servicio->precio_base : 0);
            $restante = max(0, $costoTotal - $totalAbonado);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cita actualizada correctamente',
                'data' => [
                    'total_abonado' => '$' . number_format($totalAbonado, 2),
                    'restante' => '$' . number_format($restante, 2),
                    'abono_fila' => '$' . number_format($totalAbonado, 2), // Update the table cell too
                    'nueva_fecha' => $cita->fecha_hora_inicio->format('d M'),
                    'nueva_hora' => $cita->fecha_hora_inicio->format('h:i A'),
                    'seguimiento' => $cita->seguimiento ? $cita->seguimiento->observaciones : 'Sin seguimiento'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }
}