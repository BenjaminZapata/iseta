<?php

namespace App\Http\Requests;

use App\Rules\Telefono;
use Illuminate\Foundation\Http\FormRequest;

class CrearAlumnoRequest extends FormRequest
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
            'dni' => ['required', 'numeric', 'max_digits:9', 'unique:alumnos,dni'],
            'nombre' => ['required', 'string', 'max:30'],
            'apellido' => ['required', 'string', 'max:30'],
            'fecha_nacimiento' => ['required', 'date', 'before:now'],
            'ciudad' => ['nullable', 'string', 'max:30'],
            'calle' => ['nullable', 'string', 'max:30'],
            'ciudad_nacimiento' => ['nullable', 'string', 'max:30'],
            'dpto' => ['nullable', 'string', 'max:5'],
            'piso' => ['nullable', 'integer', 'between:0,15'],
            'estado_civil' => ['nullable', 'integer', 'between:0,5'],
            'email' => ['required', 'email', 'max:50'],
            'nombre_institucion_secundario' => ['nullable', 'string', 'max:255'],
            'titulo_anterior' => ['nullable', 'string', 'max:255'],
            'becas' => ['nullable', 'integer', 'between:0,9'],
            'observaciones' => ['nullable'],
            'telefono_1' => ['required', new Telefono],
            'telefono_2' => ['nullable', new Telefono],
            'codigo_postal' => ['nullable', 'alpha_num', 'max:10'],
            'titulo_secundario' => ['required', 'integer', 'between:0,4'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:30'],
            'casa_numero' => ['nullable', 'numeric'],
            // luar de nacimiento, determinar si contiene espacios, limite de caracteres y ademas copiarlo tal cual en EditarAlumnooRequest.
        ];
    }

    public function messages()
    {
        return [
            'fecha_nacimiento.before' => 'El campo debe ser menor que la fecha actual.',
        ];
    }
}
