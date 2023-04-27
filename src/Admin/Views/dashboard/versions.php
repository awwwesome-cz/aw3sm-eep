<form action="options.php" method="post">
	<?php AwwwesomeEEP\Includes\Core\Settings::fields( EEP_SETTINGS_GROUP ); ?>
	<?php AwwwesomeEEP\Includes\Core\Settings::settings_sections( EEP_SETTINGS_GROUP ); ?>
	<?php AwwwesomeEEP\Includes\Core\Settings::submit_button(); ?>
</form>