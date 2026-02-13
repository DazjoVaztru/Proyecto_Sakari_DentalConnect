<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;

class TratamientoController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
        return view('tratamientos.index', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100', // Ajustado a varchar(100) de tu BD
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:50' // Ajustado a varchar(50)
        ]);

        Servicio::create([
            'id_clinica' => 1,
            'nombre_servicio' => $request->nombre,
            'precio_base' => $request->precio,
            'categoria' => $request->categoria ?? 'General' // Valor por defecto si lo dejan vacío
        ]);

        return redirect()->back()->with('success', 'Tratamiento creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        // OJO: Usamos 'id_servicio' porque así definimos la primaryKey en el modelo
        $servicio = Servicio::findOrFail($id);

        $servicio->update([
            'nombre_servicio' => $request->nombre,
            'precio_base' => $request->precio,
            'categoria' => $request->categoria
        ]);

        return redirect()->back()->with('success', 'Tratamiento actualizado.');
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return redirect()->back()->with('success', 'Tratamiento eliminado.');
    }
}
