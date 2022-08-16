<?php

namespace AwwwesomeEEP\Modules\Navigation;

use AwwwesomeEEP\Modules\Module_Base;

class Module extends Module_Base {


	public function __construct() {
		parent::__construct();
	}

	/**
	 * Widgets
	 *
	 * @return string[]
	 * @throws \Exception
	 */
	public function get_widgets() {
		// Complete list of fidgets
		if ( function_exists( "get_fields" ) ) {
			return [
				'Child_Navigation',
			];
		}

		return [];
	}

	/**
	 * Module name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'Navigatiion';
	}
}