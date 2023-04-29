<?php

namespace AwwwesomeEEP\Includes\Core;

class Notice {

	protected static array $notices_hashes = [];

	static function info( string $value, $dismisable = true, $once = false ) {
		if ( in_array( md5( $value ), self::$notices_hashes ) && $once ) {
			return;
		} else {
			self::$notices_hashes[] = md5( $value );
		}
		add_action( 'admin_notices', function () use ( $dismisable, $value ) {
			self::notice_view( $value, 'info', $dismisable );
		} );
	}

	static function waring( string $value, $dismisable = true, $once = false ) {
		if ( in_array( md5( $value ), self::$notices_hashes ) && $once ) {
			return;
		} else {
			self::$notices_hashes[] = md5( $value );
		}
		add_action( 'admin_notices', function () use ( $dismisable, $value ) {
			self::notice_view( $value, 'warning', $dismisable );
		} );
	}

	static function success( string $value, $dismisable = true, $once = false ) {
		if ( in_array( md5( $value ), self::$notices_hashes ) && $once ) {
			return;
		} else {
			self::$notices_hashes[] = md5( $value );
		}
		add_action( 'admin_notices', function () use ( $dismisable, $value ) {
			self::notice_view( $value, 'success', $dismisable );
		} );
	}

	static function error( string $value, $dismisable = true, $once = false ) {
		if ( in_array( md5( $value ), self::$notices_hashes ) && $once ) {
			return;
		} else {
			self::$notices_hashes[] = md5( $value );
		}
		add_action( 'admin_notices', function () use ( $dismisable, $value ) {
			self::notice_view( $value, 'error', $dismisable );
		} );
	}

	/**
	 * Show admin notice
	 *
	 * @param string $string
	 * @param string $style
	 * @param bool $dismissible
	 *
	 * @return void
	 */
	protected static function notice_view( string $string, string $style = 'info' | 'error' | 'warning' | 'success', $dismissible = false ) {
		$is_dismissible = $dismissible ? 'is-dismissible' : '';
		if ( $string == strip_tags( $string ) ) {
			$string = "<p>$string</p>";
		}

		echo "<div class='notice notice-$style $is_dismissible'>$string</div>";
	}
}