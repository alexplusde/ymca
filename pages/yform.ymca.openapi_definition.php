<div class="row">
	<div class="col-12">

		<?php

echo \rex_view::title(\rex_i18n::msg('yform'));


		$tables = rex_sql::factory()->getArray("SELECT table_name FROM rex_yform_table ORDER BY table_name");
		$t = [];
		foreach ($tables as $table) {
		    $t[] = $table['table_name'];
		}

		$definition = [];

		$definition['openapi'] = '3.1.0';
		$definition['info']['title'] = '<>Klassenname> API';
		$definition['info']['description'] = 'API zur Abfrage von Daten zu <Klassenname>.';
		$definition['info']['version'] = '1.0.0';
		$definition['servers'][] = ['url' => 'https://'.rex::getServer(), 'description' => 'Live-Server'];



		/* %1$s = Tabellenname, %2$s = Klassenname */

		foreach ($t as $table) {

			$table_name = $table;
			$class_name = str_replace('rex_', '', $table);

			$path = ['/rest/addonname/klassenname/1.0.0/'];
	
		    $results = rex_sql::factory()->getArray("SELECT `id`, `table_name`, `prio`, `type_name`, `type_id`, `db_type`, `name`, `label` FROM `rex_yform_field` WHERE `type_name` != 'validate' AND `table_name` = '$table' ORDER BY `prio`");

			$get = [];
			$post = [];

		    foreach ($results as $result) {

		        if ($result['type_name'] === 'fieldset') {
		            continue;
		        }
                
		        if ($result['type_name'] === 'html') {
		            continue;
		        }
		        if ($result['type_name'] === 'prio') {
		            continue;
		        }
		        if ($result['type_name'] === 'be_manager_relation') {
		            continue;
		        }
                
		        if ($result['type_id'] === 'value') {

					$get[] = $result['name'];
					$post[] = $result['name'];

		        }
                
		    }

			$path['get']['fields']["$table_name"] = $get;
			$path['post']['fields']["$table_name"] = $post;

			$definition['paths'] = $path;

			$definition
		    ?>

		<section class="rex-page-section">


			<div class="panel panel-default">

				<header class="panel-heading">
					<div class="panel-title"><?= $table ?></div>
				</header>

				<div class="panel-body">
					<h2>NOCH NICHT FERTIG ENTWICKELT, UNTERSTÜTZUNG BENÖTIGT!</h2>
					<p><strong>Fogelnden Code kopieren:</strong></p>
					<p>Tipp: im Nachgang eigenständig \rex_string::yamlEungen in der .</p>

					<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme="darcula" data-codemirror-mode="js"><?= \rex_string::yamlEncode($definition) ?></textarea>
				</div>
			</div>


		</section>

		<?php
		}

		?>

	</div>
</div>
