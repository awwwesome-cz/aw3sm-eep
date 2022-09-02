<?php

namespace AwwwesomeEEP\Includes;

class PDUpdater {
	private $file;
	private $plugin;
	private $basename;
	private $active;
	private $username;
	private $repository;
	/**
	 * Authorize TOKEN
	 * @var string|null
	 */
	private ?string $authorize_token = null;
	private $github_response;
	private string $tag_name_prefix;

	public function __construct( $file ) {
		$this->file = $file;
		add_action( 'admin_init', [ $this, 'set_plugin_properties' ] );

		return $this;
	}

	public function set_plugin_properties() {
		$this->plugin   = get_plugin_data( $this->file );
		$this->basename = plugin_basename( $this->file );
		$this->active   = is_plugin_active( $this->basename );
	}

	public function set_username( $username ) {
		$this->username = $username;
	}

	public function set_repository( $repository ) {
		$this->repository = $repository;
	}

	/**
	 * Set tag name prefix
	 *
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
			$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository );

			// Switch to HTTP Basic Authentication for GitHub API v3
			$curl = curl_init();

			$headers = [ "User-Agent: PDUpdater/1.2.3" ];
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

			curl_close( $curl );

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

	public function initialize() {
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'modify_transient' ], 10, 1 );
		add_filter( 'plugins_api', [ $this, 'plugin_popup' ], 10, 3 );
		add_filter( 'upgrader_post_install', [ $this, 'after_install' ], 10, 3 );
	}

	public function modify_transient( $transient ) {

		if ( property_exists( $transient, 'checked' ) ) {
			if ( $checked = $transient->checked ) {

				$this->get_repository_info();

				$new_version = str_replace( $this->tag_name_prefix, "", $this->github_response['tag_name'] );

				$out_of_date = version_compare( $new_version, $checked[ $this->basename ], 'gt' );

				if ( $out_of_date ) {
					foreach ( $this->github_response['assets'] as $asset ) {
						if ( $asset['content_type'] == 'application/zip' && $asset['name'] == "aw3sm-eep-$new_version.zip" ) {
							$new_files = $asset['browser_download_url'];
							break;
						}
					}

					// $new_files = $this->github_response['zipball_url'];
					$slug = current( explode( '/', $this->basename ) );

					$plugin = [
						'url'         => $this->plugin['PluginURI'],
						'slug'        => $slug,
						'package'     => $new_files ?? null,
						'new_version' => $new_version
					];

					$transient->response[ $this->basename ] = (object) $plugin;
				}
			}
		}

		return $transient;
	}

	public function plugin_popup( $result, $action, $args ) {
		if ( $action !== 'plugin_information' ) {
			return false;
		}

		if ( ! empty( $args->slug ) ) {
			if ( $args->slug == current( explode( '/', $this->basename ) ) ) {
				$this->get_repository_info();

				$new_version = str_replace( $this->tag_name_prefix, "", $this->github_response['tag_name'] );
				foreach ( $this->github_response['assets'] as $asset ) {
					if ( $asset['content_type'] == 'application/zip' && $asset['name'] == "aw3sm-eep-$new_version.zip" ) {
						$download_zip = $asset['browser_download_url'];
						break;
					}
				}

				$plugin = [
					'name'              => $this->plugin['Name'],
					'slug'              => $this->basename,
					'requires'          => $this->plugin['RequiresWP'], // TODO: get from API
					'tested'            => '5.4', // TODO: get from api
					'version'           => $new_version,
					'author'            => $this->plugin['AuthorName'],
					'author_profile'    => $this->plugin['AuthorURI'],
					'last_updated'      => $this->github_response['published_at'],
					'homepage'          => $this->plugin['PluginURI'],
					'short_description' => $this->plugin['Description'],
					'sections'          => [
						'Description' => $this->plugin['Description'],
						'Updates'     => $this->github_response['body'],
					],
					'download_link'     => $download_zip ?? null
				];

				return (object) $plugin;
			}
		}

		return $result;
	}

	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem;

		$install_directory = plugin_dir_path( $this->file );
		$wp_filesystem->move( $result['destination'], $install_directory );
		$result['destination'] = $install_directory;

		if ( $this->active ) {
			activate_plugin( $this->basename );
		}

		return $result;
	}
}