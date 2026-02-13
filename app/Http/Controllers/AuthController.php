<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Muestra la vista de Login/Registro
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesa el REGISTRO de nuevo usuario
    public function register(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios_sistema',
            'password' => 'required|string|min:6',
        ]);

        // 1. Asignar Clínica (Por defecto la 1)
        $clinicaId = 1;

        // 2. Crear el Usuario
        $user = User::create([
            'nombre_completo' => $request->nombre_completo,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Usamos password_hash
            'id_clinica' => $clinicaId,
            'rol' => 'doctor', // Por defecto registramos Doctores
            'is_active' => true,
        ]);

        // 3. Redirigir al login con mensaje de éxito
        return redirect()->route('login')->with('success', '¡Cuenta creada! Inicia sesión ahora.');
    }

    // Procesa el LOGIN
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentamos autenticar usando el campo 'password' que el guard espera,
        // pero nuestro modelo User sabe que debe usar 'password_hash' gracias a getAuthPassword()
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    // Procesa el LOGOUT (Cerrar Sesión)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
