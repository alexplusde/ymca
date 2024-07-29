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

		    $dotlangCode = '# Tabelle zu Addon `addonname` übersetzen' . "\n";

		    foreach ($results as $result) {

		        if ($result['type_name'] === 'fieldset') {
		            continue;
		        }
                
		        if ($result['type_name'] === 'html') {
		            continue;
		        }
                
		        if ($result['type_id'] === 'value') {
		            $className = \Alexplusde\Ymca\Dotlang::toClassName($result['table_name']);
		            $methodName = \Alexplusde\Ymca\Dotlang::toCamelCase($result['name']);

		            // Mapping der db_type zu PHP-Typen
		            $methodMap = [
		                'choice_status' => 'choice',
		                'choice' => 'choice',
		            ];
		            $defaultMethod = 'default';
                    

		            $methodTemplate = \Alexplusde\Ymca\Dotlang::getTypeTemplate($methodMap[$result['type_name']] ?? $defaultMethod);

		            if (strpos($result['label'], 'translate:') === 0) {
		                $translationKey = substr($result['label'], strlen('translate:'));
		                $result['label'] = rex_i18n::msg($translationKey);
		            }
		            if (strpos($result['notice'], 'translate:') === 0) {
		                $translationKey = substr($result['notice'], strlen('translate:'));
		                $result['notice'] = rex_i18n::msg($translationKey);
		            }

		            $dotlangCode .= sprintf(
		                $methodTemplate,
		                $className,
		                $methodName,
		                $result['name'],
		                $result['label'],
		                $result['notice'],
		            );
		            $dotlangCode .= "\n";
		        }
                
		    }
		    ?>

		<section class="rex-page-section">


			<div class="panel panel-default">

				<header class="panel-heading">
					<div class="panel-title"><?= $table ?></div>
				</header>

				<div class="panel-body">
					<p><strong>1. Erstelle in deinem Addon eine Datei
							<code>/lang/de_de.php</code>
							mit folgendem Inhalt. Die <code>addonname_</code> kannst du per Suchen und Ersetzen bspw. mit deinem Addonnamen ersetzen</strong></p>
					<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme="dracula"
						data-codemirror-mode="php"><?= html_entity_decode($dotlangCode) ?></textarea>
						<p><strong>Optional: Lasse die Datei in weitere Sprachen übersetzen, z.B. über diesen Prompt</p>
						<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme="vibrantink"
						data-codemirror-mode="php">Meine Programmier-Kollegin benötigt die aktuelle Programmoberfläche in Ihrer Sprache (Englisch), kannst du wieder die entsprechenden Texte dieses Programmcode-Elements für sie in ihre Sprache umwandeln?</textarea>
				</div>
			</div>


		</section>

		<?php
		}

		?>

	</div>
</div>
