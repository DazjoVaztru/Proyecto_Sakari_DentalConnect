<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePacienteRequest extends FormRequest
{
    /**
     * Solo los usuarios autenticados pueden registrar pacientes.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Reglas de validación del formulario de creación de paciente.
     *
     * Campos de contacto_emergencia son todos opcionales.
     * alergias y enfermedades_cronicas se almacenan como texto libre; no se valida array.
     */
    public function rules(): array
    {
        return [
            // Datos básicos obligatorios
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:150|unique:usuarios_sistema,email',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'nullable|in:M,F,O',

            // Datos médicos opcionales
            'tipo_sangre' => 'nullable|string|max:5',
            'peso' => 'nullable|numeric|min:1|max:300',
            'ocupacion' => 'nullable|string|max:100',
            'enfermedades_cronicas' => 'nullable|string|max:1000',
            'alergias' => 'nullable|string|max:1000',

            // Contacto de emergencia (todos opcionales)
            'emergencia_nombre' => 'nullable|string|max:100',
            'emergencia_apellido_paterno' => 'nullable|string|max:100',
            'emergencia_apellido_materno' => 'nullable|string|max:100',
            'emergencia_telefono' => 'nullable|string|max:20',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del paciente es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'telefono.required' => 'El teléfono de contacto es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'peso.numeric' => 'El peso debe ser un valor numérico.',
        ];
    }
}
