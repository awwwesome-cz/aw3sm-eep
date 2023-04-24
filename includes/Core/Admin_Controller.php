<?php

namespace AwwwesomeEEP\Includes\Core;

/**
 * Default controller for showing views
 */
abstract class Admin_Controller {
	protected function view( $view_path, $data = array() ) {
		$view_parts = explode( '.', $view_path );
		$view_file  = end( $view_parts );
		$view_path  = plugin_dir_path(__DIR__) . '../src/Admin/Views/' . str_replace( '.', '/', $view_path ) . '.php';

		if ( file_exists( $view_path ) ) {

			extract( $data );
			ob_start();
			include( $view_path );

			return ob_get_clean();
		}

		return '';
	}
}