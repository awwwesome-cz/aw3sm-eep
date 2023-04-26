<?php

namespace AwwwesomeEEP\Includes\Core;

use AwwwesomeEEP\Includes\Core\Traits\Requirement_Check;
use Exception;

/**
 * @property string $name Plugin name
 * @property string $uri Plugin WEB URL
 * @property string $version Plugin version
 * @property string $description Plugin description
 * @property string $author Plugin Author
 * @property string $author_name Plugin Author
 * @property string $author_uri Plugin Author WEB URL
 * @property string $text_domain Plugin text domain
 * @property string $domain_path Plugin domain path
 * @property string $requires_php Min PHP version
 * @property string $requires_wp Min WP version
 * @property string $basename Plugin BaseName. Example: your-plugin/your-plugin.php
 * @property string prefix Plugin Prefix used for anything for prefixing
 * @property boolean $network
 */
abstract class Plugin {
	use Requirement_Check;

	/**
	 * Set path for plugin file
	 *
	 * @return string
	 */
	abstract protected function get_path(): string;

	/**
	 * @var array
	 */
	private static array $plugin_data = [];

	/**
	 * @throws Exception
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'name':
				return self::$plugin_data["Name"];
			case 'uri':
				return self::$plugin_data["PluginURI"];
			case 'version':
				return self::$plugin_data["Version"];
			case 'description':
				return self::$plugin_data["Description"];
			case 'author':
				return self::$plugin_data["Author"];
			case 'author_uri':
				return self::$plugin_data["AuthorURI"];
			case 'author_name':
				return self::$plugin_data["AuthorName"];
			case 'text_domain':
				return self::$plugin_data["TextDomain"];
			case 'domain_path':
				return self::$plugin_data["DomainPath"];
			case 'network':
				return self::$plugin_data["Network"];
			case 'requires_wp':
				return self::$plugin_data["RequiresWP"];
			case 'requires_php':
				return self::$plugin_data["RequiresPHP"];
			default:
				if ( isset( $this->$name ) ) {
					return $this->$name;
				} else {
					throw new Exception( "Property $name not exist" );
				}
		}
	}

	/**
	 * Boot plugin
	 *
	 * @return void
	 */
	public function boot() {
		add_action( 'admin_init', [ $this, 'init' ] );
		$this->check_requirements();

		// set prefix before init from PATH
		$this->prefix = basename( $this->get_path(), '.php' );

		// set menu before admin INIT
		Menu::slug_prefix( $this->prefix );
		Menu::init_menu_pages();

	}

	function init() {
		self::$plugin_data = get_plugin_data( $this->get_path() );
		$this->basename    = plugin_basename( $this->get_path() );

		// init settings
		Settings::instance()->init();
	}

	/**
	 * @return array
	 */
	public static function plugin_data(): array {
		return self::$plugin_data;
	}

	/**
	 * Get version of plugin
	 *
	 * @return mixed
	 */
	static function version() {
		return self::$plugin_data['Version'] ?? AW3SM_EEP_VERSION;
	}
}