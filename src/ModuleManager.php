<?php

namespace AwwwesomeEEP;

use Elementor\Core\Modules_Manager;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // fixing is_plugin_active not active

/**
 * Class ModuleLoader
 *
 * Load modules
 *
 * @package AwwwesomeEEP\Modules
 */
class ModuleManager extends Modules_Manager {

	protected function get_modules_namespace_prefix() {
		return 'AwwwesomeEEP';
	}

	public function get_modules_names() {
		return [
			// set module names from folder Modules
			'Utils',
			'ACF',
			'Navigation',
			'Section',
			'WooCommerce',
			'TablePress',
		];
	}
}