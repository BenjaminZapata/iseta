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
            'nombre' => ['required','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', 'min:5','max:50'],
            'tipo_modulo' => ['nullable'],
            'carga_horaria' => ['required', 'integer'],
            'anio' => ['required', 'integer', 'min:1', 'max:6'],
            'observaciones' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.min' => 'El nombre debe tener al menos 5 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
            'carga_horaria.integer' => 'La carga horaria debe ser un número entero.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año debe ser al menos 1.',
            'anio.max' => 'El año no puede ser mayor a 6.',
        ];
    }
}
