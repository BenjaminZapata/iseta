<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarAlumnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'dni' => ['required','numeric','max:999999999'],
            'nombre' => ['required','regex:/[a-zA-Z0-9\s]+/'],
            'apellido' => ['required','regex:/[a-zA-Z0-9\s]+/'],
            'fecha_nacimiento' => ['required','date','before:now'],
            'ciudad' => ['nullable'],
            'calle' => ['nullable'],
            'casa_numero' => ['nullable','numeric','max:100000'],
            'dpto' => ['nullable'],
            'piso' => ['nullable'],
            'estado_civil' => ['required'],
            'email' => ['nullable'],
            'nombre_institucion_secundario' => ['required','string','max:255','^[A-Za-záéíóúÁÉÍÓÚÑñ\s\d]+$
'],
            'titulo_anterior' => ['nullable'],
            'becas' => ['nullable'],
            'observaciones' => ['nullable'],
            'telefono1' => ['nullable','numeric'],
            'telefono2' => ['nullable','numeric'],
            'telefono3' => ['nullable','numeric'],
            'codigo_postal' => ['nullable','alpha_num'],
            'estado' => ['required']
        ];
    }
    public function messages()
    {
        return [
            'fecha_nacimiento.before' => 'El campo debe ser menor que la fecha actual.',
        ];
    }
}
