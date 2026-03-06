<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;

class TratamientoController extends Controller
{

    public function index()
    {
        $tratamientos = Servicio::all();
        return view('dashboard', compact('tratamientos'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'categoria' => 'required|string'
        ]);

        // Verificar duplicado
        $existe = Servicio::where('nombre_servicio', $request->nombre)->first();

        if ($existe) {
            return redirect()->back()->with('error', 'El tratamiento ya existe');
        }

        Servicio::create([
            'id_clinica' => 1,
            'nombre_servicio' => $request->nombre,
            'precio_base' => $request->precio,
            'categoria' => $request->categoria
        ]);

        return redirect()->back()->with('success', 'Tratamiento creado correctamente');
    }


    public function update(Request $request, $id)
    {

        $tratamiento = Servicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'precio' => 'required|numeric',
            'categoria' => 'required'
        ]);

        // Validar duplicados
        $existe = Servicio::where('nombre_servicio', $request->nombre)
            ->where('id_servicio', '!=', $id)
            ->first();

        if ($existe) {
            return redirect()->back()->with('error', 'El tratamiento ya existe');
        }

        $tratamiento->update([
            'nombre_servicio' => $request->nombre,
            'precio_base' => $request->precio,
            'categoria' => $request->categoria
        ]);

        return redirect()->back()->with('success', 'Tratamiento actualizado');
    }


    public function destroy($id)
    {
        $tratamiento = Servicio::findOrFail($id);

        $tratamiento->delete();

        return redirect()->back()->with('success', 'Tratamiento eliminado correctamente');
    }
}