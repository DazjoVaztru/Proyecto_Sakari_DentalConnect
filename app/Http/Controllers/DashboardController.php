<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\IngresoCaja;
use App\Models\Inventario;
use App\Models\Notificacion;
use App\Models\Servicio;
use App\Models\Odontograma;
use App\Models\SeguimientoClinico;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    /**
     * DASHBOARD PRINCIPAL
     */
    public function index()
    {
        $user = Auth::user();
        $idClinica = $user->id_clinica;

        $hoy = Carbon::today();
        $ahora = Carbon::now();

        // --- Citas futuras ---
        $citasFuturas = Cita::with(['paciente','servicio'])
            ->where('id_clinica',$idClinica)
            ->where('fecha_hora_inicio','>=',$ahora)
            ->where('estado_cita','pendiente')
            ->orderBy('fecha_hora_inicio','asc')
            ->get();

        // --- Citas vencidas ---
        $citasVencidas = Cita::with(['paciente','servicio'])
            ->where('id_clinica',$idClinica)
            ->where('fecha_hora_inicio','<',$ahora)
            ->where('estado_cita','pendiente')
            ->orderBy('fecha_hora_inicio','desc')
            ->get();

        $proximasCitas = $citasFuturas->concat($citasVencidas)->take(15);

        // --- Citas hoy ---
        $citasHoyCount = Cita::where('id_clinica',$idClinica)
            ->whereDate('fecha_hora_inicio',$hoy)
            ->where('estado_cita','pendiente')
            ->count();

        // --- Pacientes activos ---
        $totalPacientes = Paciente::whereHas('usuario',function($q) use ($idClinica){
            $q->where('id_clinica',$idClinica);
        })
        ->where('is_active',true)
        ->count();

        // --- Ingresos del mes ---
        $ingresosMes = IngresoCaja::where('id_clinica',$idClinica)
            ->whereMonth('fecha_ingreso',$hoy->month)
            ->whereYear('fecha_ingreso',$hoy->year)
            ->sum('monto');

        // --- Inventario bajo ---
        $itemsBajoStock = Inventario::where('id_clinica',$idClinica)
            ->where('stock','<',5)
            ->orderBy('stock','asc')
            ->take(5)
            ->get();

        // --- Notificaciones ---
        $notificacionesPendientes = Notificacion::where('id_usuario',$user->id_usuario)
            ->where('estado','pendiente')
            ->count();

        // --- Servicios ---
        $servicios = Servicio::where('id_clinica',$idClinica)
            ->orderBy('nombre_servicio')
            ->get();

        return view('dashboard',compact(
            'proximasCitas',
            'citasHoyCount',
            'totalPacientes',
            'ingresosMes',
            'itemsBajoStock',
            'notificacionesPendientes',
            'servicios'
        ));
    }



    /**
     * DATOS DEL MODAL DE CITA
     */
    public function obtenerDatosModal($idCita)
    {
        $cita = Cita::with(['paciente','servicio','ingresos'])->findOrFail($idCita);

        $paciente = $cita->paciente;

        $costoTotal = floatval($cita->costo_estimado ?? 0);
        $totalPagado = $cita->ingresos ? $cita->ingresos->sum('monto') : 0;
        $saldo = max(0,$costoTotal-$totalPagado);

        $pacienteData = null;

        if($paciente){

            $edad = $paciente->fecha_nacimiento
                ? Carbon::parse($paciente->fecha_nacimiento)->age
                : null;

            $sexoMap = [
                'M'=>'Masculino',
                'F'=>'Femenino',
                'O'=>'Otro'
            ];

            $pacienteData = [
                'id_paciente'=>$paciente->id_paciente,
                'nombres'=>$paciente->nombre,
                'paterno'=>$paciente->apellido_paterno,
                'materno'=>$paciente->apellido_materno,
                'edad'=>$edad ? $edad.' años':'N/A',
                'edad_numero'=>$edad,
                'sexo'=>$sexoMap[$paciente->sexo] ?? 'N/A',
                'telefono'=>$paciente->telefono,
                'tipo_sangre'=>$paciente->tipo_sangre,
                'peso'=>$paciente->peso ? $paciente->peso.' kg':'N/A',
                'alergias'=>$paciente->alergias ?? 'Ninguna registrada',
                'enfermedades'=>$paciente->enfermedades_cronicas ?? 'Ninguna registrada'
            ];
        }

        $inicio = Carbon::parse($cita->fecha_hora_inicio);
        $fin = Carbon::parse($cita->fecha_hora_fin);

        $filaTabla = [
            'dia'=>$inicio->format('d/m/Y'),
            'hora'=>$inicio->format('h:i A').' – '.$fin->format('h:i A'),
            'seguimiento'=>$cita->motivo ?? ($cita->servicio->nombre_servicio ?? 'Consulta'),
            'abono'=>number_format($totalPagado,2)
        ];

        $finanzas = [
            'total'=>number_format($costoTotal,2),
            'pagado'=>number_format($totalPagado,2),
            'restante'=>number_format($saldo,2)
        ];

        $fechaCita = [
            'mes'=>(int)$inicio->format('m'),
            'anio'=>(int)$inicio->format('Y')
        ];

        $odontograma = [];

        if($paciente){
            $odontograma = Odontograma::where('id_paciente',$paciente->id_paciente)
                ->orderBy('id_odontograma','desc')
                ->get();
        }

        $hoy = Carbon::today();
        $historial = collect();

        if($paciente){

            $historial = Cita::with(['ingresos','servicio'])
                ->where('id_paciente',$paciente->id_paciente)
                ->orderBy('fecha_hora_inicio','desc')
                ->get()
                ->map(function($c) use ($idCita,$hoy){

                    $inicio = Carbon::parse($c->fecha_hora_inicio);
                    $fin = Carbon::parse($c->fecha_hora_fin);

                    $esHoy = $inicio->startOfDay()->equalTo($hoy);

                    $abono = $esHoy && $c->ingresos
                        ? $c->ingresos->sum('monto')
                        : 0;

                    $estado = match($c->estado_cita){
                        'completada'=>'Completada',
                        'pendiente'=>'Pendiente',
                        default=>'Pendiente'
                    };

                    return [
                        'id'=>$c->id_cita,
                        'dia'=>$inicio->format('d/m/Y'),
                        'hora'=>$inicio->format('h:i A').' – '.$fin->format('h:i A'),
                        'servicio'=>$c->servicio->nombre_servicio ?? 'Consulta',
                        'seguimiento'=>$c->motivo ?? 'Consulta',
                        'abono'=>number_format($abono,2),
                        'estado'=>$estado,
                        'es_actual'=>$c->id_cita==$idCita
                    ];
                });
        }

        return response()->json([
            'success'=>true,
            'paciente'=>$pacienteData,
            'fila_tabla'=>$filaTabla,
            'finanzas'=>$finanzas,
            'fecha_cita'=>$fechaCita,
            'odontograma'=>$odontograma,
            'ingresos'=>$cita->ingresos,
            'historial_citas'=>$historial
        ]);
    }



    /**
     * ACTUALIZAR CITA
     */
    public function actualizarCita(Request $request,$idCita)
    {

        $cita = Cita::findOrFail($idCita);

        $request->validate([
            'estado_cita'=>'nullable|in:pendiente,confirmada,cancelada,completada',
            'costo_estimado'=>'nullable|numeric|min:0',
            'nueva_fecha'=>'nullable|date',
            'nueva_hora'=>'nullable|date_format:H:i',
            'notas_seguimiento'=>'nullable|string|max:1000',
            'monto_abono'=>'nullable|numeric|min:0'
        ]);

        if($request->filled('estado_cita')){
            $cita->estado_cita = $request->estado_cita;
        }

        if($request->filled('costo_estimado')){
            $cita->costo_estimado = $request->costo_estimado;
        }

        if($request->filled('nueva_fecha')){

            $hora = $request->filled('nueva_hora')
                ? $request->nueva_hora
                : Carbon::parse($cita->fecha_hora_inicio)->format('H:i');

            $inicio = Carbon::parse($request->nueva_fecha.' '.$hora);

            $cita->fecha_hora_inicio = $inicio;
            $cita->fecha_hora_fin = $inicio->copy()->addMinutes(30);
        }

        $cita->save();

        if($request->filled('notas_seguimiento')){

            SeguimientoClinico::create([
                'id_cita'=>$cita->id_cita,
                'observaciones'=>$request->notas_seguimiento
            ]);

            $cita->motivo = $request->notas_seguimiento;
            $cita->save();
        }

        if($request->filled('monto_abono') && $request->monto_abono>0){

            IngresoCaja::create([
                'id_clinica'=>Auth::user()->id_clinica,
                'id_cita'=>$cita->id_cita,
                'monto'=>$request->monto_abono,
                'fecha_ingreso'=>now(),
                'metodo_pago'=>'efectivo',
                'descripcion'=>'Abono en cita'
            ]);

            $cita->load('ingresos');
        }

        $costoTotal = floatval($cita->costo_estimado ?? 0);
        $totalPagado = $cita->ingresos ? $cita->ingresos->sum('monto') : 0;
        $saldo = max(0,$costoTotal-$totalPagado);

        return response()->json([
            'success'=>true,
            'message'=>'Cita actualizada',
            'data'=>[
                'costo_total'=>'$'.number_format($costoTotal,2),
                'restante'=>'$'.number_format($saldo,2),
                'abono_fila'=>'$'.number_format($request->monto_abono ?? 0,2),
                'nueva_fecha'=>Carbon::parse($cita->fecha_hora_inicio)->format('d/m/Y'),
                'nueva_hora'=>Carbon::parse($cita->fecha_hora_inicio)->format('h:i A')
            ]
        ]);
    }



    /**
 * COMPLETAR CITA
 */
