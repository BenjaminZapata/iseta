<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarConfigRequest extends FormRequest
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
            'filas_por_tabla' => ['nullable', 'numeric'],
            'horas_habiles_inscripcion' => ['required', 'min:0'],
            'horas_habiles_desinscripcion' => ['required', 'min:0'],
            'fecha_inicial_rematriculacion' => ['nullable'],
            'fecha_final_rematriculacion' => ['nullable'],
            'anio_remat' => ['required'],
            'anio_ciclo_actual' => ['required'],
            'fecha_limite_desrematriculacion' => ['nullable'],
            'diferencia_llamados' => ['required'],
            'correo1' => ['nullable'],
            'correo2' => ['nullable'],
            'correo3' => ['nullable'],
            'telefono1' => ['nullable'],
            'telefono2' => ['nullable'],
            'telefono3' => ['nullable'],
            'mas_info1' => ['nullable'],
            'mas_info2' => ['nullable'],
            'mas_info3' => ['nullable'],
            'nombre' => ['nullable'],
            'alumno_puede_anotarse_mesa' => ['nullable'],
            'alumno_puede_bajarse_mesa' => ['nullable'],
            'alumno_puede_anotarse_cursada' => ['nullable'],
            'alumno_puede_bajarse_cursada' => ['nullable'],
            'alumno_puede_anotarse_libre' => ['nullable'],
            'modo_seguro' => ['nullable'],
        ];
    }
}
