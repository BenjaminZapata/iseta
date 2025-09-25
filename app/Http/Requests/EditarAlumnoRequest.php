<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditarAlumnoRequest extends FormRequest
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
            'dni' => [
                'required',
                Rule::unique('alumnos', 'dni')->ignore($this->route('alumno')->id),
                'numeric',
                'min_digits:7',
                'max_digits:10'
            ],
            'nombre' => ['required', 'string', 'max:30'],
            'apellido' => ['required', 'string', 'max:30'],
            'fecha_nacimiento' => ['required', 'date', 'before:now'],
            'ciudad' => ['nullable'],
            'calle' => ['nullable'],
            'casa_numero' => ['nullable', 'numeric', 'max:100000'],
            'dpto' => ['nullable'],
            'piso' => ['nullable'],
            'estado_civil' => ['required'],
            'email' => ['nullable', 'email', 'max:255'],
            'nombre_institucion_secundario' => ['nullable', 'string', 'max:255'],
            'titulo_anterior' => ['nullable'],
            'becas' => ['nullable', 'integer', 'gte:0'],
            'observaciones' => ['nullable'],
            'telefono1' => ['nullable', 'numeric'],
            'telefono2' => ['nullable', 'numeric'],
            'codigo_postal' => ['nullable', 'alpha_num'],
            'estado' => ['nullable'],
            'titulo_secundario' => ['nullable'],
            'genero' => ['required'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:255'],
        ];
    }
    public function messages()
    {
        return [
            'fecha_nacimiento.before' => 'El campo debe ser menor que la fecha actual.',
            'dni.unique' => 'Ya hay un alumno con ese DNI.',
            'dni.min_digits' => 'El campo debe tener al menos 7 dígitos.',
            'dni.max_digits' => 'El campo debe tener 10 dígitos.'

        ];
    }
}
