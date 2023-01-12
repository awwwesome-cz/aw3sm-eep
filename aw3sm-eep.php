<?php
/**
 * Plugin Name: AW3SM Elementor Extension Pack
 * Description: Elementor Extension pack is an extension plugin for Elementor page builder. Adding many widgets.
 * Version: 1.3.4-alpha
 * Stable tag: 1.4.0
 * Requires PHP: 7.4
 * Tested up to: 6.1.1
 * Requires at least: 5.6
 * Requires Elementor at least: 3.6
 * Elementor tested up to: 3.10.0
 * Requires Elementor Pro at least: 3.6
 * Elementor Pro tested up to: 3.10.1
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
