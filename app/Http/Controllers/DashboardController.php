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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    /**
     * Dashboard principal
     */
    public function index()
    {
        $user = Auth::user();
        $idClinica = $user->id_clinica;

        $hoy = Carbon::today();
        $ahora = Carbon::now();

        // Citas futuras
        $citasFuturas = Cita::with(['paciente','servicio'])
            ->where('id_clinica',$idClinica)
            ->where('fecha_hora_inicio','>=',$ahora)
            ->where('estado_cita','pendiente')
            ->orderBy('fecha_hora_inicio','asc')
            ->get();

        // Citas vencidas
        $citasVencidas = Cita::with(['paciente','servicio'])
            ->where('id_clinica',$idClinica)
            ->where('fecha_hora_inicio','<',$ahora)
            ->where('estado_cita','pendiente')
            ->orderBy('fecha_hora_inicio','desc')
            ->get();

        $proximasCitas = $citasFuturas->concat($citasVencidas)->take(15);

        // Citas de hoy
        $citasHoyCount = Cita::where('id_clinica',$idClinica)
            ->whereDate('fecha_hora_inicio',$hoy)
            ->where('estado_cita','pendiente')
            ->count();

        // Pacientes activos
        $totalPacientes = Paciente::whereHas('usuario',function($q) use ($idClinica){
            $q->where('id_clinica',$idClinica);
        })
        ->where('is_active',true)
        ->count();

        // Ingresos del mes
        $ingresosMes = IngresoCaja::where('id_clinica',$idClinica)
            ->whereMonth('fecha_ingreso',$hoy->month)
            ->whereYear('fecha_ingreso',$hoy->year)
            ->sum('monto');

        // Inventario bajo
        $itemsBajoStock = Inventario::where('id_clinica',$idClinica)
            ->where('stock','<',5)
            ->orderBy('stock','asc')
            ->take(5)
            ->get();

        // Notificaciones
        $notificacionesPendientes = Notificacion::where('id_usuario',$user->id_usuario)
            ->where('estado','pendiente')
            ->count();

        // Servicios
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
     * Obtener datos del modal
     */
    public function obtenerDatosModal($idCita)
    {

        $cita = Cita::with(['paciente','servicio','ingresos'])->findOrFail($idCita);

        $p = $cita->paciente;

        $costoTotal = floatval($cita->costo_estimado ?? 0);
        $totalPagado = $cita->ingresos ? $cita->ingresos->sum('monto') : 0;
        $saldo = max(0,$costoTotal-$totalPagado);

        $pacienteData = null;

        if($p){

            $edad = $p->fecha_nacimiento ? Carbon::parse($p->fecha_nacimiento)->age : null;

            $sexoMap = [
                'M'=>'Masculino',
                'F'=>'Femenino',
                'O'=>'Otro'
            ];

            $pacienteData = [

                'id_paciente'=>$p->id_paciente,
                'nombres'=>$p->nombre,
                'paterno'=>$p->apellido_paterno,
                'materno'=>$p->apellido_materno,
                'edad'=>$edad ? $edad.' años':'N/A',
                'edad_numero'=>$edad,
                'sexo'=>$sexoMap[$p->sexo] ?? $p->sexo ?? 'N/A',
                'telefono'=>$p->telefono,
                'tipo_sangre'=>$p->tipo_sangre,
                'peso'=>$p->peso ? $p->peso.' kg':'N/A',
                'alergias'=>$p->alergias ?? 'Ninguna registrada',
                'enfermedades'=>$p->enfermedades_cronicas ?? 'Ninguna registrada'

            ];

        }

        $filaTabla = [

            'dia'=>Carbon::parse($cita->fecha_hora_inicio)->format('d/m/Y'),
            'hora'=>Carbon::parse($cita->fecha_hora_inicio)->format('h:i A')
                .' – '.
                Carbon::parse($cita->fecha_hora_fin)->format('h:i A'),
            'seguimiento'=>$cita->motivo ?? ($cita->servicio?->nombre_servicio ?? 'Consulta'),
            'abono'=>number_format($totalPagado,2)

        ];

        $finanzas = [

            'total'=>number_format($costoTotal,2),
            'pagado'=>number_format($totalPagado,2),
            'restante'=>number_format($saldo,2)

        ];

        $fechaCita = [

            'mes'=>(int)Carbon::parse($cita->fecha_hora_inicio)->format('m'),
            'anio'=>(int)Carbon::parse($cita->fecha_hora_inicio)->format('Y')

        ];

        $odontograma = Odontograma::where('id_paciente',$p?->id_paciente)
            ->orderBy('id_odontograma','desc')
            ->get();

        return response()->json([

            'success'=>true,
            'paciente'=>$pacienteData,
            'fila_tabla'=>$filaTabla,
            'finanzas'=>$finanzas,
            'fecha_cita'=>$fechaCita,
            'odontograma'=>$odontograma,
            'ingresos'=>$cita->ingresos

        ]);

    }



    /**
     * Actualizar cita
     */
    public function actualizarCita(Request $request,$idCita)
    {

        $cita = Cita::findOrFail($idCita);

        if($request->filled('estado_cita')){
            $cita->estado_cita=$request->estado_cita;
        }

        if($request->filled('costo_estimado')){
            $cita->costo_estimado=$request->costo_estimado;
        }

        if($request->filled('nueva_fecha')){

            $hora=$request->filled('nueva_hora')
                ? $request->nueva_hora
                : Carbon::parse($cita->fecha_hora_inicio)->format('H:i');

            $cita->fecha_hora_inicio=$request->nueva_fecha.' '.$hora;
            $cita->fecha_hora_fin=Carbon::parse($cita->fecha_hora_inicio)->addMinutes(30);

        }

        $cita->save();

        if($request->filled('notas_seguimiento')){

            SeguimientoClinico::create([

                'id_cita'=>$cita->id_cita,
                'observaciones'=>$request->notas_seguimiento

            ]);

            $cita->motivo=$request->notas_seguimiento;
            $cita->save();

        }

        if($request->filled('monto_abono') && $request->monto_abono>0){

            IngresoCaja::create([

                'id_clinica'=>Auth::user()->id_clinica ?? 1,
                'id_cita'=>$cita->id_cita,
                'monto'=>$request->monto_abono,
                'fecha_ingreso'=>now(),
                'metodo_pago'=>'efectivo',
                'descripcion'=>'Abono en cita: '.$cita->motivo

            ]);

        }

        return response()->json([

            'success'=>true,
            'message'=>'Cita actualizada correctamente'

        ]);

    }



    /**
     * Completar cita
     */
    public function completarCita($idCita)
    {

        $cita=Cita::findOrFail($idCita);

        $cita->estado_cita='completada';
        $cita->save();

        return response()->json([

            'success'=>true,
            'message'=>'Cita marcada como completada.'

        ]);

    }



    /**
     * Disponibilidad del mes
     */
    public function obtenerDisponibilidadMes(Request $request)
    {

        $mes=$request->input('mes',Carbon::now()->month);
        $anio=$request->input('anio',Carbon::now()->year);
        $idClinica=Auth::user()->id_clinica;

        $diasDelMes=Carbon::createFromDate($anio,$mes,1)->daysInMonth;

        $slotsDisponiblesPorDia=16;

        $eventos=[];

        for($i=1;$i<=$diasDelMes;$i++){

            $fecha=Carbon::createFromDate($anio,$mes,$i);

            $totalCitas=Cita::where('id_clinica',$idClinica)
                ->whereDate('fecha_hora_inicio',$fecha)
                ->where('estado_cita','!=','cancelada')
                ->count();

            $estado='verde';
            $clickable=true;

            if($totalCitas>0 && $totalCitas<$slotsDisponiblesPorDia){
                $estado='amarillo';
            }

            elseif($totalCitas>=$slotsDisponiblesPorDia){
                $estado='rojo';
                $clickable=false;
            }

            if($fecha->isPast() && !$fecha->isToday()){
                $estado='gris';
                $clickable=false;
            }

            $eventos[$i]=[
                'estado'=>$estado,
                'clickable'=>$clickable
            ];

        }

        return response()->json($eventos);

    }



    /**
     * Horas ocupadas
     */
    public function horasOcupadas(Request $request)
    {
        try {
            $fecha = $request->input('fecha');
            $user = Auth::user();
            
            if (!$fecha || !$user) {
                return response()->json([
                    'horas_ocupadas' => []
                ]);
            }
            
            $idClinica = $user->id_clinica;

            // Obtener el ID del doctor actual
            $idDoctor = DB::table('doctores')
                ->join('usuarios_sistema', 'doctores.id_usuario', '=', 'usuarios_sistema.id_usuario')
                ->where('usuarios_sistema.id_clinica', $idClinica)
                ->value('doctores.id_doctor');

            if (!$idDoctor) {
                return response()->json(['horas_ocupadas' => []]);
            }

            // Buscar las citas del día (Pendientes Y Confirmadas)
            $citas = Cita::where('id_clinica', $idClinica)
                ->where('id_doctor', $idDoctor)
                ->whereDate('fecha_hora_inicio', $fecha)
                ->whereIn('estado_cita', ['pendiente', 'confirmada']) // Fundamental para detectar tu cita de las 5:30
                ->get();

            // Formatear exactamente a 'H:i'
            $horasOcupadas = $citas->map(function ($cita) {
                return Carbon::parse($cita->fecha_hora_inicio)->format('H:i');
            })->toArray();

            return response()->json([
                'horas_ocupadas' => array_values($horasOcupadas)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'horas_ocupadas' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

}