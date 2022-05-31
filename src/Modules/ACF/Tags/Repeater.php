<?php

namespace AwwwesomeEEP\Modules\ACF\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use ElementorPro\Modules\DynamicTags\ACF\Module as ACFModule;

class Repeater extends Tag {
	const TagName = "acf-repeater";

	/**
	 * Get dynamic tag name.
	 *
	 * Retrieve the name of the random number tag.
	 *
	 * @return string Dynamic tag name.
	 * @since 1.0.0
	 * @access public
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
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'ACF Repeater', 'aw3sm-eep' );
	}

	/**
	 * Get dynamic tag groups.
	 *
	 * Retrieve the list of groups the random number tag belongs to.
	 *
	 * @return array Dynamic tag groups.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_group() {
		return [ ACFModule::ACF_GROUP ];
	}

	/**
	 * Get dynamic tag categories.
	 *
	 * Retrieve the list of categories the random number tag belongs to.
	 *
	 * @return array Dynamic tag categories.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	/**
	 * Register dynamic tag controls.
	 *
	 * Add input fields to allow the user to customize the server variable tag settings.
	 *
	 * @return void
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$acf_first_group_level      = [];
		$acf_second_group_level     = [];
		$acf_first_group_level['']  = __( 'None', 'aw3sm-eep' );
		$acf_second_group_level[''] = __( 'None', 'aw3sm-eep' );

		// get all group acf_first_group_level
		$fields = get_fields();
		if ( ! $fields ) {
			$this->add_control(
				'acf_error_description',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( 'This post does not contains any ACF fields for show!', 'aw3sm-eep' ),
					'separator'       => 'none',
					'content_classes' => 'elementor-descriptor',
				]
			);
			return;
		}

		foreach ( $fields as $field_key => $field ) {
			if ( have_rows( $field_key ) ) {
				$obj                                 = get_field_object( $field_key );
				$acf_first_group_level[ $field_key ] = $obj['label'];

				// get all sub groups
				foreach ( $obj['value'] as $sub_field_key => $sub_field ) {
					$subObj                                   = get_sub_field_object( $sub_field_key );
					$acf_second_group_level[ $sub_field_key ] = $subObj['label'];
				}
			}
		}

		$this->add_control(
			'acf_group',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'First level group', 'aw3sm-eep' ),
				'options' => $acf_first_group_level,
			]
		);
		$this->add_control(
			'acf_sub_group',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'First level group', 'aw3sm-eep' ),
				'options' => $acf_second_group_level,
			]
		);
	}

	public function render() {
		$acf_group     = $this->get_settings( 'acf_group' );
		$acf_sub_group = $this->get_settings( 'acf_sub_group' );

		if ( '' === $acf_group || '' === $acf_sub_group ) {
			return;
		}

		$_finalContent = "";

		if ( have_rows( $acf_group ) ) {
			while ( have_rows( $acf_group ) ) {
				the_row();
				$subField       = get_sub_field_object( trim( $acf_sub_group ) );
				$subFieldValues = get_sub_field( trim( $acf_sub_group ) );
				foreach ( $subFieldValues as $value ) {
					$name          = $subField['choices'][ $value ]; // Get name
					$_finalContent .= "<span class='aw3sm-acf-sub-group'>$name</span> ";
				}
			}
		} else {
			$_finalContent = "$acf_group does not have any rows";
		}
		echo $_finalContent;
	}
}