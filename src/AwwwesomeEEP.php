<?php

namespace AwwwesomeEEP;

use AwwwesomeEEP\Includes\Core\Plugin;
use AwwwesomeEEP\Includes\i18n;
use AwwwesomeEEP\Includes\PDUpdater;
use AwwwesomeEEP\Includes\Loader;

/**
 * Class AwwwesomeEEP
 *
 * Main booster class
 *
 * @package AwwwesomeEEP
 */
class AwwwesomeEEP extends Plugin {
	protected array $need_activated = [
		'elementor/elementor.php'         => '3.6',
		'elementor-pro/elementor-pro.php' => '3.6',
	];

	protected function get_path(): string {
		return dirname( __DIR__ ) . "/aw3sm-eep.php";
	}

	/**
	 * Constructor
	 *
	 * Lodging default needs files, class and others
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct() {
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
	public function boot() {
		// Run original boot
		parent::boot();

		// Custom actions
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
		// TODO: only for testing!
		 $updater->authorize('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');
		//$updater->authorize( get_option( 'my_licence_key' ) );
		$updater->initialize();
	}

}