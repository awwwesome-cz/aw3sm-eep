<?php

namespace AwwwesomeEEP\Modules\ACF;

use AwwwesomeEEP\Modules\Module_Base;

class Module extends Module_Base {


	public function __construct() {
		// TODO: enable this module only if ACF activated
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
				'Repeater',
				'Repeater_Image'
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
		return 'ACF';
	}

	public function get_dynamic_tags() {
		if ( function_exists( "get_fields" ) ) {
			return [ 'Repeater' ];
		}

		return [];
	}
}