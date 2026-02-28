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
    // Muestra la vista de Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Muestra la vista de Registro (página independiente)
    public function showRegister()
    {
        return view('auth.register');
    }

    // Procesa el REGISTRO de nuevo usuario (Doctor + Clínica en transacción)
    public function register(Request $request)
    {
        // Capitalizar automáticamente la primera letra de cada campo de nombre
        $request->merge([
            'nombre' => $request->nombre ? ucwords(strtolower(trim($request->nombre))) : null,
            'apellido_paterno' => $request->apellido_paterno ? ucfirst(strtolower(trim($request->apellido_paterno))) : null,
            'apellido_materno' => $request->apellido_materno ? ucfirst(strtolower(trim($request->apellido_materno))) : null,
            'rfc_clinica' => $request->rfc_clinica ? strtoupper(trim($request->rfc_clinica)) : null,
        ]);

        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios simples (sin caracteres especiales).',
            'nombre.max' => 'El nombre no puede exceder 50 caracteres.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras (sin caracteres especiales ni espacios).',
            'apellido_paterno.max' => 'El apellido paterno no puede exceder 50 caracteres.',
            'apellido_materno.regex' => 'El apellido materno solo puede contener letras (sin caracteres especiales ni espacios).',
            'apellido_materno.max' => 'El apellido materno no puede exceder 50 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'nombre_clinica.required' => 'El nombre de la clínica es obligatorio.',
            'rfc_clinica.required' => 'El RFC de la clínica es obligatorio.',
            'rfc_clinica.regex' => 'El RFC solo puede contener letras y números (sin caracteres especiales), exactamente 12 o 13 caracteres.',
            'rfc_clinica.max' => 'El RFC no puede exceder 13 caracteres.',
            'telefono_clinica.regex' => 'El teléfono solo puede contener números (sin espacios, letras ni caracteres especiales).',
            'telefono_clinica.max' => 'El teléfono no puede exceder 12 dígitos.',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            // Letras, acentos y ñ, incluyendo espacios simples, sin caracteres especiales
            'nombre' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\x{00C0}-\x{024F}\x{00D1}\x{00F1} ]+$/u'],
            // Apellidos: solo una palabra, sin espacios
            'apellido_paterno' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\x{00C0}-\x{024F}\x{00D1}\x{00F1}]+$/u'],
            'apellido_materno' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z\x{00C0}-\x{024F}\x{00D1}\x{00F1}]+$/u'],
            'email' => 'required|email|max:100|unique:usuarios_sistema,email',
            'password' => 'required|string|min:8|confirmed',
            'nombre_clinica' => 'required|string|max:150',
            // RFC: solo letras y números, entre 12 y 13 caracteres. Removido unique para sucursales de la misma clínica.
            'rfc_clinica' => ['required', 'string', 'max:13', 'regex:/^[A-Z0-9]{12,13}$/'],
            // Teléfono: solo dígitos, sin espacios ni letras. Máximo 12 dígitos.
            'telefono_clinica' => ['nullable', 'regex:/^[0-9]{1,12}$/', 'max:12'],
            'localidad' => ['nullable', 'string', 'max:100'],
            'estado_clinica' => ['nullable', 'string', 'max:50'],
            'codigo_postal' => ['nullable', 'regex:/^[0-9]{5}$/'],
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {

            // 1. Crear la clínica primero (sin ella no existe el id_clinica)
            $clinicaId = DB::table('clinicas')->insertGetId([
                'nombre_comercial' => $request->nombre_clinica,
                'rfc_clinica' => $request->rfc_clinica,
                'numero_telefono' => $request->telefono_clinica,
                'localidad' => $request->localidad,
                'estado' => $request->estado_clinica,
                'codigo_postal' => $request->codigo_postal,
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
            ]);

            // 3. Crear automáticamente el perfil de Doctor vinculado al usuario
            DB::table('doctores')->insert([
                'id_usuario' => $usuario->id_usuario,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('login')
            ->with('success', '¡Clínica y cuenta creadas exitosamente! Ya puedes iniciar sesión.');
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

            // CRÍTICO PARA SAAS MULTI-TENANT: Guardamos en sesión la clínica del usuario logueado
            $request->session()->put('id_clinica', Auth::user()->id_clinica);

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
