<?php
/**
 * File update existing Readme
 */
include_once "config.php";

$filename_dir             = ROOT_DIR . "/readme.txt";
$filename_dir_plugin_main = ROOT_DIR . "/aw3sm-eep.php";

if ( ! file_exists( $filename_dir ) || ! file_exists( $filename_dir_plugin_main ) ) {
	return;
}

$content_txt         = file_get_contents( $filename_dir );
$content_plugin_main = file_get_contents( $filename_dir_plugin_main );

// Load plugin.ini data as array
$ini = INI;

// create replacement for readme.txt
$replacements_txt = [
	"/^Version: [0-9.\-a-zA-Z]*\n/m"        => "Version: $ini[version]\n",
	"/^Requires PHP: [0-9.]*\n/m"      => "Requires PHP: $ini[php_required]\n",
	"/^Tested up to: [0-9.]*\n/m"      => "Tested up to: $ini[tested_up_to]\n",
	"/^Stable tag: [0-9.\-a-zA-Z]*\n/m"        => "Stable tag: $ini[stable_tag]\n",
	"/^Requires at least: [0-9.]*\n/m" => "Requires at least: $ini[requires_at_least]\n",
    "/^Requires Elementor at least: [0-9.]*\n/m" => "Requires Elementor at least: $ini[elementor_requires_at_least]\n",
    "/^Elementor tested up to: [0-9.]*\n/m"      => "Elementor tested up to: $ini[elementor_tested_up_to]\n",
    "/^Requires Elementor Pro at least: [0-9.]*\n/m" => "Requires Elementor Pro at least: $ini[elementor_pro_requires_at_least]\n",
    "/^Elementor Pro tested up to: [0-9.]*\n/m"      => "Elementor Pro tested up to: $ini[elementor_pro_tested_up_to]\n",
	// TODO: replace WooCommerce texts
];

// Replace content in TXT file
$content_txt = preg_replace( array_keys( $replacements_txt ), array_values( $replacements_txt ), $content_txt );
// save as original
file_put_contents( $filename_dir, $content_txt );


// create replacement for plugin.php
$replacements_plugin_main = [
	"/^ \* Version: [0-9.\-a-zA-Z]*\n/m"                     => " * Version: $ini[version]\n",
	"/^ \* Requires PHP: [0-9.]*\n/m"                => " * Requires PHP: $ini[php_required]\n",
	"/^ \* Tested up to: [0-9.]*\n/m"                => " * Tested up to: $ini[tested_up_to]\n",
	"/^ \* Stable tag: [0-9.\-a-zA-Z]*\n/m"                  => " * Stable tag: $ini[stable_tag]\n",
	"/^ \* Requires at least: [0-9.]*\n/m"           => " * Requires at least: $ini[requires_at_least]\n",
	"/^ \* Requires Elementor at least: [0-9.]*\n/m" => " * Requires Elementor at least: $ini[elementor_requires_at_least]\n",
	"/^ \* Elementor tested up to: [0-9.]*\n/m"      => " * Elementor tested up to: $ini[elementor_tested_up_to]\n",
	"/^ \* Requires Elementor Pro at least: [0-9.]*\n/m" => " * Requires Elementor Pro at least: $ini[elementor_pro_requires_at_least]\n",
	"/^ \* Elementor Pro tested up to: [0-9.]*\n/m"      => " * Elementor Pro tested up to: $ini[elementor_pro_tested_up_to]\n",
];

// Replace content in plugin main PHP file
$content_plugin_main = preg_replace( array_keys( $replacements_plugin_main ), array_values( $replacements_plugin_main ), $content_plugin_main );
// save as original
file_put_contents( $filename_dir_plugin_main, $content_plugin_main );


