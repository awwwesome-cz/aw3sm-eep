<?php

namespace AwwwesomeEEP\Includes\Core\Traits;

/**
 * Global Singletone Trait
 */
trait Singleton {
	private static $instances = [];

	protected function __construct() {
	}

	protected function __clone() {
	}

	public static function instance(): self {
		$cls = static::class;
		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new static();
		}

		return self::$instances[ $cls ];
	}

}