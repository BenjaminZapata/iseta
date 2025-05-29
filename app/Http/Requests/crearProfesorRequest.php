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
            'dni' => ['required','numeric'],
            'nombre' => ['required','regex:/[a-zA-Z0-9\s]+/'],
            'apellido' => ['required','regex:/[a-zA-Z0-9\s]+/'],
            'fecha_nacimiento' => ['required','date'],
            'ciudad' => ['required'],
            'calle' => ['required','alpha_num'],
            'casa_numero' => ['numeric'],
            'dpto' => ['nullable'],
            'piso' => ['nullable'],
            'estado_civil' => ['required'],
            'email' => ['required'],
            'formacion_academica' => ['nullable'],
            'titulo' => ['nullable','numeric'],
            'observaciones' => ['nullable'],
            'telefono1' => ['nullable'],
            'telefono2' => ['nullable'],
            'telefono3' => ['nullable'],
            'codigo_postal' => ['required','numeric']
        ];
    }
}
