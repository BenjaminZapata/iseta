<?php

namespace App\Http\Requests;

use App\Rules\Resolucion;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class EditarCarreraRequest extends FormRequest
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
            "anio_apertura" => [
                'required',
                'numeric',
            ],
            'anio_fin' => ['nullable', 'integer', 'gt:anio_apertura', 'max_digits:4'],
            'observaciones' => ['nullable', 'string', 'max:255'],
            "vigente" => ['nullable', 'integer', 'between:0,1'],
            "resolucion_archivo" => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'anio_fin.gt' => 'El año de cierre debe ser posterior al año de apertura.',
            'anio_fin.max_digits' => 'El año de cierre no debe tener más de 4 dígitos.',
        ];
    }
}