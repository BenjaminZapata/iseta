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
    'dni' => ['required', 'numeric'],
    'nombre' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
    'apellido' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
    'fecha_nacimiento' => ['required', 'date'],
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
    'lugar_nacimiento' => ['nullable', 'string', 'max:255'],
    ];
    }
}
