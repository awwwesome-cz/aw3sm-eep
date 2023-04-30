<!-- TODO: loop list of widgets -->
<?php

use AwwwesomeEEP\ModuleManager;

foreach ( ModuleManager::modules() as $module ) {
	$name = $module->get_name();
	echo "<h1>$name</h1>";

	if ( $widgets = $module->widgets() ) {
		echo "<h2>Widgety</h2>";

		foreach ( $widgets as $tag ) {
			$w_name        = $tag->get_title();
			$w_description = "Popis widgetu"; // TODO: $widget->;
			?>
			<div class="card">
				<h2 class="title"><?= $w_name ?></h2>
				<p>
					<?= $w_description ?>
				</p>
			</div>
			<?php
		}
	}

	if ( $tags = $module->tags() ) {
		echo "<h2>Tagy</h2>";

		foreach ( $tags as $tag ) {
			$w_name        = $tag->get_title();
			$w_description = "Popis tagu"; // TODO: $tag->;
			?>
			<div class="card">
				<h2 class="title"><?= $w_name ?></h2>
				<p>
					<?= $w_description ?>
				</p>
			</div>
			<?php
		}
	}

	if ( $extends = $module->extends() ) {
		echo "<h2>Widget Extends</h2>";

		foreach ( $extends as $extend ) {
			$w_name        = $extend->get_title();
			$w_description = "Popis extendu widgetu"; // TODO: $extend->;
			?>
			<div class="card">
				<h2 class="title"><?= $w_name ?></h2>
				<p>
					<?= $w_description ?>
				</p>
			</div>
			<?php
		}
	}
}
?>

<div class="card">
	<h2 class="title">Section Booster</h2>
	<span>Deprecated - use <a href="?page=elementor#tab-experiments">Flexbox Containers</a></span>
	<!-- TODO: color, custom badge class-->
	<p>
		Extending existing Section & Columns for flexbox ready.
	</p>
</div>