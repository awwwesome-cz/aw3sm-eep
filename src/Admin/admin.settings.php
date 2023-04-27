<?php
/*
 * Register all settings used in plugin
 */

// register custom page for DB
use AwwwesomeEEP\Includes\Core\Settings;

// register settings options
const EEP_SETTINGS_GROUP  = 'aw3sm-eep-dashboard';
const EEP_SETTINGS_OPTION = 'aw3sm-eep-options'; // DB option key
const EEP_BETA_SECTION    = 'beta-settings';

// register group with DB option key
Settings::register( EEP_SETTINGS_GROUP, EEP_SETTINGS_OPTION );

Settings::add_section( EEP_BETA_SECTION,
	'Staň se beta testerem',
	function () {
		echo "Zapnutím funkce Beta Tester získáte upozornění na dostupnost nové beta verze Elementor Extension Pack 
		nebo Elementor Extension Pack Pro.";
	},
	EEP_SETTINGS_GROUP
);

Settings::add_field( 'beta_program', "Beta Tester", function () {
	$options = Settings::get_option( EEP_SETTINGS_OPTION );
	$option  = $options['beta_program'] ?? 'disabled';
	?>
	<select name="<?= EEP_SETTINGS_OPTION ?>[beta_program]">

		<option value="disabled" <?= $option == 'disabled' ? 'selected' : null ?>>Nepovoleno</option>
		<option value="active" <?= $option == 'active' ? 'selected' : null ?>>Povoleno</option>
	</select>

	<p class="description">
		<span style="color: red">Upozornění: Nedoporučujeme aktualizovat beta verze na produkčních webech.</span>
	</p>
	<?php
},
	EEP_SETTINGS_GROUP,
	EEP_BETA_SECTION );