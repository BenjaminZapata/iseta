<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Telefono implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string) $value;

        // Máximo 30 caracteres
        if (strlen($value) > 30) {
            $fail('El campo telefono no puede superar los 30 caracteres.');

            return;
        }

        // Validar caracteres permitidos: solo números, letras y un guion
        if (! preg_match('/^[0-9\-]+$/', $value)) {
            $fail('El campo telefono solo puede contener números, letras y un guion (-).');

            return;
        }

     // Solo validar cantidad de guiones si es telefono1 o telefono2
if (in_array($attribute, ['telefono1', 'telefono2'])) {
    // Determinar número legible
    $numero = $attribute === 'telefono1' ? '1' : '2';

    // Debe tener exactamente 1 guion
    if (substr_count($value, '-') !== 1) {
        $fail("El campo teléfono {$numero} debe contener exactamente 1 guion (-).");
        return;
    }
}


        // El guion no puede estar al inicio ni al final
        if (preg_match('/(^-|-$)/', $value)) {
            $fail('El guion no puede estar al inicio ni al final.');

            return;
        }

        // El guion debe estar después de al menos 2 números
        if (! preg_match('/^[0-9]{2,}-[0-9]+$/', $value)) {
            $fail('El guion debe estar después de al menos 2 números.');

        }
    }
}
