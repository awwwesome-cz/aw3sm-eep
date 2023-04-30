<?php

namespace AwwwesomeEEP;

use AwwwesomeEEP\Includes\Core\Traits\Singleton;
use AwwwesomeEEP\Modules\Module_Base;
use Elementor\Core\Base\Module;
use Elementor\Core\Modules_Manager;

/**
 * Class ModuleLoader
 *
 * Load modules
 *
 * @package AwwwesomeEEP\Modules
 */
class ModuleManager extends Modules_Manager {
	/**
	 * Get all modules
	 * @return Module_Base[]
	 */
	public static function modules(): array {
		return (new self())->get_modules(null);
	}

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