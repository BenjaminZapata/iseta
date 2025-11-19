<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarMesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mesa = $this->route('mesa');

        return [
            'prof_presidente' => 'required|not_in:,0',
            'prof_vocal_1' => 'required|not_in:,0',
            'prof_vocal_2' => 'nullable',
            'llamado' => 'required|in:1,2',
            'fecha' => 'required|date',

            'observaciones' => [
                'nullable',
                'max:150',
                function ($attribute, $value, $fail) use ($mesa) {

                    // Solo validar si cambiaron el presidente
                    if ($mesa && $mesa->prof_presidente != $this->prof_presidente) {

                        // 1️⃣ No escribió nada
                        if (empty($value)) {
                            return $fail('Debe ingresar una observación si cambió el profesor presidente de la mesa.');
                        }

                        // 2️⃣ Escribió exactamente lo mismo que ya tenía
                        if ($mesa->observaciones === $value) {
                            return $fail('Debe modificar la observación para explicar el cambio del profesor presidente.');
                        }
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'prof_presidente.required' => 'El profesor presidente es obligatorio.',
            'prof_vocal_1.required' => 'El profesor vocal 1 es obligatorio.',
            'llamado.required' => 'Debe seleccionar el número de llamado.',
            'fecha.required' => 'Debe ingresar la fecha del llamado.',
            'fecha.date' => 'La fecha no es válida.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 150 caracteres.',
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
