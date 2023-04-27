<?php
/**
 * Plugin Name: AW3SM Elementor Extension Pack
 * Description: Elementor Extension pack is an extension plugin for Elementor page builder. Adding many widgets.
 * Version: 1.6.0-dev
 * Stable tag: 1.5.1
 * Requires PHP: 7.4
 * Tested up to: 6.2
 * Requires at least: 6.0
 * Requires Elementor at least: 3.6
 * Elementor tested up to: 3.12.2
 * Requires Elementor Pro at least: 3.6
 * Elementor Pro tested up to: 3.12.3
 * Plugin URI: https://awwwesome.cz/eep
 * Author: awwwesome.cz
 * Author URI: https://awwwesome.cz
 * License: A "Slug" license name e.g. GPL2
 */

use AwwwesomeEEP\AwwwesomeEEP;

defined('ABSPATH') || exit;

// Import všech potřebných knihoven
if (is_readable(__DIR__ . '/lib/autoload.php')) {
    require_once __DIR__ . '/lib/autoload.php';
}

const AW3SM_EEP_VERSION = "1.5.1"; // TODO: add to auto replace in .build folder!!!

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
$plugin->boot();
