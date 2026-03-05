<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class TratamientoController extends Controller
{
    public function index()
    {
        $idClinica = Auth::user()->id_clinica ?? 1;
        $servicios = Servicio::where('id_clinica', $idClinica)->get();

        return view('tratamientos.index', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÁÉÍÓÚÜáéíóúüÑñ\s]+$/'
            ],
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:50',
        ], [
            'nombre.regex' => 'El nombre del tratamiento solo puede contener letras y espacios.'
        ]);

        Servicio::create([
            'id_clinica' => Auth::user()->id_clinica ?? 1,
            'nombre_servicio' => $request->nombre,
            'precio_base' => $request->precio,
            'categoria' => $request->categoria ?? 'General'
        ]);

        return redirect()->back()->with('success', 'Tratamiento creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÁÉÍÓÚÜáéíóúüÑñ\s]+$/'
            ],
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:50',
        ], [
            'nombre.regex' => 'El nombre del tratamiento solo puede contener letras y espacios.'
        ]);

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