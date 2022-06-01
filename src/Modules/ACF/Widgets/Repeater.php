<?php

namespace AwwwesomeEEP\Modules\ACF\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Repeater extends Widget_Base {

	public function get_name() {
		return 'acf-repeater';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'ACF Repeater', 'aw3sm-eep' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-editor-list-ul';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the button widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 2.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'basic' ];
	}

	protected function register_controls() {

		// init action
		do_action( 'aw3sm_eep/widgets/acf/repeater/template/before_section_start', $this );

		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Text Repeat', 'aw3sm-eep' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// init action
		do_action( 'aw3sm_eep/widgets/acf/repeater/template/section_start', $this );

		$acf_available_fields     = [];
		$acf_available_fields[''] = __( 'None', 'aw3sm-eep' );

		$acf_second_group_level     = [];
		$acf_second_group_level[''] = __( 'None', 'aw3sm-eep' );

		// get all group acf_first_group_level
		$fields = get_field_objects();

		if ( ! is_array( $fields ) ) {
			$this->add_control(
				'acf_error_description',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( 'For activate ACF pickers please set preview settings as post as contains ACF non empty fields and reload editor.', 'aw3sm-eep' ),
					'separator'       => 'none',
					'content_classes' => 'elementor-descriptor',
				]
			);
			return;
		}

		foreach ( $fields as $field_key => $field ) {
			if ( $field['type'] === 'select' ) {
				$acf_available_fields[ $field_key ] = $field['label'];
			}
			if ( $field['type'] === 'group' && isset( $field['sub_fields'] ) ) {

				$acf_available_fields[ $field_key ] = $field['label'];

				// if group
				foreach ( $field['sub_fields'] as $sub_field_key => $sub_field ) {
					$acf_second_group_level[ $sub_field['name'] ] = $sub_field['label'];
				}
			}
		}


		$this->add_control(
			'acf_info_description',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Allow to select ACF groups with single or multiple choices like "text", "select" ...', 'aw3sm-eep' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
			]
		);


		$this->add_control(
			'acf_field',
			[
				'label'   => esc_html__( 'ACF Item', 'aw3sm-eep' ), // 1 level of ACF group
				'type'    => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $acf_available_fields,
			],
		);


		$this->add_control(
			'acf_group_description',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'First level ACF Group Items must have "group" type', 'aw3sm-eep' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->add_control(
			'acf_sub_field',
			[
				'label'     => esc_html__( 'ACF Group Items', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SELECT2,
				'condition' => [
					'acf_field!' => [ '', 'tools' ],
				],
				'default'   => '',
				'options'   => $acf_second_group_level,
			],
		);

		// init action
		do_action( 'aw3sm_eep/widgets/acf/repeater/template/before_section_end', $this );

		$this->end_controls_section();

		// init action
		do_action( 'aw3sm_eep/widgets/acf/repeater/template/after_section_end', $this );

		$this->start_controls_section(
			'style_template',
			[
				'label' => __( 'Display', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->register_style_controls();

		$this->end_controls_section();

	}

	protected function register_style_controls() {


		$this->add_control(
			'span_color',
			[
				'label'     => esc_html__( 'Text color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'global'   => [
					// 'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .acf-repeater-list span',
			]
		);

		$this->add_control(
			'spacing',
			[
				'label'      => esc_html__( 'Spacing', 'aw3sm-eep' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max'  => 5,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .acf-repeater-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'default'    => [
					'size' => 20,
				],
			]
		);

		//
		// Přidám responzivní pro zarovnání slopců
		$this->add_responsive_control(
			'scb_section_horizontal_align',
			[
				'label'     => __( 'Horizontal align', 'aw3sm-eep' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''              => __( 'Default', 'aw3sm-eep' ),
					'flex-start'    => __( 'Start', 'aw3sm-eep' ),
					'flex-end'      => __( 'End', 'aw3sm-eep' ),
					'center'        => __( 'Center', 'aw3sm-eep' ),
					'space-between' => __( 'Space Between', 'aw3sm-eep' ),
					'space-around'  => __( 'Space Around', 'aw3sm-eep' ),
					'space-evenly'  => __( 'Space Evenly', 'aw3sm-eep' ),
				],
				'selectors' => [ // Zde se předají data do CSS
					'{{WRAPPER}} .acf-repeater-list' => 'justify-content: {{VALUE}};',
				],
			]
		);
	}

	protected function render() {
		// get settings
		$acf_group = $this->get_settings( 'acf_field' );

		if ( '' === $acf_group ) {
			return;
		}

		$_finalContent = '';

		$field = get_field_object( $acf_group );
		if ( ! $field ) {
			return;
		}
		$_finalContent .= "<div class=\"acf-repeater-list\" style='display: flex'>";
		if ( $field['type'] != 'group' ) {
			foreach ( $field['value'] as $value ) {
				$name          = $field['choices'][ $value ];
				$_finalContent .= "<div class=\"acf-repeater-item\">";
				$_finalContent .= "<span>$name</span>";
				$_finalContent .= "</div>";
			}
		} else {
			$acf_sub_field = $this->get_settings( 'acf_sub_field' );
			if ( '' === $acf_sub_field ) {
				return;
			}

			$sub_field_obj = get_field_object( $field['name'] . '_' . $acf_sub_field );
			if ( ! $sub_field_obj['multiple'] ) {
				$name = $sub_field_obj['name'];

				// single value
				$_finalContent .= "<div class=\"acf-repeater-item\">";
				$_finalContent .= "<span>$name</span>";
				$_finalContent .= "</div>";
			} else {
				// array of values
				foreach ( $sub_field_obj['value'] as $value ) {
					$name = $sub_field_obj['choices'][ $value ]; // Get name

					$_finalContent .= "<div class=\"acf-repeater-item\">";
					$_finalContent .= "<span>$name</span>";
					$_finalContent .= "</div>";
				}
			}
		}
		$_finalContent .= "</div>";

		echo $_finalContent;
	}
}