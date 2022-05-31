<?php
require_once "config.php";
$cleanup = true;

// Generate readme and info
require_once "text-generator.php";

$build_cfg_dir = getcwd();
$parent_dir    = dirname( $build_cfg_dir );
// Defaults - override in config.php
$src_dir = $parent_dir;
$zip_dir = $build_cfg_dir;
$tmp_dir = "$build_cfg_dir/tmp";

if ( ! isset( $plugin_slug ) ) {
	$plugin_slug = basename( $src_dir );
}

define( "DEST_DIR", "$tmp_dir/$plugin_slug" );
$dest_dir = DEST_DIR;
// remove if exist in tmp folder
clean();

// create empty dir for copy
mkdir( DEST_DIR, 0777, true );

// get filters
$filter_file = "$build_cfg_dir/filter";

// sync only files not in filter
system( "rsync -r --filter=\". $filter_file\" \"$src_dir\"/* \"$dest_dir\"" );

chdir( $tmp_dir );

// create zip name
$version  = INI['version'];
$zip_name = "$plugin_slug-$version.zip";


if ( file_exists( $zip_dir . '/' . $zip_name ) ) {
	echo "\nRemoving existing zip: " . $zip_dir . '/' . $zip_name . "\n\n";
	// Ensure we don't add files to an existing zip
	unlink( $zip_dir . '/' . $zip_name );
}

echo `zip -r $zip_name $plugin_slug -x "*.DS_Store"`;

// move zip to .build folder
if ( $zip_dir != $tmp_dir ) {
	`mv $zip_name "$zip_dir"/`;
}

clean();

function clean() {
	if ( file_exists( DEST_DIR ) ) {
		// Remove by system command
		system( "rm -rf " . escapeshellarg( DEST_DIR ) );
	}
}
