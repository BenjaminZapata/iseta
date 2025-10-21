<?php

namespace App\Http\Requests;

use App\Rules\Telefono;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'max_digits:10',
            ],
            'nombre' => ['required', 'string', 'max:30'],
            'apellido' => ['required', 'string', 'max:30'],
            'fecha_nacimiento' => [
                'required',
                'date',
                Rule::date()->before(now()->subYear(18)),
            ],
            'estado' => ['required', 'integer', 'between:0,1'],
            'ciudad' => ['nullable', 'string', 'max:30'],
            'calle' => ['nullable', 'string', 'max:30'],
            'casa_numero' => ['nullable', 'numeric', 'max_digits:4'],
            'dpto' => ['nullable', 'string', 'max:5'],
            'piso' => ['nullable', 'integer', 'between:0,15'],
            'estado_civil' => ['required', 'integer', 'between:0,5'],
            'email' => ['required', 'email', 'max:50'],
            'nombre_institucion_secundario' => ['nullable', 'string', 'max:255'],
            'titulo_anterior' => ['nullable', 'string', 'max:50'],
            'becas' => ['nullable', 'integer', 'between:0,9'],
            'telefono1' => ['required', new Telefono],
            'telefono2' => ['nullable', new Telefono],
            'codigo_postal' => ['nullable', 'string', 'max:10'],
            'titulo_secundario' => ['required', 'integer', 'between:0,3'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'fecha_nacimiento.before' => 'El campo debe ser menor que la fecha actual.',
            'dni.unique' => 'Ya hay un alumno con ese DNI.',
            'dni.min_digits' => 'El campo debe tener al menos 7 dígitos.',
            'dni.max_digits' => 'El campo debe tener 10 dígitos.',

        ];
    }
}
