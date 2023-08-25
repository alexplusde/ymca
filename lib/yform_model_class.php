<?php

class ymca
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
        $typeTemplates = [
            'value' => '
    /** @api */
    public function get%s() : %s {
        return $this->getValue("%s");
    }
            ',
            'datetime' => '
    /** @api */
    public function get%s() : string {
        return $this->getValue("%s");
    }
            ',
            'relation' => '
    /** @api */
    public function get%1$s() : ?rex_yform_manager_dataset {
        return $this->getRelatedDataset("%3$s");
    }
            ',
            'collection' => '
    /** @api */
    public function get%1$s() : ?rex_yform_manager_collection {
        return $this->getRelatedCollection("%3$s");
    }
            ',
        ];

        return $typeTemplates[$type_name] ?? '';
    }

}
