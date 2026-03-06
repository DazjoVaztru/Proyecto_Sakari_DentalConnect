<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Helpers\StringHelper;


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
     * Aplica sanitización a los datos antes de la validación.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nombre' => StringHelper::capitalizeName($this->nombre),
            'apellido_paterno' => StringHelper::capitalizeName($this->apellido_paterno),
            'apellido_materno' => StringHelper::capitalizeName($this->apellido_materno),
            'emergencia_nombre' => StringHelper::capitalizeName($this->emergencia_nombre),
            'emergencia_apellido_paterno' => StringHelper::capitalizeName($this->emergencia_apellido_paterno),
            'emergencia_apellido_materno' => StringHelper::capitalizeName($this->emergencia_apellido_materno),
        ]);
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
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'apellido_paterno' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'apellido_materno' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'telefono' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9]+$/'],
            'email' => [
                'required',
                'email',
                'max:150',
                // Sólo un paciente por clínica. usamos regla personalizada para filtrar por id_clinica
                \Illuminate\Validation\Rule::unique('usuarios_sistema', 'email')
                    ->where(function ($query) {
                        return $query->where('id_clinica', Auth::user()->id_clinica);
                    }),
            ],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'sexo' => ['required', 'in:M,F,O'],

            // Datos médicos sensibles — todos obligatorios para expediente clínico
            'tipo_sangre' => ['required', 'string', 'max:5'],
            'peso' => ['required', 'integer', 'min:1', 'max:500'],
            'direccion' => ['required', 'string', 'max:100'],
            'ocupacion' => ['nullable', 'string', 'max:100'],
            'enfermedades_cronicas' => ['required', 'string', 'max:1000'],
            'alergias' => ['required', 'string', 'max:1000'],

            // Contacto de emergencia (nombre y teléfono obligatorios)
            'emergencia_nombre' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'emergencia_apellido_paterno' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'emergencia_apellido_materno' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'emergencia_telefono' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9]+$/'],
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del paciente es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras y espacios.',
            'apellido_materno.regex' => 'El apellido materno solo puede contener letras y espacios.',
            'telefono.required' => 'El teléfono de contacto es obligatorio.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'peso.required' => 'El peso del paciente es obligatorio para el expediente clínico.',
            'peso.integer' => 'El peso debe ser un valor numérico entero (sin decimales).',
            'peso.max' => 'El peso no puede exceder los 500 kg.',
            'enfermedades_cronicas.required' => 'Las enfermedades crónicas son obligatorias. Si no tiene, escriba "Ninguna".',
            'alergias.required' => 'Las alergias son obligatorias. Si no tiene, escriba "Ninguna".',
            'direccion.required' => 'La dirección es obligatoria para el expediente clínico.',
            'emergencia_nombre.regex' => 'El nombre del contacto de emergencia solo puede contener letras y espacios.',
            'emergencia_apellido_paterno.regex' => 'El apellido paterno del contacto solo puede contener letras y espacios.',
            'emergencia_apellido_materno.regex' => 'El apellido materno del contacto solo puede contener letras y espacios.',
            'emergencia_telefono.regex' => 'El teléfono del contacto de emergencia solo puede contener números.',
            'sexo.required' => 'El sexo del paciente es obligatorio.',
            'tipo_sangre.required' => 'El tipo de sangre es obligatorio.',
            'emergencia_nombre.required' => 'El nombre del contacto de emergencia es obligatorio.',
            'emergencia_telefono.required' => 'El teléfono del contacto de emergencia es obligatorio.',
        ];
    }
}
