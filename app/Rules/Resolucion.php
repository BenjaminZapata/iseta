<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Resolucion implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string) $value;

        $parts = explode('/', $value);

        switch (true) {
            // Longitud total
            case strlen($value) > 10:
                $fail("El campo $attribute no puede superar los 10 caracteres.");
                break;

            // Espacios
            case str_contains($value, ' '):
                $fail("El campo $attribute no debe contener espacios.");
                break;

            // Formato (debe tener exactamente 2 partes)
            case count($parts) !== 2:
                $fail("El campo $attribute debe tener el formato 'XXX/YY' o 'XXXX/YYYY'.");
                break;

            // Resolución: solo números
            case !ctype_digit($parts[0]):
                $fail("El número de resolución en $attribute debe contener solo números.");
                break;

            // Resolución: longitud entre 3 y 4
            case strlen($parts[0]) < 3 || strlen($parts[0]) > 4:
                $fail("El número de resolución en $attribute debe tener entre 3 y 4 números.");
                break;

            // Año: solo números
            case !ctype_digit($parts[1]):
                $fail("El año de emisión en $attribute debe contener solo números.");
                break;

            // Año: longitud debe ser 2 o 4
            case !in_array(strlen($parts[1]), [2, 4], true):
                $fail("El año de emisión en $attribute debe tener exactamente 2 o 4 números.");
                break;
        }
    }
}
