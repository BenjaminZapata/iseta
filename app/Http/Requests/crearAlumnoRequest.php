<?php

namespace App\Http\Requests;

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
            'dni' => ['required', 'numeric', 'max:255'],
            'nombre' => ['required'],
            'apellido' => ['required'],
            'fecha_nacimiento' => ['required', 'date', 'before:now'],
            'ciudad' => ['nullable'],
            'calle' => ['nullable'],
            'casa_numero' => ['nullable', 'numeric', 'max:100000'],
            'dpto' => ['nullable'],
            'piso' => ['nullable'],
            'estado_civil' => ['required'],
            'email' => ['nullable'],
            'nombre_institucion_secundario' => ['nullable', 'string', 'max:255'],
            'titulo_anterior' => ['nullable'],
            'becas' => ['nullable'],
            'observaciones' => ['nullable'],
            'telefono1' => ['nullable', 'numeric'],
            'telefono2' => ['nullable', 'numeric'],
            'telefono3' => ['nullable', 'numeric'],
            'codigo_postal' => ['nullable', 'alpha_num'],
            'estado' => ['nullable'],
            ' titulo_secundario ' => 'required|in:Fotocopia del título original secundario,Certificado de constancia de título en trámite,Constancia de alumno del último año del nivel secundario,No entregado',   
            'genero' => ['required']

        ];
    }
    public function messages()
    {
        return [
            'fecha_nacimiento.before' => 'El campo debe ser menor que la fecha actual.',
        ];
    }
}
