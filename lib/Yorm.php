<?php

namespace Alexplusde\Ymca;

class Yorm
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
'value' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : %2$s {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(mixed $value) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
',
'checkbox' => '
    /* %4$s */
    /** @api */
    public function get%1$s(bool $asBool = false) : mixed {
        if($asBool) {
            return (bool) $this->getValue("%3$s");
        }
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(int $value = 1) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
            ',
'textarea' => '
    /* %4$s */
    /** @api */
    public function get%1$s(bool $asPlaintext = false) : %2$s {
        if($asPlaintext) {
            return strip_tags($this->getValue("%3$s"));
        }
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(mixed $value) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
            ',
'datetime' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?string {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(string $datetime) : self {
        $this->setValue("%3$s", $datetime);
        return $this;
    }
            ',
'be_media' => '
    /* %4$s */
    /** @api */
    public function get%1$s(bool $asMedia = false) : mixed {
        if($asMedia) {
            return rex_media::get($this->getValue("%3$s"));
        }
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(string $filename) : self {
        if(rex_media::get($filename)) {
            $this->setValue("%3$s", $filename);
        }
        return $this;
    }
            ',
'be_table' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?array {
        return json_decode($this->getValue("%3$s"), true);
    }
    /** @api */
    public function set%1$s(array|string $value) : self {
        if (is_array($value)) {
            $value = json_encode($value, JSON_PRETTY_PRINT);
        }
        $this->setValue("%3$s", $value);
        return $this;
    }
            ',
'datestamp' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?string {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(string $value) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
',
'int' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?int {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(int $value) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
',
'number' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?float {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(float $value) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
            ',
'prio' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?int {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(int $value) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
            ',
'time' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : string {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(string $value = "00:00") : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
            ',
'domain' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?yrewrite_domain {
        return yrewrite_domain::get($this->getValue("%3$s"));
    }
    /** @api */
    public function get%1$sId() : ?int {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(int $value) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
',
'be_user' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?rex_user {
        return rex_user::get($this->getValue("%3$s"));
    }
    /** @api */
    public function set%1$s(mixed $value) : self {
        $this->setValue("%3$s", $value);
        return $this;
    }
',
'be_link' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : ?rex_article {
        return rex_article::get($this->getValue("%3$s"));
    }
    public function get%1$sId() : ?int {
        return $this->getValue("%3$s");
    }
    public function get%1$sUrl() : ?string {
        if($article = $this->get%1$s()) {
            return $article->getUrl();
        }
    }
    /** @api */
    public function set%1$s(string $id) : self {
        if(rex_article::get($id)) {
            $this->getValue("%3$s", $id);
        }
        return $this;
    }
',
'relation' => '
    /* %4$s */
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
'choice' => '
    /* %4$s */
    /** @api */
    public function get%1$s() : mixed {
        return $this->getValue("%3$s");
    }
    /** @api */
    public function set%1$s(mixed $param) : mixed {
        $this->setValue("%3$s", $param);
        return $this;
    }
',
];

        return $typeTemplates[$type_name] ?? '';
    }

}
