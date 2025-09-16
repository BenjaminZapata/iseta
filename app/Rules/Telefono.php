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

        // Validar que el + esté solo al inicio si existe
        if (substr_count($value, '+') > 1 || (strpos($value, '+') > 0)) {
            $fail("El campo $attribute solo puede contener un '+' al inicio.");
            return;
        }

        // Contar guiones
        $dashCount = substr_count($value, '-');
        if ($dashCount < 1 || $dashCount > 2) {
            $fail("El campo $attribute debe contener al menos 1 y como máximo 2 guiones (-).");
            return;
        }

        // Verificar que no haya guiones consecutivos ni al inicio/final
        if (preg_match('/(^-|--|-$)/', $value)) {
            $fail("Los guiones no pueden estar al inicio, al final ni consecutivos.");
            return;
        }

        // Verificar paréntesis balanceados
        $open = substr_count($value, '(');
        $close = substr_count($value, ')');
        if ($open !== $close) {
            $fail("Los paréntesis deben estar balanceados.");
            return;
        }

        // Validar caracteres permitidos
        if (!preg_match('/^\+?[0-9a-zA-Z\-\(\) ]+$/', $value)) {
            $fail("El campo $attribute solo puede contener números, letras, guiones (-), paréntesis () y espacios, con '+' solo al inicio.");
        }
    }
}
