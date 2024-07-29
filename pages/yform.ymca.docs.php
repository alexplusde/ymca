<div class="row">
	<div class="col-12">

		<?php

		echo \rex_view::title(\rex_i18n::msg('yform'));

		$tables = rex_sql::factory()->getArray("SELECT table_name FROM rex_yform_table ORDER BY table_name");
		$t = [];
		foreach ($tables as $table) {
		    $t[] = $table['table_name'];
		}

		foreach ($t as $table) {

		    $results = rex_sql::factory()->getArray("SELECT `id`, `table_name`, `prio`, `type_name`, `type_id`, `db_type`, `name`, `label`, `notice` FROM `rex_yform_field` WHERE `type_name` != 'validate' AND `table_name` = '$table' ORDER BY `prio`");

		    $readmeCode = '
# Die Klasse `MeineKlasse`

Kind-Klasse von `rex_yform_manager_dataset`, damit stehen alle Methoden von YOrm-Datasets zur Verfügung. Greift auf die Tabelle `MeineTabelle` zu.

> Es werden nachfolgend zur die durch dieses Addon ergänzte Methoden beschrieben. Lerne mehr über YOrm und den Methoden für Querys, Datasets und Collections in der [YOrm Doku](https://github.com/yakamara/yform/blob/master/docs/04_yorm.md)

## Alle Einträge erhalten

```php
$entries = MeineKlasse::query()->find(); // YOrm-Standard-Methode zum Finden von Einträgen, lässt sich mit where(), Limit(), etc. einschränken und Filtern.
```

## Methoden und Beispiele
';

		    foreach ($results as $result) {

		        if ($result['type_name'] === 'fieldset') {
		            continue;
		        }
                
		        if ($result['type_name'] === 'html') {
		            continue;
		        }
                
		        if ($result['type_id'] === 'value') {
		            $className = \Alexplusde\Ymca\Docs::toClassName($result['table_name']);
		            $methodName = \Alexplusde\Ymca\Docs::toCamelCase($result['name']);

		            // Mapping der db_type zu PHP-Typen
		            $methodMap = [
		                'be_link' => 'be_link',
		                'be_manager_relation' => 'relation',
		                'be_manager_collection' => 'collection',
		                'be_media' => 'be_media',
		                'be_media_preview' => 'be_media',
		                'be_table' => 'be_table',
		                'be_user' => 'be_user',
		                'checkbox' => 'checkbox',
		                'choice_status' => 'choice',
		                'datestamp' => 'datestamp',
		                'datetime' => 'datetime',
		                'domain' => 'domain',
		                'integer' => 'int',
		                'number' => 'number',
		                'prio' => 'integer',
		                'text' => 'value',
		                'textarea' => 'textarea',
		                'time' => 'time',
		            ];
		            $defaultMethod = 'value';
                    

		            $methodTemplate = \Alexplusde\Ymca\Docs::getTypeTemplate($methodMap[$result['type_name']] ?? $defaultMethod);

					if (strpos($result['label'], 'translate:') === 0) {
		                $translationKey = substr($result['label'], strlen('translate:'));
		                $result['label'] = rex_i18n::msg($translationKey);
		            }
					if (strpos($result['notice'], 'translate:') === 0) {
		                $translationKey = substr($result['notice'], strlen('translate:'));
		                $result['notice'] = rex_i18n::msg($translationKey);
		            }

		            $readmeCode .= sprintf(
		                $methodTemplate,
						$className,
						$methodName,
		                $result['name'],
		                $result['label'],
		                $result['notice'],
		            );
		        }
                
		    }

?>

		<section class="rex-page-section">


			<div class="panel panel-default">

				<header class="panel-heading">
					<div class="panel-title"><?= $table ?></div>
				</header>

				<div class="panel-body">
					<p><strong>Erstelle in deinem Addon eine <code>/docs/##_<?= $table ?>.md</code> mit folgendem Inhalt:</strong></p>
					<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme="darcula" data-codemirror-mode="php"><?= $readmeCode ?></textarea>
				</div>
			</div>


		</section>

		<?php
		}

		?>

	</div>
</div>
