<?php

namespace App\Http\Controllers\Utils;

class CustomValidator
{
    public static function isValidState($state)
    {
        $validStates = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

        return in_array(strtoupper($state), $validStates);
    }

    // Outras funções utilitárias podem ser adicionadas aqui, se necessário

    public static function removeNumbers($value)
    {
        return preg_replace('/\d+/', '', $value);
    }

    public static function generateSlug($string)
    {
        // Converter para minúsculas
        $string = strtolower($string);

        // Remover caracteres especiais
        $string = preg_replace('/[^a-z0-9\s]/', '', $string);

        // Substituir espaços por traços
        $string = str_replace(' ', '-', $string);

        // Limitar o comprimento
        $string = substr($string, 0, 255);

        // Remover traços duplicados
        $string = preg_replace('/-+/', '-', $string);

        return $string;
    }
}