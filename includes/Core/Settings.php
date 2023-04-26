<?php

namespace AwwwesomeEEP\Includes\Core;

use AwwwesomeEEP\Includes\Core\Traits\Singleton;
use BadMethodCallException;

/**
 * @method static register( string $option_group, string $option_name, $args = [] )
 * @method register( string $option_group, string $option_name, $args = [] )
 * @method static add_field( string $id, string $title, callable $callback, string $page, $section = 'default', $args = [] )
 * @method add_field( string $id, string $title, callable $callback, string $page, $section = 'default', $args = [] )
 * @method static add_section( string $id, string $title, callable $callback, string $page )
 * @method add_section( string $id, string $title, callable $callback, string $page )
 */
class Settings {

	use Singleton;

	protected $to_register = [];
	protected $fields = [];
	protected $sections = [];

	public static function __callStatic( $method, array $arguments ) {
		if ( $method === 'register' ) {
			self::instance()->register( $arguments[0], $arguments[1], $arguments[2] ?? [] );

			return;
		}
		if ( $method === 'add_field' ) {
			self::instance()->add_field(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4] ?? 'default',
				$arguments[5] ?? []
			);

			return;
		}
		if ( $method === 'add_section' ) {
			self::instance()->add_section(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
			);

			return;
		}

		throw new BadMethodCallException( "Method $method does not exist." );
	}

	public function __call( $method, $arguments ) {
		if ( $method === 'register' ) {
			$this->_register( $arguments[0], $arguments[1], $arguments[2] );

			return;
		}
		if ( $method === 'add_field' ) {
			$this->_add_field(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4] ?? 'default',
				$arguments[5] ?? []
			);

			return;
		}
		if ( $method === 'add_section' ) {
			$this->_add_section(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
			);

			return;
		}
		throw new BadMethodCallException( "Method $method does not exist." );
	}

	protected function _register( $option_group, $option_name, $args = [] ) {
		$this->to_register[ $option_group ] = [
			'name' => $option_name,
			'args' => $args
		];
	}


	protected function _add_field( $id, $title, $callback, $page, $section = 'default', $args = [] ) {
		$this->fields[ $id ] = [
			'title'    => $title,
			'callback' => $callback,
			'page'     => $page,
			'section'  => $section,
			'args'     => $args,
		];
	}

	protected function _add_section( $id, $title, $callback, $page ) {
		$this->sections[ $id ] = [
			'title'    => $title,
			'callback' => $callback,
			'page'     => $page,
		];

	}

	/**
	 * Initialize fields
	 *
	 * @return void
	 */
	function init() {
		foreach ( $this->to_register as $option_group => $item ) {
			register_setting( $option_group, $item['name'], $item['args'] );
		}
		// TODO: empty array $this->to_register
		foreach ( $this->sections as $id => $field ) {
			add_settings_section( $id, $field['title'], $field['callback'], $field['page'] );
		}
		// TODO: empty array $this->sections
		foreach ( $this->fields as $id => $field ) {
			add_settings_field( $id, $field['title'], $field['callback'], $field['page'], $field['section'], $field['args'] );
		}
		// TODO: empty array $this->fields

	}

}