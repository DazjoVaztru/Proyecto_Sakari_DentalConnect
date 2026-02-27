<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateClinicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo usuarios autenticados con clínica asociada
        return Auth::check() && Auth::user()->id_clinica !== null;
    }

    public function rules(): array
    {
        return [
            'nombre_comercial' => 'required|string|max:150',
            'numero_telefono' => 'nullable|string|max:20',
            'localidad' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_comercial.required' => 'El nombre de la clínica es obligatorio.',
        ];
    }
}
