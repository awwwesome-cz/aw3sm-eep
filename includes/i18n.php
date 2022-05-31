<?php


namespace AwwwesomeEEP\Includes;


/**
 * Class i18n
 *
 * Loading language taxdomains
 *
 * @package AwwwesomeEEP\Includes
 */
class i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0
	 */
	public function LoadPluginTextdomain() {
		load_plugin_textdomain(
			'aw3sm-eep',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}