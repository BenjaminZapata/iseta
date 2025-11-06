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
            'fecha_2' => ['exclude_if:cantidad_llamados,1', 'required', 'date'],
            'observaciones' => ['nullable', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'carrera.required' => 'La carrera es obligatoria.',
            'id_asignatura.required' => 'La asignatura es obligatoria.',
            'prof_presidente.required' => 'El profesor presidente es obligatorio.',
            'prof_vocal_1.required' => 'El profesor vocal 1 es obligatorio.',
            'prof_vocal_2.required' => 'El profesor vocal 2 es obligatorio.',
            'cantidad_llamados.required' => 'La cantidad de llamados es obligatoria.',
            'fecha_1.required' => 'La fecha del primer llamado es obligatoria.',
            'fecha_1.date' => 'La fecha del primer llamado no es una fecha válida.',
            'fecha_2.required' => 'La fecha del segundo llamado es obligatoria cuando la cantidad de llamados es 2.',
            'fecha_2.date' => 'La fecha del segundo llamado no es una fecha válida.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 150 caracteres.',
        ];
    }
}
