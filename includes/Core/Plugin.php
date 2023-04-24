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
	private array $plugin_data = [];

	/**
	 * @throws Exception
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'name':
				return $this->plugin_data["Name"];
			case 'uri':
				return $this->plugin_data["PluginURI"];
			case 'version':
				return $this->plugin_data["Version"];
			case 'description':
				return $this->plugin_data["Description"];
			case 'author':
				return $this->plugin_data["Author"];
			case 'author_uri':
				return $this->plugin_data["AuthorURI"];
			case 'author_name':
				return $this->plugin_data["AuthorName"];
			case 'text_domain':
				return $this->plugin_data["TextDomain"];
			case 'domain_path':
				return $this->plugin_data["DomainPath"];
			case 'network':
				return $this->plugin_data["Network"];
			case 'requires_wp':
				return $this->plugin_data["RequiresWP"];
			case 'requires_php':
				return $this->plugin_data["RequiresPHP"];
			default:
				throw new Exception( "Property $name not exist" );
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
	}

	function init() {
		$this->plugin_data = get_plugin_data( $this->get_path());
		$this->basename = plugin_basename($this->get_path());
		// $this->plugin_data = get_plugin_data( WP_PLUGIN_DIR . "/" . $this->plugin_name );
	}

	/**
	 * @return array
	 */
	public function get_plugin_data(): array {
		return $this->plugin_data;
	}
}