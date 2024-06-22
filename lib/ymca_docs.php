<?php

class ymca_docs
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
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
echo $dataset->get%2$s();
```

### `set%2$s(mixed $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'checkbox' => '
### `get%2$s(bool $asBool = false)`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$wert = $dataset->get%2$s(true);
```

### `set%2$s(int $value = 1)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s(1);
$dataset->save();
```
',
        'textarea' => '
### `get%2$s(bool $asPlaintext = false)`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$text = $dataset->get%2$s(true);
```

### `set%2$s(mixed $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'datetime' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$datetime = $dataset->get%2$s();
```

### `set%2$s(string $datetime)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($datetime);
$dataset->save();
```
',
        'be_media' => '
### `get%2$s(bool $asMedia = false)`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$media = $dataset->get%2$s(true);
```

### `set%2$s(string $filename)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($filename);
$dataset->save();
```
',
        'be_table' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$tabelle = $dataset->get%2$s();
```

### `set%2$s(array|string $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'datestamp' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$datestamp = $dataset->get%2$s();
```

### `set%2$s(string $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'int' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$int = $dataset->get%2$s();
```

### `set%2$s(int $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'number' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$nummer = $dataset->get%2$s();
```

### `set%2$s(float $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'prio' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$prio = $dataset->get%2$s();
```

### `set%2$s(int $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'time' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$zeit = $dataset->get%2$s();
```

### `set%2$s(string $value = "00:00")`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'domain' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$domain = $dataset->get%2$s();
```

### `set%2$s(int $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'be_user' => '
### `get%2$s()`

Gibt folgenden Wert

    zurück

: %4$s

Beispiel:

```php
$dataset = %1$s::get($id);
$benutzer = $dataset->get%2$s();
```

### `set%2$s(mixed $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'be_link' => '
### `get%2$s(bool $asArticle = false)`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$artikel = $dataset->get%2$s(true);
```

### `set%2$s(string $id)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($id);
$dataset->save();
```
',
        'relation' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$beziehung = $dataset->get%2$s();
```

### `set%2$s(mixed $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'collection' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$sammlung = $dataset->get%2$s();
```

### `set%2$s(mixed $value)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($value);
$dataset->save();
```
',
        'choice' => '
### `get%2$s()`

Gibt den Wert für das Feld `%3$s` (%4$s) zurück: %5$s

Beispiel:

```php
$dataset = %1$s::get($id);
$auswahl = $dataset->get%2$s();
```

### `set%2$s(mixed $param)`

Setzt den Wert für das Feld `%3$s` (%4$s).

```php
$dataset = %1$s::create();
$dataset->set%2$s($param);
$dataset->save();
```
',
        ];

        return $typeTemplates[$type_name] ?? '';
    }


}
