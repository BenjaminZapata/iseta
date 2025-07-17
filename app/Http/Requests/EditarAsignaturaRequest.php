<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarAsignaturaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required'],
            'tipo_modulo' => ['required'],
            'carga_horaria' => ['required', 'regex:/^\d+$/'],
            'anio' => ['required', 'regex:/^\d+$/'],
            'observaciones' => ['nullable'],
        ];
    }
}
