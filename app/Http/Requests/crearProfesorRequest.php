<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearProfesorRequest extends FormRequest
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
    'dni' => ['required', 'numeric'],
    'nombre' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
    'apellido' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
    'fecha_nacimiento' => ['required', 'date', 'before:now'],
    'ciudad' => ['required', 'string', 'max:100'],
    'calle' => ['required', 'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/u'],
    'casa_numero' => ['numeric'],
    'dpto' => ['nullable', 'string', 'max:10'],
    'piso' => ['nullable', 'numeric'],
    'estado_civil' => ['required', 'string'],
    'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
    'formacion_academica' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
    'titulo' => ['nullable', 'numeric'],
    'observaciones' => ['nullable', 'string'],
    'telefono1' => ['nullable', 'numeric'],
    'telefono2' => ['nullable', 'numeric'],
    'telefono3' => ['nullable', 'numeric'],
    'codigo_postal' => ['required', 'alpha_num'],
    ];
    }

    public function messages()
{
    return [
        'ciudad.max' => 'El nombre de la ciudad es demasiado largo. Máximo 100 caracteres.',
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
        'apellido.required' => 'El apellido es obligatorio.',
        'apellido.max' => 'El apellido no puede tener más de 50 caracteres.',
        'dni.required' => 'El DNI es obligatorio.',
        'dni.max' => 'El DNI no puede tener más de 15 caracteres.',
        'email.email' => 'El email ingresado no es válido.',
        'email.max' => 'El email no puede tener más de 100 caracteres.',
    ];
}
}
