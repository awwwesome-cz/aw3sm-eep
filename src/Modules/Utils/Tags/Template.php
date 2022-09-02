<?php

namespace AwwwesomeEEP\Modules\Utils\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Plugin;
use ElementorPro\Modules\DynamicTags\Module as DynamicTagsModule;

/**
 * @since 1.3.0
 */
class Template extends Tag {

	const TagName = "aw3sm-template";

	/**
	 * Get tag name.
	 *
	 * Retrieve the name of the tag.
	 *
	 * @return string Tag name.
	 */
	public function get_name() {
		return self::TagName;
	}

	/**
	 * Get dynamic tag title.
	 *
	 * Returns the title of the random number tag.
	 *
	 * @return string Dynamic tag title.
	 */
	public function get_title() {
		return esc_html__( 'Template', 'aw3sm-eep' );
	}

	/**
	 * Get dynamic tag groups.
	 *
	 * Retrieve the list of groups the random number tag belongs to.
	 *
	 * @return array Dynamic tag groups.
	 */
	public function get_group() {
		return [ DynamicTagsModule::SITE_GROUP ];
	}

	/**
	 * Get dynamic tag categories.
	 *
	 * Retrieve the list of categories the random number tag belongs to.
	 *
	 * @return array Dynamic tag categories.
	 */
	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::BASE_GROUP,
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY
		];
	}

	/**
	 * Register dynamic tag controls.
	 *
	 * Add input fields to allow the user to customize the server variable tag settings.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->add_control(
			'template_id',
			[
				'label'        => esc_html__( 'Template', 'aw3sm-eep' ),
				'type'         => 'query',
				'placeholder'  => esc_html__( 'Select Template', 'aw3sm-eep' ),
				'label_block'  => true,
				'autocomplete' => [
					'object' => 'library_template',
				]
			]
		);
	}

	/**
	 * Render output
	 *
	 * @return void
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['template_id'] ) ) {
			echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'] );
		}
	}
}