public function completarCita($idCita)
{
    try {

        $cita = Cita::findOrFail($idCita);

        // Marcar como completada
        $cita->estado_cita = 'completada';
        $cita->save();

        $idClinica = Auth::user()->id_clinica;
        $hoy = Carbon::today();

        // Contar citas pendientes hoy
        $pendientes = Cita::where('id_clinica', $idClinica)
            ->whereDate('fecha_hora_inicio', $hoy)
            ->whereIn('estado_cita', ['pendiente', 'confirmada'])
            ->count();

        // Calcular ingresos del mes
        $ingresosMes = IngresoCaja::where('id_clinica', $idClinica)
            ->whereMonth('fecha_ingreso', now()->month)
            ->whereYear('fecha_ingreso', now()->year)
            ->sum('monto');

        return response()->json([
            'success' => true,
            'message' => 'Cita completada',
            'stats' => [
                'pendientes_hoy' => $pendientes,
                'ingresos_mes' => number_format($ingresosMes, 2, '.', ',')
            ]
        ]);

    } catch (\Exception $e) {

        Log::error("Error completar cita: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error del servidor'
        ], 500);
    }
}

    /**
     * DISPONIBILIDAD DEL MES
     */
    public function obtenerDisponibilidadMes(Request $request)
    {

        $mes = $request->input('mes',Carbon::now()->month);
        $anio = $request->input('anio',Carbon::now()->year);

        $idClinica = Auth::user()->id_clinica;

        $inicioMes = Carbon::createFromDate($anio,$mes,1);
        $dias = $inicioMes->daysInMonth;

        $slotsPorDia = 16;

        $eventos = [];

        for($i=1;$i<=$dias;$i++){

            $fecha = Carbon::createFromDate($anio,$mes,$i);

            $total = Cita::where('id_clinica',$idClinica)
                ->whereDate('fecha_hora_inicio',$fecha)
                ->where('estado_cita','!=','cancelada')
                ->count();

            $estado='verde';
            $clickable=true;

            if($total>0 && $total<$slotsPorDia){
                $estado='amarillo';
            }

            if($total>=$slotsPorDia){
                $estado='rojo';
                $clickable=false;
            }

            if($fecha->isPast() && !$fecha->isToday()){
                $estado='gris';
                $clickable=false;
            }

            $horasOcupadas = ceil($total/2);
            $horasDisponibles = max(0,8-$horasOcupadas);

            $eventos[$i]=[
                'estado'=>$estado,
                'clickable'=>$clickable,
                'total_citas'=>$total,
                'horas_ocupadas'=>$horasOcupadas,
                'horas_disponibles'=>$horasDisponibles,
                'slots_ocupados'=>$total,
                'slots_totales'=>$slotsPorDia
            ];
        }

        return response()->json($eventos);
    }

}