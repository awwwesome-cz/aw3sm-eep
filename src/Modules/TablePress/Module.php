<?php

namespace AwwwesomeEEP\Modules\TablePress;

use AwwwesomeEEP\Modules\Module_Base;

class Module extends Module_Base {

	public static function is_active() {
		return is_plugin_active( 'tablepress/tablepress.php' );
	}

	/**
	 * Widgets
	 *
	 * @return string[]
	 * @throws \Exception
	 */
	public function get_widgets() {
		return [
			'Tablepress_Table',
		];
	}

	/**
	 * Module name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'TablePress';
	}
}