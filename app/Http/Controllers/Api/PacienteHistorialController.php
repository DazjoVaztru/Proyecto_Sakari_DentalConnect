<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\SignoVital;
use App\Models\EvolucionTratamiento;

class PacienteHistorialController extends Controller
{
    /**
     * Retorna los últimos signos vitales del paciente.
     */
    public function signosVitales($idPaciente)
    {
        $registros = SignoVital::where('paciente_id', $idPaciente)
            ->orderBy('fecha_registro', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $registros,
        ]);
    }

    /**
     * Retorna las evoluciones de tratamiento del paciente.
     */
    public function evoluciones($idPaciente)
    {
        $registros = EvolucionTratamiento::where('id_paciente', $idPaciente)
            ->orderBy('fecha_evolucion', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $registros,
        ]);
    }

    /**
     * Guarda una nueva evolución clínica y permite adjuntar una imagen.
     */
    public function storeEvolucion(\Illuminate\Http\Request $request, $idPaciente)
    {
        $request->validate([
            'descripcion_avance' => 'required|string|max:100',
            'plan_tratamiento' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // máximo 5MB
        ]);

        $evolucion = new EvolucionTratamiento();
        $evolucion->id_paciente = $idPaciente;
        $evolucion->fecha_evolucion = now();
        $evolucion->descripcion_avance = $request->descripcion_avance;
        $evolucion->plan_tratamiento = $request->plan_tratamiento;
        $evolucion->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Guardar en public/storage/evoluciones
            $path = $file->storeAs('evoluciones', $filename, 'public');

            \App\Models\EvolucionClinicaImagen::create([
                'id_evolucion' => $evolucion->id_evolucion,
                'ruta_imagen' => $path,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Evolución guardada correctamente.',
            'data' => $evolucion
        ]);
    }
}
