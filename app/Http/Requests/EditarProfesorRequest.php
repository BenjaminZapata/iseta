<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarProfesorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dni' => ['required', 'numeric', 'digits_between:1,15'],
            'nombre' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', 'max:50'],
            'apellido' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', 'max:50'],
            'fecha_nacimiento' => ['required', 'date', 'before:now'],
            'ciudad' => ['required', 'string', 'max:100'],
            'calle' => ['required', 'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/u', 'max:100'],
            'casa_numero' => ['nullable', 'numeric', 'max:99999'],
            'dpto' => ['nullable', 'string', 'max:10'],
            'piso' => ['nullable', 'numeric', 'max:99'],
            'estado_civil' => ['required', 'string'],
            'email' => ['required', 'email', 'max:100'],
            'formacion_academica' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', 'max:150'],
            'titulo' => ['nullable', 'numeric'],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'telefono1' => ['nullable', 'numeric'],
            'telefono2' => ['nullable', 'numeric'],
            'telefono3' => ['nullable', 'numeric'],
            'codigo_postal' => ['required', 'alpha_num', 'max:10'],
        ];
    }

    public function messages()
    {
        return [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.numeric' => 'El DNI debe ser numérico.',
            'dni.digits_between' => 'El DNI no puede tener más de 15 dígitos.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'apellido.max' => 'El apellido no puede superar los 50 caracteres.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'ciudad.required' => 'La ciudad es obligatoria.',
            'ciudad.max' => 'La ciudad no puede superar los 100 caracteres.',
            'calle.required' => 'La calle es obligatoria.',
            'calle.max' => 'La calle no puede superar los 100 caracteres.',
            'casa_numero.max' => 'El número de casa no puede superar los 99999.',
            'dpto.max' => 'El departamento no puede superar los 10 caracteres.',
            'piso.max' => 'El piso no puede superar los 99.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email ingresado no es válido.',
            'email.max' => 'El email no puede superar los 100 caracteres.',
            'formacion_academica.required' => 'La formación académica es obligatoria.',
            'formacion_academica.max' => 'La formación académica no puede superar los 150 caracteres.',
            'observaciones.max' => 'Las observaciones no pueden superar los 500 caracteres.',
            'codigo_postal.max' => 'El código postal no puede superar los 10 caracteres.',
        ];
    }
}
