<?php

namespace AwwwesomeEEP;

use Elementor\Core\Modules_Manager;

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
			'ACF',
			'Navigation',
		];
	}
}