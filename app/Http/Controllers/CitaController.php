<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio; // Asumiendo que tienes modelo Servicio
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_paciente' => 'required|exists:pacientes,id_paciente',
            'fecha_hora' => 'required|date',
            'id_servicio' => 'required|exists:catalogo_servicios,id_servicio' // Validamos que el servicio exista
        ]);

        try {
            // 1. BUSCAR EL PRECIO REAL DEL SERVICIO SELECCIONADO
            $servicioData = Servicio::findOrFail($request->id_servicio);
            $precioActual = $servicioData->precio_base;
            $nombreServicio = $servicioData->nombre_servicio;

            // 2. CREAR LA CITA CON EL PRECIO
            Cita::create([
                'id_clinica' => 1,
                'id_paciente' => $request->id_paciente,
                'id_doctor' => 1, // Doctor default que creamos
                'id_servicio' => $request->id_servicio, // Guardamos el ID
                'fecha_hora_inicio' => $request->fecha_hora,
                'fecha_hora_fin' => Carbon::parse($request->fecha_hora)->addHour(),
                'estado_cita' => 'pendiente',
                'motivo' => $nombreServicio, // Guardamos el nombre como respaldo
                'costo_estimado' => $precioActual // <--- ¡AQUÍ SE GUARDA EL PRECIO!
            ]);

            return redirect()->back()->with('success', 'Cita agendada correctamente con costo de $' . number_format($precioActual, 2));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al agendar: ' . $e->getMessage());
        }
    }
}
