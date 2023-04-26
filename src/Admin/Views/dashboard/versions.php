<form action="options.php" method="post">
	<!-- TODO: dodělat jako wrapper -->
	<?= settings_fields( EEP_SETTINGS_GROUP ); ?>
	<?= do_settings_sections( EEP_SETTINGS_GROUP ); ?>

	<?= submit_button(); ?>
</form>