<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicidad;
use Illuminate\Support\Facades\Storage;

class PublicidadController extends Controller
{
    /**
     * Muestra la lista de anuncios publicitarios ordenados por fecha.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Traemos las publicidades ordenadas por las más nuevas
        $anuncios = Publicidad::orderBy('created_at', 'desc')->get();
        return view('publicidad.index', compact('anuncios'));
    }

    /**
     * Almacena una nueva promoción publicitaria.
     *
     * Valida la imagen y los datos, guarda la imagen en disco y crea el registro
     * en la base de datos asociado al usuario actual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('ads', 'public');
            }

            // CREAR EL REGISTRO CON EL ID DEL USUARIO
            Publicidad::create([
                'id_usuario' => \Illuminate\Support\Facades\Auth::id(), // <--- ¡AQUÍ ESTÁ LA MAGIA!
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'imagen_path' => $path ?? null,
                'activo' => 1
            ]);

            return redirect()->back()->with('success', '¡Promoción publicada correctamente!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una promoción publicitaria.
     *
     * Borra el registro de la base de datos y el archivo de imagen asociado del disco.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $anuncio = Publicidad::findOrFail($id);

        // 1. Borramos la imagen del disco para no dejar basura
        if ($anuncio->imagen_path) {
            Storage::disk('public')->delete($anuncio->imagen_path);
        }

        // 2. Borramos el registro de la BD
        $anuncio->delete();

        return redirect()->back()->with('success', 'Promoción eliminada correctamente.');
    }
}