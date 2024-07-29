<?php

namespace Alexplusde\Ymca;

class Dotlang
{
    public static function toCamelCase($input)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $input)));
    }
    public static function toClassName($input)
    {
        return str_replace('rex_', '', $input);
    }
    public static function getTypeTemplate($type_name)
    {
        $typeTemplates =
        [
            'default' => 'addonname_%3$s = %4$s',
            'choice' => 'addonname_%3$s = %4$s', // To Do: Alle Choice Values ausgeben
        ];

        return $typeTemplates[$type_name] ?? '';
    }


}
