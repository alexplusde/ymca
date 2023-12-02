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
    public function get%1$s() : %1$s {
        return $this->getValue("%1$s");
    }
    /** @api */
    public function set%1$s(mixed $value) : self {
        $this->setValue("%1$s", $value);
        return $this;
    }
            ',
            'checkbox' => '
    /** @api */
    public function get%1$s(bool $asBool = false) : mixed {
        if($asBool) {
            return (bool) $this->getValue("%1$s");
        }
        return $this->getValue("%1$s");
    }
    /** @api */
    public function set%1$s(int $value = 1) : self {
        $this->setValue("%1$s", $value);
        return $this;
    }
            ',
            'textarea' => '
    /** @api */
    public function get%1$s(bool $asStripped = false) : %1$s {
        if($asStripped) {
            return strip_tags($this->getValue("%1$s");
        }
        return $this->getValue("%1$s");
    }
    /** @api */
    public function set%1$s(mixed $value) : self {
        $this->setValue("%1$s", $value);
        return $this;
    }
            ',
            'datetime' => '
    /** @api */
    public function get%1$s() : string {
        return $this->getValue("%1$s");
    }
    /** @api */
    public function set%1$s(string $datetime) : self {
        $this->setValue("%1$s", $datetime);
        return $this;
    }
            ',
            'be_media' => '
    /** @api */
    public function get%1$s(bool $asMedia = false) : string {
        if($asMedia) {
            return rex_media::get($this->getValue("%1$s"));
        }
        return $this->getValue("%1$s");
    }
    /** @api */
    public function set%1$s(string $filename) : self {
        if(rex_media::get($filename)) {
            $this->setValue("%1$s", $filename);
        }
        return $this;
    }
            ',
            'be_article' => '
    /** @api */
    public function get%1$s(bool $asArticle = false) : ?mixed {
        if($asArticle) {
            return rex_article::get($this->getValue("%1$s"));
        }
        return $this->getValue("%1$s");
    }
    public function get%1$sUrl() : ?string {
        if($article = $this->get%1$s()) {
            return $article->getUrl();
        }
    }
    /** @api */
    public function set%1$s(string $id) : self {
        if(rex_article::get($id)) {
            $this->setValue("%1$s", $id);
        }
        return $this;
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
