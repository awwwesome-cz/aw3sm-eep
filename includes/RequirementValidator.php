<?php


namespace AwwwesomeEEP\Includes;

use jayjay666\WPRequirementsChecker\Validator;

/**
 * Class RequirementValidator
 *
 * Requirements & validator checker for WP & server & plugins
 *
 * @package AwwwesomeEEP\Includes
 */
class RequirementValidator {
	/**
	 * Check plugin and server requirements
	 */
	static function requirementsValidate() {
		// Check requirements
		$validator = new Validator( '7.4', 'aw3sm-eep/aw3sm-eep.php', 'aw3sm-eep' );
		$validator->add_required_plugin( 'elementor/elementor.php', '3.6' );
		if ( ! $validator->check() ) {
			return;
		}
	}
}