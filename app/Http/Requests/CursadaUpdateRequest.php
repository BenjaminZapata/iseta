<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CursadaUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'condicion' => ['required', 'numeric', 'between:0,6'],
            'aprobada' => ['required', 'numeric', 'between:1,5'],
            'nota' => ['nullable', 'numeric', 'between:4,10'],
            'observaciones' => ['nullable', 'string', 'max:20'],
            'primer_cuatrimestre_nota' => ['nullable', 'numeric', 'between:0,10'],
            'segundo_cuatrimestre_nota' => ['nullable', 'numeric', 'between:0,10'],
        ];
    }
}
