<div class="row">
	<div class="col-12">

		<?php

echo \rex_view::title(\rex_i18n::msg('yform'));


		$tables = rex_sql::factory()->getArray("SELECT table_name FROM rex_yform_table ORDER BY table_name");
		$t = [];
		foreach ($tables as $table) {
		    $t[] = $table['table_name'];
		}

		$bootTemplate = '
if (rex_addon::get(\'yform\')->isAvailable() && !rex::isSafeMode()) {
	rex_yform_manager_dataset::setModelClass(
		\'rex_%1$s\',
		%1$s::class, // Hier anpassen, falls Namespace verwendet wird
	);
}'
		;
		$classTemplate = '

/* Falls gewünscht, namespace an das Projekt oder eigenes GitHub-Repository anpassen - und in der boot.php! */
// namespace Project\Addon;

/* Nicht benötigte Klassen entfernen */
use rex_article;
use rex_user;
use rex_media;
use yrewrite_domain;
use rex_yform_manager_collection;
use rex_yform_manager_dataset;

class %1$s extends rex_yform_manager_dataset {
	%2$s
}';


		foreach ($t as $table) {

		    $results = rex_sql::factory()->getArray("SELECT `id`, `table_name`, `prio`, `type_name`, `type_id`, `db_type`, `name`, `label` FROM `rex_yform_field` WHERE `type_name` != 'validate' AND `table_name` = '$table' ORDER BY `prio`");

		    $generatedClasses = '';

		    foreach ($results as $result) {

		        if ($result['type_name'] === 'fieldset') {
		            continue;
		        }
                
		        if ($result['type_name'] === 'html') {
		            continue;
		        }
                
		        if ($result['type_id'] === 'value') {
		            $className = \Alexplusde\Ymca\Yorm::toClassName($result['table_name']);
		            $methodName = \Alexplusde\Ymca\Yorm::toCamelCase($result['name']);

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
                    

		            $methodTemplate = \Alexplusde\Ymca\Yorm::getTypeTemplate($methodMap[$result['type_name']] ?? $defaultMethod);

		            // Default-Typ, falls kein passender db_type gefunden wird oder mehrere zulässig sind.
		            $typeMap = [
		                'bigint' => 'int',
		                'date' => '?string',
		                'datetime' => '?\DateTime',
		                'int' => 'int',
		                'tinyint(1)' => '?bool',
		                'text' => '?string',
		                'varchar(191)' => '?string',
		            ];
		            $defaultType = 'mixed';

		            $returnType = $typeMap[$result['db_type']] ?? $defaultType;

		            if (strpos($result['label'], 'translate:') === 0) {
		                $translationKey = substr($result['label'], strlen('translate:'));
		                $result['label'] = rex_i18n::msg($translationKey);
		            }

		            $generatedClasses .= sprintf(
		                $methodTemplate,
		                $methodName,
		                $returnType,
		                $result['name'],
		                $result['label'],
		                ""
		            );
		        }
                
		    }

		    $bootCode = "<?php " . sprintf($bootTemplate, $className) . "?>";
		    $classCode = "<?php " .sprintf($classTemplate, $className, $generatedClasses) . "?>";
		    ?>

		<section class="rex-page-section">


			<div class="panel panel-default">

				<header class="panel-heading">
					<div class="panel-title"><?= $table ?></div>
				</header>

				<div class="panel-body">
					<p><strong>1. In die <code>boot.php</code> muss folgender auskommentierte Code:</strong></p>
	<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme="eclipse" data-codemirror-mode="php"><?= $bootCode ?></textarea>

					<p><strong>2. Erstelle eine Datei
						<code>lib/<?= $className ?>.php</code> im
						<code>project</code>-Addon oder
						eigenen Addon mit folgendem Inhalt:
					</strong></p>
					<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme="darcula" data-codemirror-mode="php"><?= $classCode ?></textarea>
				</div>
			</div>


		</section>

		<?php
		}

		?>

	</div>
</div>
