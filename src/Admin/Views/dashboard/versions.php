<?php
// TODO: add wrapper fx
// register custom page for DB
register_setting( 'aw3sm-eep-dashboard', 'aw3sm-eep-dashboard-options' );
// add section
add_settings_section( 'beta-settings',
	'Staň se beta testerem',
	function () {
		echo "Zapnutím funkce Beta Tester získáte upozornění na dostupnost nové beta verze Elementor Extension Pack 
		nebo Elementor Extension Pack Pro.";
	}, 'aw3sm-eep-dashboard' );

// add field
add_settings_field(
	'beta-program',
	__( 'Beta Tester', 'my-textdomain' ),
	function () {
		?>
		<select name="beta_program">

			<option value="no" selected="selected">Nepovoleno</option>
			<option value="yes">Povoleno</option>
		</select>

		<p class="description">
			<span style="color: red">Upozornění: Nedoporučujeme aktualizovat beta verze na produkčních webech.</span>
		</p>
		<?php
	},
	'aw3sm-eep-dashboard',
	'beta-settings'
);
?>
<form action="options.php" method="post">
	<?= settings_fields( 'aw3sm-eep-dashboard-options' ); ?>
	<?= do_settings_sections( 'aw3sm-eep-dashboard' ); ?>

	<?= submit_button(); ?>
</form>