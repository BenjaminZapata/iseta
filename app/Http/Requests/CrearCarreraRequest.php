<?php

namespace App\Http\Requests;

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
        'nombre' => ['required', 'string', 'max:255'],
        'resolucion' => ['nullable', 'string', 'max:255'],
        'anio_apertura' => ['required', 'integer', 'min:1900'],
        'anio_fin' => ['nullable', 'integer', 'min:2000', 'gte:anio_apertura'],
        'observaciones' => ['nullable', 'string'],
    ];
}

public function messages()
{
    return [
        'anio_fin.min' => 'El año de cierre debe ser igual o posterior al año 2000.',
        'anio_fin.gte' => 'El año de cierre debe ser igual o posterior al año de apertura.',
    ];
}


}
