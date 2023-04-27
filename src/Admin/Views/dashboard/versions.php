<form action="options.php" method="post">
	<!-- TODO: dodělat jako wrapper -->
	<?php AwwwesomeEEP\Includes\Core\Settings::fields( EEP_SETTINGS_GROUP ); ?>
	<?= do_settings_sections( EEP_SETTINGS_GROUP ); ?>

	<?= submit_button(); ?>
</form>