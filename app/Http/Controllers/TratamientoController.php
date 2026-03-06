<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class TratamientoController extends Controller
{

  public function index()
{
    // Obtenemos los tratamientos filtrados por la clínica del usuario
    $tratamientos = Servicio::where('id_clinica', Auth::user()->id_clinica)->get();

    // ESTE ES EL CAMBIO CLAVE:
    // Antes decía 'dashboard', pero tu archivo es 'tratamientos/index.blade.php'
    return view('tratamientos.index', compact('tratamientos'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:100'
        ]);

        $idClinica = Auth::user()->id_clinica;

        // Verificar duplicado en la misma clínica
        $existe = Servicio::where('nombre_servicio', $request->nombre)
                    ->where('id_clinica', $idClinica)
                    ->first();

        if ($existe) {
            return redirect()->back()->with('error', 'El tratamiento ya existe');
        }

        Servicio::create([
            'id_clinica' => $idClinica,
            'nombre_servicio' => $request->nombre,
            'precio_base' => $request->precio,
            'categoria' => $request->categoria
        ]);

        return redirect()->back()->with('success', 'Tratamiento creado correctamente');
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:100'
        ]);

        $idClinica = Auth::user()->id_clinica;

        $tratamiento = Servicio::where('id_servicio', $id)
                        ->where('id_clinica', $idClinica)
                        ->firstOrFail();

        // Validar duplicado
        $existe = Servicio::where('nombre_servicio', $request->nombre)
                    ->where('id_clinica', $idClinica)
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

        return redirect()->back()->with('success', 'Tratamiento actualizado correctamente');
    }


    public function destroy($id)
    {
        $idClinica = Auth::user()->id_clinica;

        $tratamiento = Servicio::where('id_servicio', $id)
                        ->where('id_clinica', $idClinica)
                        ->firstOrFail();

        $tratamiento->delete();

        return redirect()->back()->with('success', 'Tratamiento eliminado correctamente');
    }

}