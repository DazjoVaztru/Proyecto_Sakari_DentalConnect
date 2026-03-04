<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Capitaliza la primera letra de cada palabra y convierte el resto a minúsculas
     * Ej: "MarCo AnTONio OsoRIO" -> "Marco Antonio Osorio"
     * También limpia los espacios múltiples entre palabras y a los extremos.
     *
     * @param string|null $string
     * @return string|null
     */
    public static function capitalizeName(?string $string): ?string
    {
        if (empty($string)) {
            return null;
        }

        // 1. Eliminar espacios múltiples intermedios y a los extremos
        $string = preg_replace('/\s+/', ' ', trim($string));

        // 2. Convertir todo a minúsculas primero (manejando correctamente tildes y eñes)
        $string = mb_strtolower($string, 'UTF-8');

        // 3. Capitalizar cada palabra (manejando correctamente tildes y eñes)
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }
}
