<?php

namespace App\Http\Requests;

use App\Rules\Resolucion;
use Illuminate\Foundation\Http\FormRequest;

class CrearCarreraRequest extends FormRequest
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
    public function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:151'],
            'resolucion' => ['required', new Resolucion, 'unique:carreras,resolucion'],
            'anio_apertura' => [
                'required',
                'max_digits:4',
                'numeric',
            ],
            'resolucion_archivo' => ['nullable',
                'file',
                'mimes:pdf',
                'max:2048',
            ],
            'anio_fin' => ['nullable', 'integer', 'gt:anio_apertura', 'max_digits:4'],
            'observaciones' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'anio_fin.gte' => 'El año de cierre debe ser posterior al año de apertura.',
            'anio_apertura.between' => 'El año de apertura debe estar entre '.(now()->year - 1).' y '.(now()->year + 1).'.',
            'anio_apertura.numeric' => 'El año de apertura debe ser un número válido.',
            'anio_fin.max_digits' => 'El año de cierre no debe tener más de 4 dígitos.',
        ];
    }

    public function attributes()
    {
        return [
            'anio_fin' => 'año de cierre',
            'anio_apertura' => 'año de apertura',
        ];
    }
}
