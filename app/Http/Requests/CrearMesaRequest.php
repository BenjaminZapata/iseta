<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
 use Illuminate\Support\Facades\DB;

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
    $rules = [
        'carrera' => 'required|not_in:0,any',
        'id_asignatura' => 'required|not_in:0,any',
        'prof_presidente' => 'required|not_in:,0',
        'prof_vocal_1' => ['required', 'not_in:,0'],
        'prof_vocal_2' => ['nullable',],
        'cantidad_llamados' => ['required'],
        'fecha_1' => ['required', 'date'],
        'fecha_2' => ['exclude_if:cantidad_llamados,1', 'required', 'date'],
        'observaciones' => ['nullable', 'max:150'],
    ];

    // 🧩 Buscamos el profesor original vinculado en la tabla intermedia
    $profesorOriginal = DB::table('carrera_asignatura_profesor')
        ->where('id_carrera', $this->carrera)
        ->where('id_asignatura', $this->id_asignatura)
        ->value('id_profesor'); // devuelve el id del profesor vinculado

    // ⚙️ Si existe un profesor vinculado y el presidente elegido es distinto → observaciones obligatoria
    if ($profesorOriginal && $profesorOriginal != $this->prof_presidente) {
        $rules['observaciones'][] = 'required';
    }

    return $rules;
}


    public function messages(): array
    {
        return [
            'carrera.required' => 'Debe seleccionar una carrera.',
        'carrera.min' => 'Debe seleccionar una carrera.',
        'id_asignatura.required' => 'Debe seleccionar una asignatura.',
            'prof_presidente.required' => 'El profesor presidente es obligatorio.',
            'prof_vocal_1.required' => 'El profesor vocal 1 es obligatorio.',
            'cantidad_llamados.required' => 'La cantidad de llamados es obligatoria.',
            'fecha_1.required' => 'La fecha del primer llamado es obligatoria.',
            'fecha_1.date' => 'La fecha del primer llamado no es una fecha válida.',
            'fecha_2.required' => 'La fecha del segundo llamado es obligatoria cuando la cantidad de llamados es 2.',
            'fecha_2.date' => 'La fecha del segundo llamado no es una fecha válida.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 150 caracteres.',
            'observaciones.required' => 'Debe ingresar una observación si el profesor presidente no coincide con el profesor asignado originalmente.',
        ];
    }

       public function attributes()
    {
        return [
            'id_asignatura' => 'asignatura',
            'prof_presidente' => 'Presidente de mesa',
            'prof_vocal_1' => 'Profesor Vocal 1',
            'prof_vocal_2' => 'Profesor Vocal 2',
        ];
    }
}
