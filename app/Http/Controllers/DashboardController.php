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

        $citasFuturas = Cita::with(['paciente','servicio'])
            ->where('id_clinica',$idClinica)
            ->where('fecha_hora_inicio','>=',$ahora)
            ->where('estado_cita','pendiente')
            ->orderBy('fecha_hora_inicio','asc')
            ->get();

        $citasVencidas = Cita::with(['paciente','servicio'])
            ->where('id_clinica',$idClinica)
            ->where('fecha_hora_inicio','<',$ahora)
            ->where('estado_cita','pendiente')
            ->orderBy('fecha_hora_inicio','desc')
            ->get();

        $proximasCitas = $citasFuturas->concat($citasVencidas)->take(15);

        $citasHoyCount = Cita::where('id_clinica',$idClinica)
            ->whereDate('fecha_hora_inicio',$hoy)
            ->where('estado_cita','pendiente')
            ->count();

        $totalPacientes = Paciente::whereHas('usuario',function($q) use ($idClinica){
            $q->where('id_clinica',$idClinica);
        })
        ->where('is_active',true)
        ->count();

        $ingresosMes = IngresoCaja::where('id_clinica',$idClinica)
            ->whereMonth('fecha_ingreso',$hoy->month)
            ->whereYear('fecha_ingreso',$hoy->year)
            ->sum('monto');

        $itemsBajoStock = Inventario::where('id_clinica',$idClinica)
            ->where('stock','<',5)
            ->orderBy('stock','asc')
            ->take(5)
            ->get();

        $notificacionesPendientes = Notificacion::where('id_usuario',$user->id_usuario)
            ->where('estado','pendiente')
            ->count();

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
     * ACTUALIZAR CITA
     */
    public function actualizarCita(Request $request,$idCita)
    {

        $cita = Cita::with('ingresos')->findOrFail($idCita);

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
            $fin = $inicio->copy()->addHour();

            // VALIDAR HORARIO OCUPADO
            $existe = Cita::where('id_clinica',Auth::user()->id_clinica)
                ->where('id_cita','!=',$cita->id_cita)
                ->whereDate('fecha_hora_inicio',$inicio->toDateString())
                ->where(function($query) use ($inicio,$fin){

                    $query->whereBetween('fecha_hora_inicio',[$inicio,$fin])
                          ->orWhereBetween('fecha_hora_fin',[$inicio,$fin])
                          ->orWhere(function($q) use ($inicio,$fin){
                                $q->where('fecha_hora_inicio','<=',$inicio)
                                  ->where('fecha_hora_fin','>=',$fin);
                          });

                })
                ->exists();

            if($existe){
                return response()->json([
                    'success'=>false,
                    'message'=>'Ese horario ya está ocupado'
                ],422);
            }

            $cita->fecha_hora_inicio = $inicio;
            $cita->fecha_hora_fin = $fin;
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

        try{

            $cita = Cita::findOrFail($idCita);

            $cita->estado_cita='completada';
            $cita->save();

            $idClinica = Auth::user()->id_clinica;
            $hoy = Carbon::today();

            $pendientes = Cita::where('id_clinica',$idClinica)
                ->whereDate('fecha_hora_inicio',$hoy)
                ->whereIn('estado_cita',['pendiente','confirmada'])
                ->count();

            $ingresosMes = IngresoCaja::where('id_clinica',$idClinica)
                ->whereMonth('fecha_ingreso',now()->month)
                ->whereYear('fecha_ingreso',now()->year)
                ->sum('monto');

            return response()->json([
                'success'=>true,
                'message'=>'Cita completada',
                'stats'=>[
                    'pendientes_hoy'=>$pendientes,
                    'ingresos_mes'=>number_format($ingresosMes,2,'.',',')
                ]
            ]);

        }catch(\Exception $e){

            Log::error("Error completar cita: ".$e->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'Error del servidor'
            ],500);
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

        $slotsPorDia = 8;

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

            $horasOcupadas = $total;
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


    /**
     * HORARIOS DISPONIBLES
     */
    public function horariosDisponibles(Request $request)
    {

        $fecha = $request->fecha;
        $idClinica = Auth::user()->id_clinica;

        $horarios = [
            '09:00','09:30','10:00','10:30',
            '11:00','11:30','12:00','12:30',
            '13:00','13:30','14:00','14:30',
            '15:00','15:30','16:00','16:30'
        ];

        $ocupadas = Cita::where('id_clinica',$idClinica)
            ->whereDate('fecha_hora_inicio',$fecha)
            ->pluck('fecha_hora_inicio')
            ->map(function($hora){
                return Carbon::parse($hora)->format('H:i');
            });

        $disponibles = collect($horarios)->diff($ocupadas)->values();

        return response()->json($disponibles);
    }

}