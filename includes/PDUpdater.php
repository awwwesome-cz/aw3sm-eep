<?php

namespace AwwwesomeEEP\Includes;

use AwwwesomeEEP\Includes\Core\Settings;

class PDUpdater {
	/**
	 * Hold plugin Absolute PATH
	 * @var string|null
	 */
	private ?string $plugin_path = null;

	/**
	 * Hold plugin DATA (array of plugin data)
	 * @var array
	 */
	private array $plugin = [];

	/**
	 * Hold base name like your-plugin/your-plugin.php
	 * @var string|null
	 */
	private ?string $plugin_basename = null;

	/**
	 * Check if plugin activated
	 * @var bool
	 */
	private bool $is_active = false;

	/**
	 * GitHub Username
	 * @var string|null
	 */
	private ?string $github_username = null;

	/**
	 * GitHub Repository
	 * @var string|null
	 */
	private ?string $github_repository = null;

	/**
	 * Authorize TOKEN
	 * @var string|null
	 */
	private ?string $authorize_token = null;
	/**
	 * @deprecated
	 */
	private $github_response;
	private string $tag_name_prefix;

	/**
	 * @param string $plugin Absolute path to plugin main file. Example: /var/.../plugins/your-plugin/your-plugin.php
	 */
	public function __construct( string $plugin ) {
		$this->plugin_path = $plugin;
		add_action( 'admin_init', [ $this, 'set_plugin_properties' ] );
	}

	/**
	 * Init default plugin properties from WP
	 *
	 * @return void
	 */
	public function set_plugin_properties() {
		$this->plugin          = get_plugin_data( $this->plugin_path );
		$this->plugin_basename = plugin_basename( $this->plugin_path );
		$this->is_active       = is_plugin_active( $this->plugin_basename );
	}

	/**
	 * Set GitHub username
	 *
	 * @param $github_username string GitHub Username
	 *
	 * @return void
	 */
	public function set_github_username( string $github_username ) {
		$this->github_username = $github_username;
	}

	/**
	 * Set GitHub repository
	 *
	 * @param string $github_repository
	 *
	 * @return void
	 */
	public function set_github_repository( string $github_repository ) {
		$this->github_repository = $github_repository;
	}

	/**
	 * Set tag name prefix
	 *
	 * If you have release tag like v1.2.3 you need set tag prefix 'v'
	 *
	 * @param string $tag_name_prefix
	 */
	public function set_tag_name_prefix( string $tag_name_prefix ): void {
		$this->tag_name_prefix = $tag_name_prefix;
	}

	public function authorize( $token ) {
		$this->authorize_token = $token;
	}

