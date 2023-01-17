<?php

namespace AwwwesomeEEP\Modules\WooCommerce;

use AwwwesomeEEP\Modules\Module_Base;

class Module extends Module_Base {

	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	/**
	 * Widgets
	 *
	 * @return string[]
	 * @throws \Exception
	 */
	public function get_widgets() {
		return [
			'Sale_Badge',
		];
	}

	/**
	 * Module name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'Woo Commerce';
	}
}