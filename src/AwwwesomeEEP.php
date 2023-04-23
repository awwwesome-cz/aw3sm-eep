<?php

namespace AwwwesomeEEP;

use AwwwesomeEEP\Includes\i18n;
use AwwwesomeEEP\Includes\PDUpdater;
use AwwwesomeEEP\Includes\RequirementValidator;
use AwwwesomeEEP\Includes\Loader;

/**
 * Class AwwwesomeEEP
 *
 * Main booster class
 *
 * @package AwwwesomeEEP
 */
class AwwwesomeEEP {

	/**
	 * Constructor
	 *
	 * Loadiging default needs files, class and others
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct() {
		RequirementValidator::requirementsValidate();// TODO: fix header error
		// TODO: need Elementor Pro
		$this->i18n();
		// Load Module Manager
		//
		////// Loader::addAction('elementor/init', new ModuleManager(), 'load'); // TODO: Nevím proč nefunguje, asi nějaký bug!!!!

		// update update
		$this->check_update();
	}

	function on_elementor_init() {
		$module_manager = new ModuleManager();
	}


	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function i18n() {
		$plugin_i18n = new i18n();
		Loader::addAction( 'plugins_loaded', $plugin_i18n, 'LoadPluginTextdomain' );
	}

	/**
	 * Run the loader to execute all the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		Loader::run();
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );
	}

	/**
	 * Check for update
	 *
	 * Check GitHub update
	 *
	 * @return void
	 */
	private function check_update() {
		$updater = new PDUpdater( dirname( __DIR__ ) . "/aw3sm-eep.php" );
		$updater->set_github_username( 'awwwesome-cz' );
		$updater->set_github_repository( 'aw3sm-eep' );
		$updater->set_tag_name_prefix( 'v' );
		//$updater->authorize( get_option( 'my_licence_key' ) );
		$updater->initialize();
	}

}