<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearMesaRequest extends FormRequest
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
            'carrera' => ['required'],
            'id_asignatura' => ['required'],
            'prof_presidente' => ['required'],
            'prof_vocal_1' => ['required'],
            'prof_vocal_2' => ['required'],
            'cantidad_llamados' => ['required'],
            'fecha_1' => ['required', 'date'],
            'fecha_2' => ['exclude_if:cantidad_llamados,1', 'required', 'date']
        ];
    }
}
