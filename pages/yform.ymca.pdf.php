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

		    $fragmentCode = '
<?php
// @var  $dataset
$dataset = $this->getVar(\'dataset\');
?>
<!DOCTYPE html>
<html>
	<head>
		<style>
			* {
				font-family: \'Arial\', sans-serif;
			}

			html {
				padding: 0;
				margin: 0;
				font-size: 8pt;
				line-height: 1.3;
			}

			body {
				padding: 0;
				margin: 0;
				position: relative;
			}

			.background {
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				z-index: -1;
			}

			.background img {
				width: 100%;
				height: 100%;
				display: block;
			}

			.content {
				position: absolute;
				top: 0cm;
				left: 0cm;
				right: 0cm;
				bottom: 0cm;
			}

			strong {
				font-weight: bold;
			}


			table {
				width: 100%;
				border-collapse: collapse;
			}

			th,
			td {
				padding: 1mm;
				vertical-align: top;
			}

			th {
				text-align: left;
			}
		</style>
	</head>
	<body>
		<div class="background">
			<!--<img src="media/teilnahmebescheinigung.jpg">-->
		</div>
		<div class="content">
			';

		    foreach ($results as $result) {

		        if ($result['type_name'] === 'fieldset') {
		            continue;
		        }
                
		        if ($result['type_name'] === 'html') {
		            continue;
		        }
                
		        if ($result['type_id'] === 'value') {
		            $className = \Alexplusde\Ymca\Pdf::toClassName($result['table_name']);
		            $methodName = \Alexplusde\Ymca\Pdf::toCamelCase($result['name']);

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
                    

		            $methodTemplate = \Alexplusde\Ymca\Pdf::getTypeTemplate($methodMap[$result['type_name']] ?? $defaultMethod);

		            if (strpos($result['label'], 'translate:') === 0) {
		                $translationKey = substr($result['label'], strlen('translate:'));
		                $result['label'] = rex_i18n::msg($translationKey);
		            }
		            if (strpos($result['notice'], 'translate:') === 0) {
		                $translationKey = substr($result['notice'], strlen('translate:'));
		                $result['notice'] = rex_i18n::msg($translationKey);
		            }

					$fragmentCode .= '			';

		            $fragmentCode .= sprintf(
		                $methodTemplate,
		                $className,
		                $methodName,
		                $result['name'],
		                $result['label'],
		                $result['notice'],
		            );
		            $fragmentCode .= "\n";
		        }
                
		    }

		    $fragmentCode .= '
		</div>
	</body>

</html>
';


$RexApiPdfGeneratorCode = '
<?php

class rex_api_'.$table.' extends rex_api_function
{
    protected $published = true;

    public function execute(): void
    {
        // Parameter abrufen und auswerten
        $hash = rex_request(\'hash\', \'string\', false);

        if (!$hash || !rex_backend_login::hasSession()) { /* Fehlerfall */

            header(\'Content-Type: application/json\');
            echo json_encode([]);
            exit;
			
        } else {

            $dataset = ModelClass::query()->where(\'hash\', $hash)->findOne();

			$document = new rex_fragment();

			/* Aktueller Datensatz der ModelClass */
			$document->setVar(\'dataset\', $dataset, false);

			/* PDF generieren */
			$pdf_content = $document->parse(\'addonname/pdf_'.$table.'.php\');
			$pdf_filename =  \'Document \' . $date->getId();

			rex_response::cleanOutputBuffers();
			$pdf = new PdfOut();
			$pdf->loadHtml($pdf_content);

			// Optionen
			$options = $pdf->getOptions();
			$options->setChroot(rex_path::frontend());
			$options->setDpi(300);
			$options->setFontCache(rex_path::addonCache(\'pdfout\', \'fonts\'));
			$options->setIsRemoteEnabled(true);
			$pdf->setOptions($options);
			$pdf->setPaper(\'A4\', \'portrait\');
			$pdf->render();

            header(\'Content-Type: application/pdf\');
			$pdf->stream($pdf_filename, array(\'Attachment\' => true));
            die();
        }
    }
}
';
		    ?>

		<section class="rex-page-section">


			<div class="panel panel-default">

				<header class="panel-heading">
					<div class="panel-title"><?= $table ?></div>
				</header>

				<div class="panel-body">
					<p>Voraussetzung: Ein `hash`-Feld oder ein anderes Identifier-Feld, das nicht zu erraten ist.
					<p><strong>1. Erstelle in deinem Addon eine Datei
							<code>/fragments/addonname/pdf_<?= $table ?>.php</code>
							mit folgendem Inhalt:</strong></p>
					<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme=""
						data-codemirror-mode="php"><?= html_entity_decode($fragmentCode) ?></textarea>
						<p><strong>2. Erstelle in deinem Addon eine Datei
							<code>/lib/rex_api_<?= $table ?>.php</code>
							mit folgendem Inhalt:</strong></p>
					<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme="darcula"
						data-codemirror-mode="php"><?= html_entity_decode($RexApiPdfGeneratorCode) ?></textarea>
						<p><strong>3. Der Aufruf erfolgt über <?= rex::getServer() ?>index.php?rex-api-call=<?= $table ?>&hash=1234567890</strong></p>
				</div>
			</div>


		</section>

		<?php
		}

		?>

	</div>
</div>
