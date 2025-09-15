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
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'regex:/^[^\d]*$/'],
            "resolucion" => ['required'],
            "anio_apertura" => ['required', 'numeric', 'min:1900', 'max:2100'],
            "anio_fin" => ['nullable', 'numeric','min:1900', 'max:2100' ,'gte:anio_apertura'],
            "observaciones" => ['nullable']
        ];
    }
}
