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
            'nombre' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/u', 'max:50'],
            'observaciones' => ['nullable', 'max:150'],
            'carga_horaria' => ['required', 'integer', 'between:1,12'],
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y números.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',


            'carga_horaria.required' => 'La cantidad de modulos es obligatoria.',
            'carga_horaria.integer' => 'La cantidad de modulos debe ser un número entero.',
            'carga_horaria.between' => 'La cantidad de modulos debe estar entre 1 y 12.',


            'observaciones.max' => 'Las observaciones no pueden tener más de 150 caracteres.',
        ];
    }
}
