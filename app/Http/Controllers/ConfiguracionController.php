<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Clinica;
use App\Models\User;
use App\Models\Doctor;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $clinica = Clinica::find($user->id_clinica);

        // 1. Datos del Doctor (Buscamos al usuario con rol doctor de esta clínica)
        $doctorUser = User::where('id_clinica', $user->id_clinica)
            ->where('rol', 'doctor')
            ->first();

        // Obtenemos sus datos extendidos (cédula, etc) si existen
        $doctorPerfil = $doctorUser ? Doctor::where('id_usuario', $doctorUser->id_usuario)->first() : null;

        // 2. Datos de Recepcionistas (Lista)
        $recepcionistas = User::where('id_clinica', $user->id_clinica)
            ->where('rol', 'recepcionista')
            ->get();

        return view('configuracion.index', compact('user', 'clinica', 'doctorUser', 'doctorPerfil', 'recepcionistas'));
    }

    // Actualizar datos de la Clínica
    public function updateClinica(Request $request)
    {
        $clinica = Clinica::find(Auth::user()->id_clinica);

        $clinica->update($request->validate([
            'nombre_comercial' => 'required|string|max:150',
            'numero_telefono' => 'nullable|string|max:15',
            'localidad' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
        ]));

        return back()->with('success', 'Datos de la clínica actualizados.');
    }

    // Actualizar datos del Usuario (Doctor o Recepcionista)
    public function updateUsuario(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios_sistema,id_usuario',
            'nombre_completo' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'nullable|min:6' // Opcional cambiar contraseña
        ]);

        $usuario = User::find($request->id_usuario);

        // Seguridad: Solo permitir editar usuarios de MI propia clínica
        if ($usuario->id_clinica != Auth::user()->id_clinica) {
            return back()->with('error', 'No tienes permiso para editar este usuario.');
        }

        $data = [
            'nombre_completo' => $request->nombre_completo,
            'email' => $request->email,
        ];

        // Si escribió contraseña, la actualizamos
        if ($request->filled('password')) {
            // Usamos password_hash ya que es el atributo real en BD,
            // aunque el Mutator del modelo podría manejar 'password', 
            // la lógica directa aquí es segura. El modelo User usa User::create 
            // con password_hash en AuthController, mantengamos consistencia.
            // Pero User::update ignora $fillable y $guarded dependiendo del metodo.
            // Lo ideal es asignar el atributo correcto.
            $data['password_hash'] = Hash::make($request->password);
        }

        $usuario->update($data);

        // Si es doctor, actualizamos datos extra
        if ($usuario->rol == 'doctor') {
            Doctor::updateOrCreate(
                ['id_usuario' => $usuario->id_usuario],
                [
                    'cedula_profesional' => $request->cedula_profesional,
                    'horario_default' => $request->horario_default
                ]
            );
        }

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    // Crear nueva Recepcionista
    public function storeRecepcionista(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'email' => 'required|email|unique:usuarios_sistema,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'id_clinica' => Auth::user()->id_clinica,
            'nombre_completo' => $request->nombre_completo,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'rol' => 'recepcionista',
            'is_active' => 1
        ]);

        return back()->with('success', 'Recepcionista agregada correctamente.');
    }
}
