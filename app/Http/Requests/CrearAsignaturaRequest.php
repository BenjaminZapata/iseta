<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearAsignaturaRequest extends FormRequest
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
            'nombre' => ['required','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', 'min:5','max:50'],
            'tipo_modulo' => ['nullable'],
            'carga_horaria' => ['required', 'integer'],
            'anio' => ['required', 'integer', 'min:1', 'max:6'],
            'observaciones' => ['nullable'],
        ];
    }
}
