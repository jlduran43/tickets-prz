<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RutChileno implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rut = strtoupper(preg_replace('/[^0-9K]/', '', $value));

        if (strlen($rut) < 2) {
            $fail('El RUT ingresado no es válido.');
            return;
        }

        $cuerpo = substr($rut, 0, -1);
        $dv = substr($rut, -1);

        if (!ctype_digit($cuerpo)) {
            $fail('El RUT ingresado no es válido.');
            return;
        }

        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $suma += intval($cuerpo[$i]) * $multiplicador;

            $multiplicador++;

            if ($multiplicador > 7) {
                $multiplicador = 2;
            }
        }

        $resto = $suma % 11;
        $resultado = 11 - $resto;

        if ($resultado === 11) {
            $dvCalculado = '0';
        } elseif ($resultado === 10) {
            $dvCalculado = 'K';
        } else {
            $dvCalculado = (string) $resultado;
        }

        if ($dv !== $dvCalculado) {
            $fail('El RUT ingresado no es válido.');
        }
    }
}