	private function get_repository_info() {
		if ( is_null( $this->github_response ) ) {
			$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->github_username,
				$this->github_repository );

			// Switch to HTTP Basic Authentication for GitHub API v3
			$curl = curl_init();

			$headers = [ "User-Agent: PDUpdater/1.2.3" ]; // TODO: version
			if ( $this->authorize_token ) {
				$headers[] = "Authorization: token " . $this->authorize_token;
			}

			curl_setopt_array( $curl, [
				CURLOPT_URL            => $request_uri,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "GET",
				CURLOPT_HTTPHEADER     => $headers
			] );

			$response = curl_exec( $curl );
			$info     = curl_getinfo( $curl );

			curl_close( $curl );

			// check validity (!403)
			if ( $info['http_code'] != 200 ) {
				error_log( $response, true );

				return;
			}

			$response = json_decode( $response, true );

			if ( is_array( $response ) ) {
				$response = current( $response );
			}

			/*if ( $this->authorize_token ) {
				// TODO: access_token
				$response['zipball_url'] = add_query_arg( 'access_token', $this->authorize_token,
					$response['zipball_url'] );
			}*/

			$this->github_response = $response;
		}
	}

	/**
	 * Get info about the latest version by beta level
	 *
	 * @param $beta_level
	 *
	 * @return false|mixed|string|null
	 */
	private function get_latest_version( $beta_level ) {
		$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->github_username,
			$this->github_repository );

		// Switch to HTTP Basic Authentication for GitHub API v3
		$curl = curl_init();

		$headers = [ "User-Agent: PDUpdater/1.2.3" ]; // TODO: version
		if ( $this->authorize_token ) {
			$headers[] = "Authorization: token " . $this->authorize_token;
		}

		curl_setopt_array( $curl, [
			CURLOPT_URL            => $request_uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => $headers
		] );

		$response = curl_exec( $curl );
		$info     = curl_getinfo( $curl );

		curl_close( $curl );

		// check validity (!403)
		if ( $info['http_code'] != 200 ) {
			error_log( $response, true );

			return null;
		}

		$response = json_decode( $response, true );

		// if not array, nothing to do
		if ( ! is_array( $response ) ) {
			return null;
		}

		// filter array by beta_level and get latest
		switch ( $beta_level ) {
			case 'dev':
				$response = array_filter( $response, function ( $data ) {
					// TODO: check prefix
					return preg_match( "/^v[0-9.]*-dev[0-9.]*?$/", $data['tag_name'] );
				} );
				break;
			case 'alpha':
				$response = array_filter( $response, function ( $data ) {
					// TODO: check prefix
					return preg_match( "/^v[0-9.]*-alpha[0-9.]*?$/", $data['tag_name'] );
				} );
				break;
			case 'beta':
				$response = array_filter( $response, function ( $data ) {
					// TODO: check prefix
					return preg_match( "/^v[0-9.]*-beta[0-9.]*?$/", $data['tag_name'] );
				} );
				break;
			default:
				// Default disable pre-releases
				$response = array_filter( $response, function ( $data ) {
					return $data['prerelease'] == true;
				} );
		}

		// get latest from array
		if ( is_array( $response ) ) {
			return current( $response );
		} else {
			return null;
		}
	}

	/**
	 * Run Initialize process
	 *
	 * @return void
	 */
	public function initialize() {
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_update' ], 10, 1 );

		// overide default popup from WordPress API to GitHut API
		add_filter( 'plugins_api', [ $this, 'plugin_popup' ], 10, 3 );
		// run code after install complete
		add_filter( 'upgrader_post_install', [ $this, 'after_install' ], 10, 3 );
	}

	/**
	 * Check updates from GitHub API
	 *
	 * @param $transient
	 *
	 * @return mixed
	 */
	public function check_update( $transient ) {

		if ( property_exists( $transient, 'checked' ) ) {
			if ( $transient->checked && isset( $transient->checked[ $this->plugin_basename ] ) ) {

				$options = Settings::get_option( EEP_SETTINGS_OPTION );
				$level   = $options['beta_program'] ?? 'disabled';

				$response = $this->get_latest_version( $level );

				// clear name
				$new_version = $this->remove_prefix( $response['tag_name'] );

				// get version
				$out_of_date = version_compare( $new_version, $transient->checked[ $this->plugin_basename ], 'gt' );

				if ( $out_of_date ) {
					foreach ( $response['assets'] as $asset ) {
						if ( $asset['content_type'] == 'application/zip' && $asset['name'] == "aw3sm-eep-$new_version.zip" ) {
							$new_files = $asset['browser_download_url'];
							break;
						}
					}

					// $new_files = $this->github_response['zipball_url'];
					$slug = current( explode( '/', $this->plugin_basename ) );

					$plugin = [
						'url'         => $this->plugin['PluginURI'],
						// TODO: hodilo error, že to je undefined pole, zjistit proč... hodilo po zapnutí dockeru "PluginURI"
						'slug'        => $slug,
						'package'     => $new_files ?? null,
						'new_version' => $new_version
					];

					$transient->response[ $this->plugin_basename ] = (object) $plugin;
				}
			}
		}

		return $transient;
	}

	/**
	 * Modify default WP PopUp from WP API
	 *
	 * - Using GitHub API
	 *
	 * @param $_data
	 * @param $_action
	 * @param $_args
	 *
	 * @return mixed|\stdClass
	 */
	public function plugin_popup( $_data, $_action, $_args ) {
		if ( 'plugin_information' !== $_action ) {
			return $_data;
		}

		if ( ! isset( $_args->slug ) || ( $_args->slug !== current( explode( '/', $this->plugin_basename ) ) ) ) {
			return $_data;
		}

		$options = Settings::get_option( EEP_SETTINGS_OPTION );
		$level   = $options['beta_program'] ?? 'disabled';

		$response = $this->get_latest_version( $level );


		$new_version = $this->remove_prefix( $response['tag_name'] );
		foreach ( $response['assets'] as $asset ) {
			if ( $asset['content_type'] == 'application/zip' && $asset['name'] == "aw3sm-eep-$new_version.zip" ) {
				$download_zip = $asset['browser_download_url'];
				break;
			}
		}

		$api_request_transient         = new \stdClass();
		$api_request_transient->name   = $this->plugin['Name'];
		$api_request_transient->slug   = $this->plugin_basename;
		$api_request_transient->author = $this->plugin['AuthorName'];
		// $api_request_transient->author_profile = $this->plugin['AuthorURI'];
		$api_request_transient->homepage = $this->plugin['PluginURI'];  // TODO: get from API
		$api_request_transient->requires = $this->plugin['RequiresWP'];  // TODO: get from API
		$api_request_transient->tested   = '6.0'; // TODO: get from api

		$api_request_transient->version       = $new_version;
		$api_request_transient->last_updated  = $response['published_at'];
		$api_request_transient->download_link = $download_zip ?? null;
		/*$api_request_transient->banners = [
			"high" => "https://",
			"low" => "https://"
		];*/
		$api_request_transient->autoupdate = true;

		$api_request_transient->sections = [
			// tabs in information plugin page
			'Description' => $this->plugin['Description'],
			// 'Updates'     => $this->github_response['body'],
			'Changelog'   => $response['body'],
		];

		$_data = $api_request_transient;

		return $_data;
	}

	/**
	 * Run code after install complete
	 *
	 * - Reactivate plugin
	 *
	 * @param $response
	 * @param $hook_extra
	 * @param $result
	 *
	 * @return mixed
	 */
	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem;

		$install_directory = plugin_dir_path( $this->plugin_path );
		$wp_filesystem->move( $result['destination'], $install_directory );
		$result['destination'] = $install_directory;

		if ( $this->is_active ) {
			activate_plugin( $this->plugin_basename );
		}

		return $result;
	}

	/**
	 * Protecting pre-releases
	 *
	 * If any pre-release available, return true
	 *
	 * Using Setting option from plugin
	 *
	 * @param $beta_program_level string Beta program level
	 *
	 * @return bool
	 * @deprecated
	 */
	protected function protect_prerelease( string $beta_program_level ): bool {
		if ( isset( $this->github_response['prerelease'] )
		     && $this->github_response['prerelease']
		     && $beta_program_level == 'disabled' ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Remove prefix from version name
	 *
	 * @param string $tag_name
	 *
	 * @return string
	 */
	protected function remove_prefix( string $tag_name ): string {
		if ( substr( $tag_name, 0, strlen( $this->tag_name_prefix ) ) == $this->tag_name_prefix ) {
			$tag_name = substr( $tag_name, strlen( $this->tag_name_prefix ) );
		}

		return $tag_name;
	}
}