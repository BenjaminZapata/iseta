<?php

namespace App\Http\Requests;

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
            "nombre" => ['required'],
            "resolucion" => ['required'],
            "anio_apertura" => ['required','numeric'],
            "anio_fin" => ['nullable','numeric'],
            "observaciones" => ['nullable'],
            "vigente" => ['nullable'],
            "resolucion_archivo" => ['nullable']
        ];
    }
}
