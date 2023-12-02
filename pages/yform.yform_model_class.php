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
		%1$s::class,
	);
}'
		;
		$classTemplate = '
class %1$s extends \rex_yform_manager_dataset {
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
		            $className = ymca::toClassName($result['table_name']);
		            $methodName = ymca::toCamelCase($result['name']);

		            // Mapping der db_type zu PHP-Typen
		            $methodMap = [
		                'be_link' => 'be_link',
		                'be_manager_relation' => 'relation',
		                'be_manager_collection' => 'collection',
		                'be_media' => 'be_media',
		                'be_media_preview' => 'be_media',
		                'be_user' => 'be_user',
		                'checkbox' => 'checkbox',
		                'choice_status' => 'choice',
		                'datestamp' => 'datestamp',
		                'datetime' => 'datetime',
		                'domain' => 'domain',
		                'integer' => 'integer',
		                'number' => 'number',
		                'prio' => 'integer',
		                'text' => 'value',
		                'textarea' => 'textarea',
		                'time' => 'time',
		            ];
		            $defaultMethod = 'value';
                    

		            $methodTemplate = ymca::getTypeTemplate($methodMap[$result['type_name']] ?? $defaultMethod);

		            // Default-Typ, falls kein passender db_type gefunden wird oder mehrere zulässig sind.
		            $typeMap = [
		                'bigint' => 'integer',
		                'date' => '?string',
		                'datetime' => '?\DateTime',
		                'int' => 'integer',
		                'tinyint(1)' => '?bool',
		                'text' => '?string',
		                'varchar(191)' => '?string',
		            ];
		            $defaultType = 'mixed';

		            $returnType = $typeMap[$result['db_type']] ?? $defaultType;

		            $generatedClasses .= sprintf(
		                $methodTemplate,
		                $methodName,
		                $returnType,
		                $result['name']
		            );
		        }
                
		    }

		    $bootCode = sprintf($bootTemplate, $className);
		    $classCode = sprintf($classTemplate, $className, $generatedClasses);
		    ?>

		<section class="rex-page-section">


			<div class="panel panel-default">

				<header class="panel-heading">
					<div class="panel-title"><?= $table ?></div>
				</header>

				<div class="panel-body">
					<p># 1. In die <code>boot.php</code> muss folgender auskommentierte Code:</p>
					<pre class="pre-scrollable">
						<?= $bootCode ?>
					</pre>

					<p># 2. Erstelle eine Datei
						<code>lib/<?= $className ?>.php</code> im
						<code>project</code>-Addon oder
						eigenen Addon mit folgendem Inhalt:
					</p>

					<pre class="pre-scrollable">
						<?= $classCode ?>
					</pre>
				</div>
			</div>


		</section>

		<?php
		}

		?>

	</div>
</div>
