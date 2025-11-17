<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Mesa;

class EditarMesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'prof_presidente' => 'required|not_in:,0',
            'prof_vocal_1' => ['required', 'not_in:,0'],
            'prof_vocal_2' => ['nullable'],
            'llamado' => ['required', 'in:1,2'],
            'fecha' => ['required', 'date'],
            'observaciones' => ['nullable', 'max:150'],
        ];

        // 🧩 Obtener la mesa original desde el parámetro de la ruta
        $mesa = $this->route('mesa'); // Laravel la inyecta automáticamente

        if ($mesa && $mesa->prof_presidente != $this->prof_presidente) {
            // ⚙️ Si cambió el presidente, observaciones pasa a ser obligatoria
            $rules['observaciones'][] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'prof_presidente.required' => 'El profesor presidente es obligatorio.',
            'prof_vocal_1.required' => 'El profesor vocal 1 es obligatorio.',
            'prof_vocal_2.required' => 'El profesor vocal 2 es obligatorio.',
            'llamado.required' => 'Debe seleccionar el número de llamado.',
            'fecha.required' => 'Debe ingresar la fecha del llamado.',
            'fecha.date' => 'La fecha no es válida.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 150 caracteres.',
            'observaciones.required' => 'Debe ingresar una observación si cambió el profesor presidente de la mesa.',
        ];
    }

    public function attributes()
    {
        return [
            'prof_presidente' => 'Presidente de mesa',
            'prof_vocal_1' => 'Profesor vocal 1',
            'prof_vocal_2' => 'Profesor vocal 2',
        ];
    }
}
