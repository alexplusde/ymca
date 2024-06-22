<div class="row">
	<div class="col-12">

		<?php

echo \rex_view::title(\rex_i18n::msg('yform'));


		$tables = rex_sql::factory()->getArray("SELECT table_name FROM rex_yform_table ORDER BY table_name");
		$t = [];
		foreach ($tables as $table) {
		    $t[] = $table['table_name'];
		}

		/* %1$s = Tabellenname, %2$s = Klassenname */

		foreach ($t as $table) {

			$table_name = $table;

			$route = [];
			$route['path'] = "/v1/$table/";
			$route['auth'] = '\rex_yform_rest_auth_token::checkToken';
			$route['type'] = "$table::class";
			$route['query'] = "$table::query()";
	
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

			@$route['get']['fields']["$table_name"] = $get;
			@$route['post']['fields']["$table_name"] = $post;
			$route['delete']['fields']["$table_name"] = ['id'];

		    $routeCode = '$route = new \rex_yform_rest_route(' . var_export($route, true) . ');';
		    ?>

		<section class="rex-page-section">


			<div class="panel panel-default">

				<header class="panel-heading">
					<div class="panel-title"><?= $table ?></div>
				</header>

				<div class="panel-body">
					<p><strong>1. In die <code>boot.php</code> muss folgender Code:</strong></p>
					<textarea class="form-control codemirror" rows="5" readonly data-codemirror-theme="darcula" data-codemirror-mode="php"><?= $routeCode ?></textarea>
				</div>
			</div>


		</section>

		<?php
		}

		?>

	</div>
</div>
