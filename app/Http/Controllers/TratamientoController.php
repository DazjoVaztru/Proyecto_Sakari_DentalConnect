<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class TratamientoController extends Controller
{
    /**
     * Muestra la lista de tratamientos/servicios disponibles.
     */
    public function index()
    {
        $idClinica = Auth::user()->id_clinica ?? 1;
        $servicios = Servicio::where('id_clinica', $idClinica)->get();

        return view('tratamientos.index', compact('servicios'));
    }

    /**
     * Almacena un nuevo tratamiento/servicio.
     */
    public function store(Request $request)
    {
        $idClinica = Auth::user()->id_clinica ?? 1;

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÁÉÍÓÚÜáéíóúüÑñ\s]+$/'
            ],
            'precio' => [
                'required',
                'numeric',
                'min:0'
            ],
            'categoria' => [
                'nullable',
                'string',
                'max:50'
            ]
        ], [
            'nombre.required' => 'El nombre del tratamiento es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.min' => 'El precio no puede ser negativo.'
        ]);

        $nombre = trim($request->nombre);

        // 🔴 VERIFICAR DUPLICADO (ignora mayúsculas/minúsculas)
        $existe = Servicio::where('id_clinica', $idClinica)
            ->whereRaw('LOWER(TRIM(nombre_servicio)) = ?', [strtolower($nombre)])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe un tratamiento con ese nombre.');
        }

        Servicio::create([
            'id_clinica' => $idClinica,
            'nombre_servicio' => $nombre,
            'precio_base' => $request->precio,
            'categoria' => $request->categoria ?? 'General'
        ]);

        return back()->with('success', 'Tratamiento creado correctamente.');
    }

    /**
     * Actualiza la información de un tratamiento existente.
     */
    public function update(Request $request, $id)
    {
        $idClinica = Auth::user()->id_clinica ?? 1;

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÁÉÍÓÚÜáéíóúüÑñ\s]+$/'
            ],
            'precio' => [
                'required',
                'numeric',
                'min:0'
            ],
            'categoria' => [
                'nullable',
                'string',
                'max:50'
            ]
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.'
        ]);

        $servicio = Servicio::findOrFail($id);
        $nombre = trim($request->nombre);

        // 🔴 VERIFICAR DUPLICADO EXCLUYENDO EL ACTUAL
        $existe = Servicio::where('id_clinica', $idClinica)
            ->whereRaw('LOWER(TRIM(nombre_servicio)) = ?', [strtolower($nombre)])
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe otro tratamiento con ese nombre.');
        }

        $servicio->update([
            'nombre_servicio' => $nombre,
            'precio_base' => $request->precio,
            'categoria' => $request->categoria ?? 'General'
        ]);

        return back()->with('success', 'Tratamiento actualizado.');
    }

    /**
     * Elimina un tratamiento.
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return back()->with('success', 'Tratamiento eliminado.');
    }
}