<?php

/*
Plugin Name: AW3SM Elementor Extension Pack
Plugin URI: https://awwwesome.cz/eep
Description: Elementor Extension pack is an extension plugin for Elementor page builder. Adding many widgets.
Version: 1.0.0
Author: awwwesome.cz
Author URI: https://awwwesome.cz
License: A "Slug" license name e.g. GPL2
*/


use AwwwesomeEEP\AwwwesomeEEP;

defined( 'ABSPATH' ) || exit;

// Import všech potřebných knihoven
if ( is_readable( __DIR__ . '/lib/autoload.php' ) ) {
	require_once __DIR__ . '/lib/autoload.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

$plugin = new AwwwesomeEEP();
$plugin->run();
