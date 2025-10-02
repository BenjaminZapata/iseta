<?php

namespace App\Http\Requests;

use App\Rules\Telefono;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;



class CrearProfesorRequest extends FormRequest
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
            'dni' => ['required', 'integer', 'gte:0', 'unique:profesores,dni', 'max_digits:10'],
            'nombre' => ['required', 'string', 'max:30'],
            'apellido' => ['required', 'string', 'max:30'],
            'fecha_nacimiento' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->age < 18) {
                        $fail('El profesor debe tener al menos 18 años.');
                    }
                },
            ],
            'ciudad' => ['nullable', 'string', 'max:30'],
            'calle' => ['nullable', 'string', 'max:30'],
            'casa_numero' => ['nullable', 'numeric', 'max_digits:4'],
            'dpto' => ['nullable', 'string', 'max:5'],
            'piso' => ['nullable', 'numeric', 'max_digits:15'],
            'estado_civil' => ['nullable'],
            'email' => ['required', 'email', 'max:50'],
            'formacion_academica' => ['required', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', 'max:150'],
            'titulo' => ['nullable', 'numeric'],
            'observaciones' => ['nullable', 'string', 'max:150'],
            'telefono1' => ['required', new Telefono, 'max:30'],
            'telefono2' => ['nullable', new Telefono, 'max:30'],
            'codigo_postal' => ['nullable', 'alpha_num', 'max:10'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:255'],
            'anio_ingreso' => ['required', 'date_format:Y', 'before_or_equal:now', 'after:1980'],
        ];
    }

    public function messages()
    {
        return [
            'ciudad.max' => 'El nombre de la ciudad es demasiado largo. Máximo 30 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'casa_numero.max_digits' => 'El número de casa no puede tener más de 4 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 30 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.max' => 'El apellido no puede tener más de 30 caracteres.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.max_digits' => 'El DNI no puede tener más de 10 caracteres.',
            'fecha_nacimiento.before_or_equal' => 'El profesor debe tener al menos 18 años.',
            'email.email' => 'El email ingresado no es válido.',
            'email.max' => 'El email no puede tener más de 50 caracteres.',
            'formacion_academica.required' => 'La formación académica es obligatoria.',
            'formacion_academica.regex' => 'La formación académica solo puede contener letras y espacios.',
            'formacion_academica.max' => 'La formación académica no puede tener más de 150 caracteres.',
            'telefono_1.required' => 'El teléfono 1 es obligatorio.',
            'telefono_1.max' => 'El teléfono 1 no puede tener más de 30 caracteres.',
            'telefono_2.max' => 'El teléfono 2 no puede tener más de 30 caracteres.',
            'año_ingreso.required' => 'El año de ingreso es obligatorio.',
        ];
    }

    public function attributes()
    {
        return [
            'anio_ingreso' => 'año de ingreso'
        ];
    }
}
