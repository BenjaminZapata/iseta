<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarMesaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'prof_presidente'=>['required'],
            'prof_vocal_1'=>['required'],
            'prof_vocal_2'=>['required'],
            'llamado'=>['required','in:1,2'],
            'fecha'=>['required','date'],
            'observaciones' => ['nullable', 'max:150']
        ];
    }
}
