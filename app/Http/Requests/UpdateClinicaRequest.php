<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Helpers\StringHelper;


class UpdateClinicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo usuarios autenticados con clínica asociada
        return Auth::check() && Auth::user()->id_clinica !== null;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nombre_comercial' => StringHelper::capitalizeName($this->nombre_comercial),
            'localidad' => StringHelper::capitalizeName($this->localidad),
            'estado' => StringHelper::capitalizeName($this->estado),
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre_comercial' => 'required|string|max:150',
            'numero_telefono' => 'nullable|string|max:20|regex:/^[0-9]+$/',
            'localidad' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'estado' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_comercial.required' => 'El nombre de la clínica es obligatorio.',
            'numero_telefono.regex' => 'El teléfono solo puede contener números.',
            'localidad.regex' => 'La localidad solo puede contener letras y espacios.',
            'estado.regex' => 'El estado solo puede contener letras y espacios.',
        ];
    }
}
