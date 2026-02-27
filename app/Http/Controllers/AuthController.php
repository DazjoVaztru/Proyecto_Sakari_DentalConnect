<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    // Muestra la vista de Login/Registro
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesa el REGISTRO de nuevo usuario (Doctor + Clínica en transacción)
    public function register(Request $request)
    {
        // Capitalizar automáticamente la primera letra de cada campo de nombre
        $request->merge([
            'nombre' => $request->nombre ? ucfirst(strtolower(trim($request->nombre))) : null,
            'apellido_paterno' => $request->apellido_paterno ? ucfirst(strtolower(trim($request->apellido_paterno))) : null,
            'apellido_materno' => $request->apellido_materno ? ucfirst(strtolower(trim($request->apellido_materno))) : null,
        ]);

        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras (sin números ni caracteres especiales).',
            'nombre.max' => 'El nombre no puede exceder 50 caracteres.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener una palabra con letras (sin caracteres especiales).',
            'apellido_paterno.max' => 'El apellido paterno no puede exceder 50 caracteres.',
            'apellido_materno.regex' => 'El apellido materno solo puede contener una palabra con letras (sin caracteres especiales).',
            'apellido_materno.max' => 'El apellido materno no puede exceder 50 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener mínimo 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'nombre_clinica.required' => 'El nombre de la clínica es obligatorio.',
            'rfc_clinica.required' => 'El RFC de la clínica es obligatorio.',
            'rfc_clinica.unique' => 'Este RFC ya está registrado.',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            // Solo letras (incluyendo acentos), una sola palabra, sin caracteres especiales
            'nombre' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/u'],
            'apellido_paterno' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/u'],
            'apellido_materno' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/u'],
            'email' => 'required|email|max:100|unique:usuarios_sistema,email',
            'password' => 'required|string|min:6|confirmed',
            'nombre_clinica' => 'required|string|max:150',
            'rfc_clinica' => 'required|string|max:13|unique:clinicas,rfc_clinica',
            'telefono_clinica' => 'nullable|string|max:15',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('login')
                ->withErrors($validator)
                ->withInput()
                ->with('show_register', true);
        }

        DB::transaction(function () use ($request) {

            // 1. aqui creamos la clinica, hayq ue recordar que si no existe clinica, no se puede crear un usuario al cual ligarla
            $clinicaId = DB::table('clinicas')->insertGetId([
                'nombre_comercial' => $request->nombre_clinica,
                'rfc_clinica' => strtoupper($request->rfc_clinica),
                'numero_telefono' => $request->telefono_clinica,
                'config_anticipo_pct' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Crear el usuario en usuarios_sistema referenciando la clínica creada
            $nombreCompleto = trim(
                $request->nombre . ' ' .
                $request->apellido_paterno . ' ' .
                ($request->apellido_materno ?? '')
            );

            $usuario = User::create([
                'id_clinica' => $clinicaId,
                'nombre_completo' => $nombreCompleto,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 'doctor',
                'is_active' => true,
            ]);

            // 3. Crear automáticamente el perfil de Doctor vinculado al usuario
            DB::table('doctores')->insert([
                'id_usuario' => $usuario->id_usuario,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('login')
            ->with('success', '¡Clínica y cuenta creadas! Ya puedes iniciar sesión.');
    }

    // Procesa el LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    // Procesa el LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
