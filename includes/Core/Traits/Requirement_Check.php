<?php

namespace AwwwesomeEEP\Includes\Core\Traits;

trait Requirement_Check {

	/**
	 * Automatic plugin deactivation
	 *
	 * @var bool
	 */
	protected bool $deactivate_automatically = true;

	/**
	 * Text domain for language translations
	 *
	 * @var string|null
	 */
	private ?string $text_domain = null;

	/**
	 * Example:
	 *
	 * [
	 *    'elementor/elementor.php' => '1.0'
	 * ];
	 *
	 * If does not need version
	 * [
	 *    'elementor/elementor.php' => null
	 * ];
	 * @var array Associative plugins with min version as value
	 */
	protected array $need_activated = [];

	/**
	 * Hold errors messages
	 *
	 * @var string[]
	 */
	private array $requirements_errors = [];


	/**
	 * Boot function
	 *
	 * @return void
	 */
	protected function check_requirements() {
		add_action( 'admin_init', [ $this, 'init_requirements' ] );

	}

	public function init_requirements() {
		$this->check_php_version();

		$this->check_required_plugins();

		// check errors
		if ( count( $this->requirements_errors ) > 0 ) {
			// revert activation
			if ( $this->deactivate_automatically ) {
				$this->revert_activation();
			}
			// show danger notice
			add_action( 'admin_notices', [ $this, 'plugin_requirements_notice' ] );
		}
	}

	protected function check_required_plugins() {
		// get plugin data

		// Check every plugin what exist and activate status
		foreach ( $this->need_activated as $requirement_plugin_name => $requirement_plugin_version ) {
			$message = null;

			// Check plugin and activation
			if ( ! ( $this->check_mu_plugin( $requirement_plugin_name ) || is_plugin_active( $requirement_plugin_name ) ) ) {
				$message = sprintf( __( 'Plugin <strong>%s</strong> requires plugin <strong>%s</strong>, must be installed and activated.',
					$this->text_domain ),
					$this->name,
					$requirement_plugin_name );
			} else {
				// Check plugin version
				if ( $this->check_mu_plugin( $requirement_plugin_name ) ) {
					// MU plugin
					$installed_plugin_data = get_plugin_data( WPMU_PLUGIN_DIR . '/' . $requirement_plugin_name );
				} else {
					// Normal plugin
					$installed_plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $requirement_plugin_name );
				}

				// check versions of plugins
				if ( ! version_compare( $installed_plugin_data['Version'], $requirement_plugin_version, '>=' ) ) {
					$message = sprintf( __( 'Plugin <strong>%s</strong> requires plugin <strong>%s</strong> in version <strong>%s</strong> or later. Installed version is <strong>%s</strong>.',
						$this->text_domain ),
						$this->name,
						$installed_plugin_data['Name'],
						$requirement_plugin_version,
						$installed_plugin_data['Version']
					);
				}
			}

			// Add message to requirements errors
			if ( ! empty( $message ) ) {
				$this->requirements_errors[ $requirement_plugin_name ] = $message;
			}
		}
	}

	/**
	 * Check if MU plugin exist
	 *
	 * @param $plugin
	 *
	 * @return bool
	 */
	protected function check_mu_plugin( $plugin ): bool {
		return file_exists( WPMU_PLUGIN_DIR . '/' . $plugin );
	}


	/**
	 * Build notices error
	 *
	 * Show error of plugins & disable activation
	 *
	 * @access public
	 * @since 1.0
	 */
	public function plugin_requirements_notice(): void {
		if ( $this->deactivate_automatically ) {
			$this->revert_activation();
		}

		$list = "";
		foreach ( $this->requirements_errors as $requirements_error ) {
			$list .= "<li>$requirements_error</li>";
		}
		printf( '<div class="notice notice-error is-dismissible"><ul>%s</ul></div>', $list );
	}

	/**
	 * Unset Activate
	 *
	 * Remove $_GET['activate]
	 * Deactivate plugin
	 *
	 * @access private
	 * @since 1.0
	 */
	private function revert_activation(): void {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		if ( is_plugin_active( $this->basename ) ) {
			deactivate_plugins( $this->basename );
		}
	}

	/**
	 * Check PHP version
	 *
	 * @return void
	 */
	private function check_php_version(): void {
		if ( PHP_VERSION < $this->requires_php ) {
			$this->requirements_errors[] = sprintf( __( 'Plugin <strong>%s</strong> requires PHP %s or newer.',
				$this->text_domain ),
				$this->name,
				$this->requires_php );
		}
	}
